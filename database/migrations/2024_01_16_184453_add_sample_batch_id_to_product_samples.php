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
            $table->unsignedBigInteger('sample_batch_id')->after('product_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_samples', function (Blueprint $table) {
            $table->dropColumn('sample_batch_id');
        });
    }
};
