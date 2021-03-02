<?php

namespace App\Modules\Novaposhta\Core\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Office;
use App\Modules\Novaposhta\Core\Services\NewPost;

class NovaPoshtaController extends Controller
{
    // type NewPost warehouse: post office & cargo office
    const POST_OFFICE  = '841339c7-591a-42e2-8233-7a0a00f0ed6f';
    const CARGO_OFFICE = '9a68df70-0267-42a8-bb5c-37f427e36ee4';

    private $np;

    public function __construct(NewPost $np)
    {
        $this->np = $np;
    }

    public function cities()
    {
        /*set_time_limit ( 0 );
        $page = 1;
        while ($page) {
            $rawCities = $this->np->getSettlements('','','','', $page);
            if (!count($rawCities))  break;
            $cities = $this->parseCities($rawCities);
            $page++;
            City::insert($cities);
        }

        return 'inserted '. $page.' page.';*/
    }

    public function offices()
    {
        set_time_limit ( 0 );

        Office::truncate();

        $cities = City::all()->take(1000);
        foreach ($cities as $item) {
            $city = City::find($item->id);
            if ($city) {
                $ref = $city->ref;
                $rawOffices1 = $this->np->getWarehousesByRef($ref, self::POST_OFFICE);
                $rawOffices2 = $this->np->getWarehousesByRef($ref, self::CARGO_OFFICE);
                $offices = $this->parseOffices(array_merge($rawOffices1, $rawOffices2), $ref);
                //dd($ref, $offices);
                Office::insert($offices);
            }
        }


    }

    private function parseOffices(array $offices, string $ref='') :array
    {
        if (!count($offices)) return [];
        $result = [];
        foreach ($offices as $key => $office)
        {
            $result[$key]['ref'] = $ref;
            $result[$key]['number'] = (int) $office->Number;
            $result[$key]['name_ua'] = $office->Description;
            $result[$key]['name_ru'] = $office->DescriptionRu;
            $result[$key]['short_address_ua'] = $office->ShortAddress;
            $result[$key]['short_address_ru'] = $office->ShortAddressRu;
        }

        return $result;
    }

    /*private function parseCities(array $cities) :array
    {
        if (!count($cities)) return [];

        $result = [];
        foreach ($cities as $key => $city)
        {
            if ($city->Warehouse =='1') {
                $result[$key]['ref'] = $city->Ref;
                $result[$key]['name_ua'] = $city->Description;
                $result[$key]['name_ru'] = $city->DescriptionRu;
                $result[$key]['region'] = $city->RegionsDescription;
                $result[$key]['area'] = $city->AreaDescription;
                $result[$key]['name'] = $city->Description . ' - ' . $city->RegionsDescription . ' - ' . $city->AreaDescription;
                $result[$key]['type'] = $city->SettlementTypeDescription;
            }
        }

        return $result;
    }*/
}
