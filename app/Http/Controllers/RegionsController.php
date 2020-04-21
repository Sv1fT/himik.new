<?php

namespace App\Http\Controllers;
use App\Advert;
use App\Category;
use App\City;
use App\Country;
use App\Region;
use App\Subcategory;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RegionsController extends Controller
{
    public function showRegions()
    {
        $advertcount = Advert::select(DB::raw('count(*) as advert_count'))
            ->get();

        $category =Category::select('category.*',DB::raw('(select count(*) from advert where advert.category = category.id) as count_sub'))
            ->where('category.id','<>','36')
            ->orderBy('title','ASC')
            ->get();

        $regions =Region::select('region.*',DB::raw('(select count(*) from advert where advert.region = region.id) as count_reg'))
            ->where('country_id',0)->orderBy('name','ASC')->get();

        $countrys =Country::select('country.*',DB::raw('(select count(*) from advert where advert.country = country.id) as count_reg'))
            ->whereIn('id',[0,5,21,52,78,81,89,99,101,106,121,146,191,1])
            ->orderBy('name','ASC')
            ->get();



        $users = User::select(DB::raw('count(*) as user_count'))
            ->get();

        $title = str_slug($category[0]->title, "-");

        $subcategory = Subcategory::
            select(DB::raw('count(*) as subcategory_count'))
            ->get();


        return view('regions')
            ->with('advertcount', $advertcount)
            ->with('users', $users)
            ->with('category', $category)
            ->with('subcategory', $subcategory)
            ->with('regions',$regions)
            ->with('countrys',$countrys)
            ->with('url',$title);
    }

    public function showRegionsadv($id)
    {
        $region_adv = Advert::
        select('advert.*','category.title AS categorytitle','region.name AS region_title','users_attributes.*')
        ->join('region', 'advert.region','=','region.id')
        ->join('category', 'advert.category','=','category.id')
        ->join('users','advert.user_id','=','users.id')->
                join('users_attributes','users.id','=','users_attributes.user_id')->
        where('advert.region','=',$id)->paginate(15);

        return view('advert')->with('advert',$region_adv);
    }

    public function ShowPageRegions($id)
    {
        $region_title = Region::where('id',$id)->first();


        $advertcount = Advert::select(DB::raw('count(*) as advert_count'))
            ->where('advert.region','=',$id)
            ->get();

        $category =Category::select('category.*',DB::raw('(select count(*) from advert where advert.category = category.id and advert.region = '.$id.') as count_sub'))
            ->where('category.id','<>','36')
            ->orderBy('title','ASC')
            ->get();

        $users = User::select(DB::raw('count(*) as user_count'))
            ->get();

        $title = str_slug($category[0]->title, "-");

        $subcategory = Subcategory::select(DB::raw('count(*) as subcategory_count'))
            ->get();

        $count = DB::table('delivery')->select(DB::raw('count(distinct(email)) as count'))->get();

        $advert = Advert::with('types','citys')->
        select('advert.*','category.title AS categorytitle','region.name AS region_title','advert.show AS `show`')
            ->join('region', 'advert.region','=','region.id')
            ->join('category', 'advert.category','=','category.id')
            ->join('users','advert.user_id','=','users.id')
            ->orwhere('advert.region',$id)
            ->where('advert.status',1)
            ->orderBy('advert.updated_at','desc')->paginate(15);


        return view('regions.tsb')->with('regions_title',$region_title)->with('advert',$advert)->with('counts',$count)->with('advertcount', $advertcount)->with('users', $users)->with('category', $category)->with('subcategory', $subcategory)->with('url',$title);
    }

    public function regionSelectPost($name)
    {

        //$this->data['regions'] = $region->getRegions($name);
        $array0 = Region::where('name','LIKE','%'.$name."%")->get()->toArray();
        $array1 = City::where('name','LIKE','%'.$name."%")->get()->toArray();
        $array2 = Country::where('name','LIKE','%'.$name."%")->get()->toArray();

        $array = array_merge($array0,$array1,$array2);








        return $array;

    }
}
