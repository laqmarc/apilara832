<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AdminUserTest extends TestCase
{
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function testRegisterUserOnDatabase (){

        $this->artisan('passport:install');
        $this->withoutExceptionHandling();
        $response = $this->postJson('api/players', [
                'name' => $this->faker->name,
                'email' => $this->faker->email,
                'password' => '12341234',
                'password_confirmation' => '12341234',
                'role' => 'user'
        ]);

        $user = User::first();
        $this->assertDatabaseHas('users', $user->toArray());
    }

    /** @test */
    public function testUserCanLogin(){      

        $user = User::factory()->make();
        $params = ['email' => 'marc@quexulo.cat', 'password' => '12341234',];
        $response =  $this->post( '/api/login', $params);
        $isuser = $user ? true : false;
        $this->assertTrue($isuser);
    }
    
    /** @test */
    public function testUserCanLogout($user = null){

        $user = $user ?: User::factory()->create();
        $user = Passport::actingAs($user);
        $response = $this->actingAs($user, 'api')->json('POST', '/api/logout');
        $response->assertOk();

    }

    /** @test */
    public function testUserCanUpdate(){

        $this->artisan('passport:install');    
        $user = User::factory()->create();
        $user = Passport::actingAs($user);
        $response = $this->actingAs($user, 'api')->put(route('updateName', $user->id),
        ['name' => 'ma',
         'email' => 'maa@m.com'  
        ]);
        $response->assertOk();
        
    }

    /** @test */
    public function testEmailRequiredOnLogIn(){

        $this->artisan('passport:install');
        $response = $this->post('api/login', [
            'email' => '',
            'password' => '12341234'
        ]);

        $response->assertStatus(302);

    }
    
    /** @test */
    public function testPasswordRequiredOnLogIn(){

        $this->artisan('passport:install');
        $response = $this->post('api/login', [
            'email' => 'aaa@n.cat',
            'password' => ''
        ]);

        $response->assertStatus(302);

    }


    public function testEmailAndPasswordRequiredOnLogIn(){

        $this->artisan('passport:install');
        $response = $this->post('api/login', [
            'email' => '',
            'password' => ''
        ]);

        $response->assertStatus(302);

    }

    /** @test */
    public function testLoginShowErrors(){

        $response = $this->post('api/login', []);
        $response->assertStatus(302);

    }

    public function testRegisterShowErrors(){

        $response = $this->post('api/players', []);
        $response->assertStatus(302);

    }

}