<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CalculateMonthlySales extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:calculate-monthly-sales';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // get monthly sales

        $currentYear = Carbon::now()->year;
        $sales = Sale::whereYear('date', $currentYear)->get()->groupBy(function($date) {
            return Carbon::parse($date->date)->format('m');
        });

        $monthlyTotals = [];

        // Loop through each month of the year
        for ($month = 1; $month <= 12; $month++) {
            // Format the month to have leading zeros if necessary
            $formattedMonth = str_pad($month, 2, '0', STR_PAD_LEFT);

            // Check if there are sales for this month
            if (isset($sales[$formattedMonth])) {
                // Calculate total sales for the month
                $totalSales = $sales[$formattedMonth]->sum(function ($sale) {
                    $quantity = $sale->quantity;
                    $product_code = $sale->product_code;
                    $productPrice = Product::where('code', $product_code)->value('price');
                    return $productPrice * $quantity;
                });

                $totalSales = round($totalSales);

                // Store the total sales for the month
                $monthlyTotals[$formattedMonth] = $totalSales;
            } else {
                // If there are no sales for this month, set the total to zero
                $monthlyTotals[$formattedMonth] = 0;
            }
        }
        Cache::put('monthlyTotals', $monthlyTotals, 24 * 60 * 60);
    }
}
