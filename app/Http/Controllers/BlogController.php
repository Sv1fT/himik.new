<?php

namespace App\Http\Controllers;

use App\Advert;
use App\Blog;
use App\User;
use App\UserAttributes;
use Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Constraint;
use Intervention\Image\Facades\Image;
use TCG\Voyager\Facades\Voyager;

class BlogController extends Controller
{

    public function index()
    {
        $blog = Blog::latest()->where('active', 1)->get()->groupBy(function ($date) {
            return $date->created_at->formatLocalized('%d %B %Y');
        });

        $companyes = UserAttributes::with('blog')
            ->where('users_attributes.user_id', '!=', 2)
            ->where('users_attributes.user_id', '!=', 47)
            ->join('region', 'region.id', '=', 'users_attributes.region')
            ->join('city', 'city.id', '=', 'users_attributes.city')
            ->join('country', 'country.id', '=', 'users_attributes.country')
            ->join('blog', 'blog.user_id', '=', 'users_attributes.user_id')
            ->select('users_attributes.*', DB::raw('(select count(*) from blog where blog.user_id = users_attributes.user_id) as count_sub'), 'region.name AS region_title', 'city.name as city_title')->groupBy('users_attributes.id')->orderBy('count_sub', 'DESC')->take(12)->get();

//             User::with('attributes')->
//             select('users.*',DB::raw('(select count(*) from blog where user_id = users.id) as count_sub'))
//             #->join('region','user.region','=','region.id')
//             #->join('blog','user.id','=','blog.user_id')
// ->join('blog','blog.user_id','=','users.id')
//             ->groupBy('users.id')
//             ->orderBy('count_sub','DESC')->get();

        #$company = DB::table('user')->where('count_sub','>',0)->get();


        return view('blog.indexBlog')->with('blog', $blog)->with('company', $companyes);
    }


    public function postShow($id)
    {
        $blogget = Blog::find($id);
        

        if (isset($blogget->name)) {

            $user = Blog::where('id', $id)->get();

            $new = Advert::
            select('advert.*',
                'category.title AS categorytitle',
                'region.name AS region_title',
                'advert.show AS `show`', DB::raw("(select count(*) from advert where user_id = users.id) as count_sub"))
                ->join('region', 'advert.region', '=', 'region.id')
                ->join('category', 'advert.category', '=', 'category.id')
                ->join('users', 'advert.user_id', '=', 'users.id')
                ->join('users_attributes', 'users.id', '=', 'users_attributes.user_id')
                ->where('users.id', '=', $user[0]->user_id)->orderBy('date', 'desc')->paginate(10);
            return view('blog.postblog')->with('postblog', $blogget)->with('new', $new);

        } else {
            abort(404);
        }
    }

    public function companyShow($id)
    {
        $user = User::with('attributes')->where('id', '=', $id)->get();
        $blogget = Blog::latest()->where('active', 1)->where('user_id', $user[0]->id)->take(12)->get()->groupBy(function ($date) {
            return $date->created_at->formatLocalized('%d %B %Y');
        });
        $new = Advert::
        select('advert.*',
            'category.title AS categorytitle',
            'region.name AS region_title',
            'advert.show AS `show`', DB::raw("(select count(*) from advert where user_id = users.id) as count_sub"))
            ->join('region', 'advert.region', '=', 'region.id')
            ->join('category', 'advert.category', '=', 'category.id')
            ->join('users', 'advert.user_id', '=', 'users.id')
            ->join('users_attributes', 'users.id', '=', 'users_attributes.user_id')
            ->where('users.id', '=', $id)->orderBy('date', 'desc')->paginate(10);
        return view('blog.companyblog')->with('postblog', $blogget)->with('new', $new)->with('user', $user);
    }


    public function showpage(Blog $blog)
    {

        if (Auth::guest()) {
            return redirect('login');
        } else {
            $this->data['blog'] = $blog->getBlog();
            return view('lk.blog', $this->data);
        }
    }

