<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    protected $table = 'goods';

    protected $fillable = ['article','name','price_opt','count'];
}
