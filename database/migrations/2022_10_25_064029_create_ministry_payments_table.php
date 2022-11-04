<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMinistryPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ministry_payments', function (Blueprint $table) {
            $table->id();
            $table->string('amount');
            $table->string('balance');
            $table->unsignedBigInteger('ministry_id');
            $table->string('name');
            $table->unsignedBigInteger('transaction_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ministry_payments');
    }
}
