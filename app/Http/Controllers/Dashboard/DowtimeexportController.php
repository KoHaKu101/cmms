<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Machine\Machine;
use App\Models\Machine\MachineRepairREQ;
use App\Models\Machine\MachinePlanPm;
use App\Models\Machine\SparePartPlan;
use App\Models\Machine\Pmplanresult;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\DB;
use App\Exports\DowntimeExports;
use Maatwebsite\Excel\Facades\Excel;

class DowtimeexportController extends Controller
{
  public function __construct(){
    $this->middleware('auth');
  }
  public function Dowtimeexport(Request $request){
    return Excel::download(new DowntimeExports(date('Y')), 'downtime.xlsx');
  }
}
