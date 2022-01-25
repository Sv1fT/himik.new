<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAttributes extends Model
{
    public function regions()
    {
        return $this->hasOne(Region::class,'id','region_id');
    }

    public function cities()
    {
        return $this->hasOne(City::class,'id','city_id');
    }

    public function countries()
    {
        return $this->hasOne(Country::class,'id','country_id');
    }
}
