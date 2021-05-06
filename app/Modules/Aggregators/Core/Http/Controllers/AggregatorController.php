<?php

namespace App\Modules\Aggregators\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Services\Xml\Data;

class AggregatorController extends Controller
{
    private $xmlData;
    private $categories;

    public function __construct(Data $xmlData)
    {
        $this->xmlData = $xmlData;
        $this->categories = [
            0 => ['id' => 1, 'name' => 'Женская парфюмерия 50мл',  'short' => 'Женский парфюм'],
            1 => ['id' => 2, 'name' => 'Женская парфюмерия 100мл', 'short' => 'Женский парфюм'],
            2 => ['id' => 3, 'name' => 'Мужская парфюмерия 50мл',  'short' => 'Мужской парфюм'],
            3 => ['id' => 4, 'name' => 'Мужская парфюмерия 100мл', 'short' => 'Мужской парфюм'],
        ];
    }

    public function promUa()
    {
        //only categories 1 и 2 ( man & woman parfumes) (? it can be edited ...)
        //$categories = Category::whereIn('id', [1,2])->get();
        //all data for create prom.ua xml-file; here only Man & Woman parfume volume 50 or 100 ml

        return response()->view('aggregators.prom_ua_xml', [
            'products' => $this->xmlData->products(),
            'categories' => $this->categories,
        ])->header('Content-Type', 'text/xml');
    }

    /*public function changeDescription()
{
    //$newMainNotes = 'Основные ноты';//'Основные аккорды'
    $newStartNote = 'Начальная нота';
    $newHeartNote = 'Нота сердца';
    $newFinishNote = 'Конечная нота';
    $new = [$newStartNote, $newHeartNote, $newFinishNote];

    //$oldMainNotes = 'Основные аккорды';
    $oldStartNote = 'Верхние ноты';
    $oldHeartNote = 'Ноты сердца';
    $oldFinishNote = 'Базовая нота';
    $old = [$oldStartNote, $oldHeartNote, $oldFinishNote];

    $descriptions = Product::where('description', 'LIKE', '%'.$oldStartNote.'%')
        ->get()
        ->map(function ($item) use($old, $new) {
            $item->description = str_replace($old, $new, $item->description);
            $item->save();
            return $item;
        });

    //dd($descriptions);//nixera ne vidno, but it work :)
    return true;
}*/

    //
    public function googleLocal()
    {
        return response()->view('aggregators.google_local_xml', [
            'products' => $this->xmlData->products(),
            'categories' => $this->categories,
        ])->header('Content-Type', 'text/xml');
    }

    public function googleOriginal()
    {
        return response()->view('aggregators.google_original_xml', [
            'products' => $this->xmlData->products(),
            'categories' => $this->categories,
        ])->header('Content-Type', 'text/xml');
    }
}
