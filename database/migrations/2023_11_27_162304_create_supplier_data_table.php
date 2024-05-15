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
        Schema::create('supplier_data', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("invoice_no");
            $table->bigInteger("supplier_id");
            $table->string("head");
            $table->string("quantity");
            $table->string("amount");
            $table->string("total");
            $table->boolean("status")->default(false);
            $table->string("amount_status")->default("In");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_data');
    }
};
