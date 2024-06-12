<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'shop_id', 'rating', 'comment', 'review_image'];

    public function shop(){
        return $this->belongsTo(Shop::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function getAverageRating($shop_id){
        return number_format($this->where('shop_id', $shop_id)->avg('rating'), 2);
    }
}
