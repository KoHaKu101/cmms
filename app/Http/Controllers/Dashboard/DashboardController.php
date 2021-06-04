<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Machine\Machine;
use App\Models\Machine\MachineRepairREQ;
use App\Models\Machine\MachineSysTemCheck;
// use App\Models\Machine\MachineRepairREQ;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class DashboardController extends Controller
{
  public function __construct(){
    $this->middleware('auth');
  }
  public function Sumaryline(){
    //dd($data_set);
    return View('machine/dashboard/sumaryline');
  }
  public function Dashboard(){
    //dashboardสรุป
    $dataset = Machine::select('MACHINE_CHECK')->first();
    $datarepair = MachineRepairREQ::select('CLOSE_STATUS')->first();


    //dashboardเครื่องจักรLINE
    $data_line = Machine::select('MACHINE_LINE')->first();
    //แจ้งซ่อม
    $datarepairlist = MachineRepairREQ::select('MACHINE_CODE','DOC_DATE')
                                    ->where('CLOSE_STATUS','=','9')->orderBy('DOC_DATE','DESC')->take(9)->get();
    // dd($data_set);
    return View('machine/dashboard/dashboard',compact('datarepairlist','dataset','data_line','datarepair'));
  }
  public function Notification(Request $request){
    $data = MachineRepairREQ::select('*')->orderBy('PRIORITY','DESC')->orderBy('DOC_DATE')->take(4)->get();


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
    // $notify = (Carbon::now() >= $data->SYSTEM_MONTHSTORE) ? $data->get()  : ""  ;
    // $datacount = MachineRepairREQ::where('CLOSE_STATUS','9')->get()->count();
    // dd($data);

    return response()->json(['datamonth' => $data]);
  }
  public function SystemcheckMonthlycount(Request $request){
    $data = MachineSysTemCheck::select('SYSTEM_MONTHSTORE')->whereDate('SYSTEM_MONTHSTORE','<=',Carbon::now('Asia/Bangkok'))->take(4)
                                ->get()->count();
    return response()->json(['datamonthcount' => $data]);
  }









  //**********************************************************************************************************//
  public function Logout(){
      Auth::logout();
      return Redirect()->route('login')->with('success','User Logout');
  }
}
