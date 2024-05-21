<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Shop;
use App\Models\Course;
use App\Http\Requests\CreateShopRequest;
use App\Http\Requests\EditShopRequest;
use App\Http\Requests\CreateCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use Illuminate\Support\Facades\Auth;

class RepresentativeController extends Controller
{
    // 店舗作成画面表示
    public function getCreateShop(){
        $areas = Area::all();
        $genres = Genre::all();
        return view('create_shop', compact('areas', 'genres'));
    }

    // 店舗作成機能
    public function createShop(CreateShopRequest $request){
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->storeAs('public/shop_images', $imageName);
                $images[] = $imageName;
            }
        }
        $user_id = Auth::id();
        $new_shop = Shop::create([
            'user_id' => $user_id,
            'name' => $request->name,
            'area_id' => $request->area_id,
            'explanation' => $request->explanation,
            'photo' => json_encode($images)
        ]);

        $shop_id = $new_shop->id;

        if($request->genre_id){
            $genre = Genre::findOrFail($request->genre_id);
            $new_shop->genres()->attach($genre);
        }elseif($request->new_genre){
            $new_genre = Genre::create([
                'name' => $request->new_genre,
            ]);
            $genre_id = $new_genre->id;
            $new_shop->genres()->attach($genre_id);
        }

        return redirect('/create-shop');
    }

    // 自身の店舗一覧表示
    public function myShops(){
        $user_id = Auth::id();
        $shops = Shop::where('user_id', $user_id)->get();
        return view('my_shops', compact('shops'));
    }

    // 店舗編集画面
    public function getMyShopEdit($shop_id){
        $areas = Area::all();
        $genres = Genre::all();
        $shop = Shop::where('id', $shop_id)->first();
        return view('my_shop_edit', compact('areas', 'genres', 'shop'));
    }

    // 店舗情報編集機能
    public function postMyShopEdit(EditShopRequest $request){
        $shop = Shop::find($request->shop_id);

        if($request->genre_id){
            $genre_id = $request->genre_id;
        }elseif($request->new_genre){
            $new_genre = Genre::create([
                'name' => $request->new_genre,
            ]);
            $genre_id = $new_genre->id;
        }
        $shop->genres()->sync([$genre_id]);

        if($request->images){
            $images = [];
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->storeAs('public/shop_images', $imageName);
                $images[] = $imageName;
            }

            $update_shop = $shop->update([
                'name' => $request->name,
                'area_id' => $request->area_id,
                'explanation' => $request->explanation,
                'photo' => json_encode($images)
            ]);
            return redirect('/my-shops');
        }else{
            $update_shop = $shop->update([
                'name' => $request->name,
                'area_id' => $request->area_id,
                'explanation' => $request->explanation,
            ]);
            return redirect('/my-shops');
        }
    }

    // 自身の店舗の予約状況
    public function myShopReservation($shop_id, $num){
        $shop = Shop::find($shop_id);
        $date = now()->addDays($num)->format('Y-m-d');
        $reservations = $shop->reservations()->whereDate('date', $date)->orderBy('time')->get();

        return view('my_shop_reservation', compact('shop', 'date', 'reservations', 'num'));
    }

    // コース一覧
    public function courses($shop_id){
        $shop = Shop::find($shop_id);
        $courses = Course::where('shop_id', $shop_id)->get();
        return view('courses', compact('shop', 'courses'));
    }

    // コース新規作成
    public function createCourse(CreateCourseRequest $request){
        Course::create([
            'shop_id' => $request->shop_id,
            'name' => $request->name,
            'price' => $request->price
        ]);
        return redirect("/courses/{$request->shop_id}");
    }

    // コース編集
    public function updateCourse(UpdateCourseRequest $request){
        $course = Course::find($request->course_id);
        $course->name = $request->name;
        $course->price = $request->price;
        $course->save();
        return redirect("/courses/{$request->shop_id}");
    }

    // コース削除
    public function deleteCourse(Request $request){
        Course::destroy($request->course_id);
        return redirect("/courses/{$request->shop_id}");
    }
}
