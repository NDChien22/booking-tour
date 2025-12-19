<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SampleTourSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo region
        $regionId = DB::table('regions')->insertGetId([
            'region_name' => 'Miền Bắc',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Tạo city
        $cityId = DB::table('cities')->insertGetId([
            'city_name' => 'Hà Nội',
            'region_id' => $regionId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Tạo tour sample
        DB::table('tours')->insert([
            'title' => 'Hà Nội City Tour 1N',
            'description' => 'Tham quan phố cổ, hồ Gươm, Văn Miếu, thưởng thức ẩm thực Hà Nội.',
            'url_img' => 'https://example.com/hanoi.jpg',
            'itinerary' => '08:00 đón khách - 12:00 ăn trưa - 17:00 kết thúc',
            'duration' => 1,
            'price' => 1500000,
            'city_id' => $cityId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
