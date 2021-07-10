<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Pmchangesparepart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('PMCS_CMMS_PM_SPAREPART', function (Blueprint $table) {
        $table->BigInteger('UNID')->primary();
        $table->BigInteger('PM_PLAN_UNID')->nullable();

        $table->date('PLAN_DATE')->nullable();
        $table->BigInteger('MACHINE_PLAN_UNID')->nullable();
        $table->string('MACHINE_CODE',50)->nullable();
        $table->string('MACHINE_LINE',40)->nullable();
        $table->string('MACHINE_NAME',500)->nullable();
        $table->string('PM_USER_CHECK')->nullable();
        $table->date('CHANGE_DATE')->nullable();
        $table->BigInteger('SPAREPART_UNID')->nullable();
        $table->string('SPAREPART_CODE')->nullable();
        $table->string('SPAREPART_NAME')->nullable();
        $table->float('SPAREPART_COST')->default(0);
        $table->float('TOTAL_COST')->default(0);
        $table->integer('TOTAL_PIC')->default(0);

        $table->string('CREATE_BY',200)->nullable();
        $table->string('CREATE_TIME',50)->nullable();
        $table->string('MODIFY_BY',200)->nullable();
        $table->string('MODIFY_TIME',50)->nullable();
    });
  }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('PMCS_CMMS_PM_SPAREPART');
    }
}
