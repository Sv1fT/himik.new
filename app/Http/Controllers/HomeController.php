<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Advert;
use App\Blog;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('web');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $top_day = Cache::remember('top_day', '1800', function () {
            return Advert::orderBy('views_day', 'desc')->first();
        });

        $new_adverts = Cache::remember('new_adverts', '1800', function () {
            return Advert::with('types')->where(['status'=> 1, 'show'=>1])->orderBy('created_at','desc')->take(6)->get();
        });

        $blogs = Cache::remember('blogs', '1800', function () {
            return Blog::latest()->where('active',1)->take(5)->get()->groupBy(function($date) {
                return $date->created_at->formatLocalized('%d %B %Y');
            });
        });
        return view('home',compact('top_day','new_adverts','blogs'));
    }
}
