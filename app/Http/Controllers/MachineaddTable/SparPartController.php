<?php

namespace App\Http\Controllers\MachineaddTable;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;

//******************** model ***********************
use App\Models\Machine\SparePart;
use App\Models\Machine\MachineSparePart;
use App\Models\Machine\SparePartPlan;
use App\Models\Machine\Machine;
use App\Models\SettingMenu\MailSetup;
//************** Package form github ***************
use RealRashid\SweetAlert\Facades\Alert;



class SparPartController extends Controller
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

  public function List(Request $request,$SPAREPART_UNID = NULL){
    $PAGE_PAGINATE               = isset($request->PAGE_PAGINATE) ? $request->PAGE_PAGINATE : 10;
    $SEARCH                      = isset($request->SEARCH) ? $request->SEARCH : '';
    $SPAREPART_UNID              = isset($SPAREPART_UNID) ? $SPAREPART_UNID : '';
    $OPEN                        = 0;
    $DATA_MACHINESPAREPART       = NULL ;
    $DATA_MACHINESPAREPART_FIRST = NULL ;
    $DATA_SPAREPART    = SparePart::where(function ($query) use ($SEARCH) {
                                   if ($SEARCH != '') {
                                      $query->where('SPAREPART_CODE', 'like', '%'.$SEARCH.'%')
                                            ->orwhere('SPAREPART_NAME','like','%'.$SEARCH.'%')
                                            ->orwhere('SPAREPART_MODEL','like','%'.$SEARCH.'%')
                                            ->orwhere('SPAREPART_SIZE','like','%'.$SEARCH.'%');
                                    }
                                   })
                                  ->orderBy('SPAREPART_CODE','ASC')->paginate($PAGE_PAGINATE,['*'],'sparepartpage');

    if ($SPAREPART_UNID != '') {
      $OPEN = 1;
      $DATA_MACHINESPAREPART = MachineSparePart::where('SPAREPART_UNID','=',$SPAREPART_UNID)
                                               ->orderBy('MACHINE_CODE')->paginate(10,['*'],'machinepage');
      $DATA_MACHINESPAREPART_FIRST = SparePart::where('UNID','=',$SPAREPART_UNID)->first();
    }
    return View('machine.sparepart.index',compact('PAGE_PAGINATE','SEARCH','DATA_SPAREPART','DATA_MACHINESPAREPART','DATA_MACHINESPAREPART_FIRST','OPEN'));
  }
  public function Save(Request $request){
    $validated = $request->validate([
      'SPAREPART_CODE'           => 'required|unique:PMCS_CMMS_SPAREPART|max:200',
      ],
      [
      'SPAREPART_CODE.required'  => '????????????????????????????????????',
      'SPAREPART_CODE.unique'    => '???????????????????????????????????????',
      ]);
    $SPAREPART_CODE   = $request->SPAREPART_CODE;
    $UNID             = $this->randUNID('PMCS_CMMS_SPAREPART');
    $STATUS           = $request->STATUS != '' ? $request->STATUS : 1;
    $SPAREPART_INDEX  = SparePart::count();
    $REMARK           = $request->SPAREPART_REMARK != '' ? $request->SPAREPART_REMARK : '';
      SparePart::insert([
        'SPAREPART_CODE'      => $SPAREPART_CODE
        ,'UNID'               => $UNID
        ,'SPAREPART_NAME'     => $request->SPAREPART_NAME
        ,'SPAREPART_MODEL'    => $request->SPAREPART_MODEL
        ,'SPAREPART_SUBMODEL' => ''
        ,'SPAREPART_REMARK'   => $REMARK
        ,'SPAREPART_SIZE'     => $request->SPAREPART_SIZE
        ,'STOCK_MIN'          => $request->STOCK_MIN
        ,'UNIT'               => $request->UNIT
        ,'SUPPLIER_CODE'      => ''
        ,'STATUS'             => $STATUS
        ,'SPAREPART_COST'     => $request->SPAREPART_COST
        ,'SPAREPART_INDEX'    => $SPAREPART_INDEX+1
        ,'CREATE_BY'          => Auth::user()->name
        ,'CREATE_TIME'        => Carbon::now()
        ,'MODIFY_BY'          => Auth::user()->name
        ,'MODIFY_TIME'        => Carbon::now()
      ]);
      alert()->success('??????????????????????????????????????????????????????',)->autoClose($milliseconds = 3000);
    return redirect()->route('SparPart.List');
  }
  public function Update(Request $request){
    $SPAREPART_UNID   = $request->SPAREPART_UNID;
    $STATUS           = $request->STATUS != '' ? $request->STATUS : 1;
    if ($SPAREPART_UNID == '') {
      alert()->error('??????????????????????????? ??????????????????????????????')->autoclose('2000');
      return redirect()->route('SparPart.List');
    }
    SparePart::where('UNID','=',$SPAREPART_UNID)->update([
         'SPAREPART_CODE'     => $request->SPAREPART_CODE
        ,'SPAREPART_NAME'     => $request->SPAREPART_NAME
        ,'SPAREPART_MODEL'    => $request->SPAREPART_MODEL
        ,'SPAREPART_REMARK'   => $request->SPAREPART_REMARK
        ,'SPAREPART_SIZE'     => $request->SPAREPART_SIZE
        ,'STOCK_MIN'          => $request->STOCK_MIN
        ,'UNIT'               => $request->UNIT
        ,'SPAREPART_COST'     => $request->SPAREPART_COST
        ,'STATUS'             => $STATUS
        ,'MODIFY_BY'          => Auth::user()->name
        ,'MODIFY_TIME'        => Carbon::now()
      ]);
      MachineSparePart::where('SPAREPART_UNID','=',$SPAREPART_UNID)->update([
        'STATUS' => $STATUS
      ]);
      SparePartPlan::where('SPAREPART_UNID','=',$SPAREPART_UNID)->update([
        'STATUS_OPEN' => $STATUS
      ]);
      alert()->success('??????????????????????????????????????????????????????')->autoclose('1500');
    return redirect()->route('SparPart.List');
  }
  public function Delete(Request $request){
    $SPAREPART_UNID = $request->SPAREPART_UNID;
    $count_machinesparepart = MachineSparePart::where('SPAREPART_UNID','=',$SPAREPART_UNID)->count();
    if ($count_machinesparepart > 0 && $SPAREPART_UNID != '') {
      return Response()->json(['res' => false ]);
    }else {
          SparePart::where('UNID','=',$SPAREPART_UNID)->delete();
          MachineSparePart::where('SPAREPART_UNID','=',$SPAREPART_UNID)
                          ->delete();
          SparePartPlan::where('SPAREPART_UNID','=',$SPAREPART_UNID)
                      ->where('STATUS','!=','COMPLETE')
                      ->delete();
          return Response()->json(['res' => true ]);
    }

  }
  public function SaveMachine(Request $request){
    $totalmonth         = MailSetup::select('AUTOPLAN')->first();
    if (!isset($totalmonth->AUTOPLAN)) {
        return Response()->json(['res' => false]);
    }
     $MACHINE_UNID    = $request->MACHINE_UNID;
     $PERIOD          = $request->PERIOD;
     $DATESTART       = $request->DATESTART;
     $SPARTPART_UNID  = $request->SPARTPART_UNID;
     $SPARTPART_CODE  = $request->SPARTPART_CODE;
     $SPAREPART_QTY   = $request->SPAREPART_QTY;
     $MACHINE         = Machine::select('MACHINE_CODE')->where('UNID','=',$MACHINE_UNID)->first();
     $SPAREPART       = SparePart::where('UNID','=',$SPARTPART_UNID)->first();
     $count_sparepart = MachineSparePart::where('MACHINE_UNID','=',$MACHINE_UNID)
                                        ->where('SPAREPART_UNID','=',$SPARTPART_UNID)
                                        ->count();

     $SPAREPART_COST = $SPAREPART->SPAREPART_COST ;
    if ($SPAREPART_QTY > 0) {
      $SPAREPART_COST =  $SPAREPART_COST ;
    }
     if ($count_sparepart > 0) {
       MachineSparePart::where('MACHINE_UNID','=',$MACHINE_UNID)
                       ->where('SPAREPART_UNID','=',$SPAREPART->UNID)
                       ->update([
                          'MACHINE_UNID'   => $MACHINE_UNID
                         ,'MACHINE_CODE'   => $MACHINE->MACHINE_CODE
                         ,'SPAREPART_UNID' => $SPARTPART_UNID
                         ,'SPAREPART_NAME' => $SPAREPART->SPAREPART_NAME
                         ,'SPAREPART_CODE' => $SPAREPART->SPAREPART_CODE
                         ,'STATUS'         => 9
                         ,'REMARK'         => ''
                         ,'SPAREPART_QTY'  => $SPAREPART_QTY
                         ,'UNIT'           => $SPAREPART->UNIT
                         ,'PERIOD'         => $PERIOD
                         ,'LAST_CHANGE'    => $DATESTART
                         ,'NEXT_PLAN_DATE' => ''
                         ,'COST_STD'       => $SPAREPART_COST
                         ,'MODIFY_BY'      => Auth::user()->name
                         ,'MODIFY_TIME'    => Carbon::now()
                       ]);
         SparePartPlan::Where('MACHINE_UNID','=',$MACHINE_UNID)
                     ->where('SPAREPART_UNID','=',$SPARTPART_UNID)
                     ->where('STATUS','!=','COMPLETE')
                     ->where('PLAN_DATE','>',Carbon::parse($DATESTART))->delete();
          $this->PlanSave($MACHINE_UNID,$PERIOD,$DATESTART,$SPARTPART_UNID,$SPAREPART_QTY,$SPAREPART_COST);
     }else {
      $checkdata = MachineSparePart::insert([
        'UNID'                => $this->randUNID('PMCS_CMMS_MACHINE_SPAREPART')
        ,'MACHINE_UNID'       => $MACHINE_UNID
        ,'MACHINE_CODE'       => $MACHINE->MACHINE_CODE
        ,'SPAREPART_UNID'     => $SPARTPART_UNID
        ,'SPAREPART_NAME'     =>$SPAREPART->SPAREPART_NAME
        ,'SPAREPART_CODE'     =>$SPAREPART->SPAREPART_CODE
        ,'STATUS'             => 9
        ,'REMARK'             => ''
        ,'SPAREPART_QTY'      => $SPAREPART_QTY
        ,'PERIOD'             => $PERIOD
        ,'LAST_CHANGE'        => $DATESTART
        ,'NEXT_PLAN_DATE'     => ''
        ,'COST_STD'           => $SPAREPART_COST
        ,'CREATE_BY'          => Auth::user()->name
        ,'CREATE_TIME'        => Carbon::now()
        ,'MODIFY_BY'          => Auth::user()->name
        ,'MODIFY_TIME'        => Carbon::now()
      ]);
      if ($checkdata) {
        $this->PlanSave($MACHINE_UNID,$PERIOD,$DATESTART,$SPARTPART_UNID,$SPAREPART_QTY,$SPAREPART_COST);
      }
    }

    return redirect()->route('SparPart.List');
  }
  public function DeleteMachine(Request $request,$MACHINE_UNID = NULL , $SPAREPART_UNID = NULL){

    if ($MACHINE_UNID != NULL && $SPAREPART_UNID != NULL) {
      SparePartPlan::Where('MACHINE_UNID','=',$MACHINE_UNID)
                  ->where('SPAREPART_UNID','=',$SPAREPART_UNID)
                  ->where('STATUS','!=','COMPLETE')
                  ->where('PLAN_DATE','>',Carbon::now())->delete();
       MachineSparePart::where('MACHINE_UNID','=',$MACHINE_UNID)
                       ->where('SPAREPART_UNID','=',$SPAREPART_UNID)
                       ->delete();
      return Response()->json(['res' => true ]);
    }else {
      return Response()->json(['res' => false ]);
    }
  }
  public function PlanSave($MACHINE_UNID = NULL,$PERIOD = 0,
    $DATESTART = NULL,$SPARTPART_UNID = NULL,$SPAREPART_QTY = 0,$SPAREPART_COST = 0){

    $totalloop          = 0;
    $totalmonth         = MailSetup::select('AUTOPLAN')->first();
    $preiodmonth        = $PERIOD;
    $pm_lastdate        = $DATESTART;
    $MACHINE            = Machine::select('UNID','MACHINE_CODE','MACHINE_LINE')->where('UNID','=',$MACHINE_UNID)->first();
    $SPAREPART          = SparePart::where('UNID','=',$SPARTPART_UNID)->first();

    $TOTAL_COST = 0;
    if ($SPAREPART_QTY > 0 && $SPAREPART_COST > 0) {
      $TOTAL_COST = $SPAREPART_QTY * $SPAREPART_COST;
    }
    for ($i = 0; $i < $totalmonth->AUTOPLAN ; $i++) {
        if (($i%$preiodmonth == 0)) {
          $totalloop++;
          $pm_lastdate    = Carbon::parse($pm_lastdate)->addMonth($preiodmonth);
          $pm_plandate    = $pm_lastdate;
          if ($MACHINE_UNID != "" && $SPARTPART_UNID != "") {
            $pmnext_date        = Carbon::parse($pm_plandate)->addmonth($preiodmonth);
            SparePartPlan::insert([
              'UNID'                =>  $this->randUNID('PMCS_CMMS_SPAREPART_PLAN')
              ,'MACHINE_UNID'       =>  $MACHINE->UNID
              ,'MACHINE_CODE'       =>  $MACHINE->MACHINE_CODE
              ,'MACHINE_LINE'       =>  $MACHINE->MACHINE_LINE
              ,'SPAREPART_UNID'     =>  $SPAREPART->UNID
              ,'SPAREPART_NAME'     =>  $SPAREPART->SPAREPART_NAME
              ,'SPAREPART_CODE'     =>  $SPAREPART->SPAREPART_CODE
              ,'STATUS'             =>  'NEW'
              ,'STATUS_OPEN'        =>  9
              ,'REMARK'             =>  ''
              ,'PLAN_QTY'           =>  $SPAREPART_QTY
              ,'ACT_QTY'            =>  0
              ,'UNIT'               =>  $SPAREPART->UNIT
              ,'PERIOD'             =>  $preiodmonth
              ,'DOC_YEAR'           =>  $pm_lastdate->format('Y')
              ,'DOC_MONTH'          =>  $pm_lastdate->format('m')
              ,'PLAN_DATE'          =>  $pm_lastdate
              ,'NEXT_DATE'          =>  $pmnext_date
              ,'COMPLETE_DATE'      =>   ''
              ,'COST_STD'           =>   $SPAREPART_COST
              ,'TOTAL_COST'         =>   $TOTAL_COST
              ,'COST_ACT'           =>   0
              ,'CREATE_BY'          => Auth::user()->name
              ,'CREATE_TIME'        => Carbon::now()
              ,'MODIFY_BY'          => Auth::user()->name
              ,'MODIFY_TIME'        => Carbon::now()
            ]);
          }
        }
    }

  }
  public function GetMachineList(Request $request,$SPAREPART_UNID = NULL){
    $SPAREPART_UNID        = $SPAREPART_UNID != '' ? $SPAREPART_UNID : '';
    $DATA_MACHINESPAREPART = MachineSparePart::select('MACHINE_UNID')->where(function($query) use ($SPAREPART_UNID){
                                                if ($SPAREPART_UNID != '') {
                                                  $query->where('SPAREPART_UNID','=',$SPAREPART_UNID);
                                                }
                                              })->get();
      $array_mc = array();
      foreach ($DATA_MACHINESPAREPART as $index => $row) {
        $array_mc[] = $row->MACHINE_UNID;
      }
      if (count($array_mc) > 0) {
        $DATA_MACHINE          = Machine::select('UNID','MACHINE_CODE','MACHINE_LINE','MACHINE_RANK_MONTH')->whereNotIn('UNID',$array_mc)
                                        ->where('MACHINE_STATUS','=','9')->where('MACHINE_TYPE_STATUS','=','9')->get();
      }else {
        $DATA_MACHINE          = Machine::select('UNID','MACHINE_CODE','MACHINE_LINE','MACHINE_RANK_MONTH')->where('MACHINE_STATUS','=','9')
                                        ->where('MACHINE_TYPE_STATUS','=','9')->get();
      }
        $html = '';
              foreach ($DATA_MACHINE as $key => $row_machine) {
                $MACHINE_UNID = $row_machine->UNID;
            $html.= '<div>
                    <form name="FRM_'.$MACHINE_UNID.'" id="FRM_'.$MACHINE_UNID.'" method="GET">
                      <tr id="'.$MACHINE_UNID.'">
                        <td>
                          <div class="form-check">
                          <label class="form-check-label">
                            <input class="form-check-input add-machine" type="checkbox" value="'.$MACHINE_UNID.'"
                            id="MACHINE_UNID_'.$MACHINE_UNID.'" name="MACHINE_UNID_'.$MACHINE_UNID.'"
                            >
                            <span class="form-check-sign">'.$row_machine->MACHINE_CODE.'</span>
                          </label>
                        </div>
                        </td>
                        <td>'.$row_machine->MACHINE_LINE.'</td>
                        <td>
                          <div class="input-group">
                            <input type="number" class="form-control form-control-sm bg-info text-white add-period"
                             id="PERIOD_'.$MACHINE_UNID.'" name="PERIOD_'.$MACHINE_UNID.'" data-unid="'.$MACHINE_UNID.'"
                             min=0 max=12 onchange="';
                        $html.="addmachinetosparepart('".$MACHINE_UNID."')";

                       $html.='" value="'.$row_machine->MACHINE_RANK_MONTH.'">
                          </div>
                        </td>
                        <td>
                          <div class="input-group">
                            <input type="date" class="form-control form-control-sm bg-info text-white add-datestart"
                             id="DATESTART_'.$MACHINE_UNID.'" name="DATESTART_'.$MACHINE_UNID.'" data-unid="'.$MACHINE_UNID.'"
                             onchange="';
                        $html.="addmachinetosparepart('".$MACHINE_UNID."')";

                       $html.='" value="'.date("Y-m-d").'">
                          </div>
                        </td>
                        <td>
                          <div class="input-group">
                            <input type="number" class="form-control form-control-sm bg-info text-white add-qty"
                             id="SPAREPART_QTY_'.$MACHINE_UNID.'" name="SPAREPART_QTY_'.$MACHINE_UNID.'" data-unid="'.$MACHINE_UNID.'"
                              min=0 onchange="';
                         $html.="addmachinetosparepart('".$MACHINE_UNID."')";

                        $html.='" value="0">
                          </div>
                        </td>
                  </tr></form></div>';
              }
       $html.='';
      return Response()->json(['res' => $html]);

  }
}
