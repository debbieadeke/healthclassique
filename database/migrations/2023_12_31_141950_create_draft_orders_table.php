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
        Schema::create('draft_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->integer('no_of_phases')->default(1);
            $table->integer('rows_per_phase')->default(1);
            $table->string('category')->nullable();
            $table->integer('product_quantity_target')->default(1);
            $table->string('packaging_phase')->default('on');
            $table->string('labour_phase')->default('on');
            $table->integer('total_batch_quantity')->default(1);
            $table->decimal('total_batch_cost', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('draft_orders');
    }
};
