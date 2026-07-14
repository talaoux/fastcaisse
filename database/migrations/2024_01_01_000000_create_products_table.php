<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('reference')->unique();
                $table->string('barcode')->nullable();
                $table->string('name');
                $table->text('description')->nullable();
                $table->enum('category', ['alimentation', 'boissons', 'hygiene', 'divers']);
                $table->decimal('purchase_price', 10, 2);
                $table->decimal('selling_price', 10, 2);
                $table->integer('stock')->default(0);
                $table->integer('minimum_stock')->default(10);
                $table->string('image')->nullable();
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->timestamps();

                // Index pour améliorer les performances
                $table->index('reference');
                $table->index('category');
                $table->index('status');
            });

            return;
        }

        DB::statement("ALTER TABLE products CHANGE COLUMN sku reference varchar(255) NULL");
        DB::statement("ALTER TABLE products CHANGE COLUMN buy_price purchase_price decimal(10,2) NULL");
        DB::statement("ALTER TABLE products CHANGE COLUMN price selling_price decimal(10,2) NOT NULL");
        DB::statement("ALTER TABLE products CHANGE COLUMN min_stock minimum_stock int(11) NOT NULL DEFAULT 10");

        if (!Schema::hasColumn('products', 'barcode')) {
            DB::statement("ALTER TABLE products ADD COLUMN barcode varchar(255) NULL AFTER reference");
        }

        if (!Schema::hasColumn('products', 'description')) {
            DB::statement("ALTER TABLE products ADD COLUMN description text NULL AFTER name");
        }

        if (!Schema::hasColumn('products', 'status')) {
            DB::statement("ALTER TABLE products ADD COLUMN status enum('active','inactive') NOT NULL DEFAULT 'active' AFTER image");
        }

        DB::statement("UPDATE products SET reference = CONCAT('REF-', LPAD(id, 6, '0')) WHERE reference IS NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
