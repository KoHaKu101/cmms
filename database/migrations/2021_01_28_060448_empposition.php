<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class empposition extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('PMCS_EMP_POSITION', function (Blueprint $table) {
        // ต้องมีทุกหน้า
        $table->BigInteger('UNID')->primary();
        $table->integer('EMP_POSITION_INDEX')->nullable()->default(0);
        $table->string('EMP_POSITION_CODE',50)->nullable();
        $table->string('EMP_POSITION_NAME',200)->nullable()->unique();
        $table->integer('EMP_POSITION_LIMIT')->nullable()->default(0);

        $table->string('REMARK',500)->nullable();
        $table->integer('STATUS')->nullable();

        // ต้องมีทุกหน้า
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
          Schema::dropIfExists('PMCS_EMP_POSITION');
    }
}
