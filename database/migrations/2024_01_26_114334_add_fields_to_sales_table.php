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
        Schema::table('sales', function (Blueprint $table) {
            $table->string('customer_code')->nullable()->after('product_id');
            $table->string('customer_name')->nullable()->after('customer_code');
            $table->string('product_code')->nullable()->after('customer_name');
            $table->string('product_name')->nullable()->after('product_code');
            $table->enum('processed', ['Yes', 'No'])->default('No')->after('product_name');
            $table->integer('quantity')->nullable()->after('processed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['customer_code', 'customer_name', 'product_code', 'product_name', 'processed']);
        });
    }
};
