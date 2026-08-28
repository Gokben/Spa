<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class ExampleEmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $employees = [
            [
                'first_name' => 'Ayşe', 'last_name' => 'Demir', 'personnel_no' => 'TEST-001',
                'hire_date' => '2024-01-15', 'birth_date' => '1992-05-10', 'blood_group' => 'A+',
                'gender' => 'Kadın', 'phone' => '0212 555 10 01', 'mobile_phone' => '0532 555 10 01',
                'email' => 'ayse.demir@example.test', 'city' => 'İstanbul', 'district' => 'Şişli',
                'address' => 'Örnek Mahallesi, Test Sokak No: 1', 'status' => 'aktif',
            ],
            [
                'first_name' => 'Mehmet', 'last_name' => 'Kaya', 'personnel_no' => 'TEST-002',
                'hire_date' => '2023-08-01', 'birth_date' => '1988-11-22', 'blood_group' => '0+',
                'gender' => 'Erkek', 'phone' => '0212 555 10 02', 'mobile_phone' => '0533 555 10 02',
                'email' => 'mehmet.kaya@example.test', 'city' => 'İstanbul', 'district' => 'Beşiktaş',
                'address' => 'Deneme Caddesi No: 22 Daire: 4', 'status' => 'aktif',
            ],
            [
                'first_name' => 'Elif', 'last_name' => 'Aydın', 'personnel_no' => 'TEST-003',
                'hire_date' => '2025-03-10', 'birth_date' => '1997-02-14', 'blood_group' => 'B-',
                'gender' => 'Kadın', 'phone' => null, 'mobile_phone' => '0534 555 10 03',
                'email' => 'elif.aydin@example.test', 'city' => 'İstanbul', 'district' => 'Kadıköy',
                'address' => 'Test Sitesi B Blok Daire: 7', 'status' => 'aktif',
            ],
            [
                'first_name' => 'Can', 'last_name' => 'Yıldız', 'personnel_no' => 'TEST-004',
                'hire_date' => '2022-06-20', 'termination_date' => '2026-07-31',
                'birth_date' => '1985-09-30', 'blood_group' => 'AB+', 'gender' => 'Erkek',
                'phone' => '0212 555 10 04', 'mobile_phone' => '0535 555 10 04',
                'email' => 'can.yildiz@example.test', 'city' => 'İstanbul', 'district' => 'Bakırköy',
                'address' => 'Sahil Yolu No: 18', 'status' => 'ayrıldı',
            ],
            [
                'first_name' => 'Deniz', 'last_name' => 'Arslan', 'personnel_no' => 'TEST-005',
                'hire_date' => '2026-01-05', 'birth_date' => '1994-07-18', 'blood_group' => '0-',
                'gender' => 'Belirtmek İstemiyor', 'phone' => null, 'mobile_phone' => '0536 555 10 05',
                'email' => 'deniz.arslan@example.test', 'city' => 'İstanbul', 'district' => 'Beyoğlu',
                'address' => 'Uygulama Sokak No: 5', 'status' => 'yasaklı',
            ],
        ];

        foreach ($employees as $employee) {
            Employee::query()->updateOrCreate(
                ['personnel_no' => $employee['personnel_no']],
                $employee,
            );
        }
    }
}
