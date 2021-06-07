<?php

namespace App\Http\Controllers\Machine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Middleware\VerifyCsrfToken;
use Carbon\Carbon;
use Auth;
use Cookie;
//******************** model ***********************
use App\Models\MachineAddTable\SelectMainRepair;
use App\Models\MachineAddTable\SelectSubRepair;
use App\Models\Machine\Machine;
use App\Models\Machine\MachineEMP;
use App\Models\Machine\MachineRepairREQ;
//************** Package form github ***************
use App\Exports\MachineExport;
use Maatwebsite\Excel\Facades\Excel;

class MachineRepairController extends Controller
{
  public function __construct(){
    $cookie_array = array('0' => 'empcode','1' => 'selectmainrepair','2' => 'selectsubrepair','3' => 'priority' );
    foreach ($cookie_array as $index => $row) {
      Cookie::queue(Cookie::forget($row));
    }
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

  public function Index(Request $request){

    $SEARCH = isset($request->SEARCH) ? '%'.$request->SEARCH.'%' : '%';

    $dataset = MachineRepairREQ::where(function ($query) use ($SEARCH) {
              $query->where('MACHINE_CODE', 'like', $SEARCH)
                  ->orWhere('DOC_NO', 'like', $SEARCH);})
                            ->orderby('CLOSE_STATUS','DESC')
                            ->orderBy('DOC_DATE','DESC')
                            ->paginate(10);
   $SEARCH = str_replace('%','',$SEARCH);
    return View('machine/repair/repairlist',compact('dataset','SEARCH'));
  }

  public function PrepareSearch(Request $request){

    $search = $request->search;
    $machine = NULL;
    if (isset($search)) {
      $MACHINE_CODE = '%'.$search.'%';
      $machine = Machine::where('MACHINE_CODE','like',$MACHINE_CODE)->get();
    }
    return View('machine/repair/search',compact('machine'));
  }

  public function Create($UNID){

      $dataset = SelectMainRepair::where('STATUS','=','9')->get();
      $data_emp = MachineEMP::select('EMP_CODE','EMP_NAME')->selectraw('dbo.decode_utf8(EMP_NAME) as EMP_NAME')->where('EMP_STATUS','=','0')->where('REF_UNID','=',$UNID)->get();
      $datamachine = Machine::where('UNID','=',$UNID)->first();

    return View('machine/repair/formreq',compact('dataset','datamachine','data_emp'));
  }
  public function Store(Request $request,$MACHINE_UNID){
      $CLOSE_STATUS = '9';
      $MACHINE_UNID = $MACHINE_UNID;

      $EMP_CODE = $request->cookie('empcode');
      $SELECT_MAIN_EPAIR_UNID = $request->cookie('selectmainrepair');
      $SELECT_SUB_EPAIR_UNID = $request->cookie('selectsubrepair');
      $PRIORITY = $request->cookie('priority');
      $DATA_MACHINE = Machine::where('UNID','=',$MACHINE_UNID)->first();
      $DATA_SELECTMACHINEREPAIR = SelectMainRepair::where('UNID','=',$SELECT_MAIN_EPAIR_UNID)->first();
      $DATA_SELECTSUBREPAIR = SelectSubRepair::where('UNID','=',$SELECT_SUB_EPAIR_UNID)->first();
      $DATA_EMP = MachineEMP::where('EMP_CODE','=',$EMP_CODE)->where('REF_UNID','=',$MACHINE_UNID)->first();
      $UNID = $this->randUNID('PMCS_CMMS_REPAIR_REQ');

      $rowcount = MachineRepairREQ::selectraw('max(DOC_NO)DOC_NO,max(DOC_DATE)DOC_DATE')->get();

      $DOC_NO_RESET = date('Y-m', strtotime($rowcount[0]->DOC_DATE));


      $DOC_YY = date('y')+543;
      $DOC_NO = $rowcount[0]->DOC_NO;
      dd($rowcount->DOC_NO+1);
      if ($DOC_NO_RESET > date('Y-m')) {
        $DOC_NO = 'RE'.$DOC_YY.date('m').'-'. 0001;
      }else {
        $DOC_NO = $rowcount[0]->DOC_NO+1;
      }


      MachineRepairREQ::insert([
        'UNID'=> $UNID
        ,'MACHINE_UNID'          => $DATA_MACHINE->UNID
        ,'MACHINE_CODE'          => $DATA_MACHINE->MACHINE_CODE
        ,'MACHINE_LINE'          => $DATA_MACHINE->MACHINE_LINE
        ,'MACHINE_NAME'          => $DATA_MACHINE->MACHINE_NAME
        ,'MACHINE_STATUS'        => $DATA_SELECTSUBREPAIR->STATUS_MACHINE
        ,'REPAIR_MAINSELECT_UNID'=> $DATA_SELECTMACHINEREPAIR->UNID
        ,'REPAIR_MAINSELECT_NAME'=> $DATA_SELECTMACHINEREPAIR->REPAIR_MAINSELECT_NAME
        ,'REPAIR_SUBSELECT_UNID' => $DATA_SELECTSUBREPAIR->UNID
        ,'REPAIR_SUBSELECT_NAME' => $DATA_SELECTSUBREPAIR->REPAIR_SUBSELECT_NAME
        ,'EMP_UNID'              => $DATA_EMP->UNID
        ,'EMP_CODE'              => $DATA_EMP->EMP_CODE
        ,'EMP_NAME'              => $DATA_EMP->EMP_NAME
        ,'PRIORITY'              => $PRIORITY
        ,'DOC_NO'                => $DOC_NO
        ,'DOC_DATE'              => date('Y-m-d')
        ,'REPAIR_REQ_TIME'       => date("h:i:s")
        ,'CLOSE_STATUS'          => $CLOSE_STATUS
        ,'CLOSE_BY'              => ''
        ,'CREATE_BY'             =>Auth::user()->name
        ,'CREATE_TIME'           =>Carbon::now()
        ,'MODIFY_BY'             => Auth::user()->name
        ,'MODIFY_TIME'           => Carbon::now()

      ]);
      $cookie_array = array('0' => 'empcode','1' => 'selectmainrepair','2' => 'selectsubrepair','3' => 'priority' );
      foreach ($cookie_array as $index => $row) {
        Cookie::queue(Cookie::forget($row));
      }
      return redirect()->route('repair.list');

  }
  public function Edit($UNID) {
    $data_repairreq = MachineRepairREQ::select('*')->selectraw('dbo.decode_utf8(EMP_NAME) as EMP_NAME')
                                                   ->where('UNID','=',$UNID)->first();
    $MACHINE_UNID = $data_repairreq->MACHINE_UNID;
    $dataset = SelectMainRepair::where('STATUS','=','9')->get();
    $data_emp = MachineEMP::select('EMP_CODE','EMP_NAME')->selectraw('dbo.decode_utf8(EMP_NAME) as EMP_NAME')
                          ->where('EMP_STATUS','=','0')->where('REF_UNID','=',$MACHINE_UNID)->get();
    $datamachine = Machine::where('UNID','=',$MACHINE_UNID)->first();
    $data_selectsubrepair = SelectSubRepair::where('REPAIR_MAINSELECT_UNID','=',$data_repairreq->REPAIR_MAINSELECT_UNID)->get();
    return view('machine/repair/edit',compact('data_repairreq','dataset','datamachine','data_emp','data_selectsubrepair'));

  }
  public function Update(Request $request,$UNID){

      if(!empty($request->MACHINE_NOTE)){
        $MACHINE_NOTE = implode(",",$request->MACHINE_NOTE);

      }elseif(empty($request->MACHINE_NOTE)) {

        $MACHINE_NOTE = $request->MACHINE_NOTE;

      }
    $request->CLOSE_STATUS = '9';
    $data_set = MachineRepairREQ::where('UNID','=',$UNID)->update([
          'MACHINE_DOCNO'         => $request->MACHINE_DOCNO,
          'MACHINE_DOCDATE'       => $request->MACHINE_DOCDATE,
          'MACHINE_TIME'          => $request->MACHINE_TIME,
          'MACHINE_NUMBER'        => $request->MACHINE_NUMBER,
          'MACHINE_CODE'          => $request->MACHINE_CODE,
          'MACHINE_NAME'          => $request->MACHINE_NAME,
          'MACHINE_LOCATION'      => $request->MACHINE_LOCATION,
          'MACHINE_CAUSE'         => $request->MACHINE_CAUSE,
          'MACHINE_CAUSE_DT'      => $request->MACHINE_CAUSE_DT,
          'MACHINE_BY_REPAIR'     => $request->MACHINE_BY_REPAIR,
          'REPAIR_DOCDATE'        => $request->REPAIR_DOCDATE,
          'REPAIR_TIME'           => $request->REPAIR_TIME,
          'MACHINE_INSPECTION'    => $request->MACHINE_INSPECTION,
          'MACHINE_BECAUSE'       => $request->MACHINE_BECAUSE,
          'MACHINE_NOTE'          => $MACHINE_NOTE,
          'STATUS'                => $request->STATUS,
          'POSTED'                => $request->POSTED,
          'TODAY_DOC'             => $request->TODAY_DOC,
          'TODAY_YY'              => $request->TODAY_YY,
          'TODAY_MM'              => $request->TODAY_MM,
          'TODAY_MAX'             => $request->TODAY_MAX,
          'EMP_CODE'              => $request->EMP_CODE,
          'EMP_NAME'              => $request->EMP_NAME,
          'SECTION_CODE'          => $request->SECTION_CODE,
          'MACHINE_TYPE'          => $request->MACHINE_TYPE,
          'BU_JOB_NAME'           => $request->BU_JOB_NAME,
          'BU_TYPE'               => $request->BU_TYPE,
          'BU_DESCRIPTION'        => $request->BU_DESCRIPTION,
          'BU_DUEDATE'            => $request->BU_DUEDATE,
          'RP_CODE'               => $request->RP_CODE,
          'EG_DESC'               => $request->EG_DESC,
          'EG_TYYPE'              => $request->EG_TYYPE,
          'EX_DESC1'              => $request->EX_DESC1,
          'RECORD_STATUS'         => $request->RECORD_STATUS,
          'TIMESTAMP'             => $request->TIMESTAMP,
          'CLOSE_STATUS'          => $request->CLOSE_STATUS,
          'CM_STARTDATE'          => $request->CM_STARTDATE,
          'CM_ENDDATE'            => $request->CM_ENDDATE,
          'CLOSE_BY'              => $request->CLOSE_BY,
          'CLOSE_TIME'            => $request->CLOSE_TIME,
          'MODIFY_BY'             => Auth::user()->name,
          'MODIFY_TIME'           => Carbon::now(),
      ]);
            return Redirect()->route('repair.edit',[$UNID])->with('success','อัพเดทรายการ สำเร็จ');
          }
  public function Delete($UNID){
            $CLOSE_STATUS = '1';
              $data_set = MachineRepairREQ::where('UNID',$UNID)->update([
                      'CLOSE_STATUS'          => $CLOSE_STATUS,

                'MODIFY_BY'            => Auth::user()->name,
                'MODIFY_TIME'          => Carbon::now(),
                ]);
              return Redirect()->back()-> with('success','ปิดเอกสารเสำเร็จ ');
          }
  public function SelectRepairDetail(Request $request){

    $UNID = $request->UNID;
    $data_selectsubrepair = SelectSubRepair::where('REPAIR_MAINSELECT_UNID','=',$UNID)->get();
    $html = '<div class="row">';
    foreach ($data_selectsubrepair as $index => $data_row) {
      $html.='<div class="col-sm-6 col-md-3">
        <a  onclick="selectrepairdetail(this)"  data-unid="'.$data_row->UNID.'" data-name="'.$data_row->REPAIR_SUBSELECT_NAME.'"style="cursor:pointer">
        <div class="card card-stats card-primary card-round">
          <div class="card-body">
            <div class="row">
              <div class="col-5">
                <div class="icon-big text-center">
                  <i class="fas fa-wrench"></i>
                </div>
              </div>
              <div class="col-7 col-stats">
                <div class="numbers">
                  <p class="card-category">'.$data_row->REPAIR_SUBSELECT_NAME.'</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </a >
      </div>';
    }
    $html.='</div>
    <div class="card-action text-center">
      <button type="button" class="btn btn-warning mx-1 my-1"
      onclick="previousstep(this)"
      data-step="step1"><i class="fas fa-arrow-alt-circle-left mr-1"></i>Previous</button>
      <button type="button" class="btn btn-primary mx-1 my-1"
      onclick="nextstep(this)"
      data-step="step3">Next <i class="fas fa-arrow-alt-circle-right ml-1"></i></button>
    </div>';
    return Response()->json(['html' => $html]);
  }
}
