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
        Schema::create('incentive_metrics', function (Blueprint $table) {
            $table->id();
            $table->string('percentage');
            $table->decimal('kPIs', 10, 2);
            $table->decimal('tier_1', 10, 2);
            $table->decimal('tier_2', 10, 2);
            $table->decimal('tier_3', 10, 2);
            $table->decimal('total_individual', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incentive_metrics');
    }
};
