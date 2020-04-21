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

class JobsController extends Controller
{
    public function showPage()
    {
        $rezume = Rezume::with('city_get')->where('status',1)->orderBy('id','desk')->get();
        $razdel = Razdel::all();
        $vacant = Job::with(array('value'=>function($query){
        $query->with('citys');
    }))->with('city_get')->where('status',1)->paginate(7);
        $region_job = Region::orderBy('name','asc')->get();
        $country_job = Country::all();
        #dd($job);
        return view('jobs.jobs',compact('razdel','rezume','vacant', 'region_job', 'country_job'));
    }
}
