<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProductVariant;
use App\Services\Import\Csv;

/**
 * Class UpdateAuctionRating
 * В таблице products апдейтит поле auction_rating значениями взятыми
 * из Csv файла. Csv файл должен лежать (иметь имя) в /public/src/auction_rating.csv
 * (если нет - менять код стр.50) В csv файле должны быть поля art100,auction_rating
 * Поля для апдейта ищутся по значению art в таблице product_variants и через связь
 * $variant->product()->update() делается апдейт. Т.е. это сработает только в ларавеле.
 * @package App\Console\Commands
 */
class UpdateAuctionRating extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:rating';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update product table with auction product ratings from csv file';

    /**
     * Create a new command instance.
     *
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
        $ratingData = Csv::parseCsv(public_path('src'. DIRECTORY_SEPARATOR .'auction_rating.csv'), ',');
        //dd($ratingData);

        $i = 0;
        foreach ($ratingData as $item) {
            $variant = ProductVariant::with('product')->where('art', $item['art100'])->first();
            if ($variant && ctype_digit($item['auction_rating'])) {
                //dd($variant->product, gettype($item['auction_rating']));
                $variant->product()->update([
                    'auction_rating' => $item['auction_rating'],
                ]);

                $i++;
            }
        }

        echo "\n".'updated '. $i.' records.';
        return 0;
    }
}
