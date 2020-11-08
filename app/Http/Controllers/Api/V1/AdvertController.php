<?php

namespace App\Http\Controllers\Api\V1;

use App\Advert;
use App\City;
use App\UserFavoriteAdverts;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class AdvertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $adverts = Advert::active()
        ->category($request->category)
        ->subcategory($request->subcategory)
        ->price($request->price)
        ->region($request->region)
        ->show($request->type)
        ->with('types','statuses')
        ->paginate(24);

        // dd($adverts);
        foreach($adverts as $advert){
            $advert->filename = Storage::disk('public')->exists($advert->filename) ? '/storage/'.$advert->filename : 'https://image.freepik.com/free-vector/error-404-found-glitch-effect_8024-4.jpg';
        }

        return $adverts;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($slug)
    {
        $advert = Advert::with('types','city','region','country','user')->whereSlug($slug)->first();

        $title = [];

            $advert1 = (explode(" ", $advert->title));

            foreach ($advert1 as $value1) {
                array_push($title,str_replace(",","", $value1));
            }


        $similar_adverts = Advert::with('user','user.attributes','types','user.attributes.city','user.adverts')->when(!empty($title),function($query) use ($title){
            $query->where('title','LIKE','%'.$title[0].'%');
            if(count($title) > 1){
                foreach ($title as $item){
                    $query->orWhere('content','LIKE','%'.$item.'%');
                    $query->orWhere('title','LIKE','%'.$item.'%');
                }
            }
            return $query;
        })->take(7)->orderBy('title')->get();
        $similar_adverts->forget(0);
        // SEOMeta::setTitle($advert->title);
        // SEOMeta::setDescription($advert->content);
        // SEOMeta::addMeta('article:published_time', $advert->created_at->toW3CString(), 'property');
        // SEOMeta::addMeta('article:section', $advert->category->title, 'property');
        // SEOMeta::addKeyword(['key1', 'key2', 'key3']);

        // OpenGraph::setDescription($advert->resume);
        // OpenGraph::setTitle($advert->title);
        // OpenGraph::setUrl('http://current.url.com');
        // OpenGraph::addProperty('type', 'article');
        // OpenGraph::addProperty('locale', 'pt-br');
        // OpenGraph::addProperty('locale:alternate', ['pt-pt', 'en-us']);

        // OpenGraph::addImage(['url' => 'http://himik/storage/'.$advert->filename, 'size' => 300]);

        // JsonLd::setTitle($advert->title);
        // JsonLd::setDescription($advert->content);
        // JsonLd::setType('Article');
        // JsonLd::addImage('http://himik/storage/'.$advert->filename);

        return view('advert.show',compact('advert','similar_adverts'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function favorite(Request $request)
    {
        if(Auth::check()){
            if(!empty($request->favorite)){
                if(UserFavoriteAdverts::find($request->favorite)->delete()){
                    Session::flash('success', 'Объявление удалено из избранного.');
                }else{
                    Session::flash('error', 'Что то пошло не так, попробуйте позже.');
                }
            }else{
                if(UserFavoriteAdverts::create(['advert_id' => $request->id,'user_id'=> Auth::id()])){
                    Session::flash('success', 'Объявление добавлено в избранное.');
                }else{
                    Session::flash('error', 'Что то пошло не так, попробуйте позже.');
                }
            }


            return View::make('flash-message');
        }else{
            return response()->json(['redirect'=>'/login']);
        }

    }

}
