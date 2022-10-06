<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\DiceController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Cada jugador/a pot veure un llistat de totes les tirades que ha realitzat, 
// amb el valor de cada dau i si s'ha guanyat o no la partida. 
// A més, pots saber el seu percentatge d'èxit per totes les tirades que ha fet.
// Es pot eliminar tot el llistat de tirades per un jugador/a.

// El programari ha de permetre a l'administrador/a de l'aplicació 
// visualitzar tots els jugadors/es que hi ha al sistema, 
// veure el percentatge d'èxit de cada jugador/a i
//  el percentatge d'èxit mitjà de tots els jugadors/es al sistema.

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//PLAYER
// POST /players : crea un jugador/a. -                                                                         test ok
Route::post('players', [AuthController::class, 'register'])->name('register');
// LOGIN -                                                                                                      test ok
Route::post('login', [AuthController::class, 'login']);


Route::middleware('auth:api')->group(function () {
//GAME
// POST /players/{id}/games/ : un jugador/a específic realitza una tirada dels daus. -                          test ok
Route::post('/players/{id}/games', [DiceController::class, 'throwDice'])->name('throwDice');
// GET /players/{id}/games: retorna el llistat de jugades per un jugador/a. -                                   test ok
Route::get('/players/{id}/games', [DiceController::class, 'allGames'])->name('allGames');
// DELETE /players/{id}/games: elimina les tirades del jugador/a. -                                             test ok
Route::delete('/players/{id}/games', [DiceController::class, 'deleteAllGames'])->name('deleteAllGames');

//STATS
// GET /players: retorna el llistat de tots els jugadors/es del sistema amb el seu percentatge mitjà d’èxits -  test ok
Route::get('/players', [DiceController::class, 'stats'])->name('stats');
// GET /players/ranking: retorna el rànquing mitjà de tots els jugadors/es del sistema. wl percentatge mitjà -  test ok
Route::get('players/ranking',[DiceController::class, 'rankingPlayers'])->name('rankingPlayers');
// GET /players/ranking/loser: retorna el jugador/a amb pitjor percentatge d’èxit. -                            test ok
Route::get('players/ranking/loser',[DiceController::class, 'rankingPlayersLoser'])->name('rankingPlayersLoser');
// GET /players/ranking/winner: retorna el jugador/a amb millor percentatge d’èxit.                             test ok
Route::get('players/ranking/winner',[DiceController::class, 'rankingPlayersWinner'])->name('rankingPlayersWinner');
// PUT /players/{id} : modifica el nom del jugador/a. -                                                         test ok
Route::put('/players/{id}',[AuthController::class, 'updateName'])->name('updateName');
//Logout -                                                                                                      test ok
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

});

Route::fallback(function(){
    return response()->json([
        'message' => 'Page Not Found.'], 404);
});