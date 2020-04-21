<?php

namespace App\Http\Controllers;

use App\Advert;
use App\City;
use App\Country;
use App\Job;
use App\Razdel;
use App\Region;
use App\Rezume;
use App\User;
use App\UserAttributes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = Input::get('quote');


        $region = Region::orderBy('name')->get();

        $country = Country::whereIn('id',[0,5,21,52,78,81,89,99,101,106,121,146,191,1])->orderBy('name')->get();

        $city = City::orderBy('name')->get();

        $q = urldecode($q);
        $categories = Razdel::all();

        if($_GET['user'] == 'advert') {
            $posts = Advert::with('types','citys')->
            select('advert.*', 'region.name as region_title')
                ->join('category', 'advert.category', '=', 'category.id')
                ->join('region', 'advert.region', '=', 'region.id')
                ->join('users', 'advert.user_id', '=', 'users.id')

                ->where('advert.title', 'like', '%' . $q . '%')->orderBy('updated_at','desk')->paginate(5);
            return view('search.search')->with('search',$posts)->with('q',$q)->with('region',$region)->with('country',$country)->with('city',$city);
        }
        elseif($_GET['user'] == 'user')
        {
            $posts = User::with('attributes')->
            select('users.*', 'region.name as region_title',
                'city.name as city')
                ->join('users_attributes', 'users.id', '=', 'users_attributes.user_id')
                ->join('region', 'users_attributes.region', '=', 'region.id')
                ->join('city', 'users_attributes.city', '=', 'city.id')
                ->join('country', 'users_attributes.country', '=', 'country.id')
                ->where('users_attributes.description', 'like', '%' . $q . '%')
                ->orwhere('users_attributes.company', 'like', '%' . $q . '%')->paginate(15);

            return view('search.searchCompany')->with('search',$posts)->with('q',$q)->with('country',$country)->with('region',$region)->with('city',$city);
        }elseif($_GET['user'] == 'vacant')
        {

            $posts = Job::with('city_get')->where('description', 'like', '%' . $q . '%')
                ->orwhere('name', 'like', '%' . $q . '%')->paginate(15);


            
            
            return view('search.vacant')->with('search',$posts)->with('q',$q)->with('country',$country)->with('region',$region)->with('city',$city)->with('categories',$categories);
        }elseif($_GET['user'] == 'resume')
        {

            $posts = Rezume::with('city_get')->where('description', 'like', '%' . $q . '%')
                ->orwhere('dolzhnost', 'like', '%' . $q . '%')
                ->paginate(15);

            return view('search.resume')->with('search',$posts)->with('q',$q)->with('country',$country)->with('region',$region)->with('city',$city)->with('categories',$categories);
        }




    }

    public function SearchCompany(Request $request)
    {

        $region = Region::orderBy('name')->get();

        $country = Country::whereIn('id',[0,5,21,52,78,81,89,99,101,106,121,146,191,1])->orderBy('name')->get();
        $city = City::orderBy('name')->get();
        if($request->price_min > 1 && $request->price_max >1)
        {
            $price_max = $request->price_max;
            $price_min = $request->price_min;
        }
        else{
            $price_max = 100000000000000000;
            $price_min = 0;
        }

        if($request->params['user'] == 'advert') {
            if($request->region == "null"){
                $posts = Advert::with('types')->
                select('advert.*','region.name as region_title')
                    ->join('category', 'advert.category', '=', 'category.id')
                    ->join('region', 'advert.region', '=', 'region.id')
                    ->join('users', 'advert.user_id', '=', 'users.id')
                    ->join('type', 'advert.id', '=', 'type.advert_id')

                    ->where([
                        ['advert.country',$request->country],
                        ['advert.title', 'like', '%' . $request->params['quote'] . '%']
                    ])
                    ->orwhere([
                        ['advert.content', 'like', '%' . $request->params['quote'] . '%'],
                        ['advert.country',$request->country],
                    ])
                    //->whereBetween('type.price',[$price_min,$price_max])
                    ->get();
            }
            elseif($request->city == "null"){
                $posts = Advert::with('types')->
                select('advert.*','region.name as region_title')
                    ->join('category', 'advert.category', '=', 'category.id')
                    ->join('region', 'advert.region', '=', 'region.id')
                    ->join('users', 'advert.user_id', '=', 'users.id')
                    ->join('type', 'advert.id', '=', 'type.advert_id')

                    ->where([
                        ['advert.country',$request->country],
                        ['advert.region',$request->region],
                        ['advert.title', 'like', '%' . $request->params['quote'] . '%']
                    ])
                    ->orwhere([
                        ['advert.content', 'like', '%' . $request->params['quote'] . '%'],
                        ['advert.country',$request->country],
                        ['advert.region',$request->region],
                    ])
                    //->whereBetween('type.price',[$price_min,$price_max])
                    ->get();
            }
            else{
                $posts = Advert::with('types')->
                select('advert.*','region.name as region_title')
                    ->join('category', 'advert.category', '=', 'category.id')
                    ->join('region', 'advert.region', '=', 'region.id')
                    ->join('users', 'advert.user_id', '=', 'users.id')
                    ->join('type', 'advert.id', '=', 'type.advert_id')

                    ->where([
                        ['advert.country',$request->country],
                        ['advert.region',$request->region],
                        ['advert.city',$request->city],
                        ['advert.title', 'like', '%' . $request->params['quote'] . '%']
                    ])
                    ->orwhere([
                        ['advert.content', 'like', '%' . $request->params['quote'] . '%'],
                        ['advert.country',$request->country],
                        ['advert.region',$request->region],
                        ['advert.city',$request->city],
                    ])
                    ->get();
            }

            session()->put(['search_region' => $request->region, 'search_city'=>$request->city, 'search_country'=>$request->country]);

            return view('search.particals.search_advert')->with('posts',$posts)->with('q',$request->name)->with('country',$country)->with('region',$region)->with('city',$city);
        }elseif($request->params['user'] == 'vacant')
        {
            if($request->region == "null"){
                if($request->category == "null")
                {
                    
                    $posts = Job::with(array('value'=>function($query){
                        $query->with('citys');
                    }))->with('city_get')
                        ->where([
                            ['name', 'like', '%' . $request->params['quote'] . '%'],
                            ['country',$request->country],
                        ])
                        ->orwhere([
                            ['description', 'like', '%' . $request->params['quote'] . '%'],
                            ['country',$request->country],
                        ])
                        ->paginate(15);
                }
                else{
                    $posts = Job::with(array('value'=>function($query){
                        $query->with('citys');
                    }))->with('city_get')
                        ->where([
                        ['name', 'like', '%' . $request->params['quote'] . '%'],
                        ['country',$request->country],
                        ['category',$request->category],
                    ])
                        ->orwhere([
                            ['description', 'like', '%' . $request->params['quote'] . '%'],
                            ['country',$request->country],
                            ['category',$request->category],
                        ])
                        ->paginate(15);
                }

            }
            elseif($request->city == "null"){
                if($request->category == "null") {
                    $posts = Job::with(array('value' => function ($query) {
                        $query->with('citys');
                    }))->with('city_get')
                        ->where([
                            ['name', 'like', '%' . $request->params['quote'] . '%'],
                            ['region',$request->region],
                        ])
                        ->orwhere([
                            ['description', 'like', '%' . $request->params['quote'] . '%'],
                            ['region',$request->region],
                        ])
                        ->paginate(15);
                }else{
                    $posts = Job::with(array('value' => function ($query) {
                        $query->with('citys');
                    }))->with('city_get')
                        ->where([
                            ['name', 'like', '%' . $request->params['quote'] . '%'],
                            ['region',$request->region],
                            ['category',$request->category],
                        ])
                        ->orwhere([
                            ['description', 'like', '%' . $request->params['quote'] . '%'],
                            ['region',$request->region],
                            ['category',$request->category],
                        ])
                        ->paginate(15);
                }
            }
            else{
                if($request->category == "null") {
                    $posts = Job::with(array('value' => function ($query) {
                        $query->with('citys');
                    }))->with('city_get')
                        ->where([
                            ['name', 'like', '%' . $request->params['quote'] . '%'],
                            ['region',$request->region],
                            ['city',$request->city],
                            ['country',$request->country],
                        ])
                        ->orwhere([
                            ['description', 'like', '%' . $request->params['quote'] . '%'],
                            ['region',$request->region],
                            ['city',$request->city],
                            ['country',$request->country],
                        ])
                        ->paginate(15);
                }else{
                    $posts = Job::with(array('value' => function ($query) {
                        $query->with('citys');
                    }))->with('city_get')
                        ->where([
                            ['name', 'like', '%' . $request->params['quote'] . '%'],
                            ['region',$request->region],
                            ['city',$request->city],
                            ['country',$request->country],
                            ['category',$request->category],
                        ])
                        ->orwhere([
                            ['description', 'like', '%' . $request->params['quote'] . '%'],
                            ['region',$request->region],
                            ['city',$request->city],
                            ['country',$request->country],
                            ['category',$request->category],
                        ])
                        ->paginate(15);
                }
            }


                

            return view('search.particals.search_vacants')->with('search',$posts)->with('q',$request->params['quote'])->with('country',$country)->with('region',$region)->with('city',$city);
        }elseif($request->params['user'] == 'resume')
        {
            if($request->region == "null"){
                if($request->category == "null") {

                    $posts = Rezume::where([
                        ['dolzhnost', 'like', '%' . $request->params['quote'] . '%'],
                        ['country', $request->country]
                    ])
                        ->orwhere([
                            ['description', 'like', '%' . $request->params['quote'] . '%'],
                            ['country', $request->country]
                        ])
                        ->paginate(15);
                }else{

                    $posts = Rezume::where([
                        ['dolzhnost', 'like', '%' . $request->params['quote'] . '%'],
                        ['country', $request->country],
                        ['category', $request->category],
                    ])
                        ->orwhere([
                            ['description', 'like', '%' . $request->params['quote'] . '%'],
                            ['country', $request->country],
                            ['category', $request->category],
                        ])
                        ->paginate(15);
                }
            }
            elseif($request->city == "null"){
                if($request->category == "null") {
                    $posts = Rezume::where([
                        ['dolzhnost', 'like', '%' . $request->params['quote'] . '%'],
                        ['region', $request->region],
                    ])
                        ->orwhere([
                            ['description', 'like', '%' . $request->params['quote'] . '%'],
                            ['region', $request->region],
                        ])->paginate(15);
                }else{
                    $posts = Rezume::where([
                        ['dolzhnost', 'like', '%' . $request->params['quote'] . '%'],
                        ['region', $request->region],
                        ['category', $request->category],
                    ])
                        ->orwhere([
                            ['description', 'like', '%' . $request->params['quote'] . '%'],
                            ['region', $request->region],
                            ['category', $request->category],
                        ])->paginate(15);
                }
            }
            else{
                if($request->category == "null") {
                    $posts = Rezume::where([
                        ['dolzhnost', 'like', '%' . $request->params['quote'] . '%'],
                        ['region', $request->region],
                        ['city', $request->city],
                        ['country', $request->country],
                    ])
                        ->orwhere([
                            ['description', 'like', '%' . $request->params['quote'] . '%'],
                            ['region', $request->region],
                            ['city', $request->city],
                            ['country', $request->country],
                        ])->paginate(15);
                }else{
                    $posts = Rezume::where([
                        ['dolzhnost', 'like', '%' . $request->params['quote'] . '%'],
                        ['region', $request->region],
                        ['city', $request->city],
                        ['country', $request->country],
                        ['category', $request->category],
                    ])
                        ->orwhere([
                            ['description', 'like', '%' . $request->params['quote'] . '%'],
                            ['region', $request->region],
                            ['city', $request->city],
                            ['country', $request->country],
                            ['category', $request->category],
                        ])->paginate(15);
                }
            }


            return view('search.particals.search_resume')->with('search',$posts)->with('q',$request->params['quote'])->with('country',$country)->with('region',$region)->with('city',$city);
        }
        else
        {

            if($request->name == ''){
                if($request->region == "null"){
                    $posts = User::with('attributes')->
                    select('users.*', 'region.name as region_title',
                        'city.name as city')
                        ->join('users_attributes', 'users.id', '=', 'users_attributes.user_id')
                        ->join('region', 'users_attributes.region', '=', 'region.id')
                        ->join('city', 'users_attributes.city', '=', 'city.id')
                        ->where('users_attributes.country',$request->country)
                        ->get();

                }
                elseif($request->city == "null"){
                    $posts = User::with('attributes')->
                    select('users.*', 'region.name as region_title',
                        'city.name as city')
                        ->join('users_attributes', 'users.id', '=', 'users_attributes.user_id')
                        ->join('region', 'users_attributes.region', '=', 'region.id')
                        ->join('city', 'users_attributes.city', '=', 'city.id')
                        ->where('users_attributes.region',$request->region)
                        ->where('users_attributes.country',$request->country)
                        ->get();
                }
                else{
                    $posts = User::with('attributes')->
                    select('users.*', 'region.name as region_title',
                        'city.name as city')
                        ->join('users_attributes', 'users.id', '=', 'users_attributes.user_id')
                        ->join('region', 'users_attributes.region', '=', 'region.id')
                        ->join('city', 'users_attributes.city', '=', 'city.id')
                        ->where('users_attributes.region',$request->region)
                        ->where('users_attributes.city',$request->city)
                        ->where('users_attributes.country',$request->country)
                        ->get();
                }
            }
            else{
                if($request->region == "null"){
                    $posts = User::with('attributes')->
                    select('users.*', 'region.name as region_title',
                        'city.name as city')
                        ->join('users_attributes', 'users.id', '=', 'users_attributes.user_id')
                        ->join('region', 'users_attributes.region', '=', 'region.id')
                        ->join('city', 'users_attributes.city', '=', 'city.id')
                        ->where([
                            ['users_attributes.name', 'like', '%' . $request->name . '%'],
                            ['users_attributes.country',$request->country],
                        ])
                        ->orwhere([
                            ['users_attributes.description', 'like', '%' . $request->name . '%'],
                            ['users_attributes.country',$request->country],
                        ])
                        ->get();
                }
                elseif($request->city == "null"){
                    $posts = User::with('attributes')->
                    select('users.*', 'region.name as region_title',
                        'city.name as city')
                        ->join('users_attributes', 'users.id', '=', 'users_attributes.user_id')
                        ->join('region', 'users_attributes.region', '=', 'region.id')
                        ->join('city', 'users_attributes.city', '=', 'city.id')
                        ->where([
                            ['users_attributes.name', 'like', '%' . $request->name . '%'],
                            ['users_attributes.region' , $request->region],
                            ['users_attributes.country' , $request->country],
                        ])
                        ->orwhere([
                            ['users_attributes.description', 'like', '%' . $request->name . '%'],
                            ['users_attributes.region' , $request->region],
                            ['users_attributes.country', $request->country],
                        ])
                        ->get();
                }
                else{
                    $posts = User::with('attributes')->
                    select('users.*', 'region.name as region_title',
                        'city.name as city')
                        ->join('users_attributes', 'users.id', '=', 'users_attributes.user_id')
                        ->join('region', 'users_attributes.region', '=', 'region.id')
                        ->join('city', 'users_attributes.city', '=', 'city.id')
                        ->where([
                            ['users_attributes.name', 'like', '%' . $request->name . '%'],
                            ['users_attributes.region' , $request->region],
                            ['users_attributes.country' , $request->country],
                            ['users_attributes.city' , $request->city],
                        ])
                        ->orwhere([
                            ['users_attributes.description', 'like', '%' . $request->name . '%'],
                            ['users_attributes.region' , $request->region],
                            ['users_attributes.country', $request->country],
                            ['users_attributes.city', $request->city],
                        ])
                        ->get();
                }

            }




            session()->put(['search_region' => $request->region, 'search_city'=>$request->city, 'search_country'=>$request->country]);

            return view('search.particals.searchCompany')->with('search',$posts)->with('q',$request->name)->with('country',$country)->with('region',$region)->with('city',$city);
        }




    }
}
