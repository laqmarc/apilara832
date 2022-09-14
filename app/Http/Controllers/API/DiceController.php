<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Dice;
use Illuminate\Support\Facades\DB;

use function GuzzleHttp\Promise\all;

class DiceController extends Controller
{
    public function throwDice ($id)
    {
            
        $diceA = rand(1,6);
        $diceB = rand(1,6);
        $twodices = $diceA + $diceB;
    
        if($twodices !== 7) 
        {
            $result = 0;
        }else 
        {
            $result = 1;
        }
        
        Dice::create([
        "diceA" => $diceA,
        "diceB" => $diceB,
        "result" => $result,
        "user_id" => $id
        ])->where('user_id', '=', $id)->get();
    
        if($result !== 1){

            return response(["message" => "Lose!! $diceA and $diceB is: $twodices."]);
        }
        else{
            
            return response(["message" => "Nice you WIN. $diceA and $diceB is:  $twodices."]);

        }
    }

    public function allGames ($id){
        $playergames = Dice::where('user_id', '=', $id)->first('id');

        if($playergames !== null)
        {
            $throws = Dice::where('user_id', $id)->get();
            return response(["message" => "throws:", $throws]);

        }elseif($playergames == null)
        {
            return response(["message" => "No throws"]);
        }
    }

    public function deleteAllGames ($id){
        $playergames = Dice::where('user_id', '=', $id)->first('id');

        if($playergames !== null)
        {
            Dice::where('user_id', $id)->delete();
            return response(["message" => "all Games deleted."]);

        }else{
            return response(["message" => "no games to delete, play please"]);
        }
    }

    public function stats(){
        $stats  = DB::table('dices')        
        ->join('users', 'dices.user_id', '=', 'users.id')
        ->selectRaw('users.name as Users, count(dices.result) as Total_Games, sum(dices.result = 1) as Games_Win, sum(dices.result = 1)*100/count(dices.result) as Winrate')  
        ->orderby('Winrate', 'desc')
        ->groupby('users')
        ->get();

        return response(["User win rate" => $stats]);
    }

    public function rankingPlayers(){

        $allplayerRanking = DB::table('dices')
        ->join('users', 'dices.user_id', '=', 'users.id')
        ->selectRaw('sum(dices.result = 1)/count(DISTINCT dices.id)/count(DISTINCT users.id)*100 as result')
        ->get();
 
        return response(["All players win rate" => $allplayerRanking]);

    }

    public function rankingPlayersLoser(){
        $playerRankingLoser = DB::table('dices')
        ->join('users', 'dices.user_id', '=', 'users.id')
        ->selectRaw('users.name as users, sum(dices.result = 1)*100/count(dices.result) as winrate')  
        ->orderby('winrate')
        ->groupby('users')
        ->limit(1)
        ->get();
 
        return response(["The worst player" => $playerRankingLoser]);

    }

    public function rankingPlayersWinner(){
        $playerRankingWinner = DB::table('dices')
        ->join('users', 'dices.user_id', '=', 'users.id')
        ->selectRaw('users.name as users, sum(dices.result = 1)*100/count(dices.result) as winrate')  
        ->orderby('winrate', 'desc')
        ->groupby('users')
        ->limit(1)
        ->get();
 
        return response(["The best player" => $playerRankingWinner]);

    }

}
