<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Machine\MachinePlanPm;
use App\Models\Machine\SparePartPlan;




class CalendarController extends Controller
{
  public function __construct(){
    $this->middleware('auth');
  }

  public function Index(){
    $DATA_MACHINEPLANPM = MachinePlanPm::all();
    $DATA_PMPLANSPAREPART = SparePartPlan::select('PLAN_DATE','MACHINE_CODE','MACHINE_UNID','DOC_YEAR','DOC_MONTH')
                                          ->groupBy('PLAN_DATE')->groupBy('MACHINE_CODE')
                                          ->groupBy('DOC_YEAR')->groupBy('DOC_MONTH')
                                          ->groupBy('MACHINE_UNID')->get();

    return View('machine/celendar/celendar',compact('DATA_MACHINEPLANPM','DATA_PMPLANSPAREPART'));
  }
}
