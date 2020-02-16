<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Advert_type;
class Advert extends Model
{
    public function getCreateDateAttribute()
    {
        return Carbon::parse($this->created_at)->formatLocalized('%d %B %Y');
    }

    public function types()
    {
        return $this->hasMany(Advert_type::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class,'region_id','id');
    }

    public function city()
    {
        return $this->belongsTo(City::class,'city_id','id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class,'country_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
