<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class AuctionPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productAuctionPrices = [
            'auction_price' => 425,
            'auction_price_min' => 390,
            'auction_show' => 1
        ];

        // $addAuction contain count updated records
        $addAuction =  Product::with('categories')
            ->whereHas('categories', function ($query) {
                $query->whereIn('categories.id', [1,2]);
            })->update($productAuctionPrices);

    }
}
