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
        Schema::create('last_receipts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("contractor_id");
            $table->bigInteger("quotation_id");
            $table->string("head");
            $table->string("quantity");
            $table->string("amount");
            $table->string("total");
            $table->string("remarks")->nullable();
            $table->boolean("status")->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('last_receipts');
    }
};
