<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\City;
use App\Modules\Novaposhta\Core\Services\NewPost;

class UpdateCities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:cities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily update cities table';

    // service with methods call NewPost api
    private $np;

    /**
     * UpdateCities constructor.
     * @param NewPost $np
     */
    public function __construct(NewPost $np)
    {
        parent::__construct();
        $this->np = $np;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        set_time_limit ( 0 );

        City::truncate();
        $page = 1;
        while ($page) {
            $rawCities = $this->np->getSettlements('','','','', $page);
            if (!count($rawCities))  break;
            $cities = $this->parseCities($rawCities);
            $page++;
            City::insert($cities);
            echo '.';
        }

        echo "\n".'inserted '. $page.' page.';
        return 0;
    }

    private function parseCities(array $cities) :array
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
                $result[$key]['type'] = $city->SettlementTypeDescription;
                $dateTime = date('Y-m-d H:i:s');
                $result[$key]['created_at'] = $dateTime;
                $result[$key]['updated_at'] = $dateTime;
            }
        }

        return $result;
    }
}
