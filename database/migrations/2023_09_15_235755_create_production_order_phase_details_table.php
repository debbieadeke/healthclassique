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
        Schema::create('production_order_phase_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('production_order_phase_id');
            $table->unsignedBigInteger('input_id');
            $table->smallInteger('percentage')->default(1);
            $table->integer('weight')->default(1);
            $table->unsignedBigInteger('pack_size_id');
            $table->unsignedBigInteger('production_order_id')->default(0);
            $table->foreign('production_order_phase_id')->references('id')->on('production_order_phases');
            $table->foreign('input_id')->references('id')->on('inputs');
            $table->foreign('pack_size_id')->references('id')->on('pack_sizes');
            $table->foreign('production_order_id')->references('id')->on('production_orders');
            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_batch_phase_details');
    }
};
