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
        Schema::table('sample_batches', function (Blueprint $table) {
            $table->integer('quantity_invoiced')->default(0)->after('quantity_remaining');
            $table->integer('quantity_issued')->default(0)->after('quantity_invoiced');
            $table->unsignedBigInteger('Invoiced_by')->nullable()->after('quantity_issued');
            $table->unsignedBigInteger('Issued_by')->nullable()->after('invoiced_by');
            $table->dateTime('invoiced_on')->after('approved_by');
            $table->dateTime('issued_on')->after('invoiced_on');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sample_batches', function (Blueprint $table) {
            $table->dropColumn('quantity_invoiced');
            $table->dropColumn('quantity_issued');
            $table->dropColumn('invoiced_by');
            $table->dropColumn('approved_by');
            $table->dropColumn('invoiced_on');
            $table->dropColumn('issued_on');
        });
    }
};
