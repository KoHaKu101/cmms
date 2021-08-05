<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Tablecompany extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('PMCS_CMMS_COMPANY', function (Blueprint $table) {
        $table->BigInteger('UNID')->primary();

        $table->string('COMPANY_CODE',50)->nullable();
        $table->string('COMPANY_NAME',200)->nullable();
        $table->string('NOTE',500)->nullable();
        $table->Integer('STATUS')->default(9);

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
        Schema::dropIfExists('PMCS_CMMS_COMPANY');
    }
}
