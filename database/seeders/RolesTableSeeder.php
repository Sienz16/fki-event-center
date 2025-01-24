<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Define the roles
        $roles = [
            ['role_name' => 'admin'],
            ['role_name' => 'event_organizer'],
            ['role_name' => 'student'],
        ];

        // Insert roles into the database
        DB::table('roles')->insert($roles);
    }
}
