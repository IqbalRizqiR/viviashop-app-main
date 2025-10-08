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
            'alamat' => 'Tebuireng IV No.38 Cukir',
            'telepon' => '081411111769',
            'path_logo' => 'Jalan Mojolangu',
            'email' => 'info@viviashop.com',
            'maps_url' => 'https://maps.app.goo.gl/FQkhHuk1vnFZzcHg8?g_st=aw'
        ]);
    }
}
