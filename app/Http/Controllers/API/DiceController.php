<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Dice;

class DiceController extends Controller
{
    public function throwDice ($id)
    {

            // $id=3;
            $diceA = rand(1,6);
            $diceB = rand(1,6);
            $twodices = $diceA + $diceB;
        
            if($twodices == 7) 
            {
                $result = 1;
            }else 
            {
                $result = 0;
            }
            
            Dice::create([
            "diceA" => $diceA,
            "diceB" => $diceB,
            "result" => $result,
            "user_id" => $id
            ])->where('user_id', '=', $id)->get();
        
            if($result == 1){
            return response(["message" => "Nice you WIN. $diceA and $diceB is:  $twodices."]);
            }
            else{
            return response(["message" => "Lose!! $diceA and $diceB is: $twodices."]);
            }
        }
    
}
