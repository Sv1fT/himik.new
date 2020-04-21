<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use App\User;

class Advert extends Model
{
    protected $fillable = ['title', 'content', 'price', 'type', 'mass', 'user_id', 'category', 'status', 'subcategory', 'sity', 'region', 'number', 'email', 'show','filename'];
    protected $table = 'advert';

    protected $timestamp = false;

    public function getCreateDateAttribute()
    {
        return \Carbon\Carbon::parse($this->created_at)->formatLocalized('%d %B %Y');
    }

    public function scopeActive($query) {
        return $query->where('status',1);
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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // public function favorite()
    // {
    //     return $this->hasOne(UserFavoriteAdverts::class);
    // }

    public function statuses()
    {
        return $this->hasOne(Status::class,'id','status');
    }

    public function getFilename($img, $h, $w){

	   $img = \Image::make(public_path("/storage/$img"))->resize($h, $w);
		    $img->encode('jpg');
		    $type = 'jpg';
			return $base64 = 'data:image/' . $type . ';base64,' . base64_encode($img);
    }

}
