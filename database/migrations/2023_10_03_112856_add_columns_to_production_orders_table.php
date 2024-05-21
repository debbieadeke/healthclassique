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
            $table->unsignedBigInteger('created_by')->nullable()->default(1)->after('comments');
            $table->unsignedBigInteger('reviewed_by')->nullable()->default(1)->after('created_by');
            $table->unsignedBigInteger('approved_by')->nullable()->default(1)->after('reviewed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('production_orders', function (Blueprint $table) {
            $table->dropColumn('created_by','reviewed_by','approved_by');
        });
    }
};
