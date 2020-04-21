<?php

namespace App\Http\Controllers;

use App\User;
use App\UserAttributes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Storage;
use App\Profile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Region;
use Intervention\Image\Constraint;
use Intervention\Image\Facades\Image;
use TCG\Voyager\Facades\Voyager;

class ProfileController extends Controller
{
    public function index(Profile $profile)
    {


        if(Auth::guest()){
            return redirect('login');
        }
        else {

            $name = UserAttributes::firstOrCreate(['user_id' => Auth::id()]);
            $name = UserAttributes::where('user_id',Auth::id())->get();

            $name = $name[0]->name;
            session()->put('user_name',$name);
            $this->data['profile'] = $profile->getProfile();

            $this->data['regions'] = $profile->getRegion();

            $this->data['city'] = $profile->getCity();

            $this->data['country'] = $profile->getCountry();

            $time = Carbon::now('Europe/Moscow');



            UserAttributes::where('user_id',Auth::id())->update(array('updated_at'=>$time));

            return view('lk.profile', $this->data);
        }

    }

    public function editprofile(Request $request)
    {

        $this->validate($request, [
            'name'=>'required|min:3|max:24',

        ]);

        $user_id = Auth::id();
        if($request->file('file') != '')
        {
            $fullFilename = null;
            $resizeWidth = 554;
            $resizeHeight = 400;
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
        else{
            $filename = UserAttributes::select('filename')->where('user_id','=',$user_id)->get();
            $data['file'] = $filename[0]->filename;


        }

        $name = $request['name'];
        $request->session()->put('name',$name);

        User::where('id',$user_id)->update(array(
            'city' => $request['city'],
        ));
        
        $user = UserAttributes::find($user_id);
        $user->name = $request['name'];
        $user->filename = $data['file'];
        $user->company = $request['company'];
        $user->region = $request['region'];
        $user->country = $request['country'];
        $user->city = $request['city'];
        $user->address = $request['address'];
        $user->number = $request['number'];
        $user->site = $request['site'];
        $user->description = $request['description'];
        $user->updated_at = Carbon::now()->hour(+3);

        $user->save();

        #$data = DB::update("UPDATE user set contactfase='".$request['contactfase']."', company='".$request['company']."', region='".$request['region']."',filename='".$filename."', sity='".$request['sity']."', adress='".$request['adress']."', number='".$request['number']."', site='".$request['site']."', description='".$request['description']."' where id = ".$user_id);

        return redirect('/profile');
    }
}
