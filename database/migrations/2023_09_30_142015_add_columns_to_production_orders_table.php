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
        Schema::table('production_orders', function (Blueprint $table) {
            $table->string('ph')->after('status')->nullable();
            $table->string('viscocity')->after('ph')->nullable();
            $table->string('color')->after('viscocity')->nullable();
            $table->string('smell')->after('color')->nullable();
            $table->date('expiry_date')->after('smell')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('production_orders', function (Blueprint $table) {
            $table->dropColumn('ph','viscocity','color','smell','expiry_date');
        });
    }
};
