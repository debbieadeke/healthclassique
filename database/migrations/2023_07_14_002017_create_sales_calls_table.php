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
        Schema::create('sales_calls', function (Blueprint $table) {
            $table->id();
			$table->string('client_type');
			$table->integer('client_id')->default(0);
			$table->dateTime('start_time')->nullable();
			$table->dateTime('end_time')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
			$table->unsignedBigInteger('speciality_id')->nullable();
			$table->integer('double_call_colleague')->default(0);
            $table->longText('discussion_summary')->nullable();
            $table->date('next_planned_visit')->nullable();
            $table->decimal('longitude', $precision = 10, $scale = 7)->nullable();
            $table->decimal('latitude', $precision = 10, $scale = 7)->nullable();
			$table->float('location_difference', 8, 2)->default(0);
			$table->string('pharmacy_order_booked')->nullable()->default('No');
			$table->longText('pharmacy_reasons_for_not_booking')->nullable();
			$table->string('pharmacy_prescription_audit')->nullable()->default('No');
			$table->longText('pharmacy_prescription_audit_notes')->nullable();
            $table->longText('pharmacy_reasons_for_not_auditing')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->default(1);
            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('salescalls');
    }
};
