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
        Schema::create('partnership_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("contractor_id");
            $table->bigInteger("client_id");
            $table->bigInteger("invoice_no");
            $table->string("percentage");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partnership_details');
    }
};
