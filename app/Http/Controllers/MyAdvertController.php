<?php

namespace App\Http\Controllers;

use App\Advert;
use App\AdvertType;
use App\Category;
use App\Delivery;
use App\Notifications\AddAdvert;
use App\Notifications\AddAdvertAdmin;
use App\Notifications\WorkoutAssigned;
use App\Subcategory;
use App\User;
use App\UserAttributes;
use Carbon\Carbon;
use Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redirect;
use Intervention\Image\Constraint;
use Intervention\Image\Facades\Image;
use TCG\Voyager\Facades\Voyager;

class MyAdvertController extends Controller
{
    public function __construct()
    {

    }
    public function index(Advert $advert)
    {
        if(Auth::check())
        {
	        $user = Auth::user()->attributes->first();
	        if(empty($user->name) or empty($user->company) or empty($user->address) or empty($user->number) or empty($user->site)){
		        return back()->with('error','Необходимо заполнить профиль!');
	        }
            $this->data['advert_count'] = $advert->getCountadvert();
            $this->data['category'] = $advert->category();
            $this->data['subcategory'] = $advert->subcategory();
            $this->data['adverts'] = $advert->getAdverts();



            return view('lk.myadvert',$this->data);
        }
        else
        {
            return redirect('register');
        }


    }

    public function add(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'content'=>'required',

            'file' => 'image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        foreach($request['type'] as $type)
        {
            $types = $type;
        }

        foreach ($request['mass'] as $masses)
        {
            $mass = $masses;
        }

        foreach ($request['price'] as $prices)
        {
            $price = $prices;
        }

        $title = strip_tags($request['name'],'<br>');
        $content = strip_tags($request['content'],'<br>');
        $types = strip_tags($types,'<br>');
        $mass = strip_tags($mass,'<br>');
        $price = strip_tags($price,'<br>');

        $user_id = Auth::user()->id;

        $blog = new Advert();

        if($request->file('file') != '')
        {
            $fullFilename = null;
            $resizeWidth = 210;
            $resizeHeight = 180;
            $slug = $request->input('type_slug');
            $file = $request->file('file');

            $path = 'users-attributes/'.date('F').date('Y').'/';

            $filename = basename($file->getClientOriginalName(), '.'.$file->getClientOriginalExtension());
            $filename_counter = 1;

            // Make sure the filename does not exist, if it does make sure to add a number to the end 1, 2, 3, etc...
            while (Storage::disk(config('voyager.storage.disk'))->exists($path.$filename.'.'.$file->getClientOriginalExtension())) {
                $filename = basename($file->getClientOriginalName(), '.'.$file->getClientOriginalExtension()).(string) ($filename_counter++);
            }

            $fullPath = $path.$filename.'.'.$file->getClientOriginalExtension();

            $ext = $file->guessClientExtension();



            if (in_array($ext, ['jpeg', 'jpg', 'png', 'gif'])) {
                $image = Image::make($file)
                    ->resize($resizeWidth, $resizeHeight, function (Constraint $constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->insert('image/пlogo.png','bottom-right', 10, 10)
                    ->encode($file->getClientOriginalExtension(), 75);

                // move uploaded file from temp to uploads directory
                if (Storage::disk(config('voyager.storage.disk'))->put($fullPath, (string) $image, 'public')) {
                    $status = __('voyager.media.success_uploading');
                    $fullFilename = $fullPath;
                } else {
                    $status = __('voyager.media.error_uploading');
                }
            } else {
                $status = __('voyager.media.uploading_wrong_type');
            }
            $data['file'] = $fullFilename;
            #dd($data['file']);

        }
        else {
            $data['file'] = '';
        }
        $user = UserAttributes::find($user_id);
        $blog->title = $title;
        $blog->content = strip_tags($content);
        $blog->short_content = str_limit($content,80);
        $blog->category = $request['category'];
        $blog->subcategory = $request['subcategory'];
        $blog->region = $user['region'];
        $blog->city = $user['city'];
        $blog->country = $user['country'];
        $blog->show = '1';
        $blog->user_id = $user_id;


        $blog->filename = $data['file'];

        $blog->save();




        $advert_id = Advert::orderBy('id','desk')->first();

        $title = str_slug($title);

        if(!empty($request->slug))
        {
            Advert::where('id',$advert_id->id)->update(array('slug' => $request->slug));
        }
        else
        {
            Advert::where('id',$advert_id->id)->update(array('slug' => $title.'-'.$advert_id->id));
        }
        //Advert::where('id',$advert_id->id)->update(array('slug' => $title.'-'.$advert_id->id));

        $AdvertType = new AdvertType();
        $AdvertType->advert_id = $blog->id;
        $AdvertType->user_id = Auth::id();
        $AdvertType->type = $types;
        $AdvertType->mass = $mass;
        $AdvertType->price = $price;
        $AdvertType->valute = $request->valute;

        $AdvertType->save();



        $titlesubcategory = Subcategory::where('id','=',$request['subcategory'])->get();

        $getmail = Delivery::where('subcategory','=',$request['subcategory'])->where('category',$request['category'])->get();

        $category = Category::where('id',$blog->category)->get();

        $slug= $title.'-'.$advert_id->id;

        $subcategory = Subcategory::where('id',$blog->subcategory)->get();

        Notification::send(User::find(67) , new AddAdvertAdmin($slug));

        return Redirect::back();
    }

    public function editPost(Request $request,$id)
    {

        #dd($request);

        if(Auth::check())
        {
            $title = strip_tags($request['title'],'<br>');
            $content = strip_tags($request['content'],'<br>');
            $types = $request['type'];
            $mass = $request['mass'];
            $price = $request['price'];
            $valute = $request['valute'];


#dd($types);

            $user_id = Auth::user()->id;
            $blog = Advert::find($id);


            if($request->file('file') != '')
            {
                $fullFilename = null;
                $resizeWidth = 210;
                $resizeHeight = 180;
                $slug = $request->input('type_slug');
                $file = $request->file('file');

                $path = 'users-attributes/'.date('F').date('Y').'/';

                $filename = basename($file->getClientOriginalName(), '.'.$file->getClientOriginalExtension());
                $filename_counter = 1;

                // Make sure the filename does not exist, if it does make sure to add a number to the end 1, 2, 3, etc...
                while (Storage::disk(config('voyager.storage.disk'))->exists($path.$filename.'.'.$file->getClientOriginalExtension())) {
                    $filename = basename($file->getClientOriginalName(), '.'.$file->getClientOriginalExtension()).(string) ($filename_counter++);
                }

                $fullPath = $path.$filename.'.'.$file->getClientOriginalExtension();

                $ext = $file->guessClientExtension();



                if (in_array($ext, ['jpeg', 'jpg', 'png', 'gif'])) {
                    $image = Image::make($file)
                        ->resize($resizeWidth, $resizeHeight, function (Constraint $constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        })->insert('image/пlogo.png','bottom-right', 10, 10)
                        ->encode($file->getClientOriginalExtension(), 75);

                    // move uploaded file from temp to uploads directory
                    if (Storage::disk(config('voyager.storage.disk'))->put($fullPath, (string) $image, 'public')) {
                        $status = __('voyager.media.success_uploading');
                        $fullFilename = $fullPath;
                    } else {
                        $status = __('voyager.media.error_uploading');
                    }
                } else {
                    $status = __('voyager.media.uploading_wrong_type');
                }
                $data['file'] = $fullFilename;
                #dd($data['file']);

            }
            else {

                #dd($request);
                $filename = Advert::select('filename')->where('user_id', '=', $user_id)->where('id', '=', $id)->get();

            if(!empty($request['imageChange']))
            {
                #var_dump($filename[0]['filename']);
                $data['file'] = $filename[0]->filename;


            }
            else{

                if(!empty($filename[0]['filename']))
                {
                    unlink($filename[0]['filename']);
                }
                else{

                }

                $data['file'] = '';
            }
                #$data['file'] = $filename[0]->filename;


            }

            $blog->title = $title;
            if(!empty($request['slug']))
            {
                $blog->slug = $request['slug'];
            }
            else
            {
                $blog->slug = str_slug($title).'-'.$id;
            }
            $blog->content = $content;
            $blog->category = $request['category'];
            $blog->subcategory = $request['subcategory'];
            $blog->region = $request['region'];
            $blog->show = '1';
            $blog->user_id = $user_id;
            $blog->updated_at = Carbon::now();


            $blog->filename = $data['file'];

            $blog->save();




            $advert_id = Advert::orderBy('id','desk')->first();
            $title = str_slug($title);

            #Advert::where('id',$advert_id->id)->update(array('slug' => $title.'-'.$advert_id->id));

            $advert_type_count = AdvertType::where('advert_id',$id)->get();

            $advert_type_ids = AdvertType::select('id')->where('advert_id',$id)->get();

            if(count($advert_type_count) >= 2)
            {
                    for($i = 0;$i<2;$i++)
                    {

                        $AdvertType = AdvertType::find($advert_type_ids[$i]->id);
                        if($types[$i] != null && $mass[$i] != null && $valute[$i] != null)
                        {
                          $AdvertType->advert_id = $id;
                          $AdvertType->user_id = Auth::id();
                          $AdvertType->type = $types[$i];
                          $AdvertType->mass = $mass[$i];
                          $AdvertType->price = $price[$i];
                          $AdvertType->valute = $valute[$i];
                          $AdvertType->save();
                        }

                    }
            }
            else
            {
                if(!empty($types[2]))
                {
                    for($i = 0;$i<2;$i++)
                    {
                        $AdvertType = new AdvertType();

                        #dd($AdvertType);
                        #dd($types[$i]);

                        $AdvertType->advert_id = $blog->id;
                        $AdvertType->user_id = Auth::id();
                        $AdvertType->type = $types[$i];
                        $AdvertType->mass = $mass[$i];
                        $AdvertType->price = $price[$i];
                        $AdvertType->valute = $valute[$i];
                        $AdvertType->save();
                    }
                }
                else{
                    $AdvertType = AdvertType::find($blog->id);

                        #dd($AdvertType);
                        #dd($types[$i]);

                        $AdvertType->advert_id = $blog['id'];
                        $AdvertType->user_id = Auth::id();
                        $AdvertType->type = $types[0];
                        $AdvertType->mass = $mass[0];
                        $AdvertType->price = $price[0];
                        $AdvertType->valute = $valute[0];
                        $AdvertType->save();
                }

            }




            #dd($items);
            return redirect('/myadvert');
        }
        else
        {
            return redirect('login');
        }


    }

    public function editGet($id)
    {

        $category = Category::where('id','<>',36)->get();

        $subcategory = Subcategory::where('id','<>',339)->get();

        $items = Advert::with('types')->where('id','=',$id)
            ->where('user_id','=',Auth::user()->id)
            ->get();


        return view('edit.editadvert',compact('category','items','subcategory'));
    }

    public function deletePost($id)
    {

        $blog = Advert::find($id);
        $blog->delete();

        unlink('storage/'.$blog->filename);


        $AdvertType = AdvertType::find($blog->id);
        $AdvertType->delete();
    }

    public function refresh($id)
    {
        Advert::where('user_id',$id)->update(array('date'=> Carbon::now()));
    }
}
