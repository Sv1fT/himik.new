<?php

namespace App\Http\Controllers;

use App\Blog;
use App\Category;
use App\City;
use App\Job;
use App\Mails;
use App\Region;
use App\Rezume;
use App\User;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use App\Advert;
use App\Subcategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use App\VK\Vk;


class TestController extends Controller
{
    public function index()
    {
        $advert = Advert::get();


        Carbon::setLocale('ru');
        $dt = Carbon::createFromFormat('Y-m-d H:i:s',$advert[0]->created_at)->diffForHumans();

        dd($dt);

    }

    public function SubcategorySelect($id)
    {

        $array = Subcategory::where('category_id','=',$id)->orderBy('title','asc')->get();



        return $array;
    }

    public function regionSelect($id)
    {
        $array = City::where('region_id',$id)->get();
        return $array;
    }

    public function citySelect($id)
    {
        $array = City::where('name','LIKE','%'.$id."%")->get();
        return $array;
    }

    public function countrySelect($id)
    {
        $array = Region::where('country_id',$id)->get();

        return $array;
    }



    public function emailsend(Request $request)
    {
        $date = $request->url;
        $slug = $pieces = explode("/", $date);
        dd($slug);
        $user_name = Advert::with('value')->where('slug',$slug['4'])->first();
        $user_mail = User::find($user_name->user_id);

        #$emailu = DB::table('user')->where('id','=',$request->input('userid'))->get();
        $name = $request->input('name');
        $email = $request->input('email');
        $description = $request->input('content');
        $url = $_SERVER['HTTP_REFERER'];
        $phone = $request->input('phone');

        $city = file_get_contents('http://api.sypexgeo.net/json/' . $request->getClientIp());
        $city = json_decode($city);

        $mail = new Mails();
        $mail->name = $name;
        $mail->email=$email;
        $mail->massage=$description;
        $mail->number=$phone;
        $mail->url=$url;
        $mail->country=$city->country->name_ru;
        $mail->region=$city->region->name_ru;
        $mail->city=$city->city->name_ru;
        $mail->ip=$_SERVER['REMOTE_ADDR'];
        $mail->save();


        Mail::send('emails.feedback',['url'=>$url,'name'=>$name,'email'=>$email,'phone'=>$phone,'content'=>$description], function ($u) use ($request){
            $u->to($request->input('user_email'),'123')->subject('Вам ответили');
        });
        return Redirect::back();
    }

    public function emailsendVacant(Request $request)
    {

        $date = $request->url;
        $slug = $pieces = explode("/", $date);
        if($slug['3'] == 'vacant')
        {
            $user_name = Job::with('value')->where('slug',$slug['4'])->first();
        }
        else{
            $user_name = Rezume::with('attributes')->where('slug',$slug['4'])->first();
        }
        
        $user_mail = User::find($user_name->user_id);
        #$emailu = DB::table('user')->where('id','=',$request->input('userid'))->get();
        $name = $request->input('name');
        $email = $request->input('email');
        $description = $request->input('content');
        $url = $_SERVER['HTTP_REFERER'];
        $phone = $request->input('phone');

        $city = file_get_contents('http://api.sypexgeo.net/json/' . $request->getClientIp());
        $city = json_decode($city);

        $mail = new Mails();
        $mail->name = $name;
        $mail->email=$email;
        $mail->massage=$description;
        $mail->number=$phone;
        $mail->url=$url;
        $mail->country=$city->country->name_ru;
        $mail->region=$city->region->name_ru;
        $mail->city=$city->city->name_ru;
        $mail->ip=$_SERVER['REMOTE_ADDR'];
        $mail->save();

        if($slug['3'] == 'vacant')
        {
            Mail::send('emails.vacants',['user_name'=>$user_name->value->name,'url'=>$url,'name'=>$name,'email'=>$email,'phone'=>$phone,'content'=>$description], function ($u) use ($user_mail){
                $u->to($user_mail->email,'123')->subject('Вам ответили');
            });
        }
        else{
            Mail::send('emails.vacants',['user_name'=>$user_name->fio,'url'=>$url,'name'=>$name,'email'=>$email,'phone'=>$phone,'content'=>$description], function ($u) use ($user_name){
                $u->to($user_name->email,'123')->subject('Вам ответили');
            });
        }
        
        return Redirect::back();
    }

    public function sitemap()
    {
        $blogs = Blog::all();
        $adverts = Advert::with('citys')->get();
        $companys = User::all();
        $categorys = Category::all();
        $subcategorys = Subcategory::all();
        $vacants = Job::all();
        $resumes = Rezume::all();
        $regions = Region::where('country_id',0)->get();
        return response()->view('sitemap.index', compact('blogs','adverts','companys','categorys','subcategorys','vacants','resumes','regions'))->header('Content-Type', 'text/xml');
    }

    public function advert_4oclock()
    {

        $status = Advert::where('view_status',3)->get();

        if($status)
        {

            foreach ($status as $item)
            {
                $status = Advert::find($item->id);
                $status->view_status = null;
                $status->save();
            }

        }

        $top = DB::table('advert')->where('show',1)->orderBy('views_day','desc')->first();

        $top = Advert::find($top->id);
        $top->view_status = '3';
        $top->save();

        
        
    }

    public function update_advert_admin(Request $request)
    {
        $val = $request->id;
        foreach ($val as $item){

            Advert::where('id',$item)->update(['status'=>'1']);
        }
    }

    public function vk()
    {
        $accessToken = '2afa5f7641bda9c2068160a6be6ff7af2b2f861d821b9f778340bbf552b20398a849a406a1d41189e0d8a';

        $vkAPI = new Vk(['access_token' => $accessToken]);
        
        $publicID = '166807572';

        if ($vkAPI->postToPublic($publicID, "Привет Хабр!", '/images/logo.png', ['вконтакте api', 'автопостинг', 'первые шаги'])) {

            echo "Ура! Всё работает, пост добавлен\n";

        } else {

            echo "Фейл, пост не добавлен(( ищите ошибку\n";
        }
    }

    public function keywords(){
        

        $advert = Advert::all();
        $url = array();
        foreach ($advert as $value) {
            $advert1 = (explode(" ", $value->title));

            foreach ($advert1 as $value1) {
                array_push($url,"https://opt-himik.ru/search?user=advert&quote=".str_replace(",","", $value1));
                
            }
            
        }
        return response()->view('sitemap.search', compact('url'))->header('Content-Type', 'text/xml');
    }
}
