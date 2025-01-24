<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Check if the admin role exists
        $adminRole = Role::where('role_name', 'admin')->first();

        if (!$adminRole) {
            // Create the admin role if it doesn't exist
            $adminRole = Role::create(['role_name' => 'admin']);
        }

        // Create an admin user in the users table
        $adminUser = User::create([
            'name' => 'Sienz',
            'matric_no' => 'Sienz16', // Ensure this value is provided
            'email' => 'sienz@yahoo.com',
            'password' => Hash::make('Fazli12345'), // Use a secure password
        ]);

        // Assign the admin role to the user
        $adminUser->roles()->attach($adminRole);

        // Insert a corresponding record into the admins table
        DB::table('admins')->insert([
            'user_id' => $adminUser->id,
            'manage_name' => $adminUser->name,
            'manage_phoneNo' => '0123456789', // Replace with actual phone number
            'manage_email' => $adminUser->email,
            'manage_position' => 'Administrator', // Replace with actual position
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
