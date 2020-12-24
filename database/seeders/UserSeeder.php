<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $password = env('SEEDER_USER_PASSWORD', '123');

        DB::table('users')->insert([
            'name' => 'System',
            'email' => 'system@system.com',
            'password' => Hash::make($password),
            'uuid' => Str::uuid()
          ]);
    }
}
