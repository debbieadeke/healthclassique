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
        Schema::table('production_order_phases', function (Blueprint $table) {
            $table->after('phase_id', function ($table) {
                $table->string('phase')->nullable();
                $table->json('json_phase_details')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('production_order_phases', function (Blueprint $table) {
            $table->dropColumn('phase'); // Remove the newly added column
            $table->dropColumn('json_phase_details');
        });
    }
};
