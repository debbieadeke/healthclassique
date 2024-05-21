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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
			$table->string('name');
            $table->decimal('longitude', $precision = 10, $scale = 7)->nullable();
            $table->decimal('latitude', $precision = 10, $scale = 7)->nullable();
            $table->unsignedBigInteger('territory_id');
            $table->foreign('territory_id')->references('id')->on('territories');
            $table->unsignedBigInteger('created_by')->nullable()->default(1);
            $table->timestamps();
			$table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
