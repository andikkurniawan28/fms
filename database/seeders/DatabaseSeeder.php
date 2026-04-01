<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Role;
use App\Models\Termin;
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

        Termin::insert([
            ['name' => 'Langsung', 'value' => 0],
        ]);
    }
}
