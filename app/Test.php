<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Date\DateFormat;

abstract class Test extends Eloquent
{
    public function getCreatedAttribute($attr)
    {
        return DateFormat::post($attr);
    }
}
