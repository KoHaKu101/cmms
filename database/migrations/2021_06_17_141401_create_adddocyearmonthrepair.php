<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class adddocyearmonthrepair extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('PMCS_CMMS_REPAIR_REQ', function (Blueprint $table) {
          $table->integer('DOC_YEAR')->nullable()->default(0);
          $table->integer('DOC_MONTH')->nullable()->default(0);
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
