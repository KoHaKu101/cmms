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

    $dataset = Machine::select('MACHINE_CHECK')->get();
    $datarepair = MachineRepairREQ::select('CLOSE_STATUS')->get();


    $data_line1 = Machine::select('MACHINE_LINE')->where('MACHINE_LINE','=','L1')->count();
    $data_line2 = Machine::select('MACHINE_LINE')->where('MACHINE_LINE','=','L2')->count();
    $data_line3 = Machine::select('MACHINE_LINE')->where('MACHINE_LINE','=','L3')->count();
    $data_line4 = Machine::select('MACHINE_LINE')->where('MACHINE_LINE','=','L4')->count();
    $data_line5 = Machine::select('MACHINE_LINE')->where('MACHINE_LINE','=','L5')->count();
    $data_line6 = Machine::select('MACHINE_LINE')->where('MACHINE_LINE','=','L6')->count();

    $datarepairlist = MachineRepairREQ::where('CLOSE_STATUS','=','9')->orderBy('PRIORITY','DESC')->orderBy('DOC_DATE')->take(9)->get();
    $datarepairline1 = MachineRepairREQ::select('MACHINE_LINE')->where('MACHINE_LINE','=','L1')->count();
    $datarepairline2 = MachineRepairREQ::select('MACHINE_LINE')->where('MACHINE_LINE','=','L2')->count();
    $datarepairline3 = MachineRepairREQ::select('MACHINE_LINE')->where('MACHINE_LINE','=','L3')->count();
    $datarepairline4 = MachineRepairREQ::select('MACHINE_LINE')->where('MACHINE_LINE','=','L4')->count();
    $datarepairline5 = MachineRepairREQ::select('MACHINE_LINE')->where('MACHINE_LINE','=','L5')->count();
    $datarepairline6 = MachineRepairREQ::select('MACHINE_LINE')->where('MACHINE_LINE','=','L6')->count();
    return View('machine/dashboard/dashboard',compact('datarepairlist','dataset','datarepair'
    ,'data_line1','data_line2','data_line3','data_line4','data_line5','data_line6'
    ,'datarepairline1','datarepairline2','datarepairline3','datarepairline4','datarepairline5','datarepairline6'));
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









  //**********************************************************************************************************//
  public function Logout(){
      Auth::logout();
      return Redirect()->route('login')->with('success','User Logout');
  }
}
