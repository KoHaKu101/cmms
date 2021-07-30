<?php

namespace App\Http\Controllers\MachineaddTable;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
//******************** model ***********************
use App\Models\MachineAddTable\MachineStatusTable;
use App\Models\Machine\Protected;
//************** Package form github ***************


class MachineStatusTableController extends Controller
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

  public function Index(){
   
    $dataset = MachineStatusTable::orderBy('STATUS_CODE')->paginate(10);
    $datacount = MachineStatusTable::selectraw('max(STATUS_CODE)count')->first();
    return View('machine/add/machinestatus/machinestatuslist',compact('dataset','datacount'));
  }

  public function Store(Request $request){
    // dd( $request);
    $validated = $request->validate([
      'STATUS_CODE'           => 'required|unique:PMCS_CMMS_MACHINE_STATUS|max:50',
      'STATUS_NAME'           => 'required|unique:PMCS_CMMS_MACHINE_STATUS|max:200',
      ],
      [
      'STATUS_CODE.required'  => 'กรุณราใส่รหัสสถานะเครื่องจักร',
      'STATUS_CODE.unique'    => 'มีรหัสสถานะเครื่องจักร',
      'STATUS_NAME.required'  => 'กรุณาใส่สถานะเครื่องจักร',
      'STATUS_NAME.unique'    => 'มีสถานะเครื่องจักรนี้แล้ว'
      ]);
    $STATUS = isset($request->STATUS) ? '9' : '1' ;
    $datacount = MachineStatusTable::selectraw('max(STATUS_CODE)count')->first();
    // $STATUS_CODE = ($datacount->count)+1;
    $STATUS_CODE = $request->STATUS_CODE;
    MachineStatusTable::insert([
      'STATUS_CODE'     => $STATUS_CODE,
      'STATUS_NAME'     => $request->STATUS_NAME,
      'STATUS'          => $STATUS,
      'CREATE_BY'       => Auth::user()->name,
      'CREATE_TIME'     => Carbon::now(),
      'MODIFY_BY'         => Auth::user()->name,
      'MODIFY_TIME'       => Carbon::now(),
      'UNID'            => $this->randUNID('PMCS_CMMS_MACHINE_STATUS'),
    ]);
    alert()->success('ลงทะเบียน สำเร็จ')->autoclose('1500');
    return Redirect()->back();
  }

public function Update(Request $request,$UNID) {
  $STATUS = isset($request->STATUS) ? '9' : '1' ;
  $data_set = MachineStatusTable::where('UNID',$UNID)->update([
    'STATUS_CODE'     => $request->STATUS_CODE,
    'STATUS_NAME'     => $request->STATUS_NAME,
    'STATUS'          => $STATUS,
    'MODIFY_BY'         => Auth::user()->name,
    'MODIFY_TIME'       => Carbon::now(),

  ]);
  alert()->success('อัพเดทรายการสำเร็จ')->autoclose('1500');

  return Redirect()->back();

}  public function Delete($UNID) {


    $dataset = MachineStatusTable::where('UNID','=',$UNID)->delete();
    alert()->success('ลบสำเร็จ สำเร็จ')->autoclose('1500');

    return Redirect()->back();
}
}
