<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\School;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin if not exists
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@erp.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'school_id' => null,
            ]
        );

        if ($superAdmin->wasRecentlyCreated) {
            echo "Super Admin created: superadmin@erp.com / password\n";
        } else {
            echo "Super Admin already exists\n";
        }

        // Create a sample school if not exists
        $school = School::firstOrCreate(
            ['code' => 'DPS'],
            [
                'name' => 'Demo Public School',
                'address' => '123 Main Road, City',
                'phone' => '9876543210',
                'email' => 'admin@dpschool.com',
                'joining_date' => now(),
                'expiry_date' => now()->addYear(),
                'status' => 'active',
                'created_by' => $superAdmin->id,
            ]
        );

        if ($school->wasRecentlyCreated) {
            echo "Sample School created: {$school->name}\n";
        } else {
            echo "Sample School already exists\n";
        }

        // Create School Admin if not exists
        $schoolAdmin = User::firstOrCreate(
            ['email' => 'admin@dpschool.com'],
            [
                'name' => 'School Admin',
                'password' => Hash::make('password'),
                'role' => 'school_admin',
                'school_id' => $school->id,
            ]
        );

        if ($schoolAdmin->wasRecentlyCreated) {
            echo "School Admin created: admin@dpschool.com / password\n";
        } else {
            echo "School Admin already exists\n";
        }

        echo "\n=== Login Credentials ===\n";
        echo "Super Admin: superadmin@erp.com / password\n";
        echo "School Admin: admin@dpschool.com / password\n";
    }
}
