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
        Schema::table('product_samples', function (Blueprint $table) {
            $table->unsignedBigInteger('sales_call_detail_id')->nullable()->after('product_id');
            $table->foreign('sales_call_detail_id')->references('id')->on('sales_call_details')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_samples', function (Blueprint $table) {
            $table->dropForeign(['sales_call_detail_id']);
            $table->dropColumn('sales_call_detail_id');
        });
    }
};
