<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'nama_toko' => "ViviaShop",
            'alamat' => 'Jalan Mojolangu',
            'telepon' => '0182190410',
            'path_logo' => 'Jalan Mojolangu',
        ]);
    }
}
