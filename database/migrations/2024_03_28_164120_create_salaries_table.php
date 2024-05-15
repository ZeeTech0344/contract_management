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
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("employee_id");
            $table->string("basic_salary")->nullable();
            $table->string("advance")->nullable();
            $table->string("day_of_work_deduction")->nullable();
            $table->string("amount");
            $table->string("salary_month");
            $table->string("status")->default("Paid");
            $table->string("pendings");
            $table->string("addition");
            $table->string("day_of_work");
            $table->string("remarks")->nullable();
            $table->bigInteger("account_id")->nullable();
            $table->string("account_name")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
