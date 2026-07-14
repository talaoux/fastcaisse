<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check and add foreign key to sales.user_id
        try {
            \DB::statement('ALTER TABLE sales ADD CONSTRAINT IF NOT EXISTS sales_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');
        } catch (\Exception $e) {
            // Foreign key might already exist
        }

        // Check and add foreign key to sales.customer_id
        try {
            \DB::statement('ALTER TABLE sales ADD CONSTRAINT IF NOT EXISTS sales_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL');
        } catch (\Exception $e) {
            // Foreign key might already exist
        }

        // Check and add foreign key to sale_items.sale_id
        try {
            \DB::statement('ALTER TABLE sale_items ADD CONSTRAINT IF NOT EXISTS sale_items_sale_id_foreign FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE');
        } catch (\Exception $e) {
            // Foreign key might already exist
        }

        // Check and add foreign key to sale_items.product_id
        try {
            \DB::statement('ALTER TABLE sale_items ADD CONSTRAINT IF NOT EXISTS sale_items_product_id_foreign FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE');
        } catch (\Exception $e) {
            // Foreign key might already exist
        }

        // Check and add foreign key to stock_movements.product_id
        try {
            \DB::statement('ALTER TABLE stock_movements ADD CONSTRAINT IF NOT EXISTS stock_movements_product_id_foreign FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE');
        } catch (\Exception $e) {
            // Foreign key might already exist
        }

        // Check and add foreign key to stock_movements.user_id
        try {
            \DB::statement('ALTER TABLE stock_movements ADD CONSTRAINT IF NOT EXISTS stock_movements_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');
        } catch (\Exception $e) {
            // Foreign key might already exist
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            \DB::statement('ALTER TABLE stock_movements DROP FOREIGN KEY IF EXISTS stock_movements_user_id_foreign');
        } catch (\Exception $e) {}

        try {
            \DB::statement('ALTER TABLE stock_movements DROP FOREIGN KEY IF EXISTS stock_movements_product_id_foreign');
        } catch (\Exception $e) {}

        try {
            \DB::statement('ALTER TABLE sale_items DROP FOREIGN KEY IF EXISTS sale_items_product_id_foreign');
        } catch (\Exception $e) {}

        try {
            \DB::statement('ALTER TABLE sale_items DROP FOREIGN KEY IF EXISTS sale_items_sale_id_foreign');
        } catch (\Exception $e) {}

        try {
            \DB::statement('ALTER TABLE sales DROP FOREIGN KEY IF EXISTS sales_customer_id_foreign');
        } catch (\Exception $e) {}

        try {
            \DB::statement('ALTER TABLE sales DROP FOREIGN KEY IF EXISTS sales_user_id_foreign');
        } catch (\Exception $e) {}
    }
};