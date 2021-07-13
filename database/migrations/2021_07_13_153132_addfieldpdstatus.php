<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Addfieldpdstatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('PMCS_CMMS_REPAIR_REQ', function (Blueprint $table) {
        $table->integer('PD_CHECK_STATUS')  ->default(9);
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
