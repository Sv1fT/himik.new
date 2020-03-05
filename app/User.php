<?php

namespace App;

use App\Observers\UserObserver;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function attributes(){
        return $this->hasOne(UserAttributes::class,'user_id');
    }

    public function adverts()
    {
        return $this->hasMany(Advert::class);
    }

    public function favorite_adverts()
    {
        return $this->hasMany(UserFavoriteAdverts::class);
    }

    public function messages()
    {
        return $this->hasMany(UserMessages::class);
    }

    public static function boot() {

        parent::boot();

        parent::observe(new UserObserver());
    }
}
