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
        Schema::create('sales_call_details', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('sales_call_id')->nullable();
			$table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
			$table->unsignedBigInteger('speciality_id')->nullable();
			$table->unsignedBigInteger('title_id')->nullable();
            $table->string('contact')->nullable();
			$table->integer('double_call_colleague')->default(0);
            $table->date('next_planned_visit')->nullable();
            $table->longText('discussion_summary')->nullable();
			$table->decimal('longitude', $precision = 10, $scale = 7)->nullable();
            $table->decimal('latitude', $precision = 10, $scale = 7)->nullable();
			$table->float('location_difference', 8, 2)->default(0);
            $table->unsignedBigInteger('created_by')->nullable()->default(1);
            $table->timestamps();

			$table->foreign('sales_call_id')
                ->references('id')
                ->on('sales_calls')
                ->onDelete('set null');

			$table->foreign('speciality_id')
                ->references('id')
                ->on('specialities')
                ->onDelete('set null');

			$table->foreign('title_id')
                ->references('id')
                ->on('titles')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_call_details');
    }
};
