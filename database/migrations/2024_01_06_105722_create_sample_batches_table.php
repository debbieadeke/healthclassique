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
        Schema::create('sample_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->integer('quantity_requested')->default(1);
            $table->integer('quantity_approved')->default(1);
            $table->integer('quantity_dispatched')->default(1);
            $table->integer('quantity_remaining')->default(1);
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('dispatched_by')->nullable();
            $table->dateTime('approved_on');
            $table->dateTime('dispatched_on');
            $table->timestamps();

            $table->foreign('approved_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->foreign('dispatched_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sample_batches');
    }
};
