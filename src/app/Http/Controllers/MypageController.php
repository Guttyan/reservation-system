<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Models\Favorite;
use App\Models\Review;
use Carbon\Carbon;

class MypageController extends Controller
{
    public function getMypage(){
        $user_id = Auth::id();
        $currentDateTime = Carbon::now();

        if($currentDateTime->toTimeString() >= '03:00:00'){
            $reservations = Reservation::where('user_id', $user_id)->where(function ($query) use ($currentDateTime){
                $query->where('date', '>', $currentDateTime->toDateString())->orWhere(function ($query) use ($currentDateTime){
                    $query->where('date', $currentDateTime->toDateString())->where('time', '>=', $currentDateTime->subHours(3)->toTimeString());
                });
            })->get();
        }else{
            $reservations = Reservation::where('user_id', $user_id)->where(function ($query) use ($currentDateTime){
                $query->where('date', '>=', $currentDateTime->toDateString())->orWhere(function ($query) use ($currentDateTime){
                    $query->where('date', $currentDateTime->subDay()->toDateString())->where('time', '>=', $currentDateTime->subHours(3)->toTimeString());
                });
            })->get();
        }

        $favorite_shops = Auth::user()->favoriteShops;

        $averageRatings = [];
        foreach ($favorite_shops as $shop) {
            $averageRating = (new Review())->getAverageRating($shop->id);
            if ($averageRating !== null && $averageRating != '0.00') {
                $averageRatings[$shop->id] = $averageRating;
            }
        }

        return view('mypage', compact('reservations', 'favorite_shops', 'averageRatings'));
    }


    public function toggleFavorite(Request $request){
        $user_id = Auth::id();
        $shop_id = $request->shop_id;

        $favorite = Favorite::where('user_id', $user_id)
                            ->where('shop_id', $shop_id)
                            ->first();

        if($favorite){
            $favorite->delete();
            return redirect('/mypage');
        }else{
            Favorite::create([
                'user_id' => $user_id,
                'shop_id' => $shop_id,
            ]);
            return redirect('/mypage');
        }
    }
}
