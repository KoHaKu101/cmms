<?php

namespace App\Http\Controllers\Machine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Middleware\VerifyCsrfToken;
use Carbon\Carbon;
use Auth;
//******************** model ***********************
use App\Models\Machine\Machine;
use App\Models\Machine\MachineSparePart;
use App\Models\Machine\SparePart;
use App\Models\Machine\SparePartPlan;
use App\Models\SettingMenu\MailSetup;
//********************** controller ****************
use App\Http\Controllers\MachineAddTable\SparPartController;
//************** Package form github ***************
use RealRashid\SweetAlert\Facades\Alert;

class MachineSparePartController extends Controller
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
  //  ********************************   USE IN JAVASCRIPT MACHINE EDIT PAGE ***************************************************
  public function GetListSparepart(Request $request,$MACHINE_UNID = NULL){
        $MACHINE = Machine::select('MACHINE_RANK_MONTH')->where('UNID','=',$MACHINE_UNID)->first();
        $DATA_MACHINESPAREPART = MachineSparePart::select('SPAREPART_UNID')->where('MACHINE_UNID','=',$MACHINE_UNID)->get();
        $array_mc = array();
        foreach ($DATA_MACHINESPAREPART as $index => $row) {
          $array_mc[] = $row->SPAREPART_UNID;
        }
        if (count($array_mc) > 0) {
          $DATA_SPAREPART          = SparePart::select('UNID','SPAREPART_NAME','SPAREPART_CODE')->whereNotIn('UNID',$array_mc)
                                          ->where('STATUS','=','9')
                                          ->get();
        }else {
          $DATA_SPAREPART          = SparePart::select('UNID','SPAREPART_NAME','SPAREPART_CODE')->where('STATUS','=','9')
                                          ->get();
        }
    $html = '';
          foreach ($DATA_SPAREPART as $key => $row_sparepart) {
            $SPAREPART_UNID = $row_sparepart->UNID;
        $html.= '<div>
                <form name="FRM_'.$SPAREPART_UNID.'" id="FRM_'.$SPAREPART_UNID.'" method="GET">
                  <tr id="'.$SPAREPART_UNID.'">
                    <td>
                      <div class="form-check">
                      <label class="form-check-label">
                        <input class="form-check-input add-machine" type="checkbox" value="'.$SPAREPART_UNID.'"
                        id="SPAREPART_UNID'.$SPAREPART_UNID.'" name="SPAREPART_UNID'.$SPAREPART_UNID.'">
                        <span class="form-check-sign">'.$row_sparepart->SPAREPART_NAME.'</span>
                      </label>
                    </div>
                    </td>
                    <td>'.$row_sparepart->SPAREPART_CODE.'</td>
                    <td>
                      <div class="input-group">
                        <input type="number" class="form-control form-control-sm bg-info text-white add-period"
                         id="PERIOD_'.$SPAREPART_UNID.'" name="PERIOD_'.$SPAREPART_UNID.'" data-unid="'.$SPAREPART_UNID.'"
                         min=0 max=12 onchange="';
                    $html.="addmachinetosparepart('".$SPAREPART_UNID."')";

                   $html.='" value="'.$MACHINE->MACHINE_RANK_MONTH.'">
                      </div>
                    </td>
                    <td>
                      <div class="input-group">
                        <input type="date" class="form-control form-control-sm bg-info text-white add-datestart"
                         id="DATESTART_'.$SPAREPART_UNID.'" name="DATESTART_'.$SPAREPART_UNID.'" data-unid="'.$SPAREPART_UNID.'"
                         onchange="';
                    $html.="addmachinetosparepart('".$SPAREPART_UNID."')";
                    $html.='" value="'.date("Y-m-d").'">
                      </div>
                    </td>
                    <td>
                      <div class="input-group">
                        <input type="number" class="form-control form-control-sm bg-info text-white add-qty"
                         id="SPAREPART_QTY_'.$SPAREPART_UNID.'" name="SPAREPART_QTY_'.$SPAREPART_UNID.'" data-unid="'.$SPAREPART_UNID.'"
                          min=0 onchange="';
                     $html.="addmachinetosparepart('".$SPAREPART_UNID."')";

                    $html.='" value="0">
                      </div>
                    </td>
              </tr></form></div>';

          }
   $html.='';
   return Response()->json(['res' => $html]);
  }
  public function Save(Request $request){
    $totalmonth         = MailSetup::select('AUTOPLAN')->first();
    if (!isset($totalmonth->AUTOPLAN)) {
        return Response()->json(['res' => false]);
    }
    $MACHINE_UNID     = $request->MACHINE_UNID;
    $MACHINE_CODE     = $request->MACHINE_CODE;
    $PERIOD           = $request->PERIOD ;
    $DATESTART        = $request->DATESTART;
    $SPARTPART_UNID   = $request->SPARTPART_UNID;
    $SPAREPART_QTY    = $request->SPAREPART_QTY;
    $SPAREPART        = SparePart::where('UNID','=',$SPARTPART_UNID)->first();
    $count_sparepart  = MachineSparePart::where('MACHINE_UNID','=',$MACHINE_UNID)
                                       ->where('SPAREPART_UNID','=',$SPARTPART_UNID)
                                       ->count();
    $SPAREPART_COST   = $SPAREPART->SPAREPART_COST ;
    $SPAREPART_PLAN   = new SparPartController;
    if ($count_sparepart > 0) {
      MachineSparePart::where('MACHINE_UNID','=',$MACHINE_UNID)
                      ->where('SPAREPART_UNID','=',$SPAREPART->UNID)
                      ->update([
                         'MACHINE_UNID'     => $MACHINE_UNID
                        ,'MACHINE_CODE'     => $MACHINE_CODE
                        ,'SPAREPART_UNID'   => $SPARTPART_UNID
                        ,'SPAREPART_NAME'   => $SPAREPART->SPAREPART_NAME
                        ,'SPAREPART_CODE'   => $SPAREPART->SPAREPART_CODE
                        ,'STATUS'           => 9
                        ,'REMARK'           => ''
                        ,'SPAREPART_QTY'    => $SPAREPART_QTY
                        ,'UNIT'             => $SPAREPART->UNIT
                        ,'PERIOD'           => $PERIOD
                        ,'LAST_CHANGE'      => $DATESTART
                        ,'NEXT_PLAN_DATE'   => ''
                        ,'COST_STD'         => $SPAREPART_COST
                        ,'MODIFY_BY'        => Auth::user()->name
                        ,'MODIFY_TIME'      => Carbon::now()
                      ]);
        SparePartPlan::Where('MACHINE_UNID','=',$MACHINE_UNID)
                      ->where('SPAREPART_UNID','=',$SPARTPART_UNID)
                      ->where('STATUS','=','NEW')
                      ->where('PLAN_DATE','>',Carbon::parse($DATESTART))->delete();
    }else {
     $checkdata = MachineSparePart::insert([
       'UNID'             => $this->randUNID('PMCS_CMMS_MACHINE_SPAREPART')
       ,'MACHINE_UNID'    => $MACHINE_UNID
       ,'MACHINE_CODE'    => $MACHINE_CODE
       ,'SPAREPART_UNID'  => $SPARTPART_UNID
       ,'SPAREPART_NAME'  => $SPAREPART->SPAREPART_NAME
       ,'SPAREPART_CODE'  => $SPAREPART->SPAREPART_CODE
       ,'STATUS'          => 9
       ,'REMARK'          => ''
       ,'SPAREPART_QTY'   => $SPAREPART_QTY
       ,'PERIOD'          => $PERIOD
       ,'LAST_CHANGE'     => $DATESTART
       ,'NEXT_PLAN_DATE'  => ''
       ,'COST_STD'        => $SPAREPART_COST
       ,'CREATE_BY'       => Auth::user()->name
       ,'CREATE_TIME'     => Carbon::now()
       ,'MODIFY_BY'       => Auth::user()->name
       ,'MODIFY_TIME'     => Carbon::now()
     ]);
   }
   $SPAREPART_PLAN->PlanSave($MACHINE_UNID,$PERIOD,$DATESTART,$SPARTPART_UNID,$SPAREPART_QTY,$SPAREPART_COST);
  }
  public function Update(Request $request){
    $MACHINESPAREPART_UNID  = $request->MACHINESPAREPART_UNID;
    $count_machinesparepart = MachineSparePart::where('UNID','=',$MACHINESPAREPART_UNID)->count();
    if ($count_machinesparepart == 0) {
      alert()->error('ไม่พบข้อมูลที่จะบันทึก')->autoclose('1500');
      return redirect()->back();
    }
      $PLAN_DATE     = $request->PLAN_DATE;
      $PERIOD        = $request->PERIOD;
      $SPAREPART_QTY = $request->SPAREPART_QTY;
      $STATUS        = $request->STATUS != '' ? $request->STATUS : 1;
        MachineSparePart::where('UNID','=',$MACHINESPAREPART_UNID)->update([
           'STATUS'              => $STATUS
          ,'REMARK'              => $request->REMARK
          ,'SPAREPART_QTY'       => $SPAREPART_QTY
          ,'PERIOD'              => $PERIOD
          ,'LAST_CHANGE'         => $PLAN_DATE
          ,'MODIFY_BY'           => Auth::user()->name
          ,'MODIFY_TIME'         => Carbon::now()
        ]);

        $machinesparepart = MachineSparePart::select('MACHINE_UNID','SPAREPART_UNID','COST_STD')->where('UNID','=',$MACHINESPAREPART_UNID)->first();
        $MACHINE_UNID     = $machinesparepart->MACHINE_UNID;
        $SPARTPART_UNID   = $machinesparepart->SPAREPART_UNID;
        $SPAREPART_COST   = $machinesparepart->COST_STD;
        $whereplan        = ['MACHINE_UNID'   => $MACHINE_UNID,
                             'SPAREPART_UNID' => $SPARTPART_UNID,
                             'STATUS'         => 'NEW'];
        SparePartPlan::where($whereplan)->where('PLAN_DATE','>',$PLAN_DATE)->delete();

        $plansparepart = new SparPartController;
        $plansparepart->PlanSave($MACHINE_UNID,$PERIOD,$PLAN_DATE,$SPARTPART_UNID,$SPAREPART_QTY,$SPAREPART_COST);
        SparePartPlan::where('MACHINE_UNID','=',$MACHINE_UNID)->where('SPAREPART_UNID','=',$SPARTPART_UNID)
                      ->update(['STATUS_OPEN' => $STATUS]);
        alert()->success('อัพเดทข้อมูลสำเร็จ')->autoclose('1500');
        return redirect()->back();



  }
  public function Delete(Request $request){
    $MACHINE_UNID   = $request->MACHINE_UNID ;
    $SPARTPART_UNID = $request->SPAREPART_UNID ;
    if (!isset($MACHINE_UNID) && !isset($SPARTPART_UNID)) {
      return Response()->json(['res' => false]);
    }
    $count_machinesparepart = MachineSparePart::where('MACHINE_UNID','=',$MACHINE_UNID)
                                              ->where('SPAREPART_UNID','=',$SPARTPART_UNID)
                                              ->count();
    if ($count_machinesparepart == 0) {
      return Response()->json(['res' => false ]);
    }
    MachineSparePart::where('MACHINE_UNID','=',$MACHINE_UNID)
                    ->where('SPAREPART_UNID','=',$SPARTPART_UNID)
                    ->delete();
    $whereplan = ['MACHINE_UNID'    => $MACHINE_UNID,
                  'SPAREPART_UNID'  => $SPARTPART_UNID,
                  'STATUS'          => 'NEW'];
    SparePartPlan::where($whereplan)->where('PLAN_DATE','>',Carbon::now())->delete();
      return Response()->json(['res' => true ]);

  }
  public function StatusOpen(Request $request){
    $MACHINE_UNID   = $request->MACHINE_UNID;
    $SPAREPART_UNID = $request->SPAREPART_UNID;
    $STATUS         = 9 ;
    MachineSparePart::where('MACHINE_UNID','=',$MACHINE_UNID)->where('SPAREPART_UNID','=',$SPAREPART_UNID)
                    ->update(['STATUS' => $STATUS]);
    $PLAN = SparePartPlan::select('NEXT_DATE')->where('MACHINE_UNID','=',$MACHINE_UNID)->where('SPAREPART_UNID','=',$SPAREPART_UNID)
                          ->where('STATUS','!=','NEW')
                          ->orderBy('PLAN_DATE','DESC')->first();
    $DATESTART = Carbon::now();
    $NEXT_DATE = isset($PLAN->NEXT_DATE) ? $PLAN->NEXT_DATE : Carbon::now()->subMonths(1);
    if ($NEXT_DATE < $DATESTART) {

      SparePartPlan::where('MACHINE_UNID','=',$MACHINE_UNID)->where('SPAREPART_UNID','=',$SPAREPART_UNID)
                   ->where('STATUS','=','NEW')->delete();

      $MACHINE_SPAREPART = MachineSparePart::where('MACHINE_UNID','=',$MACHINE_UNID)->where('SPAREPART_UNID','=',$SPAREPART_UNID)->first();
      $SPAREPART_PLAN    = new SparPartController;
      $SPAREPART_PLAN->PlanSave($MACHINE_UNID, $MACHINE_SPAREPART->PERIOD,
      $DATESTART,$SPAREPART_UNID,$MACHINE_SPAREPART->SPAREPART_QTY,$MACHINE_SPAREPART->COST_STD);
    }
    SparePartPlan::where('MACHINE_UNID','=',$MACHINE_UNID)->where('SPAREPART_UNID','=',$SPAREPART_UNID)
                  ->update(['STATUS_OPEN' => $STATUS]);

    return Response()->json(['res'=>true]);
  }

}
