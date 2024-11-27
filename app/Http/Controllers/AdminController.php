<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Resources\CommonImages as CommonImageResourceCollection;
use Auth;
use File;
use Validator;
use App\User;
use Storage;

class AdminController extends Controller
{
    
    public function index()
    {
        $images = \App\CommonImage::orderBy('id','desc')->paginate(10);
        return new CommonImageResourceCollection($images);
    }


    public function store(Request $request)
    {
        $status = "error";
        $message = "";
        $data = null;
        $code = 200;
        $user = Auth::user();

        DB::beginTransaction();
        try {
        $new_images = new \App\CommonImage;
        $new_images->created_by = \Auth::user()->id;
        $new_images->is_deleted="y";
        $new_images->user_id=\Auth::user()->id;
        $image_url = $request->file('image');
        
        if($image_url){
          $image_url_path = $image_url->store('commonImage-covers', 'public');
          if (!\File::exists( $image_url_path)) {
            \File::makeDirectory($image_url_path, 0755, true);
            }
          File::move(storage_path('app/public/' . $image_url_path), public_path( 'storage/' . $image_url_path));
          $new_images->image_url = $image_url_path;
        }
      
        $new_images->save();
        DB::commit();
        $status = 'success';
        $message = 'Image successfully saved and published';
          

        } catch (\Exception $e) {
            $message = $e->getMessage();
            DB::rollback();
        }
    
        $data = $user->toArray(); 
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public function update(Request $request)
    {
        $status = "error";
        $message = "";
        $data = null;
        $code = 200;
        $user = Auth::user();

        DB::beginTransaction();
        try {
        $image = \App\CommonImage::findOrFail($request->id);
        $image->created_by = \Auth::user()->id;
        $image->is_deleted="y";
        $image_url = $request->file('image');
        
        if($image_url){
            if($image->image_url && file_exists(public_path('storage/' . $image->image_url))){
                File::delete(public_path( 'storage/' . $image->image_url));
            }
          $image_url_path = $image_url->store('commonImage-covers', 'public');
          File::move(storage_path('app/public/' . $image_url_path), public_path( 'storage/' . $image_url_path));
      
          $image->image_url = $image_url_path;
        }
        $image->save();

        DB::commit();
        $status = 'success';
        $message = 'Image successfully saved and published';
          

        } catch (\Exception $e) {
            $message = $e->getMessage();
            DB::rollback();
        }
        $data = $user->toArray(); 
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public function deletePermanent(Request $request)
    {
        $user = Auth::user();
        $status = "error";
        $message = "";
        $data = null;
        $code = 200;

        DB::beginTransaction();
        try {

        $image = \App\CommonImage::findOrFail($request->id);
        if($image->image_url && file_exists(public_path('storage/' . $image->image_url))){
           
            File::delete(public_path( 'storage/' . $image->image_url));
        }
        $image->forceDelete();

        $status = 'success';
        $message = 'Image permanently deleted';
      
        
        DB::commit();
        } catch (\Exception $e) {
            $message = $e->getMessage();
            DB::rollback();
        }
        $data = $user->toArray(); 
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
