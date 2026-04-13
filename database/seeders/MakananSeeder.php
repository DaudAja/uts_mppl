<?php

namespace Database\Seeders;

use App\Models\Makanan;
use Illuminate\Database\Seeder;

class MakananSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama' => 'Nasi Goreng Spesial',
                'harga'        => 25000,
                'stok'         => 20,
                'foto' => 'https://images.unsplash.com/photo-1603133872878-684f208fb84b?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'nama' => 'Mie Ayam Bakso',
                'harga'        => 15000,
                'stok'         => 15,
                'foto' => 'https://images.unsplash.com/photo-1585032226651-759b368d7246?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'nama' => 'Es Teh Manis',
                'harga'        => 5000,
                'stok'         => 50,
                'foto' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=800&q=80',
            ],
        ];

        foreach ($data as $item) {
            \App\Models\Makanan::create($item);
        }
    }
}
