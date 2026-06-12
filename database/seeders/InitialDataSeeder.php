<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InitialDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample Categories
        $categories = ['Electronics', 'Groceries', 'Clothing', 'Furniture'];
        foreach ($categories as $category) {
            \App\Models\Category::create([
                'name' => $category,
                'branch' => 'Main',
                'status' => 'active',
            ]);
        }

        // Default Store
        \App\Models\Store::create([
            'name' => 'Main Store',
            'host' => '127.0.0.1',
        ]);

        // Default Administrator Person
        $person = \App\Models\Person::create([
            'staff_id' => 'STF001',
            'title' => 'Mr.',
            'first_name' => 'David',
            'last_name' => 'Okunola',
            'sex' => 'Male',
            'dob' => '1990-01-01',
            'mstatus' => 'Single',
            'religion' => 'None',
            'phone_number' => '1234567890',
            'email' => 'admin@admin.com',
            'address' => 'Lagos, Nigeria',
            'state' => 'Lagos',
            'country' => 'Nigeria',
            'nok' => 'N/A',
            'nok_address' => 'N/A',
            'nok_contact' => 'N/A',
            'nok_email' => 'nok@example.com',
            'nok_rela' => 'N/A',
            'comments' => 'Initial system administrator',
        ]);

        // Default Administrator User
        \App\Models\User::create([
            'person_id' => $person->person_id,
            'staff_id' => 'STF001',
            'password' => Hash::make('password'),
            'position' => 'administrator',
            'creator' => 'system',
        ]);
    }
}
