<?php

namespace Tests\Feature;

use App\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class DiceTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
     public function testUserCanThrowDice(){

        $user = User::factory()->create();
        $user = Passport::actingAs($user);
        $response = $this->actingAs($user, 'api')->post(route('throwDice', $user->id));
        $response->assertOk();
    
    }

    /** @test */
    public function testUserCanShowMatches(){

        $user = User::factory()->create();
        $user = Passport::actingAs($user);
        $response = $this->actingAs($user, 'api')->get(route('stats', $user->id));
        $response->assertOk();
    
    }
        
    /** @test */
    public function testUserCanDeleteMatch(){

        $user = User::factory()->create();
        $user = Passport::actingAs($user);
        $response = $this->actingAs($user, 'api')->delete(route('deleteAllGames', $user->id));
        $response->assertOk();
    
    }

    /** @test */
    public function testAdminCanShowStats(){

        $admin = User::factory()->create(['role' => 'admin' ]);
        $admin = Passport::actingAs($admin);
        $response = $this->actingAs($admin, 'api')->get(route('stats'));
        $response->assertOk();
        
    }

    /** @test */
    public function testAdminCanShowRankingPlayers(){

        $admin = User::factory()->create(['role' => 'admin' ]);
        $admin = Passport::actingAs($admin);
        $response = $this->actingAs($admin, 'api')->get(route('rankingPlayers'));
        $response->assertOk();
        
    }

    /** @test */
    public function testAdminCanShowRankingPlayersWinner(){

        $admin = User::factory()->create(['role' => 'admin' ]);
        $admin = Passport::actingAs($admin);
        $response = $this->actingAs($admin, 'api')->get(route('rankingPlayersWinner'));
        $response->assertOk();
        
    }

    /** @test */
    public function testAdminCanShowRankingPlayersLoser(){

        $admin = User::factory()->create(['role' => 'admin' ]);
        $admin = Passport::actingAs($admin);
        $response = $this->actingAs($admin, 'api')->get(route('rankingPlayersLoser'));
        $response->assertOk();
        
   }

    /** @test */
    public function testUnauthenticatedUserCannotPlay(){

        $response = $this->postJson('api/players/{id}/games');
        $response->assertStatus(401);

    }


    /** @test */
    public function testUnauthenticatedUserCannotListPlayersWinRate(){

        $response = $this->getJson('api/players');
        $response->assertStatus(401);

    }
    /** @test */
    public function testUnauthenticatedUserCannotShowPlayersAllGames(){

        $response = $this->getJson('api/players/{id}/games');
        $response->assertStatus(401);

    }
    /** @test */
    public function testUnauthenticatedUserCannotDeletePlayersGames(){

        $response = $this->deleteJson('api/players/{id}/games');
        $response->assertStatus(401);

    }
    /** @test */
    public function testUnauthenticatedUserCannotShowRankings(){

        $response = $this->getJson('api/players/ranking');
        $response->assertStatus(401);

    }

    /** @test */
    public function testUnauthenticatedUserCannotShowLosers(){

        $response = $this->getJson('api/players/ranking/loser');
        $response->assertStatus(401);

    }

    /** @test */
    public function testUnauthenticatedUserCannotShowWinners(){

        $response = $this->getJson('api/players/ranking/loser');
        $response->assertStatus(401);
    }

    /** @test */
    public function testUnauthenticatedUserCannotUpdateName(){

        $response = $this->putJson('api/players/{id}');
        $response->assertStatus(401);
    }

}
