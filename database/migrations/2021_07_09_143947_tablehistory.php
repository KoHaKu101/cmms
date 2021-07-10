<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Tablehistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('PMCS_CMMS_HISTORY', function (Blueprint $table) {
        $table->BigInteger('UNID')->primary();
        $table->BigInteger('REPAIR_REQ_UNID')->nullable();
        $table->bigInteger('PM_PLAN_UNID')->nullable();
        $table->bigInteger('SPAREPART_PLAN_UNID')->nullable();
        $table->BigInteger('MACHINE_UNID')->nullable();
        $table->string('MACHINE_CODE',50)->nullable();
        $table->string('MACHINE_NAME',500)->nullable();
        $table->string('DOC_NO',50)->nullable();
        $table->date('DOC_DATE')->nullable();
        $table->integer('DOC_YEAR')->default(0);
        $table->integer('DOC_MONTH')->default(0);
        $table->string('DOC_TYPE',50)->nullable();

        $table->string('REPAIR_REQ_DETAIL',200)->nullable();
        $table->string('REPAIR_DETAIL',200)->nullable();
        $table->date('REPAIR_DATE')->nullable();
        $table->float('TOTAL_COST')->default(0);

        $table->string('REPORT_BY',50)->nullable();
        $table->string('INSPECTION_BY',50)->nullable();
        $table->string('APPROVED_BY',50)->nullable();
        $table->string('DOWN_TIME',50)->nullable();

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
        Schema::dropIfExists('PMCS_CMMS_HISTORY');
    }
}
