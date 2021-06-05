<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class masterimps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('PMCS_CMMS_MASTER_IMPS', function (Blueprint $table) {
          $table->BigInteger('UNID')->primary();
          $table->BigInteger('MACHINE_UNID')->nullable();
          $table->BigInteger('PM_TEMPLATE_UNID_REF')->nullable();
          $table->string('MACHINE_CODE',200)->nullable();
          $table->string('PM_TEMPLATE_NAME',200)->nullable();
          $table->date('PM_LAST_DATE')->default(date('Y-m-d'))->nullable();
          $table->date('PM_NEXT_DATE')->nullable();
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
        Schema::dropIfExists('PMCS_CMMS_MASTER_IMPS');
    }
}
