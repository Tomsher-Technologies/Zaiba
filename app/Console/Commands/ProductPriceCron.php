<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\GoldPrices;
use App\Models\ProductStock;

class ProductPriceCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product_price:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron job for updating product price';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today_gold_rate    = GoldPrices::first()->toArray();
        $gold_purity        = [18 => '18_k', 21 => '21_k', 22 => '22_k', 24 => '24_k'];
        $productStocks      = ProductStock::orderBy('status','desc')->get();
        if(!empty($productStocks)){
            foreach($productStocks as $proSk){
                $price          = 0;
                $offertag       = '';
                $product_id     = $proSk->product_id;
                $purity         = $proSk->product->purity;
                $metal_weight   = $proSk->metal_weight;
            
                $discount_applicable = false;
                if(array_key_exists($purity, $gold_purity)){
                    $goldRate = isset($today_gold_rate[$gold_purity[$purity]]) ? $today_gold_rate[$gold_purity[$purity]] : 0;
                }
        
                if($goldRate != 0){
                    $metalPrice         = $metal_weight * $goldRate;
                    $stonePrice         = $proSk->stone_price ?? 0;
                    $making_price_type  = $proSk->making_price_type;
                    $making_charge      = $proSk->making_charge ?? 0;
    
                    $total_making_charge = 0; 

                    if($making_price_type == 1){       // Per gram amount
                        $total_making_charge = $metal_weight * $making_charge;
                    }elseif($making_price_type == 2){       // Per gram percentage
                        $total_making_charge = ($metalPrice / 100) * $making_charge;
                    }elseif($making_price_type == 3){       // PC Rate
                        $total_making_charge = $making_charge;
                    }

                    $productOrgPrice = $metalPrice + $stonePrice + $total_making_charge;
                    $discountPrice = $productOrgPrice;

                    if (strtotime(date('d-m-Y H:i:s')) >= $proSk->product->discount_start_date && strtotime(date('d-m-Y H:i:s')) <= $proSk->product->discount_end_date) {
                        if ($proSk->product->discount_type == 'percent') {
                            $discountPrice = $productOrgPrice - (($productOrgPrice * $proSk->product->discount) / 100);
                            $offertag = $proSk->product->discount . '% OFF';
                        } elseif ($proSk->product->discount_type == 'amount') {
                            $discountPrice = $productOrgPrice - $proSk->product->discount;
                            $offertag = 'AED '.$proSk->product->discount.' OFF';
                        }
                    }
                    $proSk->price       = $productOrgPrice;
                    $proSk->offer_price = $discountPrice;
                    $proSk->offer_tag   = $offertag;
                    $proSk->save();
                }
            }
        }
    }
}
