<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $dice_a = rand(1,6);
        $dice_b = rand(1,6);
        $two_dices = $dice_a + $dice_b;
    
        if($two_dices !== 7){
            $result = 0;
        }
        else{
            $result = 1;
        }
        
       return [
        "dice_a" => $dice_a,
        "dice_b" => $dice_b,
        "result" => $result,
        "user_id" => User::all()->random()->id

        ];
    }
}
