<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //размеры массивов дб одинаковы.
        $category = [
            'Женские парфюмы', 'Мужские парфюмы', 'Антисептики', 'Автопарфюмы', 'Спреи для волос',
            'Годовой запас парфюма 500ml Женская парфюмерия',
            'Годовой запас парфюма 500ml Мужская парфюмерия',
        ];

        $categoryUa = [
            'Жіночі парфуми','Чоловічі парфуми','Антисептики','Автопарфуми', 'Спреї для волосся',
            'Річний запас парфуму 500ml Жіноча парфумерія',
            'Річний запас парфуму 500ml Чоловіча парфумерія',
        ];

        for ($i=0; $i < count($category); $i++) {
            Category::create([
                'name' => $category[$i],
                'name_ua' => $categoryUa[$i],
            ]);
        }
    }
}
