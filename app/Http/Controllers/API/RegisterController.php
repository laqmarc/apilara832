<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RegisterController extends Controller
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
}
