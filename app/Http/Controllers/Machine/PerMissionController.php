<?php

namespace App\Http\Controllers\Machine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
use File;
use Illuminate\Support\Facades\Hash;
//******************** model ***********************

use App\Models\Machine\Machine;
use App\Models\Machine\MachineCheckSheet;
use App\Models\Machine\Uploadimg;
use App\Models\User;
//***************** Controller ************************
use App\Http\Controllers\Machine\UploadImgController;

//************** Package form github ***************
use RealRashid\SweetAlert\Facades\Alert;
// use Intervention\Image\ImageManagerStatic as Image;


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
     // 'id' => $id,
     'name' => $request->name,
     'email' => $request->email,
     'password' => Hash::make($request->password),
     'role_v2' => $role,
     'created_at'=> $time,
     'updated_at'=> $time,
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
   $data = User::where('id','=',$request->id)->first();

   if (isset($request->password)) {
    $password = isset($request->password) ? Hash::make($request->password) : $data->password;
    $data->update([
      'name' => $request->name,
      'email' => $request->email,
      'password' => $password,
      'role_v2' => $role,
      'updated_at'=> $time,
    ]);
  }else {
    $data->update([
      'name' => $request->name,
      'email' => $request->email,
      'role_v2' => $role,
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
