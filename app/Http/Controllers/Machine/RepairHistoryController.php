<?php

namespace App\Http\Controllers\Machine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Middleware\VerifyCsrfToken;
use Carbon\Carbon;
use Auth;
use Cookie;
use Gate;
//******************** model ***********************
use App\Models\MachineAddTable\SelectMainRepair;
use App\Models\MachineAddTable\SelectSubRepair;
use App\Models\Machine\Machine;
use App\Models\Machine\EMPName;
use App\Models\Machine\SparePart;
use App\Models\Machine\RepairWorker;
use App\Models\Machine\RepairSparepart;
use App\Models\Machine\MachineRepairREQ;
//************** Package form github ***************

class RepairHistoryController extends Controller
{
  public function __construct(){
    $this->middleware('auth');
  }
  public function randUNID($table){
    $number = date("ymdhis", time());
    $length=7;
    do {
      for ($i=$length; $i--; $i>0) {
        $number .= mt_rand(0,9);
      }
    }
    while ( !empty(DB::table($table)
    ->where('UNID',$number)
    ->first(['UNID'])) );
    return $number;
   }

  public function HistoryList(Request $request){
    $DATA_REPAIR_HEADER = MachineRepairREQ::select('MACHINE_UNID','MACHINE_CODE','MACHINE_NAME')
                                            ->where('CLOSE_STATUS','=',1)
                                            ->groupBy('MACHINE_UNID','MACHINE_CODE','MACHINE_NAME')
                                            ->orderBy('MACHINE_CODE')->get();
    $DATA_REPAIR = MachineRepairREQ::select('*')->selectraw('dbo.decode_utf8(INSPECTION_NAME) as INSPECTION_NAME_TH')
                                   ->where('CLOSE_STATUS','=',1)->orderBy('MACHINE_CODE')->get();
    $DATA_SPAREPART = RepairSparepart::orderBy('SPAREPART_NAME')->get();
    return view('machine.history.list',compact('DATA_REPAIR','DATA_REPAIR_HEADER','DATA_SPAREPART'));
  }
}
