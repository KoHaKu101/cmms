<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Timesendmail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('PMCS_CMMS_SETUP_MAIL', function (Blueprint $table) {
          $table->date('DATESEND_MAIL')->nullable();
          $table->integer('DATESEND_SET')->default(7);
          $table->integer('STATUS_SEND')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('PMCS_CMMS_SETUP_MAIL');
    }
}
