<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Machine\Machine;
use App\Models\Machine\MachineRepairREQ;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\DB;
class DashboardController extends Controller
{
  public function __construct(){
    $this->middleware('auth');
    $this->middleware(function ($request, $next) {
        $checkuser = Auth::user();

        if ($checkuser->role == 'user') {

          return Redirect()->route('user.homepage');
        }else {
          return $next($request);
        }

    });
  }
  public function Sumaryline(){
    return View('machine/dashboard/sumaryline');
  }
  public function Dashboard(){

    $machine_all    = Machine::select('MACHINE_CHECK')->where('MACHINE_CHECK','!=','4')->count();
    $machine_ready  = Machine::select('MACHINE_CHECK')->where('MACHINE_CHECK','=','2')->count();
    $machine_wait   = Machine::select('MACHINE_CHECK')->where('MACHINE_CHECK','!=','2')->where('MACHINE_CHECK','!=','4')->count();



    $datarepair = MachineRepairREQ::select('CLOSE_STATUS')->where('CLOSE_STATUS','=','9')->count();

    $datarepairlist   = MachineRepairREQ::select('PRIORITY','MACHINE_CODE','MACHINE_STATUS','REPAIR_SUBSELECT_NAME','DOC_DATE')
                                        ->where('CLOSE_STATUS','=','9')->orderBy('DOC_DATE','DESC')->orderBy('PRIORITY','ASC')->take(9)->get();
    $array_line       = array();
    $array_repair     = array();
    for ($i=1; $i < 7 ; $i++) {
      $data_line            = Machine::select('MACHINE_LINE')->where('MACHINE_LINE','=','L'.$i)->count();
      $data_repair          = MachineRepairREQ::select('MACHINE_LINE')->where('MACHINE_LINE','=','L'.$i)->count();
      $array_line['L'.$i]   = $data_line;
      $array_repair['L'.$i] = $data_repair;
    }

    return View('machine/dashboard/dashboard',compact('datarepairlist','datarepair','machine_all','machine_ready','machine_wait'
    ,'array_line','array_repair'
    ));
  }


  public function Notification(Request $request){
    $data = MachineRepairREQ::select('*')->where('CLOSE_STATUS','=','9')->orderBy('PRIORITY','DESC')->orderBy('DOC_DATE')->take(4)->get();

    return response()->json(['datarepair' => $data]);
  }

  public function NotificationCount(Request $request){
    $data = MachineRepairREQ::where('CLOSE_STATUS','9')->take(4)->get()->count();
    return response()->json(['datacount' => $data]);
  }
  public function SystemcheckMonthly(Request $request){
    $systemcheck = 'PMCS_CMMS_MACHINE_SYSTEMCHECK';
    $systemtable = 'PMCS_CMMS_MACHINE_SYSTEMTABLE';
    $machine = 'PMCS_MACHINE';
    $data = MachineSysTemCheck::select($systemcheck.'.MACHINE_UNID_REF',$systemtable.'.SYSTEM_NAME'
                                ,$machine.'.MACHINE_LINE',$machine.'.MACHINE_CODE',$systemcheck.'.SYSTEM_MONTHSTORE')
                          ->leftJoin($systemtable,$systemtable.'.SYSTEM_CODE',$systemcheck.'.SYSTEM_CODE')
                          ->leftJoin($machine,$machine.'.UNID',$systemcheck.'.MACHINE_UNID_REF')
                          ->whereDate('SYSTEM_MONTHSTORE','<=',Carbon::now('Asia/Bangkok'))
                          ->orderBy('SYSTEM_MONTHSTORE','DESC')->take(4)->get();

    return response()->json(['datamonth' => $data]);
  }
  public function SystemcheckMonthlycount(Request $request){
    $data = MachineSysTemCheck::select('SYSTEM_MONTHSTORE')->whereDate('SYSTEM_MONTHSTORE','<=',Carbon::now('Asia/Bangkok'))->take(4)
                                ->get()->count();
    return response()->json(['datamonthcount' => $data]);
  }

  public function NotificationRepair(){
    $datarepairlist   = MachineRepairREQ::select('STATUS_NOTIFY','PRIORITY','MACHINE_CODE','MACHINE_STATUS','REPAIR_SUBSELECT_NAME','DOC_DATE')
                                        ->where('CLOSE_STATUS','=','9')->orderBy('DOC_DATE','DESC')->orderBy('PRIORITY','ASC')->take(9)->get();
    $last_data  = MachineRepairREQ::selectraw('UNID,STATUS_NOTIFY')->whereRaw('DOC_NO = (SELECT MAX(DOC_NO)FROM [PMCS_CMMS_REPAIR_REQ])')->first();
    $data_count = MachineRepairREQ::selectraw('UNID,STATUS_NOTIFY')->whereRaw('DOC_NO = (SELECT MAX(DOC_NO)FROM [PMCS_CMMS_REPAIR_REQ])')->count();

    $newrepair = $last_data->STATUS_NOTIFY == 9 ? true : false;
    $UNID      = $last_data->STATUS_NOTIFY == 9 ? $last_data->UNID : '';
    $NUMBER    = $data_count;
    $html = '';

    foreach ($datarepairlist as $key => $row) {
      $TEXT                  = $row->MACHINE_STATUS == 1 ? 'หยุดทำงาน' : 'ทำงานปกติ' ;
      $COLOR_PRIORITY        = $row->PRIORITY       == 9 ? 'bg-danger' : 'bg-warning';
      $COLOR_MACHINE_STATUS  = $row->MACHINE_STATUS == '1' ? 'text-danger' : 'text-warning' ;
      $NEW_IMG               = $row->STATUS_NOTIFY  == 9 ? '<img src="'.asset('assets/img/new.gif').'" class="mt--2" width="20px" height="20px">': '' ;
      $html.='<div class="row">
              <div class="d-flex col-md-6 col-lg-1">
                <input type="hidden" value="1">
                <div class="avatar avatar-online">
                  <span class="avatar-title rounded-circle border border-white '.$COLOR_PRIORITY.'" style="width:50px"><i class="fa fa-wrench"></i></span>
                </div>
              </div>
              <div class="flex-1 ml-3 pt-1 col-md-6 col-lg-7">
                <h4 class="text-uppercase fw-bold mb-1">'.$row->MACHINE_CODE .'
                <span class="'.$COLOR_MACHINE_STATUS.' pl-3">';
                  if ($row->PRIORITY == '9'){
                    $html.='<img src="'.asset('assets/css/flame.png').'" class="mt--2" width="20px" height="20px">'.$NEW_IMG.'';
                  }else {
                    $html.= $TEXT.''.$NEW_IMG.'';
                  }
                  $html.= '</span></h4>
                <span class="text-muted" >' .$row->REPAIR_SUBSELECT_NAME  .'</span>
              </div>
              <div class="float-right pt-1 col-md-6 col-lg-3">
                <h5 class="text-muted">'.$row->DOC_DATE .'</h5>

              </div>
            </div>
            <hr>';
    }


    return Response()->json(['html'=>$html,'newrepair' => $newrepair,'UNID' => $UNID,'number' => $NUMBER]);

  }








  //**********************************************************************************************************//
  public function Logout(){
      Auth::logout();
      return Redirect()->route('login')->with('success','User Logout');
  }
}
