<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller{
    public function register(Request $request){

        if($request['name'] == null){

        $validatedData = $request->validate([
            'name' => 'unique|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
            'role' => 'required'

        ]);
        
        $validatedData['name'] = 'Anònim';
        }
        else{
            
        $validatedData = $request->validate([
            'name' => 'required|unique:users|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
            'role' => 'required'
        ]);

        }

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

        return response(['user'=> Auth::user(), 'access_token'=> $accessToken, 'status' => 200]);
          
    }

    public function logout(Request $request){
        
        $request->user()->token()->revoke();
        return response([
            "message" => 'Session finished',
        ],200);

    }



    //CHECK WITH TOKEN !!!
    public function updateName(Request $request, $id){

        $playerAuth = Auth::user()->id;

        if($playerAuth == $id){
            $player = User::find($id);
            
            $request->validate([
                'name' => 'max:255',
                'email' => 'email|unique:users',  
            ]);

        }
        elseif(!User::find($id)){
            return response([
                "message" => "User not in the game, register first."
                    ], 404);
        }
        else{
            return response([
                "message" => "Need authorization,"
                    ], 401);
        }

        $player->update($request->all());
        
        return $player;
    } 

}

