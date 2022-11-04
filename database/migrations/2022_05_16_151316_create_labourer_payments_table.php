<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabourerPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('labourer_payments', function (Blueprint $table) {
            $table->id();
            $table->string('amount');
            $table->string('balance');
            $table->string('expense_name');
            $table->string('type');
            $table->unsignedBigInteger('labourer_id');
            $table->unsignedBigInteger('expenses_id');
            $table->unsignedBigInteger('project_id');
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
        Schema::dropIfExists('labourer_payments');
    }
}
