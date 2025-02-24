<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDebtorStatementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('debtor_statements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('creditor_id'); // Foreign key
            $table->unsignedBigInteger('account_id'); // Foreign key
            $table->decimal('amount', 15, 2); // Use decimal for currency
            $table->string('type');
            $table->text('description')->nullable(); // Description can be optional
            $table->decimal('balance', 15, 2); // Use decimal for currency
            $table->unsignedBigInteger('created_by')->nullable(); // Foreign key for user
            $table->unsignedBigInteger('updated_by')->nullable(); // Foreign key for user
            $table->timestamps();
            $table->softDeletes(); // If using soft deletes

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('debtor_statements');
    }
}
