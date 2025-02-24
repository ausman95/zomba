<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditorInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('creditor_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creditor_id')->constrained()->onDelete('cascade'); // Foreign key to creditors table
            $table->string('invoice_number')->unique(); // Unique invoice number
            $table->date('invoice_date');
            $table->decimal('amount', 15, 2); // Invoice amount
            $table->text('description')->nullable(); // Optional description
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('creditor_invoices');
    }
}
