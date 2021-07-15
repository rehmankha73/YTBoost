<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'rehman ahmed khan',
            'email' => 'rehmankha73@gmail.com',
            'total_coins' => random_int(1,5000),
            'remember_token' => Str::random(10),
        ]);
        User::factory()->count(20)->create();
    }
}
