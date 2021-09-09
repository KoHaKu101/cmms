<?php

namespace App\Exports\Plan;

use App\Models\Machine\MachinePlanPm;
use App\Models\Machine\Pmplanresult;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class PMExport implements FromView,ShouldAutoSize
{
  use Exportable;
  /**
    *@return \Illuminate\Support\Collection
    */


   function __construct() {

   }

  public function view(): View
  {
      $DATA_PM = MachinePlanPm::select('*')->selectraw('dbo.decode_utf8(MACHINE_NAME) as MACHINE_NAME_TH')
                              ->where('PLAN_YEAR','=',date('Y'))->where('PLAN_MONTH','=',date('n'))
                              ->orderby('MACHINE_LINE')->orderby('MACHINE_CODE')->get();
      $USER_CHECK = Pmplanresult::select('PM_PLAN_UNID','PM_USER_CHECK')->get();

      return view('machine.export.pmplan',compact(['DATA_PM','USER_CHECK']));
  }

}
