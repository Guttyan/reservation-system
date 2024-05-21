<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class StripeController extends Controller
{
    public function reserveWithStripe(Request $request)
    {
        // 予約の保存で後に必要なため予約フォームで入力された情報をセッションに保存
        $reservationData = $request->only(['date', 'time', 'number', 'course_id', 'shop_id']);
        Session::put('reservation_data', $reservationData);

        // Stripeの秘密キーをセットアップ
        Stripe::setApiKey(config('services.stripe.secret_key'));

        // コースIDから価格を取得
        $course = Course::findOrFail($request->course_id);
        $total_amount = $course->price * $request->number;

        // Stripe Checkoutのセッションを作成
        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'JPY',
                        'product_data' => [
                            'name' => $course->shop->name . ' - ' . $course->name,
                        ],
                        'unit_amount' => $total_amount,
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => route('stripe-success'), // 成功時のリダイレクトURL
            'cancel_url' => route('detail', ['shop_id' => $request->shop_id]), // キャンセル時のリダイレクトURL
        ]);

        // セッションIDをクライアントに返す
        return response()->json(['id' => $session->id]); // セッションIDをJSONレスポンスに含める
    }

    public function reserveWithStripeCreate(Request $request){
        // 予約フォームで入力された情報をセッションから取得
        $reservationData = Session::get('reservation_data');

        // 予約データを作成してデータベースに保存
        $reservation = Reservation::create([
            'user_id' => Auth::id(),
            'date' => $reservationData['date'],
            'time' => $reservationData['time'],
            'number' => $reservationData['number'],
            'shop_id' => $reservationData['shop_id'],
            'course_id' => $reservationData['course_id'],
            'payment_method' => "prepay",
        ]);

        // QRコードの生成と保存
        $qrCode = QrCode::format('png')->size(200)->generate($reservation->id);
        $qrCodePath = 'storage/qr_codes/' . time() . '_' . $reservation->id . '.png';
        file_put_contents(public_path($qrCodePath), $qrCode);

        $reservation->qr_code = $qrCodePath;
        $reservation->save();

        // セッションから予約情報を削除
        Session::forget('reservation_data');

        return redirect('/done');
    }
}
