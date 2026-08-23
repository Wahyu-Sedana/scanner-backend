<?php

namespace Database\Seeders;

use App\Models\Passcode;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@barcodescanner.com'],
            ['name' => 'Admin', 'password' => bcrypt('admin12345')],
        );

        Passcode::firstOrCreate(
            ['code' => 'COCO1234'],
            ['is_active' => true],
        );

        $this->call(EmployeeSeeder::class);
    }
}
