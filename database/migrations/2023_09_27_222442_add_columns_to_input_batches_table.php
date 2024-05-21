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
        Schema::table('input_batches', function (Blueprint $table) {
            $table->date('manufacture_date')->after('quantity_remaining')->nullable();
            $table->date('expiry_date')->after('manufacture_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('input_batches', function (Blueprint $table) {
            $table->dropColumn('manufacture_date','expiry_date');
        });
    }
};
