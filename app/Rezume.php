<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rezume extends Model
{
    public function regions()
    {
        return $this->belongsTo(Region::class, 'region','id');
    }

    public function city_get()
    {
        return $this->belongsTo(City::class, 'city','id');
    }

    public function attributes()
    {
        return $this->belongsTo(UserAttributes::class, 'user_id','user_id');
    }

}
