<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Passport;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request){

        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);
        
        $validatedData['password'] = Hash::make($request->password);
        
        $user = User::create($validatedData);
       
        $accessToken = $user->createToken('authToken')->accessToken;
        
        return response([
            
            'user' => $user,
            'acess_token' => $accessToken
        ]);
        
    }

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

    public function logout(Request $request) {
        
        $request->user()->token()->revoke();
        return response([
            "message" => 'Session finished',
        ],200);

        
    }

    public function update(Request $request, $id)
    {

        $player = User::find($id);
            
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',  
        ]);

        $player->update($request->all());
        
        return $player;
    } 

}

