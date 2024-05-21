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
        Schema::create('pharmacies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('facility_type')->default('Pharmacy');
            $table->string('class', 100)->default('A');
            $table->unsignedBigInteger('location_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('location_id')
                ->references('id')
                ->on('locations')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pharmacies');
    }
};
