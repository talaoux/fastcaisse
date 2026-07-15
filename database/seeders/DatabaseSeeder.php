<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Customer;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {        // Seed users with roles
        $this->call(\Database\Seeders\UserSeeder::class);
        // Create sample customers
        $customers = [
            ['name' => 'Client de passage', 'email' => 'passage@example.com', 'phone' => '0320000000', 'status' => true],
            ['name' => 'Société Jumbo', 'email' => 'contact@jumbo.mg', 'phone' => '0321111111', 'city' => 'Antananarivo', 'status' => true],
            ['name' => 'Marie Rakoto', 'email' => 'marie@example.com', 'phone' => '0322222222', 'city' => 'Antananarivo', 'status' => true],
        ];

        foreach ($customers as $customer) {
            Customer::firstOrCreate(['email' => $customer['email']], $customer);
        }

        // Create sample products if none exist
        if (Product::count() === 0) {
            $products = [
                [
                    'reference' => 'REF-000001',
                    'name' => 'Pain de mie tranché',
                    'description' => 'Pain de mie blanc tranché, paquet de 500g',
                    'category' => 'alimentation',
                    'purchase_price' => 1500,
                    'selling_price' => 2000,
                    'stock' => 15,
                    'minimum_stock' => 20,
                    'status' => 'active',
                ],
                [
                    'reference' => 'REF-000002',
                    'name' => 'Lait entier 1L',
                    'description' => 'Lait entier UHT, brique de 1 litre',
                    'category' => 'boissons',
                    'purchase_price' => 900,
                    'selling_price' => 1200,
                    'stock' => 120,
                    'minimum_stock' => 30,
                    'status' => 'active',
                ],
                [
                    'reference' => 'REF-000003',
                    'name' => 'Sucre blanc 1kg',
                    'description' => 'Sucre blanc cristallisé, sachet de 1kg',
                    'category' => 'alimentation',
                    'purchase_price' => 650,
                    'selling_price' => 850,
                    'stock' => 0,
                    'minimum_stock' => 15,
                    'status' => 'active',
                ],
                [
                    'reference' => 'REF-000004',
                    'name' => 'Eau minérale 1.5L',
                    'description' => 'Eau minérale naturelle, bouteille de 1.5L',
                    'category' => 'boissons',
                    'purchase_price' => 350,
                    'selling_price' => 500,
                    'stock' => 64,
                    'minimum_stock' => 50,
                    'status' => 'active',
                ],
                [
                    'reference' => 'REF-000005',
                    'name' => 'Savon liquide 500ml',
                    'description' => 'Savon liquide pour les mains, flacon de 500ml',
                    'category' => 'hygiene',
                    'purchase_price' => 2800,
                    'selling_price' => 3500,
                    'stock' => 45,
                    'minimum_stock' => 10,
                    'status' => 'active',
                ],
                [
                    'reference' => 'REF-000006',
                    'name' => 'Biscuits au chocolat',
                    'description' => 'Biscuits fourrés au chocolat, paquet de 200g',
                    'category' => 'alimentation',
                    'purchase_price' => 1100,
                    'selling_price' => 1500,
                    'stock' => 35,
                    'minimum_stock' => 12,
                    'status' => 'active',
                ],
            ];

            foreach ($products as $product) {
                Product::create($product);
            }
        }

            // Seed demo stock movements (optional)
            $this->call(\Database\Seeders\StockMovementSeeder::class);
            
            // Seed demo sales
            $this->call(\Database\Seeders\SaleSeeder::class);
    }
}
