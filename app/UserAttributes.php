<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAttributes extends Model
{
    public function region_id()
    {
        return $this->belongsTo(Region::class,'region','id');
    }

    public function city_id()
    {
        return $this->belongsTo(City::class,'city','id');
    }

    public function country_id()
    {
        return $this->belongsTo(Country::class,'country','id');
    }
}
