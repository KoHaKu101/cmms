<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Repairsparepart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('PMCS_CMMS_REPAIR_SPAREPART', function (Blueprint $table) {
        $table->BigInteger('UNID')->primary();
        $table->BigInteger('REPAIR_REQ_UNID')->nullable();
        $table->BigInteger('SPAREPART_UNID')->nullable();

        $table->string('REPAIR_DOC_NO',50)->nullable();
        $table->string('SPAREPART_CODE',50)->nullable();
        $table->string('SPAREPART_NAME',200)->nullable();
        $table->float('SPAREPART_COST')->default(0);
        $table->float('SPAREPART_TOTAL_COST')->default(0);
        $table->integer('SPAREPART_TOTAL_OUT')->default(0);
        $table->integer('SPAREPART_TYPE_OUT')->default(0); //in && out
        $table->string('SPAREPART_UNIT',50)->nullable();
        $table->string('SPAREPART_MODEL',50)->nullable();
        $table->string('SPAREPART_SIZE',50)->nullable();
        $table->date('CHANGE_DATE')->nullable();

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
      Schema::dropIfExists('PMCS_CMMS_REPAIR_SPAREPART');
    }
}
