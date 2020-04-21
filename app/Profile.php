<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Profile extends Model
{


    public function getProfile()
    {
        return UserAttributes::where('user_id',Auth::id())->get();
    }

    public function getRegion()
    {
        return Region::where('country_id',$this->getProfile()[0]['country'])->get();
    }

    public function getCity()
    {
        return City::where('region_id',$this->getProfile()[0]['region'])->get();
    }

    public function getCountry()
    {
        return Country::whereIn('id',[0,5,21,52,78,81,89,99,101,106,121,146,191,1])->orderBy('name','ASC')->get();
    }

    public function scopeUser($query)
    {
        $query->where('id','=',Auth::user()->id);
    }
}
