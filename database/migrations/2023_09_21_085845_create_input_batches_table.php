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
        Schema::create('input_batches', function (Blueprint $table) {
            $table->id();
            $table->integer('input_id');
            $table->integer('supplier_id')->nullable();
            $table->string('batch_number')->nullable();
            $table->string('lpo')->nullable();
            $table->decimal('buying_price', 8, 2)->nullable();
            $table->decimal('selling_price', 8, 2)->nullable();
            $table->dateTime('date_supplied');
            $table->integer('quantity_purchased');
            $table->integer('quantity_remaining');
            $table->integer('pack_size_id')->nullable();
            $table->integer('unit_of_measure_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('input_batches');
    }
};
