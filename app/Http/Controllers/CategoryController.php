<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Http\Resources\Category as CategoryResource;

use DB;
use Auth;
use Validator;


class CategoryController extends Controller
{

    public function slug($slug)
    {
        $criteria = Category::where('slug', $slug)->first();
        return new CategoryResource($criteria);
    }


}
