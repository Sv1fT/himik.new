<?php

namespace App\Http\Controllers;

use App\Advert;
use App\City;
use App\UserFavoriteAdverts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class AdvertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $advert = Advert::with('types','city','region','country','user','favorite')->whereSlug($slug)->first();
        return view('advert.show',compact('advert'));
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
