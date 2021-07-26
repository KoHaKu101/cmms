<?php

namespace App\Http\Controllers\MachineaddTable;

//******************** laravel *********************
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
//******************** model ***********************
use App\Models\MachineAddTable\SelectMainRepair;
use App\Models\MachineAddTable\SelectSubRepair;
use App\Models\MachineAddTable\MachineStatusTable;
use App\Models\Machine\Protected;
//******************** github **********************
use RealRashid\SweetAlert\Facades\Alert;



class MachineRepairTableController extends Controller
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
    $CHECK_UNID = isset($UNID) ? $UNID : '';
    $DATA_SELECTMAINREPAIR  = SelectMainRepair::paginate(10);
    $MACHINESTATUS          = MachineStatusTable::where('STATUS','=','9')->get();
    $SELECTMAINREPAIR_FIRST = '';
    $DATA_SELECTSUBREPAIR   = '';
    if ($CHECK_UNID != '' ) {
      $SELECTMAINREPAIR_FIRST = SelectMainRepair::where('UNID','=',$CHECK_UNID)->first();
      $DATA_SELECTSUBREPAIR   = SelectSubRepair::where('REPAIR_MAINSELECT_UNID','=',$CHECK_UNID)->paginate(10);
    }
    $OPEN = isset($UNID) ? 1 : 0;
    return View('machine/add/repair/repairlist',compact('DATA_SELECTMAINREPAIR','DATA_SELECTSUBREPAIR','MACHINESTATUS'
    ,'OPEN','SELECTMAINREPAIR_FIRST'));
  }
  public function Save(Request $request){
    $STATUS = isset($request->STATUS) ? $request->STATUS : 1;
    $REMARK = isset($request->REMARK) ? $request->REMARK : '';
    $count  = 1;
    $rowcount = SelectMainRepair::selectraw('max(REPAIR_MAINSELECT_INDEX)count')->first();
    if ($rowcount->count() > 0 ) {
      $count = $rowcount->count()+1;
    }
   SelectMainRepair::insert([
      'UNID'                    => $this->randUNID('PMCS_CMMS_SELECT_MAIN_REPAIR')
      ,'REPAIR_MAINSELECT_NAME' => $request->REPAIR_MAINSELECT_NAME
      ,'REPAIR_MAINSELECT_INDEX'=> $count
      ,'REMARK'                 => $REMARK
      ,'STATUS'                 => $STATUS
      ,'CREATE_BY'              => Auth::user()->name
      ,'CREATE_TIME'            => Carbon::now()
      ,'MODIFY_BY'              => Auth::user()->name
      ,'MODIFY_TIME'            => Carbon::now()
    ]);
    alert()->success('success','บันทึกสำเร็จ')->autoClose($milliseconds = 1000);
    return Redirect()->back();
  }

  public function Update(Request $request) {
    $UNID   = $request->REPAIR_MAINSELECT_UNID;
    $STATUS = isset($request->STATUS) ? $request->STATUS : 1;
    $REMARK = isset($request->REMARK) ? $request->REMARK : '';

      SelectMainRepair::where('UNID',$UNID)->update([
         'REPAIR_MAINSELECT_NAME'=> $request->REPAIR_MAINSELECT_NAME
        ,'REMARK'                => $REMARK
        ,'STATUS'                => $STATUS
        ,'MODIFY_BY'             => Auth::user()->name
        ,'MODIFY_TIME'           => Carbon::now()
      ]);
      SelectSubRepair::where('REPAIR_MAINSELECT_UNID','=',$UNID)->update([
        'STATUS'=> $STATUS
      ]);
      alert()->success('อัพเดทรายการสำเร็จ')->autoclose('1500');
    return Redirect()->back();
  }

  public function Delete(Request $request) {

    $UNID = $request->UNID;
    $row_count = SelectSubRepair::where('REPAIR_MAINSELECT_UNID','=',$UNID)->count();
    if ($row_count > 0) {
      return Response()->json(['res'=>false,'title'=> 'ไม่สามารถลบได้ มีข้อมูลที่ใช้งานอยู่','icon'=>'error']);
    }
      SelectMainRepair::where('UNID','=',$UNID)->delete();

    return Response()->json(['res'=>true,'title'=> 'ลบสำเร็จ สำเร็จ','icon'=>'success']);
  }

  public function SubSave(Request $request){
    $STATUS = isset($request->STATUS) ? $request->STATUS : 1;
    $REMARK = isset($request->REMARK) ? $request->REMARK : '';
    $count  = 1;
    $MAINSELECT_UNIDREF = $request->REPAIR_MAINSELECT_UNIDREF;
    $rowcount = SelectSubRepair::selectraw('max(REPAIR_SUBSELECT_INDEX)count')->first();
    if ($rowcount->count() > 0 ) {
      $count = $rowcount->count()+1;
    }
   SelectSubRepair::insert([
      'UNID'                    =>$this->randUNID('PMCS_CMMS_SELECT_SUB_REPAIR')
      ,'REPAIR_MAINSELECT_UNID' => $MAINSELECT_UNIDREF
      ,'REPAIR_SUBSELECT_NAME'  => $request->REPAIR_SUBSELECT_NAME
      ,'REPAIR_SUBSELECT_INDEX' => $count
      ,'REMARK'                 => $REMARK
      ,'STATUS'                 => $STATUS
      ,'STATUS_MACHINE'         => $request->STATUS_MACHINE
      ,'CREATE_BY'              => Auth::user()->name
      ,'CREATE_TIME'            => Carbon::now()
      ,'MODIFY_BY'              => Auth::user()->name
      ,'MODIFY_TIME'            => Carbon::now()
    ]);
      alert()->success('บันทึกสำเร็จ')->autoclose('1500');
    return Redirect()->back();
  }

  public function SubUpdate(Request $request) {

    $UNID = $request->REPAIR_SUBSELECT_UNIDREF;
    $STATUS = isset($request->STATUS) ? $request->STATUS : 1;

      SelectSubRepair::where('UNID',$UNID)->update([
        'REPAIR_SUBSELECT_NAME'=> $request->REPAIR_SUBSELECT_NAME
        ,'STATUS'              => $STATUS
        ,'STATUS_MACHINE'      => $request->STATUS_MACHINE
        ,'MODIFY_BY'           => Auth::user()->name
        ,'MODIFY_TIME'         => Carbon::now()
    ]);
    alert()->success('อัพเดทรายการสำเร็จ')->autoclose('1500');
    return Redirect()->back();
  }

  public function SubDelete(Request $request) {

    $UNID = $request->UNID;
      SelectSubRepair::where('UNID','=',$UNID)->delete();
    return Response()->json(['res'=>true,'title'=> 'ลบสำเร็จ สำเร็จ','icon'=>'success']);
  }
}
