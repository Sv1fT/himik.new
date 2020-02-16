<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserFavoriteAdverts extends Model
{
    protected $fillable = [
        'advert_id', 'user_id',
    ];
}
