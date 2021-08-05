<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Tabledocitemoutdetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('PMCS_CMMS_DOC_ITEMOUT_DETAIL', function (Blueprint $table) {
        $table->BigInteger('UNID')->primary();
        $table->BigInteger('DOC_ITEMOUT_UNID')->nullable();
        $table->BigInteger('SPAREPART_UNID')->nullable();
        $table->BigInteger('MACHINE_UNID')->nullable();

        $table->string('SPAREPART_NAME',200)->nullable();
        $table->string('SPAREPART_UNIT',50)->nullable();
        $table->string('MACHINE_CODE',50)->nullable();
        $table->integer('TOTAL_OUT')->default(0);
        $table->date('DATE_REC')->nullable();
        $table->date('DATE_REC_CORRECT')->nullable();
        $table->integer('DETAIL_INDEX')->default(0);
        $table->string('PR_CODE',50)->nullable();
        $table->string('SERVICES_CODE',50)->nullable();
        $table->integer('STATUS')->default(0);
        $table->float('COST_TOTAL')->default(0);
        $table->string('NOTE',500)->nullable();

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
        Schema::dropIfExists('PMCS_CMMS_DOC_ITEMOUT_DETAIL');
    }
}
