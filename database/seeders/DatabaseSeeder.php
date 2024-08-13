<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'id' => 1,
            'username' => 'Admin',
            'email' => 'admin@admin.it',
            'password' => Hash::make('adminadmin'),
            'userable_type' => 'App\Models\Admin',
        ]);

        DB::table('users')->insert([
            'id' => 2,
            'username' => 'Med1',
            'email' => 'med1@med.it',
            'password' => Hash::make('med1med1'),
            'userable_type' => 'App\Models\Med',
        ]);

        DB::table('users')->insert([
            'id' => 3,
            'username' => 'TestMed1',
            'email' => 'testmed1@testmed.it',
            'password' => Hash::make('testmed1testmed1'),
            'userable_type' => 'App\Models\TestMed',
        ]);
    }
}
