<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    public function login(Request $request){
        $loginData =$request->validate([
            'email'=> 'email|required',
            'password' => 'required'
        ]);
        if(!Auth()->attempt($loginData)){
            return response([
                'message' => 'Invalid Credentials',
            ]);
        }

            $user = $request->user();
            $accessToken = $user->createToken('authToken')->accessToken;

            return response(['user'=> Auth::user(), 'access_token'=> $accessToken, 'status' => 200]
        );
          
    }
}
