<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Shop;
use App\Http\Requests\ReviewRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;

class ReviewController extends Controller
{
    public function getCreateReview($shop_id){
        $shop = Shop::find($shop_id);
        return view('create_review', compact('shop'));
    }

    public function postCreateReview(ReviewRequest $request){
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->storeAs('public/review_images', $imageName);
                $images[] = $imageName;
            }
        }

        $user_id = Auth::id();

        $data = [
            'user_id' => $user_id,
            'shop_id' => $request->shop_id,
            'rating' => $request->rating,
            'review_image' => $images ? json_encode($images) : null
        ];
        if ($request->comment) {
            $data['comment'] = $request->comment;
        }
        Review::create($data);
        return redirect("/detail/{$request->shop_id}");
    }

    public function getEditReview($review_id){
        $my_review = Review::find($review_id);
        $shop = $my_review->shop;
        if ($my_review->review_image) {
            $my_review->review_image = json_decode($my_review->review_image);
        }
        return view('edit_review', compact('my_review', 'shop'));
    }

    public function editReview(ReviewRequest $request){
        $review = Review::find($request->review_id);
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->storeAs('public/review_images', $imageName);
                $images[] = $imageName;
            }
        }

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->input('comment'),
            'review_image' => $images ? json_encode($images) : null
        ]);

        return redirect("/detail/{$request->shop_id}");
    }

    public function deleteReview(Request $request){
        $review = Review::find($request->review_id);
        $shop = $review->shop;
        $review->delete();
        return redirect("/detail/{$shop->id}");
    }
}
