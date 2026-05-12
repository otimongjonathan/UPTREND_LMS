<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Staff User
        User::firstOrCreate(
            ['email' => 'staff@uptrendlms.com'],
            [
                'name' => 'Staff Admin',
                'business_name' => 'UPTREND LMS Staff',
                'address' => '123 Business Street, Kampala',
                'tel_no' => '+256700000001',
                'password' => bcrypt('password'),
                'role' => 'staff',
                'email_verified_at' => now(),
            ]
        );

        // Create Customer User
        User::firstOrCreate(
            ['email' => 'customer@uptrendlms.com'],
            [
                'name' => 'Customer User',
                'business_name' => 'Customer Business Ltd',
                'address' => '456 Customer Avenue, Kampala',
                'tel_no' => '+256700000002',
                'password' => bcrypt('password'),
                'role' => 'customer',
                'email_verified_at' => now(),
            ]
        );

        // Seed loan products and applications
        $this->call([
            LoanProductSeeder::class,
            TestDataSeeder::class,
        ]);

        echo "\n✅ Database seeded successfully!\n";
        echo "Staff Login: staff@uptrendlms.com / password\n";
        echo "Customer Login: customer@uptrendlms.com / password\n";
        echo "8 test loan applications created\n\n";
    }
}
