<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Dice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class DiceController extends Controller {

    //PLAYERS
    public function throwDice ($id){
            
        $diceA = rand(1,6);
        $diceB = rand(1,6);
        $twodices = $diceA + $diceB;
    
        if($twodices !== 7){
            $result = 0;
        }
        else{
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
        //PLAYERS
        $playergames = Dice::where('user_id', '=', $id)->first('id');

        if($playergames !== null){
            $throws = Dice::where('user_id', $id)->get();
            return response(["message" => "throws:", $throws]);
        }
        elseif($playergames == null){
            return response(["message" => "No throws"]);
        }

    }

    public function deleteAllGames ($id){
        //PLAYERS
        $playergames = Dice::where('user_id', '=', $id)->first('id');

        if($playergames !== null){
            Dice::where('user_id', $id)->delete();
            return response(["message" => "all Games deleted."]);

        }
        else{
            return response(["message" => "no games to delete, play please"]);
        }
    }

    public function stats(){
        // El programari ha de permetre a l'administrador/a de l'aplicació 
        // visualitzar tots els jugadors/es que hi ha al sistema, 
        // veure el percentatge d'èxit de cada jugador/a i
        //  el percentatge d'èxit mitjà de tots els jugadors/es al sistema.

        //ADMIN
        if (Auth::user()->role != 'admin') {
            return response()->json([
                'message' => 'U cant see it, you are not an admin',
                
            ]);
        } 
        else {

        $stats  = DB::table('dices')        
        ->join('users', 'dices.user_id', '=', 'users.id')
        ->selectRaw('users.name as Users, count(dices.result) as Total_Games, sum(dices.result = 1) as Games_Win, sum(dices.result = 1)*100/count(dices.result) as Winrate')  
        ->orderby('Winrate', 'desc')
        ->groupby('users')
        ->get();

        return response(["User_win_rate" => $stats]);
        
        }
    }

    public function rankingPlayers(){
        //ADMIN
        if (Auth::user()->role != 'admin') {
            return response()->json([
                'message' => 'U cant see it, you are not an admin',
            ]);
        } 
        else {
        $allplayerRanking = DB::table('dices')
        ->join('users', 'dices.user_id', '=', 'users.id')
        ->selectRaw('sum(dices.result = 1)/count(DISTINCT dices.id)/count(DISTINCT users.id)*100 as result')
        ->get();
 
        return response(["All_players_win_rate" => $allplayerRanking]);

        }
    }

    public function rankingPlayersLoser(){
        //ADMIN
        if (Auth::user()->role != 'admin') {
            return response()->json([
                'message' => 'U cant see it, you are not an admin',
                
            ]);
        } 
        else {
        $playerRankingLoser = DB::table('dices')
        ->join('users', 'dices.user_id', '=', 'users.id')
        ->selectRaw('users.name as users, sum(dices.result = 1)*100/count(dices.result) as winrate')  
        ->orderby('winrate')
        ->groupby('users')
        ->limit(1)
        ->get();
 
        return response(["The_worst_player" => $playerRankingLoser]);

        }
    }

    public function rankingPlayersWinner(){
        //ADMIN
        if (Auth::user()->role != 'admin') {
            return response()->json([
                'message' => 'U cant see it, you are not an admin',
                
            ]);
        } 
        else {
        $playerRankingWinner = DB::table('dices')
        ->join('users', 'dices.user_id', '=', 'users.id')
        ->selectRaw('users.name as users, sum(dices.result = 1)*100/count(dices.result) as winrate')  
        ->orderby('winrate', 'desc')
        ->groupby('users')
        ->limit(1)
        ->get();
 
        return response(["The_best_player" => $playerRankingWinner]);
       
        }
    }

}
