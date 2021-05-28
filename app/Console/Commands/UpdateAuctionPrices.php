<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProductVariant;
use App\Services\Import\Csv;

/**
 * Class UpdateAuctionPrices
 * В таблице products апдейтит поля auction_price, auction_price_min значениями взятыми
 * из Csv файла. Csv файл должен лежать (иметь имя) в /public/src/auction_price.csv
 * (если нет - менять код стр.50) В csv файле должны быть поля art, auction_price, auction_price_min
 * Поля для апдейта ищутся по значению art в таблице product_variants и через связь
 * $variant->product()->update() делается апдейт. Т.е. это сработает только в ларавеле.
 * @package App\Console\Commands
 */
class UpdateAuctionPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:auction';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update product table with auction prices from csv file';

    /**
     * Create a new command instance.
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $auctionData = Csv::parseCsv(public_path('src\auction_price.csv'), ';');
        //dd($auctionData);

        $i = 0;
        foreach ($auctionData as $item) {
            $variant = ProductVariant::with('product')->where('art', $item['art'])->first();
            if ($variant) {
                //dd($variant->product);
                $variant->product()->update([
                    'auction_price' => $item['auction_price'],
                    'auction_price_min' => $item['auction_price_min'],
                    'auction_show' => 1,
                ]);
                //echo '.';
                $i++;
            }
        }

        echo "\n".'updated '. $i.' records.';
        return 0;
    }
}
