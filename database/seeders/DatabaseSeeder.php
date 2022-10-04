<?php

namespace Database\Seeders;

// use Dotenv\Util\Str;

use App\Models\Dice;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@m.cat',
            'email_verified_at' => now(),
            'password' => bcrypt('12341234'),
            'remember_token' => Str::random(10),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),

        ]);
        DB::table('users')->insert([
            'name' => 'user',
            'email' => 'user@m.cat',
            'email_verified_at' => now(),
            'password' => bcrypt('12341234'),
            'remember_token' => Str::random(10),
            'role' => 'user',
            'created_at' => now(),
            'updated_at' => now(),

        ]);

        Dice::factory(50)->create();
    }
}
