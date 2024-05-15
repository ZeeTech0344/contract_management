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
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("invoice_no");
            $table->bigInteger("employee_id");
            $table->string("bank_name");
            $table->string("purpose");
            $table->string("paid_for_month_date")->nullable();
            $table->string("status")->nullable();
            $table->string("amount");
            $table->string("remarks")->nullable();
            $table->string("amount_status")->default("Out");
            $table->bigInteger("given_by");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banks');
    }
};
