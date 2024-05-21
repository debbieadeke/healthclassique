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
        Schema::table('production_order_phase_details', function (Blueprint $table) {
            $table->integer('actual_weight')->after('weight')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('production_order_phase_details', function (Blueprint $table) {
            $table->dropColumn('actual_weight');
        });
    }
};
