<?php

namespace App\Http\Controllers\Machine;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
use File;
use Illuminate\Http\Request;
//******************** model ***********************
use App\Models\Machine\EMPName;
use App\Models\Machine\PositionEMP;
use App\Models\Machine\MachineEMP;
use App\Models\Machine\MachineLine;
//************** Package form github ***************
use App\Exports\MachineExport;
use Maatwebsite\Excel\Facades\Excel;



class PositionEmpController extends Controller
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

  public function List(Request $request,$POSITION_CODE = NULL){
    $DATA_POSITION = PositionEMP::select('*')->selectRaw('dbo.decode_utf8(EMP_POSITION_NAME) as EMP_POSITION_NAME')->get();
    $COUNT_EMP = EMPName::select('POSITION')->get();
    $DATA_EMP = NULL ;
    if ($POSITION_CODE != NULL) {
      $DATA_EMP = EMPName::select('EMP_CODE')->selectRaw('dbo.decode_utf8(EMP_NAME) as EMP_NAME')
      ->where('POSITION','=',$POSITION_CODE)->get();
    }

    return View('machine/postionemp/index',compact('DATA_POSITION','COUNT_EMP','DATA_EMP'));
  }
  public function Save(Request $request){
    $INDEX = PositionEMP::selectraw("max(EMP_POSITION_CODE)count
                                    ,max(EMP_POSITION_INDEX)countindex
                                    ,dbo.encode_utf8('$request->EMP_POSITION_NAME') as EMP_POSITION_NAME
                                    ,dbo.encode_utf8('$request->REMARK') as REMARK")->first();
    $EMP_POSITION_INDEX = 1;
    $EMP_POSITION_CODE  = 1;
    if ($INDEX->countindex > 0 && $EMP_POSITION_CODE > 0) {
      $EMP_POSITION_INDEX = ($INDEX->countindex)+1;
      $EMP_POSITION_CODE  = ($INDEX->count)+1;
    }
    $STATUS = isset($request->STATUS) ? 9 : 1 ;
    $REMARK = $INDEX->REMARK != '' ? $INDEX->REMARK : '';
    PositionEMP::insert([
      'UNID'=> $this->randUNID('PMCS_EMP_POSITION')
      ,'EMP_POSITION_INDEX'   => $EMP_POSITION_INDEX
      ,'EMP_POSITION_CODE'    => $EMP_POSITION_CODE
      ,'EMP_POSITION_NAME'    => $INDEX->EMP_POSITION_NAME
      ,'EMP_POSITION_LIMIT'   => $request->EMP_POSITION_LIMIT
      ,'REMARK'               => $REMARK
      ,'STATUS'               => $STATUS
      ,'CREATE_BY'            => Auth  ::user()->name
      ,'CREATE_TIME'          => Carbon::now()
      ,'MODIFY_BY'            => Auth::user()->name
      ,'MODIFY_TIME'          => Carbon::now()
    ]);
    alert()->success('บันทึกสำเร็จ')->autoclose('1000');
    return redirect()->back();

  }

  public function Update(Request $request){

    $INDEX = PositionEMP::selectraw("max(EMP_POSITION_INDEX)count
                                    ,dbo.encode_utf8('$request->EMP_POSITION_NAME') as EMP_POSITION_NAME
                                    ,dbo.encode_utf8('$request->REMARK') as REMARK")->first();
    $STATUS = isset($request->STATUS) ? 9 : 1 ;
    $REMARK = $INDEX->REMARK != '' ? $INDEX->REMARK : '';
    PositionEMP::where('UNID','=',$request->UNID)->update([
      'EMP_POSITION_NAME'    => $INDEX->EMP_POSITION_NAME
      ,'EMP_POSITION_LIMIT'   => $request->EMP_POSITION_LIMIT
      ,'REMARK'               => $REMARK
      ,'STATUS'               => $STATUS
      ,'MODIFY_BY'            => Auth::user()->name
      ,'MODIFY_TIME'          => Carbon::now()
    ]);
    alert()->success('อัพเดทรายการสำเร็จ')->autoclose('1000');
    return redirect()->back();
  }
  public function Delete(Request $request){
    $data_first = PositionEMP::where('UNID','=',$request->UNID)->first();


    EMPName::where('POSITION','=',$data_first->EMP_POSITION_CODE)->update([
      'POSITION' => "" ,
    ]);
    $data_first->delete();

    $data_get   = $data_first->get();

    foreach ($data_get as $index => $row) {
      PositionEMP::where('UNID','=',$row->UNID)->update([
        'EMP_POSITION_INDEX' => $index+1,
      ]);
    }
    alert()->success('ลบรายการสำเร็จ')->autoclose('1000');
    return Redirect()->back();
  }
}
