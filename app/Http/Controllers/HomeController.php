<?php

namespace App\Http\Controllers;

use App\City;
use App\Country;
use App\Blog;
use App\Home;
use App\Advert;
use App\Region;
use App\User;
use Date\DateFormat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function index(Home $home,Request $request)
    {
	    // if($_SERVER['SERVER_NAME'] != "opt-himik.ru"){
		//     return redirect('https://opt-himik.ru');
	    // }

	    if(isset($request->region) and $request->region != "clear") {
		    Session::forget('regiones');
		    Session::forget('regiones_id');
		    Session::forget('type_regions');
		    $region = $request->region;
		    $region_tab0 = Region::where('region.slug',$region)->get()->toArray();
	        $region_tab1 = City::where('slug',$region)->get()->toArray();
	        $region_tab2 = Country::where('slug',$region)->get()->toArray();

	        $region_tab = array_merge($region_tab0,$region_tab1,$region_tab2);


	        session()->put('regiones',$region_tab[0]['name']);
	        session()->put('regiones_id',$region_tab[0]['id']);
	        session()->put('type_regions',$region_tab[0]);

	        return redirect('/');

	    }elseif($request->region == "clear"){
		    Session::forget('regiones');
		    Session::forget('regiones_id');
		    Session::forget('type_regions');
	    }

        Carbon::setLocale('ru');
        #$this->data['blog'][0]['time'] = Carbon::createFromFormat('Y-m-d H:i:s',$this->data['blog'][0]->created_at)->diffForHumans();



			$top_day = Cache::remember('top_day', '1', function () {
            	return Advert::orderBy('views_day', 'desc')->first();
        	});

	        $new_adverts = Cache::remember('new_adverts', '1', function () {
	            return Advert::with('types')->where(['status'=> 1, 'show'=>1])->orderBy('created_at','desc')->take(9)->get();
	        });

	        $blogs = Cache::remember('blogs', '1', function () {
	            return Blog::latest()->where('active',1)->take(5)->get()->groupBy(function($date) {
	                return $date->created_at->formatLocalized('%d %B %Y');
	            });
	        });

	        $companies = Cache::remember('companies2', '1', function () {
	            return User::with('attributes')
	                ->withCount('adverts')
	                ->where('status','<','4')
	                ->where('id','!=',2)
	                ->orderBy('adverts_count','DESC')
	                ->take(15)
	                ->get();
	        });




			return view('home',compact('top_day','new_adverts','blogs','companies'));
    }

    public function verify($token)
    {
        $user = User::where('token','=',$token)->get();

        User::where('id',$user[0]['id'])->update(array('status'=>'1'));

        return redirect('/profile');
    }
    public function Logout(Request $request)
    {
//        dd("123123123");
        Auth::logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect('/');
    }




    public function EmailChange()
    {
        return view('auth.passwords.change_email');
    }

    public function PasswordChange()
    {
        return view('auth.passwords.change_pass');
    }

    public function EmailChanger(Request $request)
    {
        $data = User::where('email',$request->old_email)->first();


        if(count($data) >= 1)
        {
            $data = User::find($data->id);
            $data->email = $request->email;
            $data->save();

            return redirect()->back()->with('status','Email успешно изменен. Необходимо перезайти что бы изменения вступили в силу');
        }
        else
        {
            return redirect()->back()->with('danger','Учетных данных с данным email не найдено');
        }

    }

    public function PasswordChanger(Request $request)
    {

        $data = User::where('email',$request->email)->first();

        if(count($data) >= 1)
        {
            $data = User::find($data->id);
            $data->password = bcrypt($request->password);
            $data->save();

            return redirect()->back()->with('status','Пароль успешно изменен успешно изменен. Необходимо перезайти что бы изменения вступили в силу');
        }
        else
        {
            return redirect()->back()->with('danger','Учетных данных с данным email не найдено');
        }

    }
}
