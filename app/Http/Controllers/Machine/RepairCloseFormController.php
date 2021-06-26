<?php

namespace App\Http\Controllers\Machine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Middleware\VerifyCsrfToken;
use Carbon\Carbon;
use Auth;
use Cookie;
use Gate;
//******************** model ***********************
use App\Models\MachineAddTable\SelectMainRepair;
use App\Models\MachineAddTable\SelectSubRepair;
use App\Models\Machine\Machine;
use App\Models\Machine\EMPName;
use App\Models\Machine\SparePart;
use App\Models\Machine\RepairWorker;
use App\Models\Machine\RepairSparepart;

use App\Models\Machine\MachineRepairREQ;
//************** Package form github ***************

class RepairCloseFormController extends Controller
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

  public function EMPCallAjax(Request $request){
    $REPAIR_REQ_UNID = isset($request->REPAIR_REQ_UNID) ? $request->REPAIR_REQ_UNID : '';
    $DATA_EMPNAME = EMPName::select('*')->selectraw("dbo.decode_utf8(EMP_NAME) as EMP_NAME_TH")
                                        ->where('EMP_STATUS','=','9')->orderBy('EMP_CODE')->get();
    $DATA_SPAREPART = SparePart::where('STATUS','=','9')->orderBy('SPAREPART_NAME')->get();
    //*************************** select worker *******************************************//
    $html_select = '<select class="form-control form-control-sm col-9 REC_WORKER" id="REC_WORKER" name="REC_WORKER">
                      <option value> กรุณาเลือก </option>';
                      foreach ($DATA_EMPNAME as $index => $row){
                        $html_select.= '<option value="'.$row->UNID.'">'.$row->EMP_CODE." ".$row->EMP_NAME_TH.'</option>';
                      }
    $html_select.='</select>';
    //*************************** select sparepart **************************************//
    $html_sparepart = '<select class="form-control form-control-sm col-9 REC_WORKER_NAME" id="REC_WORKER_NAME" name="REC_WORKER_NAME" required>
      <option value=""> กรุณาเลือก </option>';
      foreach ($DATA_SPAREPART as $index => $row_sparepart){
        $html_sparepart.= '<option value="'. $row_sparepart->UNID .'" id="'. $row_sparepart->UNID .'"
                            data-sparepartcode="'.$row_sparepart->SPAREPART_CODE.'"
                            data-sparepartname="'.$row_sparepart->SPAREPART_NAME.'"
                            data-sparepartsize="'.$row_sparepart->SPAREPART_SIZE.'"
                            data-sparepartmodel="'.$row_sparepart->SPAREPART_MODEL.'"
                            data-sparepartcost="'.$row_sparepart->SPAREPART_COST.'"
                            >'.$row_sparepart->SPAREPART_CODE. ' : '. $row_sparepart->SPAREPART_NAME.'</option>';
      }
    $html_sparepart.='</select>';
    //*********************************** table ของรายละเอียด ****************************************/
    $html_detail = "";
    if ($REPAIR_REQ_UNID != '') {
      $REPAIR = MachineRepairREQ::select('*')->selectraw("dbo.decode_utf8(EMP_NAME) as EMP_NAME_TH")
                                                  ->where('UNID','=',$REPAIR_REQ_UNID)->first();
      $DATA_SELECMAIN = SelectMainRepair::where('STATUS','=','9')->get();
      $DATA_SELECSUB = SelectSubRepair::where('STATUS','=','9')->get();
      $PRIORITY_TEXT = $REPAIR->PRIORITY == '9' ? 'เร่งด่วน' : 'ไม่เร่งด่วน' ;
      $html_detail.= '<input type="hidden" id="UNID_REPAIR_REQ" name="UNID_REPAIR_REQ" value="'.$REPAIR->UNID.'">
      <table class="table table-bordered table-bordered-bd-info">
        <tbody>
          <tr>
            <td width="80px" style="background:#aab7c1;color:black;"><h5 class="my-1"> MC-NO </h5></td>
            <td > '.$REPAIR->MACHINE_CODE.' </td>
            <td style="background:#aab7c1;color:black;">LINE</td>
            <td >'.$REPAIR->MACHINE_LINE.'</td>
          </tr>
          <tr>
            <td style="background:#aab7c1;color:black;"><h5 class="my-1">พนักงาน</h5>  </td>
            <td  colspan="3"> '.$REPAIR->EMP_CODE." ".$REPAIR->EMP_NAME_TH.' </td>
          </tr>
          <tr>
            <td style="background:#aab7c1;color:black;"><h5 class="my-1">อาการ</h5>  </td>
            <td  colspan="3">
              <div class="has-error">
                <select class="select-repairdetail" id="DETAIL_REPAIR" name="DETAIL_REPAIR" >';
                foreach ($DATA_SELECMAIN as $index => $row_main){
                $html_detail.='<optgroup label="'.$row_main->REPAIR_MAINSELECT_NAME.'">';
                      foreach ($DATA_SELECSUB->where('REPAIR_MAINSELECT_UNID','=',$row_main->UNID) as $index => $row_sub){
                        $SELECTED = $row_sub->UNID == $REPAIR->REPAIR_SUBSELECT_UNID ? 'selected' : '' ;
                        $html_detail.= '<option value="'.$row_sub->UNID.'" '.$SELECTED.'
                        data-name="'.$row_sub->REPAIR_SUBSELECT_NAME.'"
                        >'.$row_sub->REPAIR_SUBSELECT_NAME.'</option>';
                      }
                $html_detail.='</optgroup>';
                    }
                $html_detail.='</select>
              </div>
            </td>
          </tr>
          <tr>
            <td style="background:#aab7c1;color:black;"><h5 class="my-1">ระดับ</h5>  </td>
            <td  colspan="3">'.$PRIORITY_TEXT.'</td>
          </tr>
        </tbody>
      </table>';
    }
    return Response()->json(['html_detail'=>$html_detail,'html_select' => $html_select,'html_sparepart' => $html_sparepart
    ,'step' => $REPAIR->WORK_STEP]);
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
  public function AddTableWorker(Request $request){
      $UNID = $request->UNID;
      $html = '';
      if (is_array($UNID)) {
        $DATA_EMP_NAME = EMPName::select('*')->selectraw('dbo.decode_utf8(EMP_NAME) as EMP_NAME_TH')->whereIn('UNID',$UNID)->get();
        foreach ($DATA_EMP_NAME as $index => $row) {
            $html.= '	<tr>
                <td>'.$index+1 .'</td>
                <td>'.$row->EMP_CODE.' '.$row->EMP_NAME_TH.'<input type="hidden"
                  id="WORKER_UNID['.$row->UNID.']" name="WORKER_UNID['.$row->UNID.']"  value="'.$row->UNID.'"></td>
                <td><button type="button" class="btn btn-danger btn-sm btn-block my-1" onclick="deleteworker(this)"
                data-empcode="'.$row->EMP_CODE.'"
                data-empname="'.$row->EMP_NAME_TH.'"
                data-empunid="'.$row->UNID.'">
                <i class="fas fa-trash"></i>ลบ</button></td>
              </tr>';
        }
      }
      return Response()->json(['html' => $html]);
  }
  public function AddSparePart(Request $request){
    // dd($request);
      $arr_TOTAL_SPAREPART = $request->TOTAL_SPAREPART;
      $UNID = array();
      $TOTAL = array();
      $html = '';
      if (isset($arr_TOTAL_SPAREPART)) {
        foreach ($arr_TOTAL_SPAREPART as $key => $row_arr) {
          $arr_UNID = array_push($UNID,$key);
          $TOTAL[$key] = $row_arr;
        }
        if (is_array($UNID)) {
          $DATA_SPARPART = SparePart::select('*')->whereIn('UNID',$UNID)->get();
          foreach ($DATA_SPARPART as $index => $row) {

              $html.= '<tr>
                  <td>
                    <button type="button" class="btn btn-warning btn-sm mx-1 my-1"
                    onclick="edittotal(this)"
                    data-unid="'.$row->UNID.'"><i class="fas fa-edit"></i></button>
                    <button type="button" class="btn btn-danger btn-sm mx-1 my-1"
                    onclick="removesparepart(this)"
                    data-unid="'.$row->UNID.'"><i class="fas fa-trash"></i></button>
                    <input type="hidden" id="SPAREPART_UNID_['.$row->UNID.']" name="SPAREPART_UNID_['.$row->UNID.']"
                    value="'.$request->TYPE_SPAREPART[$row->UNID].'">
                    <input type="hidden" id="SPAREPART_COST_['.$row->UNID.']" name="SPAREPART_COST_['.$row->UNID.']"
                    value="'.$request->SPAREPART_COST[$row->UNID].'">
                    <input type="hidden" id="SPAREPART_TOTAL_['.$row->UNID.']" name="SPAREPART_TOTAL_['.$row->UNID.']"
                    value="'.$TOTAL[$row->UNID].'">
                  </td>
                  <td>'.$row->SPAREPART_CODE.'</td>
                  <td>'.$row->SPAREPART_NAME.'</td>
                  <td>'.$row->SPAREPART_MODEL.'</td>
                  <td>'.$row->SPAREPART_SIZE.'</td>
                  <td>'.number_format($request->SPAREPART_COST[$row->UNID]).'</td>
                  <td>'.$row->UNIT.'</td>
                  <td>'. intval($TOTAL[$row->UNID]).'</td>

                </tr>';
          }
        }
      }




      return Response()->json(['html' => $html]);
  }

  public function SaveStep(Request $request){
    $REPAIR_REQ_UNID  = $request->UNID_REPAIR_REQ;
    $WORK_STEP        = $request->WORK_STEP;

    $WORK_STEP_NEXT   = $request->WORK_STEP_NEXT;
    $MACHINEREPAIRREQ = MachineRepairREQ::where('UNID','=',$REPAIR_REQ_UNID);
    $DOC_NO           = $MACHINEREPAIRREQ->first()->DOC_NO;
      if ($WORK_STEP == 'WORK_STEP_0') {
        $SelectSubRepair = SelectSubRepair::where('UNID','=',$request->DETAIL_REPAIR)->first();
        $SelectMainRepair = SelectMainRepair::where('UNID','=',$SelectSubRepair->REPAIR_MAINSELECT_UNID)->first();
        $DATA_EMP = EMPName::select('*')->selectraw('dbo.decode_utf8(EMP_NAME) as EMP_NAME_TH')->where('UNID',$request->REC_WORKER)->first();
        $DATE_NOW = Carbon::now();
        $MACHINEREPAIRREQ->update([
            'REPAIR_MAINSELECT_UNID' =>  $SelectMainRepair->UNID
            ,'REPAIR_MAINSELECT_NAME' => $SelectMainRepair->REPAIR_MAINSELECT_NAME
            ,'REPAIR_SUBSELECT_UNID' => $SelectSubRepair->UNID
            ,'REPAIR_SUBSELECT_NAME' => $SelectSubRepair->REPAIR_SUBSELECT_NAME
            ,'INSPECTION_CODE' =>  $DATA_EMP->EMP_CODE
            ,'INSPECTION_NAME' =>  $DATA_EMP->EMP_NAME
            ,'REC_WORK_DATE'=> $DATE_NOW
            ,'WORK_STEP' => $WORK_STEP_NEXT
        ]);
        $IMG = asset('image/emp/'.$DATA_EMP->EMP_ICON);
        return Response()->json(['img'=>$IMG,'name' => $DATA_EMP->EMP_NAME_TH,'date'=>$DATE_NOW->diffForHumans()]);
     }elseif ($WORK_STEP == 'WORK_STEP_1') {
      $INSPECTION_DETAIL = isset($request->INSPECTION_DETAIL) ? $request->INSPECTION_DETAIL : '';
      $TIME_START        = $request->INSPECTION_START_TIME;
      $TIME_END          = $request->INSPECTION_END_TIME;
      $DATE_START        = $request->INSPECTION_START_DATE;
      $DATE_END          = $request->INSPECTION_END_DATE;
      $MINUTES           = $this->ConvertToMinutes($TIME_START,$TIME_END,$DATE_START,$DATE_END);

      $MACHINEREPAIRREQ->update([
          'INSPECTION_START_DATE' => $DATE_START
          ,'INSPECTION_START_TIME' => $TIME_START
          ,'INSPECTION_END_DATE' => $DATE_END
          ,'INSPECTION_END_TIME' => $TIME_END
          ,'INSPECTION_DETAIL' => $INSPECTION_DETAIL
          ,'INSPECTION_RESULT_TIME' => $MINUTES
          ,'WORK_STEP' => $WORK_STEP_NEXT
          ,'MODIFY_TIME' => Carbon::now()
          ,'MODIFY_BY'    => Auth::user()->name
      ]);
    }elseif($WORK_STEP == 'WORK_STEP_2'){
      $WORKER_TYPE  = strtoupper($request->WORKER_TYPE);
      $RepairWorker = RepairWorker::where('REPAIR_REQ_UNID','=',$REPAIR_REQ_UNID);
      if ($RepairWorker->count() > 0) {
        $RepairWorker->delete();
      }
      $DATA_EMP_NAME = isset($request->WORKOUT_NAME) ? $request->WORKOUT_NAME : EMPName::whereIn('UNID',$request->WORKER_UNID)->get();
      foreach ($DATA_EMP_NAME as $key => $row) {
        $WORKER_UNID          = $WORKER_TYPE == 'OUT' ?  '' : $row->UNID ;
        $WORKER_CODE          = $WORKER_TYPE == 'OUT' ?  '' : $row->EMP_CODE ;
        $WORKER_CHECK_NAME    = $WORKER_TYPE == 'OUT' ?  EMPName::selectraw("dbo.encode_utf8('$row') as WORKER_NAME")->first() : $row->EMP_NAME ;
        $WORKER_COST          = isset($request->WORKOUT_COST) ? $request->WORKOUT_COST[$key] != NULL ? $request->WORKOUT_COST[$key] : 0 : 0;
        $WORKER_REPAIR_DETAIL = isset($request->WORKOUT_DETAIL) ? $request->WORKOUT_DETAIL[$key] : '';
        $WORKER_NAME          = $WORKER_TYPE == 'OUT' ? $WORKER_CHECK_NAME->WORKER_NAME : $row->EMP_NAME ;

        RepairWorker::insert([
          'UNID'                    =>  $this->randUNID('PMCS_CMMS_REPAIR_WORKER')
          ,'REPAIR_REQ_UNID'        =>  $REPAIR_REQ_UNID
          ,'REPAIR_DOC_NO'          =>  $DOC_NO
          ,'WORKER_UNID'            =>  $WORKER_UNID
          ,'WORKER_TYPE'            =>  $WORKER_TYPE
          ,'WORKER_CODE'            =>  $WORKER_CODE
          ,'WORKER_NAME'            =>  $WORKER_NAME
          ,'WORKER_COST'            =>  $WORKER_COST
          ,'WORKER_REPAIR_DETAIL'   =>  $WORKER_REPAIR_DETAIL
          ,'CREATE_BY'              =>  Carbon::now()
          ,'CREATE_TIME'            =>  Auth::user()->name
          ,'MODIFY_BY'              =>  Carbon::now()
          ,'MODIFY_TIME'            =>  Auth::user()->name
        ]);
      }
      $MACHINEREPAIRREQ->update([
           'WORK_STEP'     =>  $WORK_STEP_NEXT
          ,'MODIFY_TIME'  => Carbon::now()
          ,'MODIFY_BY'    => Auth::user()->name
       ]);
   }elseif ($WORK_STEP == 'WORK_STEP_3') {
      $REPAIRSPAREPART = RepairSparepart::where('REPAIR_REQ_UNID','=',$REPAIR_REQ_UNID);
      if ($REPAIRSPAREPART->count() > 0) {
          $REPAIRSPAREPART->delete();
      }
      if (is_array($request->SPAREPART_UNID_)) {
        foreach ($request->SPAREPART_UNID_ as $key => $value) {
          $SPAREPART_UNID[] = $key;
        }
        $DATA_SPARPART = SparePart::whereIn('UNID',$SPAREPART_UNID)->get();
        foreach ($DATA_SPARPART as $key => $sub_row) {
          $TOTAL_OUT = $request->SPAREPART_TOTAL_[$sub_row->UNID];
          $COST = $request->SPAREPART_COST_[$sub_row->UNID];
          $TOTAL_COST = $COST * $TOTAL_OUT;
          RepairSparepart::insert([
            'UNID'                    =>  $this->randUNID('PMCS_CMMS_REPAIR_SPAREPART')
            ,'REPAIR_REQ_UNID'        =>  $REPAIR_REQ_UNID
            ,'REPAIR_DOC_NO'          =>  $DOC_NO
            ,'SPAREPART_UNID'         =>  $sub_row->UNID
            ,'SPAREPART_CODE'         =>  $sub_row->SPAREPART_CODE
            ,'SPAREPART_NAME'         =>  $sub_row->SPAREPART_NAME
            ,'SPAREPART_COST'         =>  $COST
            ,'SPAREPART_TOTAL_COST'   =>  $TOTAL_COST
            ,'SPAREPART_TOTAL_OUT'    =>  $TOTAL_OUT
            ,'SPAREPART_TYPE_OUT'     =>  $request->SPAREPART_UNID_[$sub_row->UNID]
            ,'SPAREPART_UNIT'         =>  $sub_row->UNIT
            ,'SPAREPART_MODEL'        =>  $sub_row->SPAREPART_MODEL
            ,'SPAREPART_SIZE'         =>  $sub_row->SPAREPART_SIZE
            ,'CHANGE_DATE'            =>  Carbon::now()
            ,'CREATE_BY'              =>  Auth::user()->name
            ,'CREATE_TIME'            =>  Carbon::now()
            ,'MODIFY_BY'              =>  Auth::user()->name
            ,'MODIFY_TIME'            =>  Carbon::now()
          ]);
        }
      }

      $DATE_START        = isset($request->SPAREPART_START_DATE) ? $request->SPAREPART_START_DATE : NULL;
      $TIME_START        = isset($request->SPAREPART_START_TIME) ? $request->SPAREPART_START_TIME : NULL;
      $DATE_END          = isset($request->SPAREPART_END_DATE) ? $request->SPAREPART_END_DATE : NULL;
      $TIME_END          = isset($request->SPAREPART_END_TIME) ? $request->SPAREPART_END_TIME : NULL;
      $MINUTES           = $this->ConvertToMinutes($TIME_START,$TIME_END,$DATE_START,$DATE_END);

      $MACHINEREPAIRREQ->update([
          'SPAREPART_START_DATE'=>$DATE_START
          ,'SPAREPART_START_TIME'=>$TIME_START
          ,'SPAREPART_END_DATE'=>$DATE_END
          ,'SPAREPART_END_TIME'=>$TIME_END
          ,'SPAREPART_RESULT_TIME' =>$MINUTES
          ,'WORK_STEP'     =>  $WORK_STEP_NEXT
          ,'MODIFY_TIME'  => Carbon::now()
          ,'MODIFY_BY'    => Auth::user()->name
      ]);
  }elseif ($WORK_STEP == 'WORK_STEP_4') {
      $TIME_START         = $request->WORKER_START_TIME;
      $TIME_END           = $request->WORKER_END_TIME;
      $DATE_START         = $request->WORKER_START_DATE;
      $DATE_END           = $request->WORKER_END_DATE;
      $REPAIR_DETAIL      = $request->REPAIR_DETAIL;
      $RepairWorker       = RepairWorker::where('REPAIR_REQ_UNID','=',$REPAIR_REQ_UNID)->first();
      $MINUTES            = $this->ConvertToMinutes($TIME_START,$TIME_END,$DATE_START,$DATE_END);
      if ($RepairWorker->WORKER_TYPE == 'IN') {
          $MACHINEREPAIRREQ->update([
               'WORKERIN_START_DATE'   =>  $DATE_START
              ,'WORKERIN_START_TIME'  => $TIME_START
              ,'WORKERIN_END_DATE'    => $DATE_END
              ,'WORKERIN_END_TIME'    => $TIME_END
              ,'WORKERIN_RESULT_TIME' => $MINUTES
              ,'REPAIR_DETAIL'        => $REPAIR_DETAIL
              ,'WORK_STEP'            =>  $WORK_STEP_NEXT
              ,'MODIFY_TIME'          => Carbon::now()
              ,'MODIFY_BY'            => Auth::user()->name
          ]);
      }elseif ($RepairWorker->WORKER_TYPE == 'OUT') {
          $MACHINEREPAIRREQ->update([
            'WORKEROUT_START_DATE'   =>  $DATE_START
            ,'WORKEROUT_START_TIME'  => $TIME_START
            ,'WORKEROUT_END_DATE'    => $DATE_END
            ,'WORKEROUT_END_TIME'    => $TIME_END
            ,'WORKEROUT_RESULT_TIME' => $MINUTES
            ,'REPAIR_DETAIL'         => $REPAIR_DETAIL
            ,'WORK_STEP'             =>  $WORK_STEP_NEXT
            ,'MODIFY_TIME'           => Carbon::now()
            ,'MODIFY_BY'             => Auth::user()->name
          ]);
      }

    }

  }
  public function Result(Request $request){
    $UNID_REPAIR = $request->UNID_REPAIR;
    $RESULT_ALL_WORKER = RepairWorker::where('REPAIR_REQ_UNID','=',$UNID_REPAIR)->sum('WORKER_COST');
    $DATA_SPAREPART = RepairSparepart::where('REPAIR_REQ_UNID','=',$UNID_REPAIR)->orderBy('SPAREPART_NAME')->get();
    $DATA_REPAIR_REQ = MachineRepairREQ::select('*')->selectraw('dbo.decode_utf8(INSPECTION_NAME) as INSPECTION_NAME_TH')->where('UNID','=',$UNID_REPAIR)->first();
    $CLOSE_STATUS = $DATA_REPAIR_REQ->CLOSE_STATUS;
    $DOC_DATE =  Carbon::create($DATA_REPAIR_REQ->DOC_DATE.$DATA_REPAIR_REQ->REPAIR_REQ_TIME) ;
    if (isset($DATA_REPAIR_REQ->WORKERIN_END_DATE)) {
      $END_DATE = Carbon::create($DATA_REPAIR_REQ->WORKERIN_END_DATE.$DATA_REPAIR_REQ->WORKERIN_END_TIME);
    }else {
      $END_DATE = Carbon::create($DATA_REPAIR_REQ->WORKEROUT_END_DATE.$DATA_REPAIR_REQ->WORKEROUT_END_TIME);
    }
    //******************************** เวลา **********************************************
    $DIFF                   = $DOC_DATE->diffInRealMinutes($END_DATE);
    $DAYS                   = floor ($DIFF / 1440);
    $HOURS                  = floor (($DIFF - $DAYS * 1440) / 60);
    $MINUTES                = $DIFF - ($DAYS * 1440) - ($HOURS * 60);
    $DOWN_TIME              = $DAYS.'วัน'.$HOURS.'ชั่วโมง'.$MINUTES.'นาที';
   //*******************************************************************************************
  // $RESULT_ALL_WORKER = $DATA_WORKER > 0 ? $DATA_WORKER.'฿' : '-' ;
    $html = '<div class="col-12 col-lg-10 ml-auto mr-auto" >
      <div class="page-divider"></div>
      <div class="row">
        <div class="col-md-12">
          <div class="card card-invoice" style="border: groove;">
            <div class="card-header">
              <div class="invoice-header form-inline">
                <h3 class="invoice-title">
                  '.$DATA_REPAIR_REQ->MACHINE_CODE.'
                </h3>
              </div>
              <div class="form-inline">
                <div class="invoice-desc my-2">ผู้รับงาน : '.$DATA_REPAIR_REQ->INSPECTION_NAME_TH.'</div>
              </div>
            </div>
              <div class="card-body">
                    <div class="row">
                      <div class="col-6 col-sm-3 col-md-4 info-invoice">
                        <h5 class="sub">วันที่แจ้ง</h5>
                        <p>'.date('d-m-Y',strtotime($DOC_DATE)).'</p>
                      </div>
                      <div class="col-6 col-sm-3 col-md-4 info-invoice">
                        <h5 class="sub">วันที่ซ่อมเสร็จ</h5>
                        <p>'.date('d-m-Y',strtotime($END_DATE)).'</p>
                      </div>
                      <div class="col-12 col-sm-6 col-md-4 info-invoice">
                        <h5 class="sub">ระยะเวลาที่ใช้</h5>
                          <p>'.$DOWN_TIME.'</p>
                      </div>
                <div class="form-group">
                  <h6 class="text-uppercase mb-2 fw-bold">
                    การแก้ไข
                  </h6>
                  <p class="text-muted mb-1">
                    '.$DATA_REPAIR_REQ->REPAIR_DETAIL.'
                </div>
              </div>
              <div class="separator-solid"></div>
              <div class="row">
                <div class="col-md-12">
                  <div class="invoice-detail">
                    <div class="invoice-top">
                      <h3 class="title"><strong>รายการอะไหล่</strong></h3>
                    </div>
                    <div class="invoice-item">
                      <div class="table-responsive">
                        <table class="table table-striped">
                          <thead>
                            <tr>
                              <td><strong>รหัส</strong></td>
                              <td class="text-left"><strong>ชื่อ</strong></td>
                              <td class="text-left"><strong>จำนวน</strong></td>
                              <td class="text-right"><strong>ราคาต่อชิ้น</strong></td>
                              <td class="text-right"><strong>ราคาร่วม</strong></td>
                            </tr>
                          </thead>
                          <tbody>';
                          $COST_ALL_SPAREPART = 0;
                          foreach ($DATA_SPAREPART as $index => $row) {
                            $COST_ALL_SPAREPART = $COST_ALL_SPAREPART;
                            $html.='<tr>
                                <td>'.$row->SPAREPART_CODE.'</td>
                                <td class="text-left">'.$row->SPAREPART_NAME.'</td>
                                <td class="text-left">'.$row->SPAREPART_TOTAL_OUT.'</td>
                                <td class="text-right">'.number_format($row->SPAREPART_COST).' ฿</td>
                                <td class="text-right">'.number_format($row->SPAREPART_TOTAL_COST).' ฿</td>
                              </tr>';
                            $COST_ALL_SPAREPART = $COST_ALL_SPAREPART+$row->SPAREPART_TOTAL_COST;
                          }
                            $TOTAL_COST_ALL = $COST_ALL_SPAREPART+$RESULT_ALL_WORKER;
                     $html.='<tr>
                              <td colspan="4"class="text-right"><strong>รวม</strong></td>
                              <td class="text-right">'.number_format($COST_ALL_SPAREPART).' ฿</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <style>
            .card-invoice .transfer-total .price {
                  font-size: 28px;
                  color: #1572E8;
                  padding: 7px 0;
                  font-weight: 600;
              }
            </style>
            <div class="card-footer">
              <div class="row">
                <div class="col-7 col-sm-7 col-md-5 mb-3 mb-md-0 transfer-to">
                  <h5 class="sub">ค่าบริการช่างภายนอก</h5>
                </div>
                <div class="col-5 col-sm-5 col-md-7 transfer-total">
                  <h5 class="sub">'. number_format($RESULT_ALL_WORKER) .' ฿ </h5>
                </div>
              </div>
              <div class="row">
                <div class="col-6 col-sm-7 col-md-8 mb-3 mb-md-0 transfer-to">
                </div>
                <div class="col-6 col-sm-5 col-md-4 transfer-total">
                  <h5 class="sub">ค่าใช้จ่ายทั้งหมด</h5>
                  <div class="price">'.number_format($TOTAL_COST_ALL).' ฿</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>';
    return Response()->json(['html' => $html,'status' => $CLOSE_STATUS,'total_sparepart' => $COST_ALL_SPAREPART
                            ,'total_worker' => $RESULT_ALL_WORKER,'total_all' => $TOTAL_COST_ALL]);



  }
  public function CloseForm(Request $request){
    $DATA_REPAIR =  MachineRepairREQ::where('UNID','=',$request->UNID_REPAIR);
    $DATA_REPAIR_FIRST = $DATA_REPAIR->first();
    //********************************* Cost ************************************
    $TOTAL_COST_SPAREPART   = $request->TOTAL_SPAREPART;
    $TOTAL_COST_WORKER      = $request->TOTAL_WORKER;
    $TOTAL_COST_REPAIR      = $request->TOTAL_ALL;
    //********************************* Time *************************************
    $RESULT_INSPECTION      = $DATA_REPAIR_FIRST->INSPECTION_RESULT_TIME;
    $RESULT_SPAREPART       = $DATA_REPAIR_FIRST->SPAREPART_RESULT_TIME;
    $RESULT_WORKERIN        = $DATA_REPAIR_FIRST->WORKERIN_RESULT_TIME;
    $RESULT_WORKEROUT       = $DATA_REPAIR_FIRST->WORKEROUT_RESULT_TIME;
    $DOWNTIME               = ($RESULT_INSPECTION + $RESULT_SPAREPART + $RESULT_WORKERIN + $RESULT_WORKEROUT);

    $DATA_REPAIR->update([
      'DOWNTIME' =>  $DOWNTIME
      ,'TOTAL_COST_SPAREPART' => $TOTAL_COST_SPAREPART
      ,'TOTAL_COST_WORKER' => $TOTAL_COST_WORKER
      ,'TOTAL_COST_REPAIR' => $TOTAL_COST_REPAIR
      ,'CLOSE_BY' => $DATA_REPAIR_FIRST->INSPECTION_NAME
      ,'CLOSE_STATUS' => 1
      ,'MODIFY_BY' => Carbon::now()
      ,'MODIFY_TIME' => Auth::user()->name
    ]);

    return Response()->json(['pass'=>'true']);
  }
  public function ConvertToMinutes($TIME_START=NULL,$TIME_END=NULL,$DATE_START=NULL,$DATE_END=NULL){
    $TIME_START        = $TIME_START;
    $TIME_END          = $TIME_END;
    $DATE_START        = Carbon::create($DATE_START.$TIME_START);
    $DATE_END          = Carbon::create($DATE_END.$TIME_END);
    $DATE_DIFF         = $DATE_START->diffInRealMinutes($DATE_END);
    $MINUTES           = $DATE_DIFF;

    return $MINUTES;
  }
}