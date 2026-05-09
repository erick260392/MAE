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
        Schema::table('quotes', function (Blueprint $table) {
            $table->decimal('subtotal', 10, 2)->default(0)->after('status');
            $table->string('discount_type', 20)->default('none')->after('subtotal');
            $table->decimal('discount_value', 10, 2)->default(0)->after('discount_type');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('discount_value');
            $table->decimal('tax_amount', 10, 2)->default(0)->after('discount_amount');
        });

        Schema::table('quote_items', function (Blueprint $table) {
            $table->decimal('original_unit_price', 10, 2)->default(0)->after('quantity');
            $table->string('discount_type', 20)->default('none')->after('unit_price');
            $table->decimal('discount_value', 10, 2)->default(0)->after('discount_type');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('discount_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quote_items', function (Blueprint $table) {
            $table->dropColumn([
                'original_unit_price',
                'discount_type',
                'discount_value',
                'discount_amount',
            ]);
        });

        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn([
                'subtotal',
                'discount_type',
                'discount_value',
                'discount_amount',
                'tax_amount',
            ]);
        });
    }
};
