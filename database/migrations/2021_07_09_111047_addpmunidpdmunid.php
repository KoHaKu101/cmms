<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Addpmunidpdmunid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('PMCS_CMMS_HISTORY_REPAIR', function (Blueprint $table) {
        $table->bigInteger('PM_PLAN_UNID')               ->nullable();
        $table->bigInteger('SPAREPART_PLAN_UNID')        ->nullable();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('PMCS_CMMS_HISTORY_REPAIR');
    }
}
