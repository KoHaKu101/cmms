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
   $DATA_USER = User::orderby('role')->get();

   $DATA_REAIR = MachineRepairREQ::select('MACHINE_REPORT_NO','CREATE_TIME')->where('MACHINE_REPORT_NO','like','MRP6408-'.'%')->orderBy('CLOSE_DATE')->orderBy('CLOSE_TIME')->get();
   dd($DATA_REAIR);
   // $DATA_MACHINEREPAIRREQ = MachineRepairREQ::select('DOC_DATE','MACHINE_REPORT_NO')
   //                                           ->whereRaw('MACHINE_REPORT_NO = (SELECT MAX(MACHINE_REPORT_NO) FROM [PMCS_CMMS_REPAIR_REQ]) ')
   //                                           ->where('DOC_YEAR',date('Y'))->where('DOC_MONTH',date('m'))->first();


   // if ($DATA_MACHINEREPAIRREQ != "") {
   //     $DOC_DATE          = $DATA_MACHINEREPAIRREQ->DOC_DATE;
   //     $REPORT_NO         = $DATA_MACHINEREPAIRREQ->MACHINE_REPORT_NO;
   //     $EXPLOT            = str_replace('MRP'.Carbon::parse($DOC_DATE)->addyears(543)->format('ym').'-','',$REPORT_NO)+1;
   //     $MACHINE_REPORT_NO = 'MRP' . Carbon::parse($DOC_DATE)->addyears(543)->format('ym'). sprintf('-%04d', $EXPLOT);
   // }


   for ($i=1; $i < count($DATA_REAIR)+1; $i++) {
     $MACHINE_REPORT_NO = 'MRP'.date('y')+43 .date('m').'-'.sprintf('%04d', $i++);
     MachineRepairREQ::where('MACHINE_REPORT_NO','=','MRP6408-0001')->update(['MACHINE_REPORT_NO' => $MACHINE_REPORT_NO]);
   }
   // foreach ($DATA_REAIR as $key => $row) {
   //
   //   if ($row->MACHINE_REPORT_NO == $MACHINE_REPORT_NO) {
   //     $row->MACHINE_REPORT_NO
   //   }
   //   dd($MACHINE_REPORT_NO);
   // }
   // dd(count($DATA_REAIR));
   return View('machine.setting.permission.list',compact('DATA_USER'));
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
