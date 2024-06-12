<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RepresentativeController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\StripeController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Gate;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/email/verify', function(){
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', function(EmailVerificationRequest $request){
    $request->fulfill();
    return redirect('/thanks');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/thanks', [AuthController::class, 'thanks']);
    Route::get('/', [ShopController::class,'index'])->name('index');
    Route::get('/search', [ShopController::class, 'search'])->name('search');
    Route::get('/detail/{shop_id}', [ShopController::class, 'detail'])->name('detail');

    // 予約関連
    Route::post('/reserve', [ReservationController::class, 'reserve']);
    Route::post('/reserve-with-stripe', [StripeController::class, 'reserveWithStripe']);
    Route::get('/reserve-with-stripe/create', [StripeController::class, 'reserveWithStripeCreate'])->name('stripe-success');
    Route::get('/done', [ReservationController::class, 'done'])->name('done');
    Route::get('/reservation/edit/{reservation_id}', [ReservationController::class, 'getReserveEdit']);
    Route::post('/reservation/edit', [ReservationController::class, 'reserveEdit']);
    Route::post('/reservation/cancel', [ReservationController::class, 'reserveCancel']);

    // お気に入り
    Route::post('/favorite', [FavoriteController::class, 'toggleFavorite']);

    // マイページ
    Route::get('/mypage', [MypageController::class, 'getMypage']);
    Route::post('/mypage/favorite', [MypageController::class, 'toggleFavorite']);

    // レビュー
    Route::middleware('ensureUserRole:user')->group(function () {
        Route::get('/create/review/{shop_id}', [ReviewController::class, 'getCreateReview']);
        Route::post('/create/review', [ReviewController::class, 'postCreateReview']);
        Route::get('/edit/review/{review_id}', [ReviewController::class, 'getEditReview']);
        Route::post('/edit/review', [ReviewController::class, 'editReview']);
    });
    Route::post('/delete/review', [ReviewController::class, 'deleteReview']);

    // 管理者
    Route::middleware('role:admin')->group(function(){
        Route::get('/admin', [AdminController::class, 'getAdmin']);
        Route::get('/admin/{user_id}', [AdminController::class, 'userDetail']);
        Route::post('/representative/create', [AdminController::class, 'createRepresentative']);
    });

    // 店舗代表者
    Route::middleware('role:representative')->group(function(){
        Route::get('/create-shop', [RepresentativeController::class, 'getCreateShop']);
        Route::post('/create-shop', [RepresentativeController::class, 'createShop']);
        Route::get('/my-shops', [RepresentativeController::class, 'myShops']);
        Route::get('/my-shops/edit/{shop_id}', [RepresentativeController::class, 'getMyShopEdit']);
        Route::post('/my-shops/edit', [RepresentativeController::class, 'postMyShopEdit']);
        Route::get('/my-shops/reservation/{shop_id}/{num}', [RepresentativeController::class, 'myShopReservation']);
        Route::get('/courses/{shop_id}', [RepresentativeController::class, 'courses']);
        Route::post('/course/create', [RepresentativeController::class, 'createCourse']);
        Route::post('/course/update', [RepresentativeController::class, 'updateCourse']);
        Route::post('/course/delete', [RepresentativeController::class, 'deleteCourse']);
    });

    // 管理者と店舗代表者の両方がアクセスできるルート
    Route::middleware('role:admin|representative')->group(function(){
        Route::get('/create-mail/{shop_id?}', [MailController::class, 'createMail']);
        Route::post('/send-mail', [MailController::class, 'sendMail']);
    });
});
