<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ReklamsController extends Controller
{
    public function reklams()
    {
        return view('reklama');
    }

    public function mailsend(Request $request)
    {
        $fio = $request->input('fio');
        $number = $request->input('number');
        $email = $request->input('email');
        $description = $request->input('description');

        Mail::send('emails.reklama',['fio'=>$fio, 'number'=>$number,'email'=>$email,'description'=>$description], function ($u) use ($request){
            $u->to('opt-himik@mail.ru','Заказ рекламы')->subject('Заказ рекламы');
        });
        return redirect()->back()->with('status', 'Запрос отправлен!');
    }
}
