<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $table = 'vacant';

    public function value()
    {
        return $this->belongsTo(UserAttributes::class, 'user_id','user_id');
    }

    public function city_get()
    {
        return $this->belongsTo(City::class, 'city','id');
    }

    public function attributes()
    {
        return $this->hasMany('App\UserAttributes','user_id','user_id');
    }
}
