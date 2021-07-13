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
use App\Models\Machine\MachineEMP;
use App\Models\Machine\EMPName;
use App\Models\Machine\RepairWorker;
use App\Models\Machine\RepairSparepart;
use App\Models\Machine\MachineLine;
use App\Models\Machine\EMPALL;
use App\Models\Machine\MachineRepairREQ;
use App\Models\Machine\History;
//************** Package form github ***************
use App\Exports\MachineExport;
use Maatwebsite\Excel\Facades\Excel;

class PDRepairController extends Controller
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

  public function Index(Request $request){
    $cookie_array = array('0' => 'empcode','1' => 'selectmainrepair','2' => 'selectsubrepair','3' => 'priority' );
    foreach ($cookie_array as $index => $row) {
      Cookie::queue(Cookie::forget($row));
    }

    if ($request->cookie('table_style') == NUll) {
      Cookie::queue('table_style','2');
    }
    $SEARCH      = isset($request->SEARCH) ? '%'.$request->SEARCH.'%' : '';
    $SERACH_TEXT =  $request->SEARCH;
    $LINE = MachineLine::where('LINE_STATUS','=','9')->where('LINE_NAME','like','Line'.'%')->orderBy('LINE_NAME')->get();
    $MACHINE_LINE = isset($request->LINE) ? $request->LINE : '';
    $MONTH = isset($request->MONTH) ? $request->MONTH : date('m') ;
    $DOC_STATUS = isset($request->DOC_STATUS) ? $request->DOC_STATUS : 9 ;
    $YEAR = isset($request->YEAR) ? $request->YEAR : date('Y') ;
    $DATA_EMP = EMPName::select('*')->selectraw('dbo.decode_utf8(EMP_NAME) as EMP_NAME_TH')->where('EMP_STATUS','=',9)->get();
    $dataset = MachineRepairREQ::select('*')->selectraw('dbo.decode_utf8(EMP_NAME) as EMP_NAME_TH')
                                            ->where(function ($query) use ($MACHINE_LINE) {
                                                  if ($MACHINE_LINE != '') {
                                                     $query->where('MACHINE_LINE', '=', $MACHINE_LINE);
                                                   }
                                                  })
                                            ->where(function ($query) use ($MONTH) {
                                                    if ($MONTH > 0) {
                                                       $query->where('DOC_MONTH', '=', $MONTH);
                                                     }
                                                    })
                                            ->where(function ($query) use ($YEAR) {
                                                   if ($YEAR > 0) {
                                                      $query->where('DOC_YEAR', '=', $YEAR);
                                                    }
                                                   })
                                            ->where(function ($query) use ($SEARCH) {
                                                  if ($SEARCH != "") {
                                                     $query->where('MACHINE_CODE', 'like', '%'.$SEARCH.'%')
                                                           ->orwhere('MACHINE_NAME','like','%'.$SEARCH.'%')
                                                           ->orWhere('DOC_NO', 'like', '%'.$SEARCH.'%');
                                                   }
                                                  })
                                            ->where(function ($query) use ($DOC_STATUS) {
                                                  if ($DOC_STATUS > 0) {
                                                      $query->where('PD_CHECK_STATUS', '=', $DOC_STATUS);
                                                   }
                                                 })
                                            ->orderBy('PD_CODE','ASC')
                                            ->orderBy('DOC_YEAR','DESC')
                                            ->orderBy('DOC_MONTH','DESC')
                                            ->orderBy('DOC_NO','DESC')
                                            ->paginate(10);
    $SEARCH = $SERACH_TEXT;
    return View('machine/repair/repairlistpd',compact('dataset','SEARCH','LINE','DATA_EMP',
    'MACHINE_LINE','MONTH','YEAR','DOC_STATUS'));
  }
  public function FetchData(Request $request){
    $SEARCH         = isset($request->SEARCH) ? '%'.$request->SEARCH.'%' : '';
    $SERACH_TEXT    = $request->SEARCH;
    $MACHINE_LINE   = isset($request->LINE) ? $request->LINE : '';
    $MONTH          = isset($request->MONTH) ? $request->MONTH : 0 ;
    $DOC_STATUS     = isset($request->DOC_STATUS) ? $request->DOC_STATUS : 0 ;
    $YEAR           = isset($request->YEAR) ? $request->YEAR : date('Y') ;
    $dataset        = MachineRepairREQ::select('*')->selectraw('dbo.decode_utf8(EMP_NAME) as EMP_NAME_TH
                                                                ,dbo.decode_utf8(INSPECTION_NAME) as INSPECTION_NAME_TH')
                                            ->where(function ($query) use ($MACHINE_LINE) {
                                                  if ($MACHINE_LINE != '') {
                                                     $query->where('MACHINE_LINE', '=', $MACHINE_LINE);
                                                   }
                                                  })
                                            ->where(function ($query) use ($MONTH) {
                                                    if ($MONTH > 0) {
                                                       $query->where('DOC_MONTH', '=', $MONTH);
                                                     }
                                                    })
                                            ->where(function ($query) use ($YEAR) {
                                                   if ($YEAR > 0) {
                                                      $query->where('DOC_YEAR', '=', $YEAR);
                                                    }
                                                   })
                                            ->where(function ($query) use ($SEARCH) {
                                                  if ($SEARCH != "") {
                                                     $query->where('MACHINE_CODE', 'like', '%'.$SEARCH.'%')
                                                           ->orwhere('MACHINE_NAME','like','%'.$SEARCH.'%')
                                                           ->orWhere('DOC_NO', 'like', '%'.$SEARCH.'%');
                                                   }
                                                  })
                                            ->where(function ($query) use ($DOC_STATUS) {
                                                  if ($DOC_STATUS > 0) {
                                                      $query->where('PD_CHECK_STATUS', '=', $DOC_STATUS);
                                                   }
                                                 })
                                            ->orderBy('PD_CODE','ASC')
                                            ->orderBy('DOC_YEAR','DESC')
                                            ->orderBy('DOC_MONTH','DESC')
                                            ->orderBy('DOC_NO','DESC')
                                            ->paginate(10);
    $SEARCH         = $SERACH_TEXT;
    $html = '';
    $html_style = '';
    foreach ($dataset as $key => $row) {
      $REC_WORK_STATUS  = isset($row->INSPECTION_CODE) ? $row->INSPECTION_NAME_TH : 'รอรับงาน';
      $BTN_COLOR_STATUS = $row->INSPECTION_CODE == '' ? 'btn-mute' : ($row->CLOSE_STATUS == '1' ? 'btn-info' : 'btn-warning') ;
      $BTN_COLOR_STATUS = $row->PD_CHECK_STATUS != 9 ? 'btn-success' : $BTN_COLOR_STATUS ;
      $BTN_COLOR 			  = $row->INSPECTION_CODE == '' ? 'btn-danger' : 'btn-secondary' ;
      $BTN_TEXT  			  = $row->INSPECTION_CODE == '' ? 'รอรับงาน' : ($row->CLOSE_STATUS == '1' ? 'ดำเนินการสำเร็จ' : 'การดำเนินงาน') ;
      $BTN_TEXT					= $row->PD_CHECK_STATUS != 9 ? 'ปิดเอกสารแล้ว' : $BTN_TEXT;
      $html.= '<tr>
                <td>'.$key+1 .'</td>
                <td >'.date('d-m-Y',strtotime($row->DOC_DATE)).'</td>
                <td >'.$row->DOC_NO.'</td>
                <td >'.$row->MACHINE_LINE.'</td>
                <td >'.$row->MACHINE_CODE.'</td>
                <td >'.$row->MACHINE_NAME.'</td>
                <td >'.$row->REPAIR_SUBSELECT_NAME.'</td>d

                <td >
                  <button type="button"class="btn '.$BTN_COLOR_STATUS.' btn-block btn-sm my-1 text-left"
                  '.($row->CLOSE_STATUS == '1' ? 'onclick=pdfsaverepair("'.$row->UNID.'")' : '').'>
                    <i class="'.($row->CLOSE_STATUS == '1' ? 'fas fa-print' : '').'"style="color:black;font-size:13px"></i>

                    <span class="btn-label " style="color:black;font-size:13px">
                      '.$BTN_TEXT.'
                    </span>
                  </button>
                </td>';
                if (Gate::allows("isAdminandManager")) {
                  if ($row->CLOSE_STATUS == 1) {
                    $BTN_CONFIRM = "onclick=rec_work(this)";
                  }else {
                    $BTN_CONFIRM = '';
                  }
                  $html.='<td >
                      <button '.$BTN_CONFIRM.'
                      type="button"
                      data-unid="'.$row->UNID.'"
                      data-docno="'.$row->DOC_NO.'"
                      data-detail="'.$row->REPAIR_SUBSELECT_NAME.'"
                      class="btn '.$BTN_COLOR.' btn-block btn-sm my-1 text-left"
                     >
                       <span class="btn-label">
                       <i class="fas fa-clipboard-check mx-1"></i>'.$REC_WORK_STATUS.'
                     </span>
                     </button></td>';
                   }else {
                  $html.='<td ></td>';
                  }
      $html.= '<td >'.date('d-m-Y').'</td>
        </tr>';
      }
    foreach ($dataset as $index => $sub_row) {
      $DATA_EMP    = EMPName::where('EMP_CODE',$sub_row->INSPECTION_CODE)->first();
      $BG_COLOR    = $sub_row->PRIORITY == '9' ? 'bg-danger text-white' :  'bg-warning text-white';
      
      if ($sub_row->PD_CHECK_STATUS == '1') {
        $BG_COLOR = 'bg-success text-white';
      }elseif ($sub_row->CLOSE_STATUS == '1') {
        $BG_COLOR = 'bg-info text-white';
      }
      $IMG         = isset($DATA_EMP->EMP_ICON) ? asset('image/emp/'.$DATA_EMP->EMP_ICON) : asset('../assets/img/noemp.png');
      $WORK_STATUS = isset($sub_row->INSPECTION_NAME) ? $sub_row->INSPECTION_NAME_TH :'รอรับงาน';
      $DATE_DIFF   = $sub_row->REC_WORK_DATE != '1900-01-01 00:00:00.000'? 'รับเมื่อ:'.Carbon::parse($sub_row->REC_WORK_DATE)->diffForHumans() : 'แจ้งเมื่อ:'.Carbon::parse($sub_row->CREATE_TIME)->diffForHumans();
      $html_style .=  '<div class="col-lg-3">
          <div class="card card-round">
            <div class="card-body">
              <div class="card-title text-center fw-mediumbold '.$BG_COLOR.' ">'.$sub_row->MACHINE_CODE.'</div>
              <div class="card-list">
                <div class="item-list">
                  <div class="avatar">
                    <img src="'.$IMG.'" alt="..." class="avatar-img rounded-circle"
                    id="IMG_'.$sub_row->UNID.'">
                  </div>
                  <div class="info-user ml-3">
                    <div class="username" id="WORK_STATUS_'.$sub_row->UNID.'">'.$WORK_STATUS.'</div>
                    <div class="status" >'.$sub_row->REPAIR_SUBSELECT_NAME.'</div>';
                    if ($sub_row->PD_CHECK_STATUS == '1'){
                      $html_style .='<div class="status" id="DATE_DIFF_'.$sub_row->UNID.'" > ปิดเอกสารสำเร็จ</div>';
                    }
                    elseif ($sub_row->CLOSE_STATUS == '1'){
                      $html_style .='<div class="status" id="DATE_DIFF_'.$sub_row->UNID.'" > ดำเนินงานสำเร็จ</div>';
                    }
                    else{
                      $html_style .='<div class="status" id="DATE_DIFF_'.$sub_row->UNID.'">'.$DATE_DIFF.'</div>';
                    }
                    $html_style .='</div>
                </div>
              </div>';
              if ($sub_row->CLOSE_STATUS == 1) {
                $html_style.='<div class="row ">
                  <div class="col-md-12 text-center">
                    <button class="btn  btn-primary  btn-sm"
                    onclick="rec_work(this)"
                    data-unid="'.$sub_row->UNID.'"
                    data-docno="'.$sub_row->DOC_NO.'"
                    data-detail="'.$sub_row->REPAIR_SUBSELECT_NAME.'">
                      SELECT
                    </button>
                  </div>
                </div>';
              }
            $html_style.='</div>
                        </div>
                      </div>';
      }
    return Response()->json(['html'=>$html,'html_style' => $html_style]);
  }

  public function Store(Request $request,$MACHINE_UNID){
      //******************* Request parameter *******************//
      $CLOSE_STATUS = '9';
        $MACHINE_UNID = $MACHINE_UNID;
        $EMP_CODE = $request->cookie('empcode');
        $SELECT_MAIN_REPAIR_UNID = $request->cookie('selectmainrepair');
        $SELECT_SUB_REPAIR_UNID = $request->cookie('selectsubrepair');
        $PRIORITY = $request->cookie('priority');
        $UNID = $this->randUNID('PMCS_CMMS_REPAIR_REQ');
      //******************* data *******************//
      $DATA_MACHINE = Machine::where('UNID','=',$MACHINE_UNID)->first();
        $DATA_SELECTMACHINEREPAIR = SelectMainRepair::where('UNID','=',$SELECT_MAIN_REPAIR_UNID)->first();
        $DATA_SELECTSUBREPAIR = SelectSubRepair::where('UNID','=',$SELECT_SUB_REPAIR_UNID)->first();
        $DATA_EMP = DB::select("select EMP_TH_NAME_FIRST,EMP_CODE,UNID from EMCS_EMPLOYEE where LINE_CODE = 'PD' and EMP_CODE = '".$EMP_CODE."'");
      //******************* docno *******************//
      $DATA_MACHINEREPAIRREQ = MachineRepairREQ::selectraw('max(DOC_NO)DOC_NO,max(DOC_DATE)DOC_DATE')->first();
      $DATE_DOCNO            = Carbon::now()->addyears('543');
      $DOC_NO = 'RE' . $DATE_DOCNO->format('ym') . sprintf('-%04d', 1);
      if ($DATA_MACHINEREPAIRREQ->DOC_DATE != NULL) {
        $DATE_RESET_DOCNO      = Carbon::parse($DATA_MACHINEREPAIRREQ->DOC_DATE);
        if ($DATE_RESET_DOCNO->format('m') == Carbon::now()->format('m') ) {
          $EXPLOT = str_replace('RE'.$DATE_RESET_DOCNO->addyears('543')->format('ym').'-','',$DATA_MACHINEREPAIRREQ->DOC_NO)+1;
          $DOC_NO = 'RE' . $DATE_RESET_DOCNO->format('ym'). sprintf('-%04d', $EXPLOT);
        }
      }
      //******************* insert *******************//
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
        ,'EMP_UNID'              => $DATA_EMP[0]->UNID
        ,'EMP_CODE'              => $DATA_EMP[0]->EMP_CODE
        ,'EMP_NAME'              => $DATA_EMP[0]->EMP_TH_NAME_FIRST
        ,'PRIORITY'              => $PRIORITY
        ,'DOC_NO'                => $DOC_NO
        ,'DOC_DATE'              => date('Y-m-d')
        ,'DOC_YEAR'              => date('Y')
        ,'DOC_MONTH'             => date('m')
        ,'REPAIR_REQ_TIME'       => $DATE_DOCNO->format('H:i:s')
        ,'CLOSE_STATUS'          => $CLOSE_STATUS
        ,'CLOSE_BY'              => ''
        ,'CREATE_BY'             =>Auth::user()->name
        ,'CREATE_TIME'           =>Carbon::now()
        ,'MODIFY_BY'             => Auth::user()->name
        ,'MODIFY_TIME'           => Carbon::now()
        ]);
      //******************* Remove cookie *******************//
      $cookie_array = array('0' => 'empcode','1' => 'selectmainrepair','2' => 'selectsubrepair','3' => 'priority' );
      foreach ($cookie_array as $index => $row) {
        Cookie::queue(Cookie::forget($row));
      }
      return redirect()->route('repair.list');
  }

  public function ShowResult(Request $request){
    $UNID_REPAIR        = $request->REPAIR_REQ_UNID;

    $COST_WORKER        = RepairWorker::where('REPAIR_REQ_UNID','=',$UNID_REPAIR)->sum('WORKER_COST');
    $DATA_SPAREPART     = RepairSparepart::select('SPAREPART_CODE','SPAREPART_NAME','SPAREPART_TOTAL_OUT','SPAREPART_COST','SPAREPART_TOTAL_COST')
                                          ->where('REPAIR_REQ_UNID','=',$UNID_REPAIR)->orderBy('SPAREPART_NAME')->get();

    $REPAIR_REQ         = MachineRepairREQ::select('*')->selectraw('dbo.decode_utf8(INSPECTION_NAME) as INSPECTION_NAME_TH
                                                                   ,dbo.decode_utf8(PD_NAME) as PD_NAME_TH')
                                          ->where('UNID','=',$UNID_REPAIR)->first();
    $DATA_PD            = DB::select("select dbo.decode_utf8(EMP_TH_NAME_FIRST) as EMP_TH_NAME_FIRST_TH
                                            ,dbo.decode_utf8(EMP_TH_NAME_LAST) as EMP_TH_NAME_LAST_TH
                                            ,EMP_CODE
                                      from EMCS_EMPLOYEE  where LINE_CODE = 'PD' and EMP_STATUS = '9'");

    $PLANING_CHECK_BY   = isset($REPAIR_REQ->PD_CODE) ? $REPAIR_REQ->PD_CODE : '';
    $DOC_DATE           =  Carbon::create($REPAIR_REQ->DOC_DATE.$REPAIR_REQ->REPAIR_REQ_TIME) ;
    if (isset($REPAIR_REQ->WORKERIN_END_DATE)) {
      $END_DATE = Carbon::create($REPAIR_REQ->WORKERIN_END_DATE.$REPAIR_REQ->WORKERIN_END_TIME);
    }else {
      $END_DATE = Carbon::create($REPAIR_REQ->WORKEROUT_END_DATE.$REPAIR_REQ->WORKEROUT_END_TIME);
    }

    $TOTAL_COST_SPAREPART = $REPAIR_REQ->TOTAL_COST_SPAREPART;
    $TOTAL_COST_ALL       = $REPAIR_REQ->TOTAL_COST_REPAIR;
    //******************************** เวลา **********************************************

    $DOWMTIME   = $REPAIR_REQ->DOWNTIME;
    $DAYS       = floor ($DOWMTIME / 1440);
    $HOURS      = floor (($DOWMTIME - $DAYS * 1440) / 60);
    $MINUTES    = $DOWMTIME - ($DAYS * 1440) - ($HOURS * 60);
    $DOWN_TIME  = $DAYS.' วัน '.$HOURS.' ชั่วโมง '.$MINUTES.' นาที';
   //*******************************************************************************************
   if ($PLANING_CHECK_BY == '') {
     $TEXT = '<select class="form-control form-control-sm col-lg-4 " id="EMP_CODE" name="EMP_CODE">
             <option value>กรุณาเลือก</option>';
             foreach ($DATA_PD as $index => $row_pd) {
               $TEXT.='<option value="'.$row_pd->EMP_CODE.'">'.$row_pd->EMP_TH_NAME_FIRST_TH.' '.$row_pd->EMP_TH_NAME_LAST_TH.'</option>';
             }
            $TEXT.='</select>';
     $BTN = '<button type="button" class="btn btn-secondary btn-sm  text-right"
             id="ConFirm" data-unid="'.$UNID_REPAIR.'" >
               <i class="fas fa-clipboard-check fa-2x"> ปิดเอกสาร</i>
             </button>';
   }else {
     $TEXT = '<input type="text" class="form-control-sm form-control-plaintext bg-success text-white text-center mx-2 col-lg-4" value="'.$REPAIR_REQ->PD_NAME_TH.'" >';
     $BTN  = '<button type="button" class="btn btn-secondary btn-sm text-right"
               data-dismiss="modal" >
                 <i class="fas fa-door-open fa-2x"> ออก</i>
               </button>';
   }
    $html = '<div class="col-12 col-lg-10 ml-auto mr-auto" >
      <div class="page-divider"></div>
      <div class="row">
        <div class="col-md-12">
          <div class="card card-invoice" style="border: groove;">
            <div class="card-header">
              <div class="invoice-header row">
                <h3 class="invoice-title col-lg-10">
                  '.$REPAIR_REQ->MACHINE_CODE.'
                </h3>
                <button type="button" class="btn btn-secondary btn-sm  text-right stepclose"
                data-dismiss="modal" onclick="pdfsaverepair(\''.$REPAIR_REQ->UNID.'\')">
                  <i class="fas fa-print"> พิมพ์ </i>
                </button>
              </div>
              <div class="form-inline">
                <div class="invoice-desc my-2">ผู้รับงาน : '.$REPAIR_REQ->INSPECTION_NAME_TH.'</div>
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
                        <h5 class="sub">ระยะเวลาที่ใช้ซ่อม</h5>
                          <p>'.$DOWN_TIME.'</p>
                      </div>
                <div class="form-group">
                  <h6 class="text-uppercase mb-2 fw-bold">
                    การแก้ไข
                  </h6>
                  <p class="text-muted mb-1">
                    '.$REPAIR_REQ->REPAIR_DETAIL.'
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

                          foreach ($DATA_SPAREPART as $index => $row) {
                            $html.='<tr>
                                <td>'.$row->SPAREPART_CODE.'</td>
                                <td class="text-left">'.$row->SPAREPART_NAME.'</td>
                                <td class="text-left">'.$row->SPAREPART_TOTAL_OUT.'</td>
                                <td class="text-right">'.number_format($row->SPAREPART_COST).' ฿</td>
                                <td class="text-right">'.number_format($row->SPAREPART_TOTAL_COST).' ฿</td>
                              </tr>';
                          }

                     $html.='<tr>
                              <td colspan="4"class="text-right"><strong>รวม</strong></td>
                              <td class="text-right">'.number_format($TOTAL_COST_SPAREPART).' ฿</td>
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
                  <h5 class="sub">'. number_format($COST_WORKER) .' ฿ </h5>
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
    $footer = '<div class="col-5 col-sm-7 col-md-7 col-lg-10 form-inline justify-content-end" >
      <lable> ผู้ตรวจสอบ (PD)</lable>';
    $footer.= $TEXT.
    '</div>
    <div class="col-7 col-sm-5 col-md-5 col-lg-2  ml-auto " >'.$BTN.'</div>';

    return Response()->json(['html' => $html,'footer'=>$footer,'status' => $PLANING_CHECK_BY,'repair_unid' => $UNID_REPAIR]);

  }
 public function ConFirm(Request $request){

   $UNID     = $request->REPAIR_REQ_UNID;
   $PD_CODE  = $request->USER_PD_CODE;
   $DATA_PD  = EMPALL::select("EMP_TH_NAME_FIRST",'EMP_TH_NAME_LAST','EMP_CODE','UNID')->where('EMP_CODE','=',$PD_CODE)->where('EMP_STATUS','=','9')->first();
   $PD_NAME  = $DATA_PD->EMP_TH_NAME_FIRST.' '.$DATA_PD->EMP_TH_NAME_LAST;

   MachineRepairREQ::where('UNID','=',$UNID)->update([
      'PD_UNID'       => $DATA_PD->UNID
     ,'PD_CODE'       => $DATA_PD->EMP_CODE
     ,'PD_NAME'       => $PD_NAME
     ,'PD_CHECK_DATE' => date('Y-m-d')
     ,'PD_CHECK_TIME' => date('H:i:s')
     ,'PD_CHECK_STATUS' => 1
   ]);
   History::where('REPAIR_REQ_UNID','=',$UNID)->update([
     'PD_CHECK_BY' => $DATA_PD->UNID
   ]);
   return Response()->json(['pass'=>'true']);
 }

}
