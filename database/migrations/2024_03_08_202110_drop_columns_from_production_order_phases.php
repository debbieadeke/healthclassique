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
            // Drop foreign key constraints
            $table->dropForeign(['production_setting_id']);
            $table->dropForeign(['phase_id']);

            // Drop the columns
            $table->dropColumn('production_setting_id');
            $table->dropColumn('phase_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('production_order_phases', function (Blueprint $table) {
            // Add columns back
            $table->unsignedBigInteger('production_setting_id')->default(0)->after('id');
            $table->unsignedBigInteger('phase_id')->default(1)->after('production_setting_id');

            // Add foreign key constraints back
            $table->foreign('production_setting_id')->references('id')->on('production_settings');
            $table->foreign('phase_id')->references('id')->on('phases');
        });
    }
};
