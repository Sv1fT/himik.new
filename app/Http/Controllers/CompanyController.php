<?php

namespace App\Http\Controllers;

use App\Advert;
use App\Region;
use App\Subcategory;
use App\User;
use App\Category;
use App\UserAttributes;
use App\Blog;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(){

    }

    public function showCompany()
    {
        $advertcount = Advert::
        select(DB::raw('count(*) as advert_count'))
            ->get();

        $category = Category::
        select('category.*', DB::raw('(select count(*) from advert where advert.category = category.id) as count_sub'))
            ->where('category.id', '<>', '36')
            ->orderBy('title', 'ASC')
            ->get();

        $attributes = UserAttributes::with('values')
            ->where('user_id', '!=', 2)
            ->where('user_id', '!=', 47)
            ->join('region', 'region.id', '=', 'users_attributes.region')
            ->join('city', 'city.id', '=', 'users_attributes.city')
            ->join('country', 'country.id', '=', 'users_attributes.country')
            ->select('users_attributes.*', DB::raw('(select count(*) from advert where user_id = users_attributes.user_id) as count_sub'), 'region.name AS region_title', 'city.name as city_title')->orderBy('user_id', 'DESC')->paginate(7);

        $blog = User::with([
            'attributes' => function ($q) {
                $q
                    ->join('region', 'region.id', '=', 'users_attributes.region')
                    ->select('users_attributes.*', 'region.name AS region_title');
            }
        ])->
        select('users.*', DB::raw('(select count(*) from advert where user_id = users.id) as count_sub'))
            ->where('users.id', '!=', 2)
            ->where('users.id', '!=', 47)
            ->orderBy('users.id', 'DESC')->pluck('id')->toArray();

        $blogget = Blog::whereIn('user_id', $blog)->first();

        $companyes = UserAttributes::with('values')
            ->where('user_id', '!=', 2)
            ->where('user_id', '!=', 47)
            ->join('region', 'region.id', '=', 'users_attributes.region')
            ->join('city', 'city.id', '=', 'users_attributes.city')
            ->join('country', 'country.id', '=', 'users_attributes.country')
            ->select('users_attributes.*', DB::raw('(select count(*) from advert where user_id = users_attributes.user_id) as count_sub'), 'region.name AS region_title', 'city.name as city_title')->orderBy('count_sub', 'DESC')->take(12)->get();


        $users = User::
        select(DB::raw('count(*) as user_count'))
            ->get();

        $subcategory = Subcategory::
        select(DB::raw('count(*) as subcategory_count'))
            ->get();

        return view('company.company')
            ->with('advertcount', $advertcount)
            ->with('users', $users)
            ->with('category', $category)
            ->with('subcategory', $subcategory)
            ->with('companys', $attributes)
            ->with('companyes', $companyes)
            ->with('blogget', $blogget);
    }
}
