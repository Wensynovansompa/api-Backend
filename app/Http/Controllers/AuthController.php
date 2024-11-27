<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\User;

class AuthController extends Controller
{

    public function tokenIsValid(Request $request)
    {
        $this->validate($request, [
            'token' => 'required', 
        ]);

        $user = User::where('api_token', '=', $request->token)->firstOrFail();
        $status = "error";
        $message = "";
        $data = null;
        $code = 401;
        if($user){
            $status = 'success';
            $message = 'token terferifikasi';
            $data = $user->toArray();
            $code = 200;            
        }
        else{
            $message = "token tidak tersedi";
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required', 
            'password' => 'required',
        ]);
        $status = "error";
        $message = "";
        $data = null;
        $code = 401;
        $listsUser =  User::where('email', '=', $request->email)->get();
        if(count($listsUser) >0){
            $user = User::where('email', '=', $request->email)->firstOrFail();
            if($user){
                if (Hash::check($request->password, $user->password)) {
                    $user->generateToken();
                    $status = 'success';
                    $message = 'Login sukses';
                    $data = $user->toArray();
                    $code = 200;  
                }
                else{
                    $message = "Login gagal, password salah";
                }          
            }
        } else{
            $message = "Login gagal, username salah";
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
        
        $status = "error";
        $message = "";
        $data = null;
        $code = 400;
        if ($validator->fails()) {
            $errors = $validator->errors();
            $message = $errors;
        }
        else{
            $user = \App\User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'roles'    => 'CUSTOMER',
                'username' => $request->email,
            ]);
            if($user){
                $user->generateToken();
                
                $status = "success";
                $message = "register successfully";
                $data = $user->toArray();
                $code = 200;
            }
            else{
                $message = 'register failed';
            }
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $user->api_token = null;
            $user->save();
        }
        return response()->json([
            'status' => 'success',
            'message' => 'logout berhasil',
            'data' => []
        ], 200); 
    }




    /**
     * Handle reset password 
     */
    public function callResetPassword(Request $request)
    {

        try {
            $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
            ]);
            $user = User::where('email', $request->email)
                      ->update(['password' => Hash::make($request->password)]); 
        } catch (\Exception $e) {
            $message = $e->getMessage();
            return response()->json(['message' => $message]);
        }              
        return response()->json(['message' => 'Password reset successfully.']);
    }

}
