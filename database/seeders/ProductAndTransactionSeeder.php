<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ProductAndTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [];
        for ($i = 1; $i <= 50; $i++) {
            $products[] = [
                'name' => 'Product ' . $i,
                'stock' => rand(10, 100),
                'price' => rand(1000, 10000),
                'variant' => 'Variant ' . Str::random(5),
                'category' => 'Category ' . rand(1, 10),
                'created_at' => Carbon::now()->subDays(rand(1, 365)),
                'updated_at' => Carbon::now(),
            ];
        }
        DB::table('products')->insert($products);

        // Get all product IDs
        $productIds = DB::table('products')->pluck('product_id')->toArray();

        $transactions = [];
        for ($i = 1; $i <= 100; $i++) {
            $createdAt = Carbon::now()->subDays(rand(1, 365));
            $productId = $productIds[array_rand($productIds)];
            $qty = rand(1, 10);
            $price = DB::table('products')->where('product_id', $productId)->value('price');
            $total = $qty * $price;

            $transactions[] = [
                'customer_name' => 'Customer ' . Str::random(5),
                'qty' => $qty,
                'method_payment' => rand(0, 1) ? 'Cash' : 'Card',
                'total' => $total,
                'product_id' => $productId,
                'created_at' => $createdAt,
                'updated_at' => $createdAt->copy()->addHours(rand(1, 24)),
            ];
        }
        DB::table('transactions')->insert($transactions);
    }
}