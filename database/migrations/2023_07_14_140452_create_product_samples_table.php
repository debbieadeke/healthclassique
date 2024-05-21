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
        Schema::create('product_samples', function (Blueprint $table) {
            $table->id();
			$table->string('client_type');
			$table->unsignedBigInteger('salescall_or_detail_id')->nullable();
			$table->unsignedBigInteger('product_id')->nullable();
			$table->integer('quantity')->default(1);
            $table->unsignedBigInteger('created_by')->nullable()->default(1);
            $table->timestamps();

			$table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_samples');
    }
};
