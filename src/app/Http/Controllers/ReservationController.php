<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Models\Course;
use App\Http\Requests\ReserveRequest;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Review;

class ReservationController extends Controller
{
    public function reserve(ReserveRequest $request){
        $user_id = Auth::id();
        $reservation_data = [
            'user_id' => $user_id,
            'shop_id' => $request->shop_id,
            'date' => $request->date,
            'time' => $request->time,
            'number' => $request->number,
            'course_id' => $request->course_id ?? null,
            'qr_code' => null
        ];

        $reservation = Reservation::create($reservation_data);

        // QRコードの生成と保存
        $qrCode = QrCode::format('png')->size(200)->generate($reservation->id);
        $qrCodePath = 'storage/qr_codes/' . time() . '_' . $reservation->id . '.png';
        file_put_contents(public_path($qrCodePath), $qrCode);

        $reservation->qr_code = $qrCodePath;
        $reservation->save();

        return redirect('/done');
    }

    public function done(){
        return view('done');
    }

    public function getReserveEdit($reservation_id){
        $reservation = Reservation::find($reservation_id);
        $shop = $reservation->shop;
        $review = new Review();
        $averageRating = $review->getAverageRating($shop->id);
        $reviews = Review::where('shop_id', $shop->id)->get();
        $courses = Course::where('shop_id', $shop->id)->get();
        return view('reservation_edit', compact('reservation', 'shop', 'averageRating', 'reviews', 'courses'));
    }

    public function reserveEdit(ReserveRequest $request){
        Reservation::find($request->id)
                    ->update([
                        'date' => $request->date,
                        'time' => $request->time,
                        'number' => $request->number,
                        'course_id' => $request->course_id ?? null
                    ]);
        // reservation_idは変わらないため、QRコードは変更しない
        return redirect('/mypage')->with('result', '予約を変更しました');
    }

    public function reserveCancel(Request $request){
        Reservation::find($request->id)->delete();
        return redirect('/mypage')->with('result', '予約をキャンセルしました');
    }
}
