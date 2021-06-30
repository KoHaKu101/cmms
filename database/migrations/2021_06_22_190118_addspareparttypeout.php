<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class addspareparttypeout extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('PMCS_CMMS_REPAIR_SPAREPART', function (Blueprint $table) {
        $table->string('SPAREPART_PAY_TYPE',50)          ->default('');
        $table->string('SPAREPART_STOCK_TYPE',50)        ->default('');
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
