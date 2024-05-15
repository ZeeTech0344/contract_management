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
        Schema::create('buyer_purchaser_details', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("phone_no")->nullable();
            $table->string("account_no")->nullable();
            $table->string("cnic")->nullable();
            $table->string("address")->nullable();
            $table->string("trn_no");
            $table->string("recieved_payment")->nullable();
            $table->string("tax")->default(5);
            $table->string("status")->default("On");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buyer_purchaser_details');
    }
};
