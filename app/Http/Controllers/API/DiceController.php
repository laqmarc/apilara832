<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Dice;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class DiceController extends Controller {

    //PLAYERS
    public function throwDice ($id){

        $user_auth = Auth::user()->id;

        if(!User::find($id)){

            return response([
                "message" => "User with id: $id not registered."]);

        }elseif($user_auth == $id){
           
            $dice_a = rand(1,6);
            $dice_b = rand(1,6);
            $two_dices = $dice_a + $dice_b;
        
            if($two_dices !== 7){
                $result = 0;
            }
            else{
                $result = 1;
            }
            
            Dice::create([
            "dice_a" => $dice_a,
            "dice_b" => $dice_b,
            "result" => $result,
            "user_id" => $id
            ])->where('user_id', '=', $id)->get();
        
            if($result !== 1){
                return response(["message" => "Lose!! $dice_a and $dice_b is: $two_dices."]);
            }
            else{ 
                return response(["message" => "Nice you WIN. $dice_a and $dice_b is:  $two_dices."]);
            }

        }else{

            return response(["message" => "No authorized."]);

        }
    }
   
    public function allGames ($id){
        //PLAYERS
        $user_auth = Auth::user()->id;

        if(!User::find($id)){

            return response([
                "message" => "User with id: $id not registered."]);

        }elseif($user_auth == $id){

        $playergames = Dice::where('user_id', '=', $id)->first('id');

            if($playergames !== null){
                $throws = Dice::where('user_id', $id)->get();
                return response(["message" => "throws:", $throws]);
            }
            elseif($playergames == null){
                return response(["message" => "No throws"]);
            }
            
        }else{

            return response(["message" => "No authorized."]);

        }

    }

    public function deleteAllGames ($id){
        //PLAYERS

        $user_auth = Auth::user()->id;

        if(!User::find($id)){

            return response([
                "message" => "User with id: $id not registered."]);

        }elseif($user_auth == $id){

        $playergames = Dice::where('user_id', '=', $id)->first('id');

            if($playergames !== null){
                Dice::where('user_id', $id)->delete();
                return response(["message" => "all Games deleted."]);
            }
            else{
                return response(["message" => "no games to delete, play please"]);
            }

        }else{
            
            return response(["message" => "No authorized."]);

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
        ->selectRaw('users.name as users, count(dices.result) as total_games, sum(dices.result = 1) as games_win, sum(dices.result = 1)*100/count(dices.result) as win_rate')  
        ->orderby('win_rate', 'desc')
        ->groupby('users')
        ->get();

        return response(["user_win_rate" => $stats]);
        
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
 
        return response(["all_players_win_rate" => $allplayerRanking]);

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
        ->selectRaw('users.name as users, sum(dices.result = 1)*100/count(dices.result) as win_rate')  
        ->orderby('win_rate')
        ->groupby('users')
        ->limit(1)
        ->get();
 
        return response(["the_worst_player" => $playerRankingLoser]);

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
        ->selectRaw('users.name as users, sum(dices.result = 1)*100/count(dices.result) as win_rate')  
        ->orderby('win_rate', 'desc')
        ->groupby('users')
        ->limit(1)
        ->get();
 
        return response(["the_best_player" => $playerRankingWinner]);
       
        }
    }

}
