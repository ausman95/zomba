<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequisitionDepartmentApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requisition_department_approvals', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->string('notes');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('department_requisition_id');
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
        Schema::dropIfExists('requisition_department_approvals');
    }
}
