<?php
/**
 * Created by PhpStorm.
 * User: Sv1fT
 * Date: 16.11.2016
 * Time: 18:22
 */

namespace App;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\Translator;


/**
 * App\Home
 *
 * @mixin \Eloquent
 */
class Home extends Model
{
    var $regions;

    public function __construct()
    {
        $this->regions = session()->get('regiones_id');
    }

    public function getCountadvert()
    {
        $advert = Advert::query();
        if(!empty($this->regions)){
	        return $advert_home = Cache::remember('advert_home_region_'.$this->regions, '1800', function () use ($advert) {
            	return $advert->where('region',$this->regions)
                ->orWhere('city',$this->regions)
                ->orWhere('country',$this->regions)->count();
            });    

        }else{
	        return $advert_home = Cache::remember('advert_home', '1800', function () use ($advert) {
            	return $advert->count(); 
			});
        }
    }

    public function getUsers()
    {
        $user = UserAttributes::query();
        if(!empty($this->regions)){
	        return $user_home = Cache::remember('user_home_region_'.$this->regions, '1800', function () use ($user) {
            	return $user->where('region',$this->regions)
                ->orWhere('city',$this->regions)
                ->orWhere('country',$this->regions)->count();
            });

        }else{
	        return $user_home = Cache::remember('user_home', '1800', function () use ($user) {
            	return $user->count();
			});
        }
    }

    public function getCategory()
    {
	    return $category = Cache::remember('category_home', '1800', function () {
        	return Category::where('id','<>','36')->count();
        });
    }

    public function getSubcategory()
    {
	    return $subcategory = Cache::remember('subcategory_home', '1800', function () {
        	return Subcategory::count();
        });
    }

    public function getNewadvertCreate()
    {
        $advert = Advert::query();
        if(!empty($this->regions)){
	        return $advert_create = Cache::remember('advert_home_create_region_'.$this->regions,'1800', function() use ($advert){
		        return $advert->with('citys','types')
                ->where('advert.show','=','1')
                ->where('status',1)
                ->take(3)
                ->orderBy('created_at','desc')
                ->where('region',$this->regions)
                ->orWhere('city',$this->regions)
                ->orWhere('country',$this->regions)->get();
	        });
        }else{
	        return $advert_create = Cache::remember('advert_home_create','1800', function() use ($advert){
	            return $advert->with('citys','types')
	                ->where('advert.show','=','1')
	                ->where('status',1)
	                ->take(3)
	                ->orderBy('created_at','desc')
	                ->get();
	        });        
        }

    }
    public function getNewadvertUpdate()
    {
	    $advert = Advert::query();
        if(!empty($this->regions)){
	        return $advert_create = Cache::remember('advert_home_update_region_'.$this->regions,'1800', function() use ($advert){
		        return $advert->with('citys','types')
                ->where('advert.show','=','1')
                ->where('status',1)
                ->take(3)
                ->orderBy('date','desc')
                ->where('region',$this->regions)
                ->orWhere('city',$this->regions)
                ->orWhere('country',$this->regions)->get();
	        });
        }else{
	        return $advert_create = Cache::remember('advert_home_update','1800', function() use ($advert){
	            return $advert->with('citys','types')
	                ->where('advert.show','=','1')
	                ->where('status',1)
	                ->take(3)
	                ->orderBy('date','desc')
	                ->get();
	        });        
        }
    }

    public function productDay()
    {
	    return $top_advert = Cache::remember('advert_home_top','1800', function() {
        	return Advert::with('citys','types')->where('view_status',3)->first();
        });	
    }

    public function Vacants()
    {
        return Job::orderBy('id', 'DESC')->where('status', 1)->take(3)->get();
    }

    public function Resume()
    {
        return Rezume::with('attributes')->where('status',1)->orderBy('id','DESC')->take(5)->get();
    }

    public function getBlog()
    {
	    return $blog = Cache::remember('blog_home','1800', function() {
	        return $data = Blog::latest()->where('active',1)->take(5)->get()->groupBy(function($date) {
	            return $date->created_at->formatLocalized('%d %B %Y');
	        });
	    });    
    }

    public function getRegions()
    {
        return $regions = Cache::remember('regions','1800',function () {
            return Region::orderBy('id', 'DESC')->get();
        });
    }

    public function getSlider()
    {
	    return $slider = Cache::remember('user_slider','1800', function() {
        	return User::with('attributes')
            ->select('users.*',DB::raw('(select count(*) from advert where user_id = users.id) as count_sub'))
            ->where('status','<','4')
            ->where('id','!=',2)
            ->orderBy('count_sub','DESC')
            ->take(15)
            ->get();
        });    
    }

}
