<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Machine\Machine;
use App\Models\Machine\MachineRepairREQ;
use App\Models\Machine\MachinePlanPm;
use App\Models\Machine\SparePartPlan;
use App\Models\Machine\Pmplanresult;
use Auth;
use Gate;
use Illuminate\Support\Facades\DB;
class DashboardController extends Controller
{
  public function __construct(){
    $this->middleware('auth');

  }
  public function Dashboard(){

    $machine_all        = Machine::where('MACHINE_CHECK','!=','4')->count();
    $machine_ready      = Machine::where('MACHINE_CHECK','=','2')->count();
    $machine_wait       = Machine::whereNotIn('MACHINE_CHECK',['2','4'])->count();
    $datarepair         = MachineRepairREQ::where('CLOSE_STATUS','=','9')->count();
    $datarepairlist     = MachineRepairREQ::select('STATUS_NOTIFY','PRIORITY','MACHINE_CODE','MACHINE_STATUS','REPAIR_SUBSELECT_NAME','DOC_DATE','DOC_NO')
                                          ->where('CLOSE_STATUS','=','9')->orderBy('DOC_DATE','DESC')->orderBy("REPAIR_REQ_TIME",'DESC')
                                          ->orderBy('PRIORITY','ASC')->take(4)->get();
    $data_downtime      = MachineRepairREQ::select('MACHINE_CODE')->selectraw('MAX(DOWNTIME) as DOWNTIME')
                                          ->where('DOC_YEAR','=',date('Y'))->where('DOC_MONTH','=',date('n'))
                                          ->groupBy('MACHINE_CODE')->orderBy('DOWNTIME','DESC')
                                          ->where('CLOSE_STATUS','=',1)->take(7)->get();
    $data_count_repair  = MachineRepairREQ::selectraw('MACHINE_CODE,COUNT(MACHINE_CODE) as MACHINE_CODE_COUNT')
                                          ->where('DOC_YEAR','=',date('Y'))->where('DOC_MONTH','=',date('n'))
                                          ->where('MACHINE_LINE','like','L'.'%')
                                          ->groupBy('MACHINE_CODE')->orderBy('MACHINE_CODE_COUNT','DESC')->take(5)->get();
    $data_repair_detail = MachineRepairREQ::selectraw('REPAIR_SUBSELECT_NAME,COUNT(REPAIR_SUBSELECT_UNID) as REPAIR_SUBSELECT_UNID_COUNT')
                                          ->where('DOC_YEAR','=',date('Y'))->where('DOC_MONTH','=',date('n'))
                                          ->groupBy('REPAIR_SUBSELECT_UNID')->groupBy('REPAIR_SUBSELECT_NAME')
                                          ->orderBy('REPAIR_SUBSELECT_UNID_COUNT','DESC')->take(5)->get();
    $count_pdm           = SparePartPlan::selectraw("sum(CASE WHEN STATUS  = 'COMPLETE' THEN 1 ELSE 0 END) as COMPLETE,
	                                                   sum(CASE WHEN STATUS != 'COMPLETE' THEN 1 ELSE 0 END) as NOCOMPLETE")
                                        ->where('DOC_YEAR','=',date('Y'))->where('DOC_MONTH','=',date('n'))->first();
    // PLAN MACHINE PM
    $data_complete   = array();
    $data_uncomplete = array();
    for ($i=0; $i < 4; $i++) {
      $DATA_MACHINEPLANPM = MachinePlanPm::selectRaw("sum(CASE WHEN PLAN_STATUS  = 'COMPLETE' THEN 1 ELSE 0 END) as COMPLETE,
        	                                           sum(CASE WHEN PLAN_STATUS != 'COMPLETE' THEN 1 ELSE 0 END) as NOCOMPLETE")
                                          ->where('PLAN_YEAR','=',date('Y'))->where('PLAN_MONTH','=',date('n'))
                                          ->where('PLAN_PERIOD','=',$i * 3+3)->first();
      $data_complete[$i * 3+3]   = isset($DATA_MACHINEPLANPM->COMPLETE)   ? $DATA_MACHINEPLANPM->COMPLETE   : 0;
      $data_uncomplete[$i * 3+3] = isset($DATA_MACHINEPLANPM->NOCOMPLETE) ? $DATA_MACHINEPLANPM->NOCOMPLETE : 0;
    }
    // Dowm Time
    $downtime_machine       = array();
    $downtime_machine_code  = array();
    foreach ($data_downtime as $index => $row) {
      $downtime_machine[$index+1]       = $row->DOWNTIME;
      $downtime_machine_code [$index+1] = $row->MACHINE_CODE;
    }
    //Repair Count
    $array_count_repair     = array();
    $array_count_machine    = array();
    foreach ($data_count_repair as $index => $row) {
      $array_count_repair[$index+1] = $row->MACHINE_CODE_COUNT;
      $array_count_machine[$index+1]= $row->MACHINE_CODE;
    }
    //Repair Detail
    $array_count_detail  = array();
    $array_count_name    = array();
    foreach ($data_repair_detail as $index => $row) {
      $array_count_detail[$index+1] = $row->REPAIR_SUBSELECT_UNID_COUNT;
      $array_count_name[$index+1]   = $row->REPAIR_SUBSELECT_NAME;
    }
    $array_line       = array();
    $array_repair     = array();
    for ($i=1; $i < 7 ; $i++) {
      $array_line['L'.$i]   = Machine::where('MACHINE_LINE','=','L'.$i)->count();
      $array_repair['L'.$i] = MachineRepairREQ::where('DOC_YEAR','=',date('Y'))->where('DOC_MONTH','=',date('n'))->where('MACHINE_LINE','=','L'.$i)->count();
    }

    return View('machine/dashboard/dashboard',compact('datarepairlist','datarepair','machine_all','machine_ready','machine_wait'
    ,'array_line','array_repair','array_count_repair','array_count_machine','array_count_detail','array_count_name'
    ,'data_complete','data_uncomplete','downtime_machine','downtime_machine_code','count_pdm'
    ));
  }
  public function PM(Request $request){
    $PM_BAR_CHART = array();
    for ($i=1; $i < 7; $i++) {
      $DATA_PM_BARCHART = MachinePlanPm::selectraw("sum(CASE WHEN PLAN_PERIOD = '3'	and MACHINE_LINE = 'L$i' THEN 1 ELSE 0 END) as COUNT_MONTH3,
                                                	  sum(CASE WHEN PLAN_PERIOD = '6'	and MACHINE_LINE = 'L$i' THEN 1 ELSE 0 END) as COUNT_MONTH6,
                                                	  sum(CASE WHEN PLAN_PERIOD = '12'	and MACHINE_LINE = 'L$i' THEN 1 ELSE 0 END) as COUNT_MONTH12")
                                       ->where('PLAN_YEAR','=',date('Y'))
                                       ->where('PLAN_MONTH','=',date('n'))->get();
    $PM_BAR_CHART[$i] = $DATA_PM_BARCHART;
    }
    for ($i=1; $i < 13; $i++) {
      $DATA_PDM_BARCHART = SparePartPlan::where('DOC_YEAR','=',date('Y'))->where('DOC_MONTH','=',$i)->count();
    $PDM_BAR_CHART[$i] = $DATA_PDM_BARCHART;
    }
    $DATA_PM_TABLE = MachinePlanPM::where('MACHINE_LINE','=','L1')->where('PLAN_YEAR','=',date('Y'))->where('PLAN_MONTH','=',date('n'))->get();
    $PM_USER_CHECK = Pmplanresult::select('PM_PLAN_UNID','PM_USER_CHECK')->get();
    return view('machine.dashboard.pmandpdm',compact('PM_BAR_CHART','PDM_BAR_CHART','DATA_PM_TABLE','PM_USER_CHECK'));
  }
  public function TablePM(Request $request){

    $LINE = $request->LINE;
    $ARRAY_LINE = array('L1'=>'LINE 1','L2'=>'LINE 2','L3'=>'LINE 3','L4'=>'LINE 4','L5'=>'LINE 5','L6'=>'LINE 6',);
    $DATA_PM_TABLE = MachinePlanPM::where('MACHINE_LINE','=',$LINE)->where('PLAN_YEAR','=',date('Y'))->where('PLAN_MONTH','=',date('n'))
                                  ->orderBy('PLAN_STATUS','DESC')->orderBy('MACHINE_CODE')->get();
    $DATA_RESULT   = MachinePlanPm::selectraw("sum(CASE WHEN PLAN_STATUS = 'COMPLETE' and MACHINE_LINE = '$LINE'THEN 1 ELSE 0 END) as COMPLETE,
                                               sum(CASE WHEN PLAN_STATUS = 'EDIT'     and MACHINE_LINE = '$LINE'THEN 1 ELSE 0 END) as EDIT,
                                               sum(CASE WHEN PLAN_STATUS = 'NEW'      and MACHINE_LINE = '$LINE'THEN 1 ELSE 0 END) as NOCOMPLETE")
                                               ->where('PLAN_YEAR','=',date('Y'))->where('PLAN_MONTH','=',date('n'))->first();
    $RESULT        = array('complete'=>$DATA_RESULT->COMPLETE,'waiting'=>$DATA_RESULT->EDIT,'nocomplete'=>$DATA_RESULT->NOCOMPLETE);
    $PM_USER_CHECK = Pmplanresult::select('PM_PLAN_UNID','PM_USER_CHECK')->get();
    $html = '<table class="table table-bordered table-head-bg-info table-bordered-bd-info " id="data_table_pm">
      <thead>
        <tr>
          <th >#</th>
          <th width="12%" class="text-center">MC-CODE</th>
          <th >MC-NAME</th>
          <th width="10%">รอบ(เดือน)</th>
          <th width="16%">สถานะ</th>
          <th width="16%">ผู้ตรวจสอบ</th>
          <th width="14%">วันที่ตรวจสอบ</th>
        </tr>
      </thead>
      <tbody>';
        foreach ($DATA_PM_TABLE as $key => $row){
            $USER_CHECK  = $PM_USER_CHECK->where('PM_PLAN_UNID','=',$row->UNID)->first();
            $STATUS_TEXT = $row->PLAN_STATUS == 'COMPLETE' ? 'ตรวจสอบแล้ว' : ($row->PLAN_STATUS == 'EDIT' ? 'กำลังดำเนินการ' : 'ยังไม่ได้ตรวจสอบ');
            $STATUS_BG 	 = $row->PLAN_STATUS == 'COMPLETE' ? 'bg-success' : ($row->PLAN_STATUS == 'EDIT' ? 'bg-warning' : 'bg-danger');
            $CHECK_BY    = isset($USER_CHECK->PM_USER_CHECK) ? $USER_CHECK->PM_USER_CHECK : '-';
      $html.='<tr>
                <td class="text-center">'. $key+1 .'</td>
                <td class="text-center">'.$row->MACHINE_CODE.'</td>
                <td>'.$row->MACHINE_NAME.'</td>
                <td class="text-center">'.$row->PLAN_PERIOD.'</td>
                <td class="'. $STATUS_BG .' text-white" >'.$STATUS_TEXT.'</td>
                <td>'.$CHECK_BY.'</td>
                <td>'.$row->COMPLETE_DATE.'</td>
              </tr>';
        }
    $html.='</tbody>
      </table>';

    return Response()->json(['html'=>$html,'LINE' => $ARRAY_LINE[$LINE],'data'=>$RESULT]);
  }
  public function TablePDM(Request $request){
    $LINE           = $request->LINE;
    $ARRAY_LINE     = array('L1'=>'LINE 1','L2'=>'LINE 2','L3'=>'LINE 3','L4'=>'LINE 4','L5'=>'LINE 5','L6'=>'LINE 6',);
    $DATA_PDM_TABLE = SparePartPlan::where('MACHINE_LINE','=',$LINE)->where('DOC_YEAR','=',date('Y'))
                                    ->where('DOC_MONTH','=',date('n'))->orderBy('STATUS','DESC')->orderBy('MACHINE_CODE')->get();
    $MACHINE        = Machine::select('UNID','MACHINE_NAME');
    $html = '<table class="table table-bordered table-head-bg-info table-bordered-bd-info " id="data_table_pdm">
      <thead>
        <tr>
          <th >#</th>
          <th width="7%" class="text-center">MC-CODE</th>
          <th width="20%">MC-NAME</th>
          <th width="20%">SparePart Name</th>
          <th width="6%">ตามแผน</th>
          <th width="6%">ที่เปลี่ยน</th>
          <th width="7%">รอบ(เดือน)</th>
          <th width="11%">สถานะ</th>
          <th width="12%">ผู้ตรวจสอบ</th>
          <th width="16%">วันที่ตรวจสอบ</th>
        </tr>
      </thead>
      <tbody>';
        foreach ($DATA_PDM_TABLE as $key => $row){

            $STATUS       = $row->STATUS;
            $STATUS_TEXT  = $STATUS == 'COMPLETE' ? 'ตรวจสอบแล้ว' : ($STATUS == 'EDIT' ? 'กำลังดำเนินการ' : 'ยังไม่ได้ตรวจสอบ');
            $STATUS_BG 	  = $STATUS == 'COMPLETE' ? 'bg-success' : ($STATUS == 'EDIT' ? 'bg-warning' : 'bg-danger');
            $CHECK_BY     = isset($row->USER_CHECK) ? $row->USER_CHECK : '-';
            $MACHINE_NAME = $MACHINE->where('UNID','=',$row->MACHINE_UNID)->get();
            $MACHINE_NAME = isset($MACHINE_NAME[0]->MACHINE_NAME) ? $MACHINE_NAME[0]->MACHINE_NAME: '-';
            $COMPLETE_DATE = $row->COMPLETE_DATE == '1900-01-01' ? '-' : $row->COMPLETE_DATE;
      $html.='<tr>
                <td class="text-center">'. $key+1 .'</td>
                <td class="text-center">'.$row->MACHINE_CODE.'</td>
                <td >'.$MACHINE_NAME.'</td>
                <td >'.$row->SPAREPART_NAME.'</td>
                <td class="text-center">'.$row->PLAN_QTY.'</td>
                <td class="text-center">'.$row->ACT_QTY.'</td>
                <td class="text-center">'.$row->PERIOD.'</td>
                <td class="'. $STATUS_BG .' text-white" >'.$STATUS_TEXT.'</td>
                <td>'.$CHECK_BY.'</td>
                <td>'.$COMPLETE_DATE.'</td>
              </tr>';
        }
    $html.='</tbody>
      </table>';

    return Response()->json(['html'=>$html,'LINE' => $ARRAY_LINE[$LINE]]);
  }
  public function Downtime(Request $request){
    $DATA_DOWNTIME      = MachineRepairREQ::select('UNID')->selectraw('MAX(DOWNTIME) as DOWNTIME')
                                          ->where('DOC_YEAR','=',date('Y'))->where('DOC_MONTH','=',date('n'))
                                          ->where('CLOSE_STATUS','=',1)
                                          ->groupBy('UNID')->orderBy('DOWNTIME','DESC')->take(7)->get();
    $DATA_SUM_DOWNTIME  = MachineRepairREQ::select('MACHINE_CODE','MACHINE_UNID','MACHINE_NAME')->selectraw('SUM(DOWNTIME) as DOWNTIME')->where('DOC_YEAR','=',date('Y'))
                                          ->where('DOC_MONTH','=',date('n'))->where('CLOSE_STATUS','=',1)->groupBy('MACHINE_CODE')
                                          ->groupBy('MACHINE_UNID')->groupBy('MACHINE_NAME')
                                          ->orderBy('DOWNTIME','DESC')->take(7)->get();

    $DATA_REPAIR_SUM    = MachineRepairREQ::select('MACHINE_UNID','DOWNTIME','REPAIR_SUBSELECT_NAME','REPAIR_DETAIL')
                                          ->where('DOC_YEAR','=',date('Y'))->where('DOC_MONTH','=',date('n'))
                                          ->where('CLOSE_STATUS','=',1)->orderBy('DOWNTIME','DESC')->get();
    $ARRAY_REPAIR_UNID  = array();
    foreach ($DATA_DOWNTIME as $index => $row) {
      $ARRAY_REPAIR_UNID[$row->UNID]          = $row->UNID;
    }
    $DATA_REPAIR        = MachineRepairREQ::select('MACHINE_CODE')
                                          ->selectraw('MAX(MACHINE_UNID) as MACHINE_UNID,MAX(INSPECTION_RESULT_TIME) as INSPECTION_RESULT_TIME
                                                      ,MAX(SPAREPART_RESULT_TIME) as SPAREPART_RESULT_TIME,MAX(WORKERIN_RESULT_TIME) as WORKERIN_RESULT_TIME
                                                      ,MAX(MACHINE_CODE) as MACHINE_CODE,MAX(MACHINE_NAME) as MACHINE_NAME
                                                      ,MAX(REPAIR_SUBSELECT_NAME) as REPAIR_SUBSELECT_NAME,MAX(REPAIR_DETAIL) as REPAIR_DETAIL
                                                      ,MAX(DOWNTIME) as DOWNTIME,MAX(dbo.decode_utf8(CLOSE_BY)) as CLOSE_BY_TH')
                                          ->whereIn('UNID',$ARRAY_REPAIR_UNID)
                                          ->groupBy('MACHINE_CODE')->orderBy('DOWNTIME','DESC')->take(7)->get();

    $array_downtime_count = array();
    $array_downtime_name  = array();

    for($i = 0; $i < 7 ; $i++){
      $array_downtime_count[$i+1] = isset($DATA_SUM_DOWNTIME[$i]->DOWNTIME) ?  $DATA_SUM_DOWNTIME[$i]->DOWNTIME : '0';
      $array_downtime_name[$i+1]  = isset($DATA_SUM_DOWNTIME[$i]->MACHINE_CODE) ?  $DATA_SUM_DOWNTIME[$i]->MACHINE_CODE : '-';
    }
    return view('machine.dashboard.downtime',compact('DATA_REPAIR','array_downtime_count','array_downtime_name','DATA_REPAIR_SUM','DATA_SUM_DOWNTIME'));
  }
  public function MachineRepair(Request $request){

    $MACHINE_UNID = array();
    $MACHINE_COUNT = array();
    for ($i=1; $i < 7; $i++) {
      $COUNT_MACHINE  = MachineRepairREQ::selectraw('MACHINE_UNID,Count(MACHINE_CODE) as MACHINE_CODE_COUNT')
                                            ->where('DOC_YEAR','=',date('Y'))->where('DOC_MONTH','=',date('n'))
                                            ->where('MACHINE_LINE','=','L'.$i)
                                            ->groupBy('MACHINE_UNID')->orderBy('MACHINE_CODE_COUNT','DESC')->first();
      $MACHINE = '';
      if (isset($COUNT_MACHINE->MACHINE_UNID)) {
        $MACHINE        = Machine::select('MACHINE_CODE','MACHINE_LINE')->where('UNID','=',$COUNT_MACHINE->MACHINE_UNID)
                                 ->orderBy('MACHINE_LINE')->first();
      }
      $MACHINE_CODE['L'.$i]  = isset($MACHINE->MACHINE_CODE)              ? $MACHINE->MACHINE_CODE       : '';
      $MACHINE_COUNT['L'.$i] = isset($COUNT_MACHINE->MACHINE_CODE_COUNT)  ? $COUNT_MACHINE->MACHINE_CODE_COUNT : '';
    }
    $ORDER_BY_COUNT    = MachineRepairREQ::selectraw('MACHINE_UNID,Count(MACHINE_CODE) as MACHINE_CODE_COUNT')
                                          ->where('DOC_YEAR','=',date('Y'))->where('DOC_MONTH','=',date('n'))
                                          ->where('MACHINE_LINE','like','L'.'%')
                                          ->groupBy('MACHINE_UNID')->orderBy('MACHINE_CODE_COUNT','DESC')->get();
    $MACHINEREPAIRREQ  = MachineRepairREQ::select('*')->selectraw('dbo.decode_utf8(MACHINE_NAME) as MACHINE_NAME_TH')
                                          ->where('DOC_YEAR','=',date('Y'))->where('DOC_MONTH','=',date('n'))
                                          ->where('MACHINE_LINE','like','L'.'%')->orderBy('MACHINE_CODE','DESC')->get();

    return View('machine/dashboard/machinerepair',compact('MACHINE_CODE','MACHINE_COUNT','MACHINEREPAIRREQ','ORDER_BY_COUNT'));
  }

  public function NotificationRepair(){
    $datarepairlist   = MachineRepairREQ::select('STATUS_NOTIFY','PRIORITY','MACHINE_CODE','MACHINE_STATUS','REPAIR_SUBSELECT_NAME','DOC_DATE','DOC_NO')
                                        ->where('CLOSE_STATUS','=','9')->orderBy('DOC_DATE','DESC')->orderBy("REPAIR_REQ_TIME",'DESC')->orderBy('PRIORITY','ASC')->take(4)->get();
    $html       = '';
    foreach ($datarepairlist as $key => $row) {
      $TEXT                  = $row->MACHINE_STATUS == 1 ? 'หยุดทำงาน' : 'ทำงานปกติ' ;
      $COLOR_PRIORITY        = $row->PRIORITY       == 9 ? 'bg-danger' : 'bg-warning';
      $COLOR_MACHINE_STATUS  = $row->MACHINE_STATUS == 1 ? 'text-danger' : 'text-warning' ;
      $NEW_IMG               = $row->STATUS_NOTIFY  == 9 ? '<img src="'.asset('assets/img/new.gif').'" class="mt--2" width="40px" height="40px">': '' ;
      $html.='<a href="'.route('repair.list').'?SEARCH_MACHINE='.$row->DOC_NO.'"style="text-decoration:none;">
            <div class="row">
              <div class="d-flex col-md-6 col-lg-1">
                <input type="hidden" value="1">
                <div class="avatar avatar-online">
                  <span class="avatar-title rounded-circle border border-white '.$COLOR_PRIORITY.'" style="width:50px"><i class="fa fa-wrench"></i></span>
                </div>
              </div>
              <div class="flex-1 ml-3 pt-1 col-md-6 col-lg-7">
                <h4 class="text-uppercase fw-bold mb-1" style="color:#6c757d;">'.$row->MACHINE_CODE .'
                <span class="'.$COLOR_MACHINE_STATUS.' pl-3">';
                  if ($row->PRIORITY == '9'){
                    $html.='<img src="'.asset('assets/css/flame.png').'" class="mt--2" width="20px" height="20px">';
                  }
                    $html.= $TEXT.''.$NEW_IMG.'';
                  $html.= '</span></h4>
                <span class="text-muted" >' .$row->REPAIR_SUBSELECT_NAME  .'</span>
              </div>
              <div class="float-right pt-1 col-md-6 col-lg-3">
                <h5 class="text-muted">'.$row->DOC_DATE .'</h5>
              </div>
            </div>
            <hr>
            </a>
            ';
    }
    $last_data  = MachineRepairREQ::where('STATUS_NOTIFY','=',9)->count();
    $newrepair  = $last_data > 0 ? true : false;
    $datarepair = MachineRepairREQ::select('UNID','MACHINE_LINE','MACHINE_CODE','DOC_DATE')->where('CLOSE_STATUS','=',9)->orderBy('DOC_DATE','DESC')->orderBy("REPAIR_REQ_TIME",'DESC')->orderBy('PRIORITY','ASC')->get();
    $NUMBER     = count($datarepair);
    return Response()->json(['html'=>$html,'newrepair' => $newrepair,'number' => $NUMBER,'datarepair'=>$datarepair]);
  }
  public function UserHomePage(Request $request){
    $ROLE = $request->role;
    if (Gate::allows('isManager_Ma')) {
      return View('machine.userpage.userhomepageforma');
    }elseif(Gate::allows('isManager_Pd')) {
      return View('machine.userpage.userhomepageforpd');
    }elseif (Gate::allows('isAdmin')) {
        if ($ROLE == 'MA') {
          return View('machine.userpage.userhomepageforma');
        }elseif ($ROLE == 'PD') {
          return View('machine.userpage.userhomepageforpd');
        }else {
          return Redirect(route('dashboard'));
        }
    }
  }

  //**********************************************************************************************************//

}
