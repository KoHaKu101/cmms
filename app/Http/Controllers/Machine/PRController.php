<?php

namespace App\Http\Controllers\Machine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
use Cookie;
use App\Models\Machine\SparePart;
use App\Models\Machine\Machine;
use App\Models\Machine\DocItemOut;
use App\Models\Machine\DocItemOutDetail;
use App\Models\Machine\EMPName;
use App\Models\Machine\Company;
//******************** model ***********************
//************** Package form github ***************

class PRController extends Controller
{
  protected $pdf;
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

  public function ItemoutList(Request $request){
    $DocItemOutDetail = DocItemOut::where('STATUS','=',0);
    if ($DocItemOutDetail->count() > 0 ) {
      $DocItemOutDetail = $DocItemOutDetail->first();
      DocItemOutDetail::where('STATUS','=',0)->delete();
      DocItemOut::where('STATUS','=',0)->delete();
    }
    $COOKIE_PAGE_TYPE     = $request->cookie('PAGE_TYPE');
    if ($COOKIE_PAGE_TYPE != 'MACHINE_LIST') {
      $COOKIE_PAGE_TYPE   = $request->cookie();
      foreach ($COOKIE_PAGE_TYPE as $index => $row) {
        if ($index == 'XSRF-TOKEN' || str_contains($index,'session') == true) {
        }else {
          Cookie::queue(Cookie::forget($index));
        }
      }
    }
    $DOC_YEAR         = $request->DOC_YEAR  != '' ? $request->DOC_YEAR  :
                        ($request->Cookie('DOC_YEAR') != '' ? $request->Cookie('DOC_YEAR') : date('Y'));

    $DOC_MONTH        = $request->DOC_MONTH != '' ? $request->DOC_MONTH :
                        ($request->Cookie('DOC_MONTH')!= '' ? $request->Cookie('DOC_MONTH'): date('n'));

    $STATUS           = $request->STATUS    != '' ? $request->STATUS    :
                        ($request->Cookie('STATUS')   != '' ? $request->Cookie('STATUS')   : '9');
    $MINUTES = 30;
    Cookie::queue('DOC_YEAR',$DOC_YEAR,$MINUTES);
    Cookie::queue('DOC_MONTH',$DOC_MONTH,$MINUTES);
    Cookie::queue('STATUS',$STATUS,$MINUTES);
    Cookie::queue('PAGE_TYPE','PR_ITEM_OUT',$MINUTES);
    $DocItemOut       = DocItemOut::select('*')->selectraw('dbo.decode_utf8(EMP_NAME) as EMP_NAME_TH')
                        ->where(function($query) use ($DOC_YEAR){
                          if ($DOC_YEAR != 0) {
                            $query->where('DOC_YEAR','=',$DOC_YEAR);
                          }
                        })
                        ->where(function($query) use ($DOC_MONTH){
                          if ($DOC_MONTH != 0) {
                            $query->where('DOC_MONTH','=',$DOC_MONTH);
                          }
                        })
                        ->where(function($query) use ($STATUS){
                          if ($STATUS != 0) {
                            $query->where('STATUS','=',$STATUS);
                          }else {
                            $query->where('STATUS','!=','8');
                          }
                        })->orderBy('DOC_DATE')->orderBy('DOC_NO','DESC')->get();
    return view('machine.pr.itemoutlist',compact('DocItemOut','DOC_MONTH','DOC_YEAR','STATUS'));
  }
  public function Detail(Request $request){
    $DOC_ITEMOUT_UNID    = $request->UNID;
    $DOC_ITEM            = DocItemOut::select('DOC_TYPE','COUNT_DETAIL','DOC_DATE','COMPANY_NAME')->selectRaw('dbo.decode_utf8(EMP_NAME) as EMP_NAME_TH')
                                      ->where('UNID','=',$DOC_ITEMOUT_UNID)->first();
    $DATA_ITEM_DETAIL    = DocItemOutDetail::selectraw('UNID,DATE_REC_CORRECT,SPAREPART_NAME,MACHINE_CODE,NOTE,TOTAL_OUT,SPAREPART_UNIT,DATE_REC,PR_CODE,SERVICES_CODE')
                                           ->where('DOC_ITEMOUT_UNID','=',$DOC_ITEMOUT_UNID)->get();
    $COUNT_DETAIL        = DocItemOutDetail::select('STATUS')->where('DOC_ITEMOUT_UNID','=',$DOC_ITEMOUT_UNID)->where('STATUS','=',9)->count();
    $CHECKED_RADIO_SELL  = $DOC_ITEM->DOC_TYPE == '1' ? 'checked' : '';
    $CHECKED_RADIO_OUT   = $DOC_ITEM->DOC_TYPE == '9' ? 'checked' : '';
    $TEXT_TYPE           = $DOC_ITEM->DOC_TYPE == '9' ?  'กำหนดส่งคืน' : 'วันที่ขาย';
    $READONLY_TEXT       = 'readonly';
    $FORM                = 'form-control-plaintext';
    $BG_COLOR            = 'has-error' ;
    $STATUS              = 'EDIT';
    $TEXT_BTN            = '<i class="fas fa-edit mx-2"></i>แก้ไข';
    if ($DOC_ITEM->COUNT_DETAIL == $COUNT_DETAIL) {
      $READONLY_TEXT     = '';
      $FORM              = 'form-control';
      $STATUS            = 'SAVE';
      $TEXT_BTN          = '<i class="fas fa-save mx-2"></i>Save';
    }
    $html             = '<div class="row">
                          <div class="col-md-5 form-inline">
                            <label >วัตถุประสงค์</label>
                              <label class="container col-md-2">ขาย
                                <input type="radio" name="DOC_TYPE" id="DOC_TYPE" value="SELL"disabled '.$CHECKED_RADIO_SELL.'>
                                <span class="checkmark"></span>
                              </label>
                                <label class="container col-md-5">ขอยืม/ส่งซ่อม
                                <input type="radio" name="DOC_TYPE" id="DOC_TYPE" value="OUT" disabled '.$CHECKED_RADIO_OUT.'>
                                <span class="checkmark"></span>
                              </label>
                          </div>
                          <div class="col-md-3 form-inline">
                            <label >วันที</label>
                            <input type="date" class="form-control form-control-sm mx-2" value="'.date('Y-m-d',strtotime($DOC_ITEM->DOC_DATE)).'" readonly>
                          </div>
                          <div class="col-md-4 form-inline">
                            <label>ผู้นำออก</label>
                            <input type="text" class="form-control form-control-sm mx-2 " value="'.$DOC_ITEM->EMP_NAME_TH.'" readonly>
                          </div>
                        </div>
                        <div class="row my-2">
                          <div class="col-md-7 form-inline">
                            <label>บริษัท</label>
                            <input type="text" class="form-control form-control-sm col-md-9 mx-2" value="'.$DOC_ITEM->COMPANY_NAME.'" readonly>
                          </div>
                        </div>
                        <div class="row">
        									<div class="col-md-4 form-inline">
        										<label>มีรายละเอียดต่อไปนี้</label>
        									</div>
        								</div>
                        <div class="row my-2">
          									<div class="col-md-12">
          										<table class="table table-bordered table-head-bg-info table-bordered-bd-info ">
          										<thead>
          											<tr>
          												<th width="5%">ลำดับ</th>
          												<th width="30%">รายการ</th>
          												<th width="5%">จำนวน</th>
          												<th width="8%">หน่วย</th>
          												<th width="12%">'.$TEXT_TYPE.'</th>
                                  <th width="10%">ส่งคืนจริง</th>
                                  <th width="15%">PR Code</th>
                                  <th width="15%">Service Code</th>
          											</tr>
          										</thead>
          										<tbody>';
                              foreach ($DATA_ITEM_DETAIL as $key => $row) {
                                $UNID                      = $row->UNID;
                                $DATE_REC_CORRECT          = $row->DATE_REC_CORRECT == '' ? date('Y-m-d') : date('Y-m-d',strtotime($row->DATE_REC_CORRECT));
                      $html .= '<tr>
          												<td class="text-center">'.$key+1 .'</td>
          			                  <td>'.$row->SPAREPART_NAME.' เครื่อง : '.$row->MACHINE_CODE.' อาการ : '.$row->NOTE.'</td>
          			                  <td class="text-center">'.$row->TOTAL_OUT.'</td>
          			                  <td class="text-center">'.$row->SPAREPART_UNIT.'</td>
          			                  <td>'.date('d-m-Y',strtotime($row->DATE_REC)).'</td>
                                  <td class="'.$BG_COLOR.'">
                                    <input type="hidden" id="UNID['.$UNID.']" name="UNID['.$UNID.']" value="'.$UNID.'">
                                    <input type="date" class="'.$FORM.' form-control-sm frm_rec my-2"
                                      id="REC_DATE['.$UNID.']" name="REC_DATE['.$UNID.']"
                                      style="width: 90%;" value="'.$DATE_REC_CORRECT.'" '.$READONLY_TEXT.'>
                                  </td>
                                  <td class="'.$BG_COLOR.'">
                                    <input type="text" class="'.$FORM.' form-control-sm frm_rec my-2"
                                      id="PR_CODE['.$UNID.']" name="PR_CODE['.$UNID.']"
                                      value="'.$row->PR_CODE.'" '.$READONLY_TEXT.'>
                                  </td>
                                  <td class="'.$BG_COLOR.'">
                                    <input type="text" class="'.$FORM.' form-control-sm frm_rec my-2"
                                      id="SERVICES_CODE['.$UNID.']" name="SERVICES_CODE['.$UNID.']"
                                      value="'.$row->SERVICES_CODE.'" '.$READONLY_TEXT.'>
                                  </td>
          											</tr>';
                              }
          						$html .='</tbody>
          									</table>
          									</div>
        								</div>';

    return Response()->Json(['html' => $html,'DOC_ITEMOUT_UNID' => $DOC_ITEMOUT_UNID,'STATUS'=>$STATUS,'TEXT_BTN' => $TEXT_BTN]);
  }
  public function OpenModal(Request $request){
    $DATA_EMP      = EMPName::select('*')->selectraw('dbo.decode_utf8(EMP_NAME) as EMP_NAME_TH')->where('EMP_STATUS','=',9)->get();
    $DATA_COMPANY  = Company::where('STATUS','=',9)->orderBy('COMPANY_NAME')->get();
    $html_emp      = '<select class="form-control form-control-sm" id="EMP_UNID" name="EMP_UNID">';
    $html_company  = '<select class="form-control form-control-sm" id="COMPANY_UNID" name="COMPANY_UNID">';

    foreach ($DATA_EMP as $key => $row_emp) {
      $html_emp    .= '<option value="'.$row_emp->UNID.'">'.$row_emp->EMP_NAME_TH.'</option>';
    }
    foreach ($DATA_COMPANY as $key => $row_company) {
      $html_company.= '<option value="'.$row_company->UNID.'">'.$row_company->COMPANY_NAME.'</option>';
    }
    $html_emp      .='</select>';
    $html_company  .='</select>';
    return Response()->json(['html_emp' => $html_emp,'html_company' => $html_company]);
  }
  public function TypeSelect(Request $request){
    $html           = '';
    $date           = date('Y-m-d');
    $DOC_ITEM_UNID  = $request->DOC_ITEM_UNID;
      $DATA_MACHINE = Machine::select('UNID','MACHINE_CODE')->where('MACHINE_TYPE_STATUS','=','9')->where('MACHINE_STATUS','=','9')
                             ->orderBy('MACHINE_CODE')->get();
      $DATA_DocItemOutDetail = DocItemOutDetail::select('SPAREPART_UNID')->where('DOC_ITEMOUT_UNID','=',$DOC_ITEM_UNID)->get();
      $DATA_SELECT           = SparePart::where('STATUS','=','9')->orderBy('SPAREPART_NAME')->get();
      if (count($DATA_DocItemOutDetail) > 0 ) {
        $DATA_SELECT         = Sparepart::whereNotIn('UNID',$DATA_DocItemOutDetail)->get();
      }
      $html.='<div class="row">
              <div class="col-md-6">
                <label>รายการอะไหล่</label>
                <select class="form-control form-control-sm select2" id="SPAREPART_UNID" name="SPAREPART_UNID" >';
                $html.='<option value="0"> -- </option>';
                foreach ($DATA_SELECT as $index => $row) {
                  $html.='<option value="'.$row->UNID.'">'.$row->SPAREPART_CODE.' : '.$row->SPAREPART_NAME.'</option>';
                }
      $html.='</select>
              </div>
              <div class="col-md-4">
                <label>เครื่องจักร</label>
                <select class="form-control form-control-sm select2" id="MACHINE_UNID" name="MACHINE_UNID">';
                $html.='<option value="0"> -- </option>';
                foreach ($DATA_MACHINE as $index => $sub_row) {
                  $html.='<option value="'.$sub_row->UNID.'">'.$sub_row->MACHINE_CODE.'</option>';
                }
      $html.= '</select>
                </div>
                <div class="col-md-2">
                  <label>จำนวน</label>
                  <input type="number" class="form-control form-control-sm" id="TOTAL_OUT" name="TOTAL_OUT" min="1" value="1">
                </div>
              </div>
              <div class="row">
                <div class="col-md-6" id="DANGER_FORM">
                  <label>อาการ</label>
                  <textarea class="form-control form-control-sm" row="2" id="NOTE" name="NOTE"></textarea>
                  <lable class="text-danger" hidden id="alerttext">กรุณากรอกอาการ</lable>
                </div>
                <div class="col-md-4">
                  <label>กำหนดส่งคืน</label>
                  <input type="date" class="form-control form-control-sm" value="'.$date.'" id="DATE_REC" name="DATE_REC">
                </div>
                <div class="ml-auto col-md-2 text-right">
                  <label class="text-white">ปุ่มกด</label>
                  <button type="button" class="btn btn-primary btn-sm mx-1" id="BTN_SAVE"><i class="fas fa-plus mx-1"></i>เพิ่ม</button>
                </div>
              </div>';

    return Response()->Json(['html'=>$html]);
  }
  public function SaveStep1(Request $request){

    $UNID     = isset($request->UNID) ? $request->UNID : $this->randUNID('PMCS_CMMS_DOC_ITEMOUT');
    $COMPANY  = Company::where('UNID','=',$request->COMPANY_UNID)->first();
    $EMP      = EMPName::where('UNID','=',$request->EMP_UNID)->first();
    $CHECK    = DocItemOut::where('UNID','=',$UNID)->count();
    $DOC_TYPE = $request->DOC_TYPE == 'SELL' ? '1' : '9';
    if ($CHECK > 0) {
      DocItemOut::where('UNID','=',$UNID)->update([
        'DOC_NO'        => ''
        ,'DOC_DATE'     => date('Y-m-d',strtotime($request->DOC_DATE))
        ,'DOC_YEAR'     => date('Y',strtotime($request->DOC_DATE))
        ,'DOC_MONTH'    => date('m',strtotime($request->DOC_DATE))
        ,'DOC_TYPE'     => $DOC_TYPE
        ,'COMPANY_UNID' => $COMPANY->UNID
        ,'COMPANY_NAME' => $COMPANY->COMPANY_NAME
        ,'EMP_NAME'     => $EMP->EMP_NAME
        ,'EMP_CODE'     => $EMP->EMP_CODE
        ,'CANCEL_NOTE'  => ''
        ,'DATE_SET_REC' => ''
        ,'STATUS'       => 0
        ,'COUNT_DETAIL' => 0
        ,'COST_TOTAL'   => 0
        ,'MODIFY_BY'    => Auth::user()->name
        ,'MODIFY_TIME'  => Carbon::now()
       ]);
    }else {
      DocItemOut::insert([
        'UNID'          => $UNID
        ,'DOC_NO'       => ''
        ,'DOC_DATE'     => date('Y-m-d',strtotime($request->DOC_DATE))
        ,'DOC_YEAR'     => date('Y',strtotime($request->DOC_DATE))
        ,'DOC_MONTH'    => date('m',strtotime($request->DOC_DATE))
        ,'DOC_TYPE'     => $DOC_TYPE
        ,'COMPANY_UNID' => $COMPANY->UNID
        ,'COMPANY_NAME' => $COMPANY->COMPANY_NAME
        ,'EMP_NAME'     => $EMP->EMP_NAME
        ,'EMP_CODE'     => $EMP->EMP_CODE
        ,'CANCEL_NOTE'  => ''
        ,'DATE_SET_REC' => ''
        ,'STATUS'       => 0
        ,'COUNT_DETAIL' => 0
        ,'COST_TOTAL'   => 0
        ,'CREATE_BY'    => Auth::user()->name
        ,'CREATE_TIME'  => Carbon::now()
        ,'MODIFY_BY'    => Auth::user()->name
        ,'MODIFY_TIME'  => Carbon::now()
       ]);
    }

    return Response()->json(['UNID'=>$UNID]);
  }
  public function SaveStep2(Request $request){
    $MACHINE_UNID   = $request->MACHINE_UNID;
    $SPAREPART_UNID = $request->SPAREPART_UNID;
    if ($MACHINE_UNID == 0 && $SPAREPART_UNID == 0 ) {
      return Response()->Json(['pass' => false]);
    }
          $UNID           = $this->randUNID('PMCS_CMMS_DOC_ITEMOUT_DETAIL');
          $DOC_ITEM_UNID  = $request->DOC_ITEM_UNID;
          $SPAREPART_NAME = '';
          $SPAREPART_UNIT = '';
          $UNID_MACHINE   = '';
          $MACHINE_CODE   = '';
          $TOTAL_OUT      = 1;
          if ($SPAREPART_UNID != 0) {
            $Sparepart      = Sparepart::selectraw('UNID,SPAREPART_NAME,UNIT')->where('UNID','=',$SPAREPART_UNID)->first();
            $SPAREPART_UNID = $Sparepart->UNID;
            $SPAREPART_NAME = $Sparepart->SPAREPART_NAME;
            $SPAREPART_UNIT = $Sparepart->UNIT;
            $TOTAL_OUT      = $request->TOTAL_OUT;
          }
          if ($MACHINE_UNID != 0) {
            $Machine        = Machine::select('UNID','MACHINE_CODE')->where('UNID','=',$MACHINE_UNID)->first();
            $UNID_MACHINE   = $Machine->UNID;
            $MACHINE_CODE   = $Machine->MACHINE_CODE;
          }
          $INDEX          = DocItemOutDetail::selectraw('DETAIL_INDEX')->where('DOC_ITEMOUT_UNID','=',$DOC_ITEM_UNID)->count();
          $NOTE           = $request->NOTE != '' ? $request->NOTE : '';
          $COUNT          = 1;
          if ($INDEX > 0 ) {
            $COUNT = $INDEX+1;
          }
          DocItemOutDetail::insert([
            'UNID'             => $UNID
            ,'DOC_ITEMOUT_UNID'=> $DOC_ITEM_UNID
            ,'SPAREPART_UNID'  => $SPAREPART_UNID
            ,'MACHINE_UNID'    => $UNID_MACHINE
            ,'SPAREPART_NAME'  => $SPAREPART_NAME
            ,'SPAREPART_UNIT'  => $SPAREPART_UNIT
            ,'MACHINE_CODE'    => $MACHINE_CODE
            ,'TOTAL_OUT'       => $TOTAL_OUT
            ,'DATE_REC'        => $request->DATE_REC
            ,'DETAIL_INDEX'    => $COUNT
            ,'PR_CODE'         => ''
            ,'SERVICES_CODE'   => ''
            ,'STATUS'          => 0
            ,'COST_TOTAL'      => 0
            ,'NOTE'            => $NOTE
            ,'CREATE_BY'       => Auth::user()->name
            ,'CREATE_TIME'     => Carbon::now()
            ,'MODIFY_BY'       => Auth::user()->name
            ,'MODIFY_TIME'     => Carbon::now()
          ]);
      $loopselect            = $this->loopselect($DOC_ITEM_UNID);
    return Response()->Json(['html'=>$loopselect['html'],'select' => $loopselect['select'],'pass' => true]);

  }
  public function ShowResult(Request $request){
    $DOC_ITEM_UNID      = $request->UNID;
    $DOC_ITEM           = DocItemOut::select('*')->selectraw('dbo.decode_utf8(EMP_NAME) as EMP_NAME_TH')->where('UNID','=',$DOC_ITEM_UNID)->first();
    $DATA_ITEM_DETAIL   = DocItemOutDetail::where('DOC_ITEMOUT_UNID','=',$DOC_ITEM_UNID)->get();
    $CHECKED_RADIO_SELL = $DOC_ITEM->DOC_TYPE == '1' ? 'checked' : '';
    $CHECKED_RADIO_OUT  = $DOC_ITEM->DOC_TYPE == '9' ? 'checked' : '';
    $html             = '<div class="row">
                          <div class="col-md-7 form-inline">
                            <label >วัตถุประสงค์</label>
                              <label class="container col-md-3 mx-2">ขาย
                                <input type="radio" name="DOC_TYPE" id="DOC_TYPE" value="SELL"disabled '.$CHECKED_RADIO_SELL.'>
                                <span class="checkmark"></span>
                              </label>
                              <label class="container col-md-6">ขอยืม/ส่งซ่อม
                                <input type="radio" name="DOC_TYPE" id="DOC_TYPE" value="OUT" disabled '.$CHECKED_RADIO_OUT.'>
                                <span class="checkmark"></span>
                              </label>
                          </div>
                          <div class="col-md-5 form-inline">
                            <label >วันที</label>
                            <input type="date" class="form-control form-control-sm mx-2" value="'.date('Y-m-d',strtotime($DOC_ITEM->DOC_DATE)).'" readonly>
                          </div>
                        </div>
                        <div class="row my-2">
                          <div class="col-md-5 form-inline">
                            <label>ผู้นำออก</label>
                            <input type="text" class="form-control form-control-sm mx-2" value="'.$DOC_ITEM->EMP_NAME_TH.'" readonly>
                          </div>
                          <div class="col-md-7 form-inline">
                            <label>บริษัท</label>
                            <input type="text" class="form-control form-control-sm col-md-9 mx-2" value="'.$DOC_ITEM->COMPANY_NAME.'" readonly>
                          </div>
                        </div>
                        <div class="row">
        									<div class="col-md-4 form-inline">
        										<label>มีรายละเอียดต่อไปนี้</label>
        									</div>
        								</div>
                        <div class="row my-2">
        									<div class="col-md-12">
        										<table class="table table-bordered table-head-bg-info table-bordered-bd-info ">
        										<thead>
        											<tr>
        												<th width="5%">ลำดับ</th>
        												<th width="55%">รายการ</th>
        												<th width="10%">จำนวน</th>
        												<th width="10%">หน่วย</th>
        												<th width="20%">กำหนดส่งคืน</th>
        											</tr>
        										</thead>
        										<tbody>';
                            foreach ($DATA_ITEM_DETAIL as $key => $row) {
                    $html .= '<tr>
        												<td class="text-center">'.$key+1 .'</td>
        			                  <td>'.$row->SPAREPART_NAME.' เครื่อง : '.$row->MACHINE_CODE.' อาการ : '.$row->NOTE.'</td>
        			                  <td class="text-center">'.$row->TOTAL_OUT.'</td>
        			                  <td class="text-center">'.$row->SPAREPART_UNIT.'</td>
        			                  <td>'.date('d-m-Y',strtotime($row->DATE_REC)).'</td>
        											</tr>';
                            }
        						$html .='</tbody>
        									</table>
        									</div>
        								</div>';
    return Response()->Json(['html' => $html]);
  }
  public function SaveResult(Request $request){
    $DOC_ITEM_UNID = $request->UNID;
    $MAX_DOC_NO    = DocItemOut::selectraw('Max(DOC_NO)DOC_NO')->first();
    $DOC_NO        = $MAX_DOC_NO->DOC_NO == '' ? '' : $MAX_DOC_NO->DOC_NO;

    $DOCITEMOUT    = DocItemOut::select('DOC_NO','DOC_DATE','EMP_NAME')->where('DOC_NO','=',$DOC_NO)->first();

    $DOC_NO        = $DOCITEMOUT->DOC_NO != '' ? $DOCITEMOUT->DOC_NO : 'PI'.date('ym').sprintf('%04d', 1);

    if (date('ym',strtotime($DOCITEMOUT->DOC_DATE)) != date('ym') ) {
      $DOC_NO = 'PI'.date('ym').sprintf('%04d', 1);
    }elseif (date('ym',strtotime($DOCITEMOUT->DOC_DATE)) == date('ym') && $DOCITEMOUT->DOC_NO != '' ) {
      $REPLACE_DOCNO = str_replace('PI','',$DOC_NO);
      $DOC_NO = 'PI'.$REPLACE_DOCNO+1;
    }
    DocItemOut::where('UNID','=',$DOC_ITEM_UNID)->update([
      'DOC_NO'       => $DOC_NO
      ,'STATUS'       => 9
      ,'COUNT_DETAIL' => DocItemOutDetail::where('DOC_ITEMOUT_UNID','=',$DOC_ITEM_UNID)->count()
      ,'MODIFY_BY'    => Carbon::now()
      ,'MODIFY_TIME'  => Auth::user()->name
    ]);
    DocItemOutDetail::where('DOC_ITEMOUT_UNID','=',$DOC_ITEM_UNID)->update([
      'STATUS'       => 9
    ]);
    return Response()->json(['pass'=>true]);
  }
  public function SaveRec(Request $request){
    $UNID                  = $request->UNID;
    $DOC_ITEMOUT_UNID      = $request->DOC_ITEMOUT_UNID;
    $COUNT_PR_CODE         = count(array_filter($request->PR_CODE,function($x) { return !empty($x); }) );
    $COUNT_SERVICES_CODE   = count(array_filter($request->SERVICES_CODE,function($x) { return !empty($x); }) );
    if ($COUNT_PR_CODE == 0 && $COUNT_SERVICES_CODE == 0) {
      return Response()->Json(['pass' => false]);
    }
    foreach ($UNID as $key => $value) {
      $REC_DATE = $request->REC_DATE[$key];
      $PR_CODE = $request->PR_CODE[$key];
      $SERVICES_CODE = $request->SERVICES_CODE[$key];
      if ($REC_DATE != '' && $PR_CODE != '' && $SERVICES_CODE != '') {
        DocItemOutDetail::where('UNID','=',$key)->update([
          'DATE_REC_CORRECT' => $REC_DATE
         ,'STATUS'           => 1
         ,'PR_CODE'          => $PR_CODE
         ,'SERVICES_CODE'    => $SERVICES_CODE
       ]);
      }elseif ($PR_CODE != '' || $SERVICES_CODE != '') {
        DocItemOutDetail::where('UNID','=',$key)->update([
          'PR_CODE'         => $PR_CODE
          ,'SERVICES_CODE'  => $SERVICES_CODE
        ]);
      }
    }
      $CHECK_STATUS = DocItemOutDetail::where('DOC_ITEMOUT_UNID','=',$DOC_ITEMOUT_UNID)->where('STATUS','=',1)->count();
      $STATUS       = DocItemOutDetail::where('DOC_ITEMOUT_UNID','=',$DOC_ITEMOUT_UNID)->count();

      if ( $CHECK_STATUS == $STATUS) {
         DocItemOut::where('UNID','=',$DOC_ITEMOUT_UNID)->update([
           'STATUS' => 1 ,
         ]);
      }
      return Response()->Json(['pass' => true]);
  }
  public function DeleteDetail(Request $request){
    $UNID             = $request->UNID;
    $DOC_ITEMOUT_UNID = $request->DOC_ITEMOUT_UNID;
    $DocItemOutDetail = DocItemOutDetail::select('SPAREPART_UNID')->where('UNID','=',$UNID)->first();
    DocItemOutDetail::where('UNID','=',$UNID)->delete();
    $loopselect       = $this->loopselect($DOC_ITEMOUT_UNID);
    if ($DocItemOutDetail == '') {
    $loopselect       = $this->loopselect($DOC_ITEMOUT_UNID);
    }
    return Response()->Json(['html'=>$loopselect['html'],'select' => $loopselect['select']]);
  }
  public function CancelDoc(Request $request){
    $note = $request->note;
    $UNID = $request->unid;
    if ($note != '') {
      DocItemOut::where('UNID','=',$UNID)->update([
        'CANCEL_NOTE'   => $note
        ,'STATUS'       => '8'
        ,'MODIFY_BY'    => Carbon::now()
        ,'MODIFY_TIME'  => Auth::user()->name

      ]);
      DocItemOutDetail::where('DOC_ITEMOUT_UNID','=',$UNID)->update([
        'STATUS'       => '8'
        ,'MODIFY_BY'    => Carbon::now()
        ,'MODIFY_TIME'  => Auth::user()->name
      ]);
      return Response()->Json(['pass' => true]);
    }else {
      return Response()->Json(['pass' => false]);
    }
  }
  public function loopselect($DOC_ITEM_UNID){
    $DATA_DocItemOutDetail = DocItemOutDetail::where('DOC_ITEMOUT_UNID','=',$DOC_ITEM_UNID)->get();
    $html = '';
    $ARRAY_SPAREPART = array();
    foreach ($DATA_DocItemOutDetail as $key => $row) {
      $MACHINE = $row->MACHINE_CODE != '' ? ' เครื่อง : '.$row->MACHINE_CODE : '';
      $html.= '<tr>
                 <td class="text-center">'.$key+1 .'</td>
                 <td>'.$row->SPAREPART_NAME.$MACHINE.' อาการ : '.$row->NOTE.'</td>
                 <td class="text-center">'.$row->TOTAL_OUT.'</td>
                 <td class="text-center">'.$row->SPAREPART_UNIT.'</td>
                 <td>'.date('d-m-Y',strtotime($row->DATE_REC)).'</td>
                 <td>
                  <button type="button" class="btn btn-danger btn-sm btn-block my-1" onclick="deletedetail(this)"
                  data-unid="'.$row->UNID.'"
                  data-itemunid = "'.$row->DOC_ITEMOUT_UNID.'"><i class="fas fa-trash"></i></button>
                 </td>
               </tr>';
        $ARRAY_SPAREPART[$row->SPAREPART_UNID] = $row->SPAREPART_UNID;
    }
      $DATA_SPAREPART = Sparepart::whereNotIn('UNID',array_keys($ARRAY_SPAREPART))->get();
      $select ='<select class="form-control form-control-sm select2" id="SPAREPART_UNID" name="SPAREPART_UNID" >
                <option value="0"> -- </option>';
        foreach ($DATA_SPAREPART as $index => $row) {
          $select.='<option value="'.$row->UNID.'">'.$row->SPAREPART_CODE.' : '.$row->SPAREPART_NAME.'</option>';
        }
      $select.='</select>';
      return ['select'=>$select,'html'=>$html];




  }

}
