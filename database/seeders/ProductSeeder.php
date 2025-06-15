<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'category_id' => 1, // Assuming category_id 1 is for seals
                'nama_barang' => 'Mechanical Seal Type A',
                'harga_barang' => 1250000.00,
                'deskripsi' => 'High-quality mechanical seal for industrial pumps, durable and leak-proof',
                'stock' => 50,
                'code' => 'MS-A-2023',
                'views' => 0,
                'sold' => 0,
            ],
            [
                'category_id' => 1,
                'nama_barang' => 'Hydraulic Seal Kit',
                'harga_barang' => 850000.00,
                'deskripsi' => 'Complete hydraulic seal kit for various machinery applications',
                'stock' => 30,
                'code' => 'HS-KIT-01',
                'views' => 0,
                'sold' => 0,
            ],
            [
                'category_id' => 1,
                'nama_barang' => 'Oil Seal Double Lip',
                'harga_barang' => 350000.00,
                'deskripsi' => 'Double lip oil seal for superior protection against oil leakage',
                'stock' => 100,
                'code' => 'OS-DL-2023',
                'views' => 0,
                'sold' => 0,
            ],
            [
                'category_id' => 1,
                'nama_barang' => 'Rotary Shaft Seal',
                'harga_barang' => 650000.00,
                'deskripsi' => 'Premium rotary shaft seal for high-speed applications',
                'stock' => 25,
                'code' => 'RS-SEAL-05',
                'views' => 0,
                'sold' => 0,
            ],
            [
                'category_id' => 1,
                'nama_barang' => 'Gasket Seal Industrial Grade',
                'harga_barang' => 275000.00,
                'deskripsi' => 'Industrial grade gasket seal for high pressure and temperature',
                'stock' => 75,
                'code' => 'GS-IND-10',
                'views' => 0,
                'sold' => 0,
            ],
        ]);
    }
}
