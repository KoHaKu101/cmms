<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class addclosedateandpd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('PMCS_CMMS_REPAIR_REQ', function (Blueprint $table) {
        $table->string('MACHINE_REPORT_NO',50)   ->default('');
        $table->time('CLOSE_TIME')               ->default('');
        $table->Date('CLOSE_DATE')               ->default('');
        $table->bigInteger('PD_UNID')            ->nullable();
        $table->string('PD_CODE',50)             ->default('');
        $table->string('PD_NAME',200)            ->default('');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('PMCS_CMMS_REPAIR_REQ');
    }
}
