<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Addpdmdowntime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('PMCS_CMMS_SPAREPART_PLAN', function (Blueprint $table) {
        $table->time('START_TIME')      ->nullable();
        $table->time('END_TIME')        ->nullable();
        $table->integer('DOWNTIME')           ->default(0);
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('PMCS_MACHINE_PLAN_PM');

    }
}
