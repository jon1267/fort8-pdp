<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Office;
use App\Modules\Novaposhta\Core\Services\NewPost;

class UpdateOffices extends Command
{
    // type NewPost warehouse: post office & cargo office
    const POST_OFFICE  = '841339c7-591a-42e2-8233-7a0a00f0ed6f';
    const CARGO_OFFICE = '9a68df70-0267-42a8-bb5c-37f427e36ee4';

    // service with api methods NewPost
    public $np;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:offices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily update offices table';

    /**
     * Create a new command instance.
     * @param NewPost $np
     * @return void
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

        Office::truncate();
        $page = 1;
        while ($page) {
            $rawOffices = $this->np->getWarehousesByRef('', '', $page);
            if (!count($rawOffices))  break;
            $offices = $this->parseOffices($rawOffices);
            $page++;
            //dd($offices);
            if (count($offices)) {
                Office::insert($offices);
            }
            echo '.';
        }

        echo "\n".'inserted '. $page.' page.';
        return 0;
    }

    private function parseOffices(array $offices) :array
    {
        if (!count($offices)) return [];
        $result = [];
        foreach ($offices as $key => $office)
        {
            if ($office->TypeOfWarehouse == self::POST_OFFICE || $office->TypeOfWarehouse == self::CARGO_OFFICE) {
                $result[$key]['ref'] = $office->SettlementRef;
                $result[$key]['number'] = (int) $office->Number;
                $result[$key]['name_ua'] = $office->Description;
                $result[$key]['name_ru'] = $office->DescriptionRu;
                $result[$key]['short_address_ua'] = $office->ShortAddress;
                $result[$key]['short_address_ru'] = $office->ShortAddressRu;
                $dateTime = date('Y-m-d H:i:s');
                $result[$key]['created_at'] = $dateTime;
                $result[$key]['updated_at'] = $dateTime;
            }
        }

        return $result;
    }

}
