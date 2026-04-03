<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Packaging;
use App\Models\ProductCategory;
use App\Models\Role;
use App\Models\Termin;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::insert([
            ['name' => 'Owner'],
            ['name' => 'Admin'],
        ]);

        User::insert([
            [
                'name' => 'Era Frida Septiani',
                'role_id' => 1,
                'username' => 'era',
                'password' => bcrypt('era'),
            ]
        ]);

        Termin::insert([
            ['name' => 'Langsung', 'value' => 0],
        ]);

        ProductCategory::insert([
            ['name' => 'Tas'],
            ['name' => 'Lampu'],
        ]);

        Packaging::insert([
            ['name' => 'Small'],
            ['name' => 'Medium'],
            ['name' => 'Large'],
        ]);
    }
}
