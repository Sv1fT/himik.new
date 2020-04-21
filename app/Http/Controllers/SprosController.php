<?php

namespace App\Http\Controllers;

use App\Advert;
use App\City;
use App\Country;
use App\Region;
use Illuminate\Http\Request;

class SprosController extends Controller
{
    public function showSpros()
    {
        $spros = Advert::with('types','citys')->
        select('advert.*','region.name as region_title')
            ->join('region','advert.region','=','region.id')
            ->where('advert.show','=','0')
            ->where('status',1)
            ->orderBy('advert.id','DESC')->paginate(15);


        $region = Region::where('country_id','=',0)->orderBy('name')->get();
        $city = City::orderBy('name')->get();

        $country = Country::whereIn('id',[0,5,21,52,78,81,89,99,101,106,121,146,191,1])->orderBy('name')->get();

        return view('spros.spros',compact('spros','region','country','city'));
    }

    public function postSpros(Request $request)
    {

        $spros = new Advert();

        $spros->title = $request->title;
        $spros->slug = str_slug($request->title);
        $spros->region = $request->region;
        $spros->country = $request->country;
        $spros->city = $request->city;
        $spros->number = $request->number;
        $spros->email = $request->email;
        $spros->content = $request->content;
        $spros->category = '36';
        $spros->subcategory = '339';
        $spros->user_id = '2';
        $spros->show = '0';
        $spros->status = '0';

        $spros->save();





        Advert::where('id',$spros->id)->update(array('slug' => $request->title.'-'.$spros->id));

        return redirect()->back()->with('status','Ваше объявление на модерации');
    }
}
