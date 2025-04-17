<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Province;
use Illuminate\Database\Seeder;

class ProvinceCitySeeder extends Seeder
{
    public function run()
    {
        $provinces = [
            'تهران' => ['تهران', 'شهریار', 'اسلامشهر', 'پاکدشت'],
            'اصفهان' => ['اصفهان', 'کاشان', 'خمینی شهر', 'شاهین شهر'],
            'فارس' => ['شیراز', 'مرودشت', 'کازرون', 'جهرم'],
            // سایر استان‌ها و شهرها...
        ];

        foreach ($provinces as $provinceName => $cities) {
            $province = Province::create(['name' => $provinceName]);

            foreach ($cities as $cityName) {
                City::create([
                    'name' => $cityName,
                    'province_id' => $province->id
                ]);
            }
        }
    }
}
