<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    
    public function logout(Request $request){
        
        $request->user()->token()->revoke();
        return response([
            "message" => 'Session finished',
        ],200);
        
    }
}
