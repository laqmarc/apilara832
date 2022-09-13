<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiceController extends Controller
{
    public function roll($id)
    {
        $auser = Auth::user()->id;

        if(!User::find($id)) 
        {
            return response([
                "message" => "User not in db, register first."], 404);
        }elseif($auser == $id)
        {
            return response([
                "message" => "All ok"]);
        }
    }
}
