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
        Schema::create('production_order_phases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('production_setting_id')->default(0);
            $table->unsignedBigInteger('phase_id')->default(1);
            $table->unsignedBigInteger('production_order_id')->default(0);
            $table->foreign('production_setting_id')->references('id')->on('production_settings');
            $table->foreign('production_order_id')->references('id')->on('production_orders');
            $table->foreign('phase_id')->references('id')->on('phases');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_order_phases');
    }
};
