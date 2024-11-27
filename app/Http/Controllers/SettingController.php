<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\SettingStores;
use DB;
use Auth;
use Validator;
use File;
use Image;

class SettingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $store = SettingStores::select('*')
        ->get(); 
        foreach($store as $storeId){
            $criteria = \App\SettingStores::findOrFail($storeId->id);
        }
        
        $status = "success";
        $message = "Succes get setting store";
        $code = 200;
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $criteria ], $code);
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
            $settingStoreOld = \App\SettingStores::get();
            foreach($settingStoreOld as $setting){
                    if($setting->logo_path_url != null){
                        File::delete(public_path( 'storage/' . $setting->logo_path_url));
                    }
                    DB::table('setting_stores')->where('id', $setting->id)->delete();
            }
            $settingStore = new \App\SettingStores;
            $settingStore->store = $request->store;
            $settingStore->user_id = $user->id;
            $settingStore->role='admin';
            
            
            if($request->file('logo')){
                $logo_path = $request->file('logo')->store('store-logo', 'public');

                $file = $request->file("logo");
                $storageDestinationPath=storage_path('app/public/' . $logo_path);
                if (!\File::exists( $storageDestinationPath)) {
                    \File::makeDirectory($storageDestinationPath, 0755, true);
                }
                $img = Image::make($file->path());
                $img->resize(200, 200, function ($const) {
                    $const->aspectRatio();
                })->save($storageDestinationPath);

                File::move(storage_path('app/public/' . $logo_path), public_path( 'storage/' . $logo_path));
                $settingStore->logo_path_url = $logo_path;
            }

            $settingStore->save();
            DB::commit();
            $status = 'success';
            $message = 'Store settings saved and published successfully';
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
