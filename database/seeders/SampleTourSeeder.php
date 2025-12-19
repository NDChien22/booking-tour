<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SampleTourSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $regions = [
            'Miền Bắc' => ['Hà Nội', 'Sa Pa', 'Ninh Bình'],
            'Miền Trung' => ['Đà Nẵng', 'Huế', 'Quy Nhơn', 'Nha Trang'],
            'Miền Nam' => ['Phú Quốc', 'Cần Thơ', 'Đà Lạt'],
        ];

        $cityIds = [];

        foreach ($regions as $regionName => $cities) {
            $regionId = DB::table('regions')->insertGetId([
                'region_name' => $regionName,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            foreach ($cities as $cityName) {
                $cityIds[$cityName] = DB::table('cities')->insertGetId([
                    'city_name' => $cityName,
                    'region_id' => $regionId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        $tours = [
            ['Hà Nội City Tour 1N', 'Tham quan phố cổ, hồ Gươm, Văn Miếu, ẩm thực Hà Nội.', 'https://images.unsplash.com/photo-1505761671935-60b3a7427bad', '08:00 đón khách - 12:00 ăn trưa - 17:00 kết thúc', 1, 1500000, 'Hà Nội'],
            ['Sa Pa – Fansipan 3N2Đ', 'Check-in Fansipan, bản Cát Cát, chợ đêm Sa Pa.', 'https://images.unsplash.com/photo-1483672625400-232dfea5eb3b', 'Ngày 1: Hà Nội - Sa Pa | Ngày 2: Fansipan | Ngày 3: Hà Nội', 3, 4100000, 'Sa Pa'],
            ['Ninh Bình – Tràng An 2N1Đ', 'Khám phá Tràng An, chùa Bái Đính, hang Múa.', 'https://images.unsplash.com/photo-1528184039930-bd03972bd974', 'Ngày 1: Bái Đính - Tràng An | Ngày 2: Hang Múa - check-out', 2, 2200000, 'Ninh Bình'],
            ['Đà Nẵng – Hội An 4N3Đ', 'Biển Mỹ Khê, Bà Nà Hills, phố cổ Hội An.', 'https://images.unsplash.com/photo-1494475673543-6a6a27143fc8', 'Ngày 1: Đà Nẵng | Ngày 2: Bà Nà | Ngày 3: Hội An | Ngày 4: Tự do', 4, 4500000, 'Đà Nẵng'],
            ['Huế – Di sản 3N2Đ', 'Đại Nội, lăng Minh Mạng, chùa Thiên Mụ, ẩm thực Huế.', 'https://images.unsplash.com/photo-1535083784675-6231a28d92e7', 'Ngày 1: Đại Nội | Ngày 2: Lăng tẩm | Ngày 3: Chợ Đông Ba', 3, 3400000, 'Huế'],
            ['Quy Nhơn – Kỳ Co 3N2Đ', 'Eo Gió, Kỳ Co, hải sản tươi ngon.', 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e', 'Ngày 1: Quy Nhơn city | Ngày 2: Kỳ Co - Eo Gió | Ngày 3: Tự do', 3, 3700000, 'Quy Nhơn'],
            ['Nha Trang – Lặn biển 3N2Đ', 'VinWonders, Hòn Mun, tour lặn ngắm san hô.', 'https://images.unsplash.com/photo-1500375592092-40eb2168fd21', 'Ngày 1: City tour | Ngày 2: Đảo | Ngày 3: Tự do', 3, 3600000, 'Nha Trang'],
            ['Phú Quốc – Biển Xanh 4N3Đ', 'Sunset Sanato, Vinpearl Safari, cáp treo Hòn Thơm.', 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e', 'Ngày 1: Bắc đảo | Ngày 2: Nam đảo | Ngày 3: Safari | Ngày 4: Tự do', 4, 5600000, 'Phú Quốc'],
            ['Cần Thơ – Chợ nổi 2N1Đ', 'Chợ nổi Cái Răng, nhà cổ Bình Thủy, du thuyền sông Hậu.', 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee', 'Ngày 1: City tour | Ngày 2: Chợ nổi - đặc sản', 2, 2400000, 'Cần Thơ'],
            ['Đà Lạt – Săn mây 3N2Đ', 'Đồi chè Cầu Đất, hồ Tuyền Lâm, chợ đêm, săn mây.', 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e', 'Ngày 1: City | Ngày 2: Săn mây | Ngày 3: Tự do', 3, 3800000, 'Đà Lạt'],
        ];

        $records = [];
        foreach ($tours as [$title, $desc, $img, $itinerary, $duration, $price, $cityName]) {
            $records[] = [
                'title' => $title,
                'description' => $desc,
                'url_img' => $img,
                'itinerary' => $itinerary,
                'duration' => $duration,
                'price' => $price,
                'city_id' => $cityIds[$cityName] ?? null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('tours')->insert($records);
    }
}
