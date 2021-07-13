<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Addfieldpdcheckdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('PMCS_CMMS_REPAIR_REQ', function (Blueprint $table) {
        $table->date('PD_CHECK_DATE')  ->nullable();
        $table->time('PD_CHECK_TIME')  ->nullable();
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
