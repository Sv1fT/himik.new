<?php

namespace App\Http\Controllers\Api\V1;

use App\Advert;
use App\Http\Controllers\Controller;
use App\Observers\UserObserver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class UserController extends Controller
{

    public function index()
    {
        return view('user.index');
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function profile($id)
    {
        return User::with('attributes','attributes.city','attributes.region','attributes.country')->find($id);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $id)
    {
        $user = User::find($id);

        $user->attributes()->update($request->get('attributes'));

        if ($user->save())
        {
            return response()->json(['message'=>'Ваш профиль сохранен!','type'=>'success'],'200');

        } else {
            return response()->json(['message'=>'Ошибка','type'=>'danger'],'502');

        }
    }

    public function favorites()
    {
        $user = User::with('favorite_adverts')->whereId(Auth::id())->first();
        $user = $user->favorite_adverts;
        return view('user.profile',compact('user'));
    }

    public function messages()
    {
        $messages = 1;
    }


    /**
     * @param $user_id
     * @return mixed
     */
    public function adverts($user_id)
    {
        return Advert::with('types','statuses')->whereUser_id($user_id)->orderBy('created_at','desc')->paginate(5);
    }

    /**
     * @param Request $request
     * @param integer $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function advert_edit(Request $request,$id)
    {
        $advert = Advert::find($id);
        $request->status = 0;
        if($request->method() == "POST"){
            if($advert->update([$request->all(),'status'=>'0'])){
                return response()->json(['message'=>'Объявление обновлено и отправлено на модерацию. Спасибо что выбрали ОПТхимик!','type'=>'success'],'200');
            } else {
                return response()->json(['message'=>'Ошибка','type'=>'danger'],'502');
            }
        }

        return $advert;

    }
}
