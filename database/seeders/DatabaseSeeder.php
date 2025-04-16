<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'first_name' => 'Master',
            'last_name' => 'Admin',
            'email' => 'admin@gmail.com',
            'user_status' => '1',
            'user_type' => '1',
            'password' => bcrypt('admin@123'),
            'created_by' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
