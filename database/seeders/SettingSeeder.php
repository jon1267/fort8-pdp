<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // пока это таблица одной записи, к-рая всегда апдейтится - вставляю пустую запись
        Setting::create([
            'analytic_code'  => '',
            'header_mobile'  => '',
            'header_desktop' => '',
            'slider_show' => 0
        ]);
    }
}
