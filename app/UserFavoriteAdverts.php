<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Advert;

class UserFavoriteAdverts extends Model
{
    protected $fillable = [
        'advert_id', 'user_id',
    ];

    public function advert()
    {
        return $this->belongsTo(Advert::class);
    }
}
