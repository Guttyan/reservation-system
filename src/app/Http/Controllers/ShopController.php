<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Genre;
use App\Models\Area;
use App\Models\Review;
use App\Models\Course;

class ShopController extends Controller
{
    public function index(){
        $shops = Shop::all();
        $genres = Genre::all();
        $areas = Area::select('areas.id', 'areas.name')
            ->join('shops', 'areas.id', '=', 'shops.area_id')
            ->distinct()
            ->orderBy('areas.id', 'asc')
            ->get();

        $averageRatings = [];
        foreach ($shops as $shop) {
            $averageRating = (new Review())->getAverageRating($shop->id);
            if ($averageRating !== null && $averageRating != '0.00') {
                $averageRatings[$shop->id] = $averageRating;
            }
        }

        return view('index', compact('shops', 'genres', 'areas', 'averageRatings'));
    }

    public function detail($shop_id){
        $shop = Shop::where('id', $shop_id)->first();
        $review = new Review();
        $averageRating = $review->getAverageRating($shop_id);
        $reviews = Review::where('shop_id', $shop_id)->get();
        $courses = Course::where('shop_id', $shop_id)->get();
        return view('detail', compact('shop', 'averageRating', 'reviews', 'courses'));
    }

    public function search(Request $request){
        $genres = Genre::all();
        $areas = Area::select('areas.id', 'areas.name')
            ->join('shops', 'areas.id', '=', 'shops.area_id')
            ->distinct()
            ->orderBy('areas.id', 'asc')
            ->get();
        $query = Shop::query();

        if($request->filled('area_id')){
            $query->where('area_id', $request->area_id);
        }

        $genre_id = $request->genre_id;
        if($request->filled('genre_id')){
            $query->whereHas('genres', function ($query) use ($genre_id){
                $query->where('genres.id', $genre_id);
            });
        }

        if($request->filled('keyword')){
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $shops = $query->get();

        $averageRatings = [];
        foreach ($shops as $shop) {
            $averageRating = (new Review())->getAverageRating($shop->id);
            if ($averageRating !== null && $averageRating != '0.00') {
                $averageRatings[$shop->id] = $averageRating;
            }
        }

        return view('index', compact('shops', 'genres', 'areas', 'averageRatings'));
    }
}
