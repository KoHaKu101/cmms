<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Repairworker extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('PMCS_CMMS_REPAIR_WORKER', function (Blueprint $table) {
        $table->BigInteger('UNID')->primary();
        $table->BigInteger('REPAIR_REQ_UNID')->nullable();
        $table->BigInteger('WORKER_UNID')->nullable();

        $table->string('REPAIR_DOC_NO',50)->nullable();
        $table->string('WORKER_TYPE',50)->nullable(); //in && out
        $table->string('WORKER_CODE',50)->nullable();
        $table->string('WORKER_NAME',200)->nullable();
        $table->float('WORKER_COST')->default(0);
        $table->string('WORKER_REPAIR_DETAIL',500)->nullable();

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
      Schema::dropIfExists('PMCS_CMMS_REPAIR_WORKER');
    }
}
