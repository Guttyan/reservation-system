<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function toggleFavorite(Request $request){
        $user_id = Auth::id();
        $shop_id = $request->shop_id;

        $favorite = Favorite::where('user_id', $user_id)
                            ->where('shop_id', $shop_id)
                            ->first();

        if($favorite){
            $favorite->delete();
            return redirect('/');
        }else{
            Favorite::create([
                'user_id' => $user_id,
                'shop_id' => $shop_id,
            ]);
            return redirect('/');
        }
    }
}
