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

class ResumeController extends Controller
{
    public function showResume($name)
    {
        $jobs = Rezume::with('attributes')->where('slug',$name)->get();
        $jobs_new = Rezume::with('attributes')->where('status',1)->where('slug','<>',$name)->paginate(5);
        return view('resume.resume',compact('jobs','jobs_new'));

    }

    public function addRezume(Request $request)
    {
        if($request->file('file') != '')
        {
            $fullFilename = null;
            $resizeWidth = 170;
            $resizeHeight = null;
            $slug = $request->input('type_slug');
            $file = $request->file('file');

            $path = 'resume/'.date('F').date('Y').'/';

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
                    })
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

            if(!empty($fullFilename))
            {
                $data['file'] = $fullFilename;
            }
            else
            {
                $data['file'] = $filename[0]->filename;
            }
            #dd($data['file']);
        }
        

        $rezume = new Rezume();
        $rezume['city'] = $request['city'];
        $rezume['country'] = $request['country'];
        $rezume['region'] = $request['region'];
        if($request['pereezd'] == 'on')
            $rezume['pereezd'] = 'возможен переезд';
        else{
            $rezume['pereezd'] = 'не готов к переезду';
        }
        $rezume['fio'] = $request['fio'];
        $rezume['email'] = $request['email'];
        $rezume['number'] = $request['number'];
        $rezume['age'] = $request['age'];
        $rezume['category'] = $request['razdel'];
        $rezume['dolzhnost'] = $request['dolzhnost'];
        $rezume['filename'] = $data['file'];
        if (Auth::check()) {
            $rezume->user_id = Auth::user()->id;
        }
        else{
            $rezume->user_id = 2;
        }
        
        $rezume['price'] = $request['price'];
        $rezume['opit'] = $request['opit'];
        $rezume['education'] = $request['education'];
        $rezume['language'] = $request['language'];
        $rezume['description'] = $request['description'];
        $rezume['status'] = '0';

        $rezume->save();

        $rezume_up = Rezume::find($rezume->id);
        $rezume_up['slug'] = str_slug($request['dolzhnost']."-".$rezume->id);
        $rezume_up->save();

        return redirect()->back();

    }
}
