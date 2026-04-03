<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Packaging;
use App\Models\Product;
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
            ['name' => 'Tempat Tissue'],
            ['name' => 'Payung'],
            ['name' => 'Jam Dinding'],
            ['name' => 'Jam + Speaker Bluetooth'],
            ['name' => 'Cermin'],
            ['name' => 'Ganci'],
            ['name' => 'Slipper'],
            ['name' => 'Slayer'],
            ['name' => 'Handuk'],
            ['name' => 'Tanaman Succulent'],
            ['name' => 'Madu Akasia'],
            ['name' => 'Handsanitizer'],
            ['name' => 'Hampers Raudhah Series'],
            ['name' => 'Hampers Blooming Series'],
            ['name' => 'Hampers Jemeela Series'],
            ['name' => 'Wooden Stuff'],
            ['name' => 'Cultery Set'],
            ['name' => 'Tasbih'],
            ['name' => 'Botol / Tumbler'],
            ['name' => 'Tumbler Stainless'],
            ['name' => 'Mug & Poci'],
            ['name' => 'Miniso Pouch'],
            ['name' => 'Texture Pouch'],
            ['name' => 'Pouch Resleting'],
            ['name' => 'Fabric Pouch'],
            ['name' => 'Premium Pouch'],
            ['name' => 'Card Holder'],
            ['name' => 'Corporate Souvenir'],
            ['name' => 'Hampers Exclusive'],
            ['name' => 'Paket Hampers'],
            ['name' => 'Paket Undangan Custom Ekonomis'],
            ['name' => 'Undangan Cetak - Blangko'],
            ['name' => 'Undangan Cetak - Rustic Series'],
            ['name' => 'Undangan Cetak - Hardcover Custom'],
            ['name' => 'Undangan Cetak - Softcover Custom'],
            ['name' => 'Undangan Cetak - Undangan Custom'],
            ['name' => 'Undangan Web'],
            ['name' => 'Trays'],
            ['name' => 'Mahar'],
            ['name' => 'Souvenir Haji, Umroh & Pengajian'],
            ['name' => 'Seminar Kit'],
            ['name' => 'Paperbag'],
            ['name' => 'Totebag'],
            ['name' => 'Shopping Bag'],
            ['name' => 'Mangkok & Piring'],
            ['name' => 'Red Difusser'],
            ['name' => 'Asbak'],
            ['name' => 'Soap Dispenser'],
            ['name' => 'Kipas'],
        ]);

        Packaging::insert([
            ['name' => 'Standard'],     // 1
            ['name' => 'Plastik'],      // 2
            ['name' => 'Box'],          // 3
            ['name' => 'Tile'],         // 4
            ['name' => 'Mika'],         // 5
            ['name' => 'Doublewall'],   // 6
            ['name' => 'Softbox'],      // 7
            ['name' => 'Velvet'],       // 8
            ['name' => 'Box Mika'],     // 9
            ['name' => 'Box Sliding'],  // 10
            ['name' => 'Blacu'],        // 11
        ]);

        Product::insert([
            ['name' => 'Promosi', 'product_category_id' => 1, 'packaging_id' => 1, 'minimum_order' => 1, 'price' => 10000],
            ['name' => 'Leather 100ply', 'product_category_id' => 1, 'packaging_id' => 1, 'minimum_order' => 1, 'price' => 9000],
            ['name' => 'Acrylic', 'product_category_id' => 1, 'packaging_id' => 4, 'minimum_order' => 1, 'price' => 21000],
            ['name' => 'Kayu 300ply', 'product_category_id' => 1, 'packaging_id' => 11, 'minimum_order' => 1, 'price' => 40000],
            ['name' => 'Kayu Kecil', 'product_category_id' => 1, 'packaging_id' => 1, 'minimum_order' => 1, 'price' => 12000],
            ['name' => 'Kayu Full Stiker', 'product_category_id' => 1, 'packaging_id' => 1, 'minimum_order' => 1, 'price' => 18000],
        ]);

    }
}
