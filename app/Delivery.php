<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Delivery extends Model
{
    use Notifiable;

    protected $fillable = ['category', 'email', 'subcategory'];

    protected $table = 'delivery';

    public function routeNotificationForMail()
    {
        return $this->email;
    }
}
