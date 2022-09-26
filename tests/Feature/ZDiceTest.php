<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ZDiceTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testUserCanThrowDice(){

        $user = User::factory()->create();
        $user = Passport::actingAs($user);
        $response = $this->actingAs($user, 'api')->post(route('throwDice', $user->id));
        $response->assertOk();
    
    }

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
    public function testAdminCanShowStats()
    {

        $admin = User::factory()->create(['role' => 'admin' ]);
        $admin = Passport::actingAs($admin);
        $response = $this->actingAs($admin, 'api')->get(route('stats'));
        $response->assertOk();
        
    }

    public function testAdminCanShowRankingPlayers()
    {

        $admin = User::factory()->create(['role' => 'admin' ]);
        $admin = Passport::actingAs($admin);
        $response = $this->actingAs($admin, 'api')->get(route('rankingPlayers'));
        $response->assertOk();
        
    }

    public function testAdminCanShowRankingPlayersWinner()
    {

        $admin = User::factory()->create(['role' => 'admin' ]);
        $admin = Passport::actingAs($admin);
        $response = $this->actingAs($admin, 'api')->get(route('rankingPlayersWinner'));
        $response->assertOk();
        
    }

    public function testAdminCanShowRankingPlayersLoser()
    {

        $admin = User::factory()->create(['role' => 'admin' ]);
        $admin = Passport::actingAs($admin);
        $response = $this->actingAs($admin, 'api')->get(route('rankingPlayersLoser'));
        $response->assertOk();
        
    }
}
