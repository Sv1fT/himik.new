<?php

namespace App\Http\Controllers;

use App\Category;
use App\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubcategoryController extends Controller
{
    public function ShowSubcategory($id){
        $category = Category::where('slug',$id)->get();
        $subcategory =Subcategory::with('category')->select('subcategory.*',DB::raw('(select count(*) from advert where advert.subcategory = subcategory.id and advert.status = 1) as count_adv'))
            ->where('category_id','=',$category[0]['id'])
            ->orderBy('title','ASC')
            ->get();

        $title = str_slug($subcategory[0]->title, "-");


        return view('subcategory.subcategory')->with('subcategory',$subcategory)->with('url',$title)->with('opisan',$category);
    }
}
