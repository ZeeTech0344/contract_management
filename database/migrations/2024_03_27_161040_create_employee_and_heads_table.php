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
        Schema::create('employee_and_heads', function (Blueprint $table) {
           
            $table->id();
            $table->string("employee_no")->nullable();
            $table->string('employee_name')->nullable();
            $table->string("employee_post")->nullable();
            $table->string("cnic")->nullable();
            $table->string("dob")->nullable();
            $table->string("phone_no")->nullable();
            $table->string("father_name")->nullable();
            $table->string("father_cnic")->nullable();
            $table->string("father_phone_no")->nullable();
            $table->string("basic_sallary")->nullable();
            $table->string("image")->nullable();
            $table->string("employee_status")->nullable();
            $table->string("joining")->nullable();
            $table->string("leaving")->nullable();
            $table->string("account_for");
            $table->string("operator")->nullable();
            $table->timestamps();
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_and_heads');
    }
};
