<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Http\Requests\ReviewRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;

class ReviewController extends Controller
{
    public function completedReservations(){
        $completedReservations = Reservation::where(function ($query) {
                $query->where('date', '<', now()->toDateString())
                    ->orWhere(function ($subquery) {
                        $subquery->where('date', now()->toDateString())
                            ->where('time', '<', now()->subHours(3)->toTimeString());
                    });
            })
            ->where('user_id', auth()->id())
            ->get();
        return view('completed_reservations', compact('completedReservations'));
    }

    public function getCreateReview($reservation_id){
        $reservation = Reservation::find($reservation_id);
        $shop = $reservation->shop;
        return view('create_review', compact('reservation', 'shop'));
    }

    public function postCreateReview(ReviewRequest $request){
        $user_id = Auth::id();
        $data = [
            'user_id' => $user_id,
            'shop_id' => $request->shop_id,
            'rating' => $request->rating,
        ];
        if ($request->comment) {
            $data['comment'] = $request->comment;
        }
        Review::create($data);
        return redirect('/reservations/completed');
    }
}
