<?php

namespace App\Http\Controllers;

use App\Advert;
use App\Category;
use App\City;
use App\Job;
use App\Country;
use App\Delivery;
use App\Notifications\WorkoutAssigned;
use App\Subcategory;
use App\User;
use App\UserAttributes;
use Arrilot\Widgets\Test\Dummies\Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use PDO;
use App\Region;

class TsbController extends Controller
{
    public function ShowPage()
    {
        $category = Category::select('category.*', DB::raw('(select count(*) from advert where advert.category = category.id) as count_sub'))
            ->where('category.id', '<>', '36')
            ->orderBy('title', 'ASC')
            ->get();

        $title = str_slug($category[0]->title, "-");

        $subcategory = Subcategory::
        select(DB::raw('count(*) as subcategory_count'))
            ->get();

        $subcat_all = Subcategory::
        where('subcategory.id', '<>', 339)
            ->orderBy('title', 'ASC')
            ->get();
        $count = DB::table('delivery')->select(DB::raw('count(distinct(email)) as count'))->get();


        return view('tsb.tsb')->with('category', $category)->with('subcategory', $subcategory)->with('url', $title)->with('subcat_all', $subcat_all)->with('counts', $count);
    }


    public function ShowPageCountry($id)
    {
        $region_title = Country::where('id', $id)->first();

        $advertcount = Advert::select(DB::raw('count(*) as advert_count'))
            ->where('advert.country', '=', $id)
            ->get();

        $category = Category::select('category.*', DB::raw('(select count(*) from advert where advert.category = category.id and advert.country = ' . $id . ') as count_sub'))
            ->where('category.id', '<>', '36')
            ->orderBy('title', 'ASC')
            ->get();

        $users = User::select(DB::raw('count(*) as user_count'))
            ->get();

        $title = str_slug($category[0]->title, "-");

        $subcategory = Subcategory::select(DB::raw('count(*) as subcategory_count'))
            ->get();

        $advert = Advert::with('types')->
        select('advert.*', 'category.title AS categorytitle', 'region.name AS region_title', 'advert.show AS `show`')
            ->join('region', 'advert.region', '=', 'region.id')
            ->join('category', 'advert.category', '=', 'category.id')
            ->join('users', 'advert.user_id', '=', 'users.id')
            ->where('advert.country', $id)
            ->where('advert.status', 1)
            ->orderBy('advert.updated_at', 'desc')->paginate(15);


        return view('regions.tsb')->with('regions_title', $region_title)->with('advert', $advert)->with('advertcount', $advertcount)->with('users', $users)->with('category', $category)->with('subcategory', $subcategory)->with('url', $title);
    }


    public function ShowSubcategoryRegions($id, $region)
    {

        $subcategory = Subcategory::select('subcategory.*', DB::raw('(select count(*) from advert where advert.subcategory = subcategory.id and advert.region = ' . $id . ') as count_adv'), 'category.title as category_title')
            ->join('category', 'category.id', '=', 'subcategory.category')
            ->where('category', '=', $region)
            ->get();

        $title = str_slug($subcategory[0]->title, "-");

        return view('regions.subcategory')->with('subcategory', $subcategory)->with('url', $title);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */


    public function addfollowers(Request $data)
    {


        $v = Validator::make($data->all(), [
            'category' => 'required',
            'subcategory' => 'required',
            'email' => 'required|email|max:255',
        ]);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v->errors());
        }
        $delivery = Delivery::create($data->except('_token'));

        $category = Category::where('id', $data->category)->get();


        $subcategory = Subcategory::where('id', $data->subcategory)->get();
        $sends = Delivery::select('email')->where('email', '=', $data->email)->first();


        Notification::send($sends, new WorkoutAssigned($delivery, $category, $subcategory));

        return back()->with('status', 'Вы подписались на рассылку');

    }

    public function editPost(Request $request, $id)
    {

        $user_id = Auth::user()->id;
        if ($request->file('file') != '') {
            $filename = $request->file('file')->getClientOriginalName();
            $Path = public_path('image/archive/' . $user_id . '/register/picture');
            $Path_load = 'image/archive/' . $user_id . '/register/picture';
            $request->file('file')->move($Path, $filename);
            $data = $request->except(['file']);
            $data['file'] = $filename;
            $request->request->add(['filename' => $filename]);

        } else {

            $filename = Advert::select('filename')->where('user_id', '=', $user_id)->where('id', '=', $id)->get();

            $filename = $filename[0]->filename;

            $request->request->add(['filename' => $filename]);

            $carbon = Carbon::now();

            $request->request->add(['updated_at' => $carbon]);

        }

        Advert::where('id', '=', $id)->where('user_id', '=', $user_id)->update($request->except('_token', 'file'));

        return redirect()->back();
    }

    public function deletePost($id)
    {

    }


}
