<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $employees = [
            ['name' => 'Yester Simanullang', 'personal_email' => 'manullangy@gmail.com', 'phone' => '081360625765'],
            ['name' => 'I Dewa Made Putra', 'personal_email' => 'hrmcocogrupbali@gmail.com', 'phone' => '084124668272'],
            ['name' => 'Ayunda Mariska Astari', 'personal_email' => 'mariskaastari1@gmail.com', 'phone' => '085738155434'],
            ['name' => 'Made Ayu Ratna Sari Hanaya', 'personal_email' => 'Hanayaayu14@gmail.com', 'phone' => '081237383310'],
            ['name' => 'Juniar Ayu Deviantary Blegur L', 'personal_email' => 'juniarayu00@gmail.com', 'phone' => '082266206611'],
            ['name' => 'Ni Komang Ayuk Risna Sephiawati', 'personal_email' => 'sephia.wati24@gmail.com', 'phone' => '081949769209'],
            ['name' => 'Khevin Dwi Arbayu', 'personal_email' => 'khevinrecruitment.cocogroup@gmail.com', 'phone' => '081333343771'],
            ['name' => 'Dani Andrean Widodo', 'personal_email' => 'daniandrew7@gmail.com', 'phone' => '082337688593'],
            ['name' => 'I Made Juniandika', 'personal_email' => 'adeecommerce28@gmail.com', 'phone' => '081243461636'],
            ['name' => 'Arini Kusuma Dewi', 'personal_email' => 'ecomcocogroup@gmail.com', 'phone' => '088987140511'],
            ['name' => 'Ardy', 'personal_email' => 'ardybachtiarriyadi@gmail.com', 'phone' => '081236098997'],
            ['name' => 'Risky', 'personal_email' => 'admecommercecoco@gmail.com', 'phone' => '082145459911'],
            ['name' => 'Hetty', 'personal_email' => null, 'phone' => '081265229480'],
            ['name' => 'Billa', 'personal_email' => 'sabillaa977@gmail.com', 'phone' => '081330485732'],
            ['name' => 'Retno Adelia', 'personal_email' => 'retnoadelia1997@gmail.com', 'phone' => '085839855328'],
            ['name' => 'Anisah Adu', 'personal_email' => 'nisaaadu5@gmil.com', 'phone' => '087787470944'],
            ['name' => 'Komang pitri', 'personal_email' => 'komangpitri28@gmail.com', 'phone' => '088276099687'],
            ['name' => 'Anjani Cinta Lestari', 'personal_email' => 'cintalestari0127@gmail.com', 'phone' => '085956278540'],
            ['name' => 'Theresia Yuneti Harmina', 'personal_email' => 'theresiayunetiharmina24@gmail.com', 'phone' => '082237151279'],
            ['name' => 'Riyadlotul islahiyah', 'personal_email' => 'riyadlotul2810@gmail.com', 'phone' => '085792169165'],
            ['name' => 'Celsy Marta', 'personal_email' => 'celsymartaa@gmail.com', 'phone' => '085148491677'],
        ];

        foreach ($employees as $employee) {
            Employee::firstOrCreate(
                ['phone' => $employee['phone']],
                $employee,
            );
        }
    }
}
