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
        Schema::create('basic_user_information', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('image')->nullable();
            $table->string('employee_id')->nullable();
            $table->string('national_id')->nullable();
            $table->date('birthday')->nullable();
            $table->string('gender')->nullable();
            $table->string('address')->nullable();
            $table->string('county')->nullable();
            $table->string('town')->nullable();
            $table->string('phone')->nullable();
            $table->date('date_joined')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('basic_user_information');
    }
};
