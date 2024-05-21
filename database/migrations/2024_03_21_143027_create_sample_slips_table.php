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
        Schema::create('sample_slips', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sales_call_id');
            $table->foreign('sales_call_id')->references('id')->on('sales_calls')->onDelete('cascade');
            $table->unsignedBigInteger('sales_call_detail_id');
            $table->foreign('sales_call_detail_id')->references('id')->on('sales_call_details')->onDelete('cascade');
            $table->string('image_source');
            $table->string('sample_slip_image_url');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sample_slips');
    }
};
