<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;

class AdminController extends Controller
{
    public function getAdmin(){
        $admin_role_id = Role::where('name', 'admin')->first()->id;
        $representative_role_id = Role::where('name', 'representative')->first()->id;

        $users = User::whereDoesntHave('roles', function($query) use ($admin_role_id, $representative_role_id) {
            $query->whereIn('role_id', [$admin_role_id, $representative_role_id]);
        })->get();

        return view('admin', compact('users'));
    }

    public function userDetail($user_id){
        $user = User::where('id', $user_id)->first();
        return view('user_detail', compact('user'));
    }

    public function createRepresentative(Request $request){
        $user = User::where('id', $request->id)->first();

        $representativeRole = Role::where('name', 'representative')->first();
        $user->assignRole($representativeRole);

        return redirect('/admin');
    }

    public function showImportForm(){
        return view('import');
    }

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|file|mimes:csv,txt',
        ], [
            'csv_file.required' => 'CSVファイルを選択してください。',
            'csv_file.file' => 'ファイルを選択してください。',
            'csv_file.mimes' => 'CSV形式のファイルを選択してください。',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $path = $request->file('csv_file')->getRealPath();
        $file = fopen($path, 'r');
        $header = fgetcsv($file);

        $expectedHeader = ['店舗名', '地域', 'ジャンル', '店舗概要', '画像URL'];

        // ヘッダーがフォーマット通りか確認する
        if ($header !== $expectedHeader) {
            fclose($file);
            $errorMessage = 'CSVファイルのヘッダーが異なります。正しいフォーマットで再度アップロードしてください。';
            return redirect()->back()->with('error', $errorMessage);
        }

        $errors = [];
        $lines = [];
        $lineNumber = 1;

        while ($row = fgetcsv($file)) {
            $lineNumber++;
            $data = array_combine($header, $row);

            $validation = Validator::make($data, [
                '店舗名' => 'required|string|max:50',
                '地域' => 'required|in:東京都,大阪府,福岡県',
                'ジャンル' => 'required|in:寿司,焼肉,イタリアン,居酒屋,ラーメン',
                '店舗概要' => 'required|string|max:400',
                '画像URL' => ['required', 'url', 'regex:/\.(jpeg|png)$/i'],
            ], [
                '店舗名.required' => '店舗名は必須です。',
                '店舗名.string' => '店舗名は文字列で入力してください。',
                '店舗名.max' => '店舗名は:max文字以内で入力してください。',
                '地域.required' => '地域は必須です。',
                '地域.in' => '地域は東京都、大阪府、福岡県のいずれかを入力してください。',
                'ジャンル.required' => 'ジャンルは必須です。',
                'ジャンル.in' => 'ジャンルは寿司、焼肉、イタリアン、居酒屋、ラーメンのいずれかを入力してください。',
                '店舗概要.required' => '店舗概要は必須です。',
                '店舗概要.string' => '店舗概要は文字列で入力してください。',
                '店舗概要.max' => '店舗概要は:max文字以内で入力してください。',
                '画像URL.required' => '画像URLは必須です。',
                '画像URL.url' => '正しいURL形式で指定してください。',
                '画像URL.regex' => 'JPEGまたはPNGのみアップロード可能です。',
            ]);

            if ($validation->fails()) {
                $errors[] = "Line $lineNumber: " . implode('<br>', $validation->errors()->all());
            } else {
                // 空白文字のみの店舗名のチェック
                if (trim($data['店舗名']) === '' || mb_ereg_match('^[\s　]+$', $data['店舗名'])) {
                    $errors[] = "Line $lineNumber: 店舗名が空白文字のみです。";
                }

                // 空白文字のみの店舗概要のチェック
                if (trim($data['店舗概要']) === '' || mb_ereg_match('^[\s　]+$', $data['店舗概要'])) {
                    $errors[] = "Line $lineNumber: 店舗概要が空白文字のみです。";
                }

                // エリアやジャンルのチェック
                $area = Area::where('name', $data['地域'])->first();
                $genre = Genre::where('name', $data['ジャンル'])->first();

                if (!$area || !$genre) {
                    $errors[] = "Line $lineNumber: Invalid area or genre.";
                } else {
                    $lines[] = $data;
                }
            }
        }

        fclose($file);

        if (count($errors) > 0) {
            $errorMessages = implode('<br>', $errors);
            return redirect()->back()->with('error', $errorMessages);
        }

        // エラーがない場合のみデータベースに保存
        foreach ($lines as $data) {
            $area = Area::where('name', $data['地域'])->first();
            $genre = Genre::where('name', $data['ジャンル'])->first();

            $shop = Shop::create([
                'name' => $data['店舗名'],
                'area_id' => $area->id,
                'explanation' => $data['店舗概要'],
                'photo' => $data['画像URL'],
            ]);

            $shop->genres()->syncWithoutDetaching([$genre->id]);
        }

        return redirect()->back()->with('success', 'CSVインポートが完了しました。');
    }
}
