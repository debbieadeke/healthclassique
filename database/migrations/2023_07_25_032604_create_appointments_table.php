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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->datetime('start_time');
            $table->datetime('finish_time');
            $table->longText('comments')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
			$table->unsignedBigInteger('facility_id')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();

			$table->foreign('client_id')
                ->references('id')
                ->on('clients')
                ->onDelete('set null');

			$table->foreign('facility_id')
                ->references('id')
                ->on('facilities')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
