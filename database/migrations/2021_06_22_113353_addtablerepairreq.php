<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Addtablerepairreq extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('PMCS_CMMS_REPAIR_REQ', function (Blueprint $table) {
          //*********************** INSPECTION *********************************
          $table->string('INSPECTION_CODE',50)       ->nullable();
          $table->string('INSPECTION_NAME',200)      ->nullable();
          $table->date('INSPECTION_START_DATE')      ->nullable();
          $table->time('INSPECTION_START_TIME')      ->nullable();
          $table->date('INSPECTION_END_DATE')        ->nullable();
          $table->time('INSPECTION_END_TIME')        ->nullable();
          $table->integer('INSPECTION_RESULT_TIME')  ->default(0);
          $table->string('INSPECTION_DETAIL',500)    ->nullable();
          //*********************** BUY SPAREPART *********************************
          $table->date('SPAREPART_START_DATE')       ->nullable();
          $table->time('SPAREPART_START_TIME')       ->nullable();
          $table->date('SPAREPART_END_DATE')         ->nullable();
          $table->time('SPAREPART_END_TIME')         ->nullable();
          $table->integer('SPAREPART_RESULT_TIME')   ->default(0);
          //*********************** WORKER IN *********************************
          $table->date('WORKERIN_START_DATE')        ->nullable();
          $table->time('WORKERIN_START_TIME')        ->nullable();
          $table->date('WORKERIN_END_DATE')          ->nullable();
          $table->time('WORKERIN_END_TIME')          ->nullable();
          $table->integer('WORKERIN_RESULT_TIME')    ->default(0);
          //*********************** WORKER OUT *********************************
          $table->date('WORKEROUT_START_DATE')       ->nullable();
          $table->time('WORKEROUT_START_TIME')       ->nullable();
          $table->date('WORKEROUT_END_DATE')         ->nullable();
          $table->time('WORKEROUT_END_TIME')         ->nullable();
          $table->integer('WORKEROUT_RESULT_TIME')   ->default(0);
          //*********************** DETAIL *********************************
          $table->string('REPAIR_DETAIL',500)        ->nullable();
          $table->integer('DOWNTIME')                ->default(0);
          $table->float('TOTAL_COST_SPAREPART')      ->default(0);
          $table->float('TOTAL_COST_WORKER')         ->default(0);
          $table->float('TOTAL_COST_REPAIR')         ->default(0);
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
