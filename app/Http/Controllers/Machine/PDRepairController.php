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
//*************** Controller ************************
use App\Http\Controllers\Machine\MachineRepairController;

class PDRepairController extends Controller
{
  public function __construct(Request $request){
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
    $COOKIE_PAGE_TYPE = $request->cookie('PAGE_TYPE');
    if ($COOKIE_PAGE_TYPE != 'PD_REPAIR') {
      $DATA_COOKIE = $request->cookie();
      foreach ($DATA_COOKIE as $index => $row) {

        if ($index == 'XSRF-TOKEN' || str_contains($index,'session') == true || $index == 'table_style' || $index == 'table_style_pd') {
        }else {
          Cookie::queue(Cookie::forget($index));
        }
      }
    }

    $SEARCH       = isset($request->SEARCH_MACHINE)     ? $request->SEARCH_MACHINE     : '';
    $MACHINE_LINE = isset($request->LINE)       ? $request->LINE       : ($request->cookie('LINE')       != '' ? $request->cookie('LINE')       : 0 );
    $MONTH        = isset($request->MONTH)      ? $request->MONTH      : ($request->cookie('MONTH')      != '' ? $request->cookie('MONTH')      : date('m') ) ;
    $YEAR         = isset($request->YEAR)       ? $request->YEAR       : ($request->cookie('YEAR')       != '' ? $request->cookie('YEAR')       : date('Y') ) ;
    $DOC_STATUS   = isset($request->DOC_STATUS) ? $request->DOC_STATUS : ($request->cookie('DOC_STATUS') != '' ? $request->cookie('DOC_STATUS') : 9 );
    $MINUTES      = 30;

    Cookie::queue('PAGE_TYPE','PD_REPAIR',$MINUTES);
    Cookie::queue('LINE',$MACHINE_LINE,$MINUTES);
    Cookie::queue('MONTH',$MONTH,$MINUTES);
    Cookie::queue('DOC_STATUS',$DOC_STATUS,$MINUTES);
    Cookie::queue('YEAR',$YEAR,$MINUTES);

    $DATA_EMP     = EMPName::select('EMP_CODE','EMP_ICON')->selectraw('dbo.decode_utf8(EMP_NAME) as EMP_NAME_TH')->where('EMP_STATUS','=',9)->get();
    $dataset      = MachineRepairREQ::select('*')->selectraw('dbo.decode_utf8(EMP_NAME) as EMP_NAME_TH
                                                             ,dbo.decode_utf8(INSPECTION_NAME) as INSPECTION_NAME_TH
                                                             ,dbo.decode_utf8(MACHINE_NAME) as MACHINE_NAME_TH')
                                            ->where(function ($query) use ($MACHINE_LINE) {
                                                  if ($MACHINE_LINE > 0) {
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

    $array_EMP = array();
    $array_IMG = array();
    foreach ($DATA_EMP as $index => $row_emp) {
      $array_EMP[$row_emp->EMP_CODE] = $row_emp->EMP_NAME_TH;
      $array_IMG[$row_emp->EMP_CODE] = $row_emp->EMP_ICON;
    }
    $LINE         = MachineLine::select('LINE_CODE','LINE_NAME')->where('LINE_STATUS','=','9')->where('LINE_NAME','like','Line'.'%')->orderBy('LINE_NAME')->get();
    return View('machine/repair/repairlistpd',compact('dataset','SEARCH','LINE',
    'MACHINE_LINE','MONTH','YEAR','DOC_STATUS','array_EMP','array_IMG'));
  }
  public function FetchData(Request $request){
    $SEARCH         = isset($request->SEARCH_MACHINE) ? $request->SEARCH_MACHINE : '';
    $MACHINE_LINE   = isset($request->LINE)   ? $request->LINE   : '';
    $MONTH          = isset($request->MONTH)  ? $request->MONTH  : 0 ;
    $DOC_STATUS     = isset($request->DOC_STATUS) ? $request->DOC_STATUS : 0 ;
    $YEAR           = isset($request->YEAR)   ? $request->YEAR   : date('Y') ;
    $page           = $request->page;
    $dataset        = MachineRepairREQ::select('*')->selectraw('dbo.decode_utf8(EMP_NAME) as EMP_NAME_TH
                                                               ,dbo.decode_utf8(INSPECTION_NAME) as INSPECTION_NAME_TH
                                                               ,dbo.decode_utf8(MACHINE_NAME) as MACHINE_NAME_TH')
                                            ->where(function ($query) use ($MACHINE_LINE) {
                                                  if ($MACHINE_LINE > 0) {
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
    $html = '';
    $html_style = '';
    //********************************** TABLE ******************************************************************
    foreach ($dataset->items($page) as $key => $row) {
      //************************* BUTTON *****************************************
      $BTN_CONFIRM			= $row->CLOSE_STATUS		== '1' ? "onclick=ConFirmForm(this)" : '';
      $BTN              = '<button class="btn btn-danger btn-sm btn-block my-1"
                            style="cursor:default">รอรับงาน</button>';
      if ($row->PD_CHECK_STATUS == '1') {
        $BTN_COLOR_WORKER = 'btn-secondary';
        $BTN				      = '<button onclick=pdfsaverepair("'.$row->UNID.'") type="button"
                              class="btn btn-primary btn-block btn-sm my-1 text-left">
                              <span class="btn-label">
                              <i class="fas fa-clipboard-check mx-1"></i>
                                จัดเก็บเอกสารสำเร็จ
                              </span>
                            </button>';
      }elseif ($row->CLOSE_STATUS == '1') {
        $BTN_COLOR_WORKER = 'btn-pink';
        $BTN				      = '<button onclick=ConFirmForm(this) type="button"
                              data-unid="'.$row->UNID.'"
                              data-docno="'.$row->DOC_NO.'"
                              data-detail="'.$row->REPAIR_SUBSELECT_NAME.'"
                              class="btn btn-primary btn-block btn-sm my-1 text-left">
                              <span class="btn-label">
                              <i class="fas fa-clipboard mx-1"></i>
                                ดำเนินการสำเร็จ
                              </span>
                            </button>';
      }elseif ($row->INSPECTION_CODE != '') {
        $BTN				= '<button class="btn btn-warning btn-sm btn-block my-1 text-left"
                         style="cursor:default">
                         <i class="fas fa-wrench fa-lg mx-1"></i>
                         '.$row->INSPECTION_NAME_TH.'
                       </button>';
      }
      //************************* END BUTTON *****************************************
      $html.= '<tr>
                 <td>'.$dataset->firstItem() + $key.'</td>
                 <td width="9%">'.date('d-m-Y',strtotime($row->DOC_DATE)).'</td>
                 <td width="11%">'.$row->DOC_NO.'</td>
                 <td width="4%">'.$row->MACHINE_LINE.'</td>
                 <td width="8%">'.$row->MACHINE_CODE.'</td>
                 <td>'.$row->MACHINE_NAME_TH.'</td>
                 <td>'.$row->REPAIR_SUBSELECT_NAME.'</td>
                 <td width="15%">'.$BTN.'</td>
                 <td width="9%">'.date('d-m-Y').'</td>
              </tr>';
      }
    //********************************** CARD ******************************************************************
    foreach ($dataset->items($page) as $index => $sub_row) {
        $INSPECTION_CODE= $sub_row->INSPECTION_CODE;
        $SPAREPART_UNID = $sub_row->UNID;

        $EMP_NAME       = EMPName::select('EMP_ICON')->where('EMP_CODE','=',$INSPECTION_CODE)->first();
        $IMG         	  = isset($EMP_NAME->EMP_ICON) ? asset('image/emp/'.$EMP_NAME->EMP_ICON) : asset('../assets/img/noemp.png');
        $BG_COLOR    		= isset($INSPECTION_CODE) ? 'bg-warning' : 'bg-danger';
        $TEXT_STATUS    = isset($INSPECTION_CODE) ? 'กำลังดำเนินการ' : 'รอรับงาน';
        $WORK_STATUS 		= isset($INSPECTION_CODE) ? $sub_row->INSPECTION_NAME_TH : 'รอรับงาน';

        $IMG_PRIORITY		= $sub_row->PRIORITY == '9' ? '<img src="'.asset('assets/css/flame.png').'" class="mt--2" width="20px" height="20px">' : '';
        $DATE_DIFF   	  = $sub_row->REC_WORK_DATE != '1900-01-01 00:00:00.000'? 'รับเมื่อ:'.Carbon::parse($sub_row->REC_WORK_DATE)->diffForHumans() : 'แจ้งเมื่อ:'.Carbon::parse($sub_row->CREATE_TIME)->diffForHumans();
        $HTML_STATUS    = '<div class="status" id="DATE_DIFF_'.$SPAREPART_UNID.'">'.$DATE_DIFF.'</div>';
        $HTML_BTN       = '';
        $HTML_AVATAR    = '<img src="'.$IMG.'"id="IMG_'.$SPAREPART_UNID.'"alt="..." class="avatar-img rounded-circle">';
        if ($sub_row->PD_CHECK_STATUS == '1') {
          $TEXT_STATUS  = 'จัดเก็บเอกสารเรียบร้อย';
          $BG_COLOR  		= 'bg-success';
          $HTML_STATUS  = '<div class="status" id="DATE_DIFF_'.$SPAREPART_UNID.'" >ปิดเอกสารเรียบร้อย</div>';
          $HTML_BTN     =	'<button class="btn btn-primary  btn-sm"
                          onclick=pdfsaverepair("'.$SPAREPART_UNID.'")>
                            <i class="fas fa-print mx-1"></i>
                              PRINT
                          </button>';
           $HTML_AVATAR = '<div class="timeline-badge success rounded-circle text-center text-white" style="width: 100%;height: 100%;">
                           <i class="fas fa-check my-2" style="font-size: 35px;"></i></div>' ;
        }elseif ($sub_row->CLOSE_STATUS == '1') {
          $TEXT_STATUS  = 'ดำเนินการสำเร็จ';
          $BG_COLOR  		= 'bg-primary';
          $HTML_STATUS  = '<div class="status" id="DATE_DIFF_'.$SPAREPART_UNID.'" >ดำเนินงานสำเร็จ</div>';
          $HTML_BTN     = '<button class="btn  btn-primary  btn-sm"
                            onclick="ConFirmForm(this)"
                            data-unid="'.$SPAREPART_UNID.'"
                            data-docno="'.$sub_row->DOC_NO.'"
                            data-detail="'.$sub_row->REPAIR_SUBSELECT_NAME.'">
                              CLOSE FORM
                            </button>';
        }
        $html_style .=  '<div class="col-lg-3">
          <div class="card card-round">
            <div class="card-body">
              <div class="card-title fw-mediumbold '.$BG_COLOR.' text-white "id="BG_'.$SPAREPART_UNID.'">
                <div class="row text-center">
                  <div class="col-lg-12">
                    '.$IMG_PRIORITY.'
                    '.$sub_row->MACHINE_CODE.'
                  </div>
                </div>
                <div class="row text-center ">
                  <div class="col-lg-12">
                    <h5>'.$TEXT_STATUS.'</h5>
                    </div>
                </div>
              </div>
              <div class="card-list">
                <div class="item-list">
                  <div class="avatar">
                    '.$HTML_AVATAR.'
                  </div>
                  <div class="info-user ml-3">
                    <div class="username" style=""id="WORK_STATUS_'.$SPAREPART_UNID.'">'.$WORK_STATUS.'</div>
                    <div class="status" >'.$sub_row->REPAIR_SUBSELECT_NAME.'</div>
                     '.$HTML_STATUS.'
                  </div>
                </div>
              </div>
              <div class="row ">
                <div class="col-md-12 text-center">
                   '.$HTML_BTN.'
                </div>
              </div>
            </div>
          </div>
        </div>';
        }
        $last_data = MachineRepairREQ::selectraw('UNID,STATUS')->where('STATUS','=',9)->first();
        $data_count = MachineRepairREQ::selectraw('UNID,STATUS_NOTIFY')->where('STATUS','=',9)->count();
        $newrepair = isset($last_data->STATUS) ? true : false;
        $UNID      = isset($last_data->STATUS) ? $last_data->UNID : '';
        $NUMBER    = $data_count;
    return Response()->json(['html'=>$html,'html_style' => $html_style,'newrepair' => $newrepair,'UNID' => $UNID,'number' => $NUMBER]);
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
                                      from EMCS_EMPLOYEE
                                      where  POSITION_CODE IN ('LD','ASSTMGR','CF')
                                      and EMP_STATUS = '9'
                                      and LINE_CODE NOT IN ('QA','QC','PC','FNL','EG','MK','HR','AC','QS') ");


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
     $RENEW_BTN = '<div class="col col-sm col-lg-4 text-right ">
                    <button class="btn btn-danger btn-sm" type="button" onclick="Renew(this)" data-unid="'.$UNID_REPAIR.'">แจ้งซ่อมไม่สำเร็จ</button>
                   </div>';
     $SELECT_PD = '<select class="form-control form-control-sm col-lg-4 " id="EMP_CODE" name="EMP_CODE">
                     <option value>กรุณาเลือก</option>';
                     foreach ($DATA_PD as $index => $row_pd) {
                       $SELECT_PD.='<option value="'.$row_pd->EMP_CODE.'">'.$row_pd->EMP_TH_NAME_FIRST_TH.' '.$row_pd->EMP_TH_NAME_LAST_TH.'</option>';
                     }
    $SELECT_PD .= '</select>';
    $BTN        = '<button type="button" class="btn btn-secondary btn-sm  text-right"
                   id="ConFirm" data-unid="'.$UNID_REPAIR.'" >
                     <i class="fas fa-clipboard-check fa-2x"> ปิดเอกสาร</i>
                   </button>';
   }else {
     $RENEW_BTN = '<div class="col col-sm col-lg-4 text-right ">
                   </div>';
     $SELECT_PD = '<input type="text" class="form-control-sm form-control-plaintext bg-success text-white text-center mx-2 col-lg-7"
                    value="'.$REPAIR_REQ->PD_NAME_TH.'" >';
     $BTN       = '<button type="button" class="btn btn-secondary btn-sm text-right"
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
    $footer = $RENEW_BTN;

    $footer.='<div class="col-5 col-sm-7 col-md-7 col-lg-6 form-inline" >
              <lable> ผู้ตรวจสอบ (PD)</lable>';
    $footer.= $SELECT_PD.
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
      'PD_UNID'         => $DATA_PD->UNID
     ,'PD_CODE'         => $DATA_PD->EMP_CODE
     ,'PD_NAME'         => $PD_NAME
     ,'PD_CHECK_DATE'   => date('Y-m-d')
     ,'PD_CHECK_TIME'   => date('H:i:s')
     ,'PD_CHECK_STATUS' => 1
    ]);
    History::where('REPAIR_REQ_UNID','=',$UNID)->update([
     'PD_CHECK_BY' => $DATA_PD->UNID
    ]);
    return Response()->json(['pass'=>'true']);
    }
  public function ReadNotify(Request $request){
    $STATUS = $request->STATUS;
    MachineRepairREQ::where('STATUS','=',9)->update([
     'STATUS' => $STATUS,
    ]);
  }
  public function RenewConfirm(Request $request){
    $NOTE = $request->note;
    $UNID = $request->unid;
    if ($NOTE == '') {
      return Response()->json(['pass'=>false]);
    }else {
      MachineRepairREQ::where('UNID','=',$UNID)->update([
        'NOTE_RENEW'  => $NOTE
      ]);
      return Response()->json(['pass'=>true]);
    }

  }
  public function Renew(Request $request){
    $UNID = $request->REPAIR_UNID;
    MachineRepairREQ::where('UNID','=',$UNID)->update([
      'CLOSE_BY'          => ''
      ,'CLOSE_STATUS'    => 9
      ,'STATUS_NOTIFY'   => 9
      ,'STATUS'          => 1
      ,'PD_CHECK_STATUS' => 9
      ,'STATUS_LINE_NOTIFY' => 9
      ,'WORK_STEP'       => ''
      ,'INSPECTION_NAME' => ''
      ,'INSPECTION_CODE' => ''
    ]);
    $REPAIR_CONTROLLER = new MachineRepairController;
    $REPAIR_CONTROLLER->LineNotify($UNID);
  }
}
