<?php

namespace App\Http\Controllers\Machine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Cookie;
use Response;

//******************** model ***********************
use App\Models\Machine\MachineRepairREQ;
use App\Models\Machine\EMPName;

//************** Package form github ***************

class ConfirmPageController extends Controller
{

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
  public function repair(Request $request) {

    $UNID         = $request->REPAIR_UNID;
    $REPAIR       = MachineRepairREQ::select('*')->selectraw('dbo.decode_utf8(MACHINE_NAME) as MACHINE_NAME_TH
                                                              ,dbo.decode_utf8(EMP_NAME) as EMP_NAME_TH
                                                              ,dbo.decode_utf8(INSPECTION_NAME) as INSPECTION_NAME_TH')
                                    ->where('UNID','=',$UNID)->first();
    $DATA_EMPNAME = EMPName::select('UNID','EMP_CODE')->selectraw("dbo.decode_utf8(EMP_NAME) as EMP_NAME_TH")
                            ->where('EMP_STATUS','=','9')->orderBy('EMP_CODE')->get();
    if (isset($REPAIR->INSPECTION_CODE) && $REPAIR->INSPECTION_CODE != '') {
      $ICON = $request->STATUS == 'SUCCESS' ? 'success'   : 'warning';
      $TEXT = $request->STATUS == 'SUCCESS' ? 'บันทึกสำเร็จ' : 'มีผู้รับงานแล้ว';
      $request->session()->put('closewindow',true);
      return View('confirmpage.confirmrepair',compact('REPAIR','DATA_EMPNAME','TEXT','ICON'));
    }
    $request->session()->put('closewindow',false);
    return View('confirmpage.confirmrepair',compact('REPAIR','DATA_EMPNAME'));
  }
  public function SaveConfirm(Request $request){

    $UNID = $request->UNID;
    $EMP_UNID = $request->EMP_UNID;
    if ($EMP_UNID == '' && $UNID == '' ) {
      alert()->warning('กรุณาลองใหม่ หรือติดต่อ แอดมิน')->autoclose(1500);
      return Redirect()->back();
    }
    $DATE_NOW = date('Y-m-d');
    $EMP = EMPName::where('UNID','=',$EMP_UNID)->first();


    MachineRepairREQ::where('UNID','=',$UNID)->update([
      'INSPECTION_CODE'        =>  $EMP->EMP_CODE
      ,'INSPECTION_NAME'       =>  $EMP->EMP_NAME
      ,'REC_WORK_DATE'         =>  $DATE_NOW
      ,'WORK_STEP'             =>  'WORK_STEP_0'
      ,'STATUS_NOTIFY'         =>  1
    ]);
    alert()->success('บันทึกสำเร็จ')->autoclose(1500);
    return Redirect('/confirm/repair?REPAIR_UNID='.$UNID.'&STATUS=SUCCESS');
  }

}