    protected function add(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',

            'url' => 'required',
            'file' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if (Auth::guest()) {
            return redirect('login');
        } else {


            $name = strip_tags($request['name'], '<br>');
            $content = strip_tags($request['content'], '<br>');
            $url = strip_tags($request['url'], '<br>');
            $user_id = Auth::user()->id;

            $blog = new Blog();

            if ($request->file('file') != '') {
                $fullFilename = null;
                $resizeWidth = 210;
                $resizeHeight = 139;
                $slug = $request->input('type_slug');
                $file = $request->file('file');

                $path = 'blog/' . date('F') . date('Y') . '/';

                $filename = basename($file->getClientOriginalName(), '.' . $file->getClientOriginalExtension());
                $filename_counter = 1;

                // Make sure the filename does not exist, if it does make sure to add a number to the end 1, 2, 3, etc...
                while (Storage::disk(config('voyager.storage.disk'))->exists($path . $filename . '.' . $file->getClientOriginalExtension())) {
                    $filename = basename($file->getClientOriginalName(), '.' . $file->getClientOriginalExtension()) . (string)($filename_counter++);
                }

                $fullPath = $path . $filename . '.' . $file->getClientOriginalExtension();

                $ext = $file->guessClientExtension();


                if (in_array($ext, ['jpeg', 'jpg', 'png', 'gif'])) {
                    $image = Image::make($file)
                        ->resize($resizeWidth, $resizeHeight, function (Constraint $constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        })->insert('image/пlogo.png', 'bottom-right', 10, 10)
                        ->encode($file->getClientOriginalExtension(), 75);

                    // move uploaded file from temp to uploads directory
                    if (Storage::disk(config('voyager.storage.disk'))->put($fullPath, (string)$image, 'public')) {
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
            } else {
                $data['file'] = '';
            }

            $user = UserAttributes::where('user_id', $user_id)->get();

            $blog->name = $name;
            $blog->slug = str_slug($name);
            $blog->active = '0';
            $blog->user_id = $user_id;
            $blog->region = $user[0]->region;
            $blog->city = $user[0]->city;
            $blog->country = $user[0]->country;
            $blog->content = $content;
            $blog->url = $url;
            $blog->filename = $data['file'];
            $blog->save();

            $user = User::where('id', '=', $user_id)->get();
            # DB::update("UPDATE users set count_blog='".$user[0]->count_blog."'+1 where id = ".$user_id);
            return Redirect::back();
        }
    }

    public function editGet($id)
    {
        $blog = Blog::find($id);
        return view('blog.edit.blogedit')->with('blog', $blog);
    }


    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function editPost(Request $request, $id)
    {

        $this->validate($request, [
            'name' => 'required',

            'url' => 'required',
            'file' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        #dd($request);

        $data = Blog::find($id);

        $user_id = Auth::user()->id;

        if ($request->file('file') != '') {
            $fullFilename = null;
            $resizeWidth = 210;
            $resizeHeight = 139;
            $slug = $request->input('type_slug');
            $file = $request->file('file');

            $path = 'users-attributes/' . date('F') . date('Y') . '/';

            $filename = basename($file->getClientOriginalName(), '.' . $file->getClientOriginalExtension());
            $filename_counter = 1;

            // Make sure the filename does not exist, if it does make sure to add a number to the end 1, 2, 3, etc...
            while (Storage::disk(config('voyager.storage.disk'))->exists($path . $filename . '.' . $file->getClientOriginalExtension())) {
                $filename = basename($file->getClientOriginalName(), '.' . $file->getClientOriginalExtension()) . (string)($filename_counter++);
            }

            $fullPath = $path . $filename . '.' . $file->getClientOriginalExtension();

            $ext = $file->guessClientExtension();


            if (in_array($ext, ['jpeg', 'jpg', 'png', 'gif'])) {
                $image = Image::make($file)
                    ->resize($resizeWidth, $resizeHeight, function (Constraint $constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->insert('image/пlogo.png', 'bottom-right', 10, 10)
                    ->encode($file->getClientOriginalExtension(), 75);

                // move uploaded file from temp to uploads directory
                if (Storage::disk(config('voyager.storage.disk'))->put($fullPath, (string)$image, 'public')) {
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

        } else {

            $filenameeq = Blog::select('filename')->where('user_id', '=', $user_id)->where('id', '=', $id)->get();

            $data['file'] = $filenameeq[0]->filename;

        }

        #dd($data);

        $data->name = $request->name;
        $data->slug = str_slug($request->name);
        $data->content = $request->content;
        $data->url = $request->url;
        $data->filename = $data['file'];
        $data->save();

        return redirect('/myblog');


    }

    protected function deletePost($id)
    {
        $data = Blog::find($id);


        $data->delete();

        return Redirect::back();
    }

    public function showBlog(Blog $blog)
    {
        if (Auth::check()) {
            $this->data['blogitem'] = $blog->getBlogitem();

            return view('blog.blog', $this->data);
        } else {
            return \redirect('/login');
        }
    }
}
