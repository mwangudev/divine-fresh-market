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
        Schema::table('products', function (Blueprint $table) {
            $table->string('brand_name')->nullable()->after('name');
            $table->string('barcode')->nullable()->after('brand_name');
            //VAT
            $table->decimal('vat_percentage', 5, 2)->default(0)->after('selling_price');
            $table->string('base_unit')->nullable()->after('barcode');
            $table->decimal('base_unit_value', 10, 2)->nullable()->after('base_unit');
            $table->integer('min_stock_level')->default(5)->after('stock_quantity');
            $table->date('expiry_date')->nullable()->after('min_stock_level');
            $table->boolean('is_active')->default(true);
            $table->string('image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'brand_name',
                'barcode',
                'vat_percentage',
                'base_unit',
                'base_unit_value',
                'min_stock_level',
                'expiry_date',
                'is_active',
                'image'
            ]);
        });
    }
};
