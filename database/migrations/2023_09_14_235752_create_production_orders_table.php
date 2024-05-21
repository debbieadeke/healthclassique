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
        Schema::create('production_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('production_setting_id');
            $table->foreign('production_setting_id')->references('id')->on('production_settings');
            $table->integer('production_quantity_target')->default(1);
            $table->integer('production_quantity_actual')->default(1);
            $table->integer('total_batch_weight')->default(1);
            $table->decimal('total_batch_cost', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_orders');
    }
};
