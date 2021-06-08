<?php

namespace App\Http\Controllers\MachineaddTable;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
//******************** model ***********************
use App\Models\MachineAddTable\MachineRankTable;
use App\Models\Machine\Machine;
use App\Models\Machine\Protected;
//************** Package form github ***************
use RealRashid\SweetAlert\Facades\Alert;
use App\Exports\MachineExport;
use Maatwebsite\Excel\Facades\Excel;



class MachineRankTableController extends Controller
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

  public function Index($UNID = NULL){

    if ($UNID != NULL) {
      $datafirst   = MachineRankTable::where('UNID',$UNID)->first();
      $datamachine = Machine::where('MACHINE_RANK_CODE',$datafirst->MACHINE_RANK_CODE )->paginate(36);
      $open = '1';
    }else {
      $open = '0';
      $datafirst = NULL;
      $datamachine = NULL;
    }
    $datarank = MachineRankTable::all();
    // dd($datamachine);
    return View('machine/add/machinerank/machineranklist',compact('datarank','open','datamachine','datafirst'));
  }

  public function Store(Request $request){
    $validated = $request->validate([
      'MACHINE_RANK_CODE'           => 'required|unique:PMCS_CMMS_MACHINE_RANK|max:50',
      'MACHINE_RANK_MONTH'          => 'required|integer|min:1|max:12',

      ],
      [
      'MACHINE_RANK_CODE.required'  => 'กรุณาใส่ชื่อกลุ่ม',
      'MACHINE_RANK_CODE.unique'    => 'มีชื่อกลุ่มนี้แล้ว',
      'MACHINE_RANK_MONTH.requireed' => 'กรุณาใส่จำนวนเดือน',
      'MACHINE_RANK_MONTH.integer'   => 'กรุณาใส่จำนวนเดือนเป็นตัวเลข',
      'MACHINE_RANK_MONTH.min'  => 'ใส่จำนวนเดือนต่ำสุดได้ 1',
      'MACHINE_RANK_MONTH.max'  => 'ใส่จำนวนเดือนมากสุดได้ 12',
      ]);
      MachineRankTable::insert([
        'UNID'                 => $this->randUNID('PMCS_CMMS_MACHINE_RANK'),
        'MACHINE_RANK_CODE'    => $request->MACHINE_RANK_CODE,
        'MACHINE_RANK_MONTH'   => $request->MACHINE_RANK_MONTH,
        'MACHINE_RANK_STATUS'  => '9',
        'CREATE_BY'            => Auth::user()->name,
        'CREATE_TIME'          => Carbon::now(),
      ]);
      alert()->success('ลงทะเบียน สำเร็จ')->autoclose('1500');

    return Redirect()->back();
  }

  public function Update(Request $request) {
    $validated = $request->validate([
      'MACHINE_RANK_MONTH'          => 'required|integer|min:1|max:12',
      ],
      [
      'MACHINE_RANK_MONTH.requireed' => 'กรุณาใส่จำนวนเดือน',
      'MACHINE_RANK_MONTH.integer'   => 'กรุณาใส่จำนวนเดือนเป็นตัวเลข',
      'MACHINE_RANK_MONTH.min'  => 'ใส่จำนวนเดือนต่ำสุดได้ 1',
      'MACHINE_RANK_MONTH.max'  => 'ใส่จำนวนเดือนมากสุดได้ 12',
      ]);
      MachineRankTable::where('UNID',$request->UNID)->update([
        'MACHINE_RANK_CODE'    => $request->MACHINE_RANK_CODE,
        'MACHINE_RANK_MONTH'   => $request->MACHINE_RANK_MONTH,
        'MACHINE_RANK_STATUS'  => '9',
        'MODIFY_BY'            => Auth::user()->name,
        'MODIFY_TIME'          => Carbon::now(),
      ]);
      Machine::where('MACHINE_RANK_CODE',$request->MACHINE_RANK_CODE)->update([
        'MACHINE_RANK_CODE'    => $request->MACHINE_RANK_CODE,
        'MACHINE_RANK_MONTH'   => $request->MACHINE_RANK_MONTH,
        'MODIFY_BY'            => Auth::user()->name,
        'MODIFY_TIME'          => Carbon::now(),
      ]);
      alert()->success('อัพเดทรายการสำเร็จ')->autoclose('1500');

  return Redirect()->back();
  }
  public function Delete($UNID) {
      $data = MachineRankTable::where('UNID',$UNID)->first();
      if (Machine::where('MACHINE_RANK_CODE',$data->MACHINE_RANK_CODE)->first() != NULL) {
        alert()->error('ลบไม่สำเร็จมีรายการเชื่อมต่ออยู่')->autoclose('1500');
        return Redirect()->back();
      }else {
        $data->delete();
        alert()->success('ลบสำเร็จ สำเร็จ')->autoclose('1500');
        return Redirect()->back();
      }

  }

}
