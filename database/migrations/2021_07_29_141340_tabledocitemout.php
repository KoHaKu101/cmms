<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Tabledocitemout extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('PMCS_CMMS_DOC_ITEMOUT', function (Blueprint $table) {
        $table->BigInteger('UNID')->primary();
        $table->string('DOC_NO',50)->nullable();
        $table->date('DOC_DATE')->nullable();
        $table->Integer('DOC_YEAR')->default(0);
        $table->Integer('DOC_MONTH')->default(0);
        $table->Integer('DOC_TYPE')->default(0);

        $table->BigInteger('COMPANY_UNID')->nullable();
        $table->string('COMPANY_NAME',200)->nullable();
        $table->string('EMP_NAME',200)->nullable();
        $table->string('EMP_CODE',50)->nullable();

        $table->string('CANCEL_NOTE',500)->nullable();
        $table->date('DATE_SET_REC')->nullable();
        $table->Integer('STATUS')->default(0);
        $table->Integer('COUNT_DETAIL')->nullable(0);
        $table->float('COST_TOTAL')->default(0);


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
        Schema::dropIfExists('PMCS_CMMS_DOC_ITEMOUT');
    }
}
