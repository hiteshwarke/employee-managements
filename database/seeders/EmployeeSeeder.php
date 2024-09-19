<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use Faker\Factory as Faker;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        
        foreach (range(1, 10) as $index) {
            Employee::create([
                'employee_name' => $faker->name,
                'address' => $faker->address,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->unique()->phoneNumber,
                'dob' => $faker->date($format = 'Y-m-d', $max = '2000-01-01'),
                'image' => 'default.jpg', // You can add a default image or handle image upload separately
            ]);
        }
    }
}
