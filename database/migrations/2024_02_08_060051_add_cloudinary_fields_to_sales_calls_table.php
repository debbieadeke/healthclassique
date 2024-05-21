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
        Schema::table('sales_calls', function (Blueprint $table) {
            $table->after('pharmacy_reasons_for_not_auditing', function ($table) {
                $table->string('image_source')->default('spatie');
                $table->string('pob_image_url')->nullable();
                $table->string('pxn_audit_image_url')->nullable();
                $table->string('sample_slip_image_url')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_calls', function (Blueprint $table) {
            $table->dropColumn(['image_source', 'pob_image_url', 'pxn_audit_image_url', 'sample_slip_image_url']);
        });
    }
};
