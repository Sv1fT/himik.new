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
}
