<?php

namespace App\Exports\Plan;

use App\Models\Machine\SparePartPlan;
use App\Models\Machine\Machine;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class PDMExport implements FromView,ShouldAutoSize
{
  use Exportable;
  /**
    *@return \Illuminate\Support\Collection
    */


   function __construct() {

   }

  public function view(): View
  {
      $DATA_PDM = SparePartPlan::select('*')->where('DOC_YEAR','=',date('Y'))->where('DOC_MONTH','=',date('n'))
                              ->orderby('MACHINE_LINE')->orderby('MACHINE_CODE')->get();
      $Machine  = Machine::select('UNID')->selectraw('dbo.decode_utf8(MACHINE_NAME) as MACHINE_NAME_TH')->get();
      return view('machine.export.pdmplan',compact(['DATA_PDM','Machine']));
  }

}
