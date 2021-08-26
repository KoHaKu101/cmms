<?php

namespace App\Http\Controllers\Machine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
use File;
use Illuminate\Support\Facades\Hash;
use App\Models\Machine\MachineRepairREQ;
use App\Models\Machine\History;
//******************** model ***********************
use App\Models\User;
//************** Package form github ***************
use RealRashid\SweetAlert\Facades\Alert;


class PerMissionController extends Controller
{
  public function __construct(){
    $this->middleware('auth');
    $this->middleware(function ($request, $next) {
        $checkuser = Auth::user();

        if ($checkuser->role != 'admin') {
          alert()->error('ไม่สิทธิ์การเข้าถึง')->autoclose('1500');
          return Redirect()->route('user.homepage');
        }else {
          return $next($request);
        }

    });

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

  //อยู่ใน machine edit
 public function Home(Request $request){


   $DATA_REAIR = MachineRepairREQ::select('UNID','MACHINE_REPORT_NO','CREATE_TIME','DOC_DATE','MACHINE_REPORT_NO')
                                  // ->where('MACHINE_REPORT_NO','like','MRP6408-'.'%')
                                  ->where('CLOSE_DATE','like','2021-08'.'%')->orderBy('CLOSE_DATE')->orderBy('CLOSE_TIME')->get();
   foreach ($DATA_REAIR as $key => $row) {
     $MACHINE_REPORT_NO = 'MRP'.date('y')+43 .date('m').'-'.sprintf('%04d', 1);
     MachineRepairREQ::where('UNID','=',$row->UNID)->update(['MACHINE_REPORT_NO' => $MACHINE_REPORT_NO]);
     History::where('REPAIR_REQ_UNID','=',$row->UNID)->update(['DOC_NO'=>$MACHINE_REPORT_NO]);
     if ($row->MACHINE_REPORT_NO == $MACHINE_REPORT_NO) {
       $REPORT_NO_DATE    = MachineRepairREQ::selectraw('MAX(MACHINE_REPORT_NO) as MACHINE_REPORT_NO')->first();
       $REPORT_NO         = $REPORT_NO_DATE->MACHINE_REPORT_NO;
       $EXPLOT            = str_replace('MRP'.date('y')+43 .date('m').'-','',$REPORT_NO)+1;
       $MACHINE_REPORT_NO = 'MRP'.date('y')+43 .date('m'). sprintf('-%04d', $EXPLOT);
     }

     if ($row->UNID != $DATA_REAIR[0]->UNID) {
       MachineRepairREQ::where('UNID','=',$row->UNID)->update(['MACHINE_REPORT_NO' => $MACHINE_REPORT_NO]);
       History::where('REPAIR_REQ_UNID','=',$row->UNID)->update(['DOC_NO'=>$MACHINE_REPORT_NO]);
     }

   }

   $DATA_USER = User::orderby('role')->get();
   $MACHINEREPAIRREQ = MachineRepairREQ::orderBy('MACHINE_REPORT_NO')->get();
   $History          = History::where('REPAIR_DOC_NO','!=','')->get();
   return View('machine.setting.permission.list',compact('DATA_USER','MACHINEREPAIRREQ','History'));
 }
 public function Store(Request $request){
   $role = $request->role;

   $check_role = array("user","manager_ma","manager_pd","admin");
   if (!in_array($role,$check_role)) {
     alert()->error('เกิดข้อผิดพลาด')->autoclose('1000');
     return redirect()->back();
   }
   $time = Carbon::now();
   User::insert([
     'name'       => $request->name,
     'email'      => $request->email,
     'password'   => Hash::make($request->password),
     'role_v2'    => $role,
     'created_at' => $time,
     'updated_at' => $time,
   ]);
   alert()->success('เพิ่มผู้ใช้งานสำเร็จ')->autoclose('1000');
   return redirect()->back();
 }
 public function Update(Request $request){

   $role = $request->role;
   $check_role = array("user","manager_ma","manager_pd","admin");
   if (!in_array($role,$check_role)) {
     alert()->error('เกิดข้อผิดพลาด')->autoclose('1000');
     return redirect()->back();
   }
   $time = Carbon::now();
   $USER = User::where('id','=',$request->id)->first();

   if (isset($request->password)) {
    $password = isset($request->password) ? Hash::make($request->password) : $USER->password;
    $USER->update([
      'name'      => $request->name,
      'email'     => $request->email,
      'password'  => $password,
      'role_v2'   => $role,
      'updated_at'=> $time,
    ]);
  }else {
    $USER->update([
      'name'      => $request->name,
      'email'     => $request->email,
      'role_v2'   => $role,
      'updated_at'=> $time,
    ]);
  }
   alert()->success('เพิ่มผู้ใช้งานสำเร็จ')->autoclose('1000');
   return redirect()->back();
 }
 public function Confirm(Request $request){
   $data = User::where('role','=','admin')->first();
   $check_password = Hash::check($request->password,$data->password);

   return Response()->json(['pass'=>$check_password]);
 }
 public function Delete(Request $request){

   User::where('id','=',$request->id)->delete();
   alert()->success('ลบรายการสำเร็จ')->autoclose('1000');
   return redirect()->back();
 }




  }
