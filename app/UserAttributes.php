<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAttributes extends Model
{
    public function region()
    {
        return $this->hasOne(Region::class,'id','region_id');
    }

    public function city()
    {
        return $this->hasOne(City::class,'id','city_id');
    }

    public function country()
    {
        return $this->hasOne(Country::class,'id','country_id');
    }
}
