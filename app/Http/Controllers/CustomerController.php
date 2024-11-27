<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Validator;
use App\User;

class CustomerController extends Controller
{

    public function profile(Request $request)
    {
        $user = Auth::user();
        $status = "error";
        $message = "";
        $data = null;
        $code = 200;
        if ($user) {
            $user->name = $request->name;
            $user->password = \Hash::make($request->password);
            if($user->save()){
                $status = "success";
                $message = "Update profile success";
                $data = $user->toArray();        
            }
            else{
                $message = "Update profile failed";
            }    
        }
        else{
            $message = "User not found";
        }
        
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
