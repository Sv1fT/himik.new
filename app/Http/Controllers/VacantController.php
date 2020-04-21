<?php

namespace App\Http\Controllers;

use App\Country;
use App\City;
use App\Job;
use App\Razdel;
use App\Region;
use App\Rezume;
use App\Notifications\VacantMail;
use App\User;
use App\UserAttributes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Constraint;
use Intervention\Image\Facades\Image;
use TCG\Voyager\Facades\Voyager;

class VacantController extends Controller
{
    public function showVacants($id)
    {
        $vacant = Job::with(array('value'=>function($query){
        $query->with('citys');
    }))->with('city_get')->where('slug',$id)->get();
        $jobs_new = Job::with('value')->where('slug','<>',$id)->paginate(5);
        return view('lk.vacant_show',compact('vacant','jobs_new'));
    }

    public function showVacant()
    {
        $razdel = Razdel::all();
        $vacant = Job::with('attributes')->where('user_id',Auth::id())->paginate(7);
        $region = Region::orderBy('name','asc')->get();
        $country = Country::whereIn('id',[0,5,21,52,78,81,89,99,101,106,121,146,191,1])->orderBy('name','asc')->get();
        $city = City::orderBy('name','asc')->get();
        return view('lk.vacant',compact('razdel','vacant','region','city','country'));
    }

    public function addVacantion(Request $request)
    {
        $user = UserAttributes::with('values')->where('user_id',Auth::id())->first();
        $vacant = new Job();
        $vacant['city'] = $request->city;
        $vacant['region'] = $user->region;
        $vacant['country'] = $user->country;
        $vacant['user_id']= Auth::id();
        $vacant['category']= $request['razdel'];
        $vacant['name']= $request['name'];
        $vacant['price']= $request['price1'];
        $vacant['price1']= $request['price2'];
        $vacant['valute']= $request['valute'];
        $vacant['status']= 0;
        $vacant['opit']= $request['opit'];
        $vacant['education']= $request['education'];
        $vacant['description']= $request['description'];
        $vacant->save();

        $vacant_up = Job::find($vacant->id);
        $vacant_up['slug'] = str_slug($request['name'].'-'.$vacant->id);
        $vacant_up->save();
        return redirect()->back();
    }

    public function editVacant($id)
    {

        $razdel = Razdel::all();
        $vacant = Job::with('city_get')->where('id',$id)->get();
        $city = City::orderBy('name','asc')->get();
        return view('vacant.edit.vacant',compact('vacant','city','razdel'));
    }

    public function MailerVacant(Request $request) {
        $prev_url = URL::previous();
        $vacant = Job::where('slug',$request->slug)->pluck('user_id');
        $vacants = Job::where('slug',$request->slug)->pluck('name');
        $vacants = $vacants[0];
        $users = User::find($vacant[0]);
        $name = $request->name;
        $phone = $request->phone;
        $email = $request->email;
        $description = $request->content;
        Notification::send($users, new VacantMail($name,$phone,$email,$description,$prev_url,$vacants));

        return redirect()->back();
    }

    public function deleteVacant($id)
    {
        Job::where('id',$id)->delete();

    }

    public function editVacantPost(Request $request,$id)
    {
        $user = UserAttributes::with('values')->where('user_id',Auth::id())->first();

        $vacant = Job::find($id);
        $vacant->city = $request->city;
        $vacant->category = $request->razdel;
        $vacant->country = $user->country;
        $vacant->region = $user->region;
        $vacant->name = $request->name;
        $vacant->slug = str_slug($request->name)."-".$id;
        $vacant->price = $request->price1;
        $vacant->price1 = $request->price2;
        $vacant->valute = $request->valute;
        $vacant->opit = $request->opit;
        $vacant->education = $request->education;
        $vacant->description = $request->description;
        $vacant->save();

        return redirect('vacant');
    }

}
