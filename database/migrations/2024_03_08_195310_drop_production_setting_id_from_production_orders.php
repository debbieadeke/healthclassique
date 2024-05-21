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
        Schema::table('production_orders', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['production_setting_id']);
            // Drop column
            $table->dropColumn('production_setting_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('production_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('production_setting_id')->after('id');
            $table->foreign('production_setting_id')->references('id')->on('production_settings');
        });
    }
};
