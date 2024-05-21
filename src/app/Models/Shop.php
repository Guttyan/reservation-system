<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id', 'area_id', 'explanation', 'photo'];

    public function genres(){
        return $this->belongsToMany(Genre::class, 'shop_genre', 'shop_id', 'genre_id');
    }

    public function reservations(){
        return $this->hasMany(Reservation::class);
    }

    public function area(){
        return $this->belongsTo(Area::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function favorites(){
        return $this->hasMany(Favorite::class);
    }

    public function getPhotoAttribute($value){
        $images = json_decode($value, true);
        if (is_array($images)) {
            return $images;
        } else {
            return $value;
        }
    }

    public function reviews(){
        return $this->hasMany(Review::class);
    }

    public function couses(){
        return $this->hasMany(Course::class);
    }
}