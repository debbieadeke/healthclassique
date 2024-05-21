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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
			$table->string('first_name');
			$table->string('last_name');
			$table->string('class', 100)->default('A');
			$table->string('category')->nullable()->default('Doctor');
			$table->unsignedBigInteger('location_id')->nullable();
			$table->unsignedBigInteger('title_id')->nullable();
            $table->unsignedBigInteger('speciality_id')->nullable();

            $table->unsignedBigInteger('created_by')->nullable()->default(1);
            $table->timestamps();
			$table->softDeletes();

			$table->foreign('location_id')
                ->references('id')
                ->on('locations')
                ->onDelete('set null');

			$table->foreign('title_id')
                ->references('id')
                ->on('titles')
                ->onDelete('set null');

            $table->foreign('speciality_id')
                ->references('id')
                ->on('specialities')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
