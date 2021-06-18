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
use App\Models\Machine\SparePart;
use App\Models\Machine\MachineLine;

use App\Models\Machine\MachineRepairREQ;
//************** Package form github ***************
use App\Exports\MachineExport;
use Maatwebsite\Excel\Facades\Excel;

class MachineRepairController extends Controller
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
    $SEARCH      = isset($request->SEARCH) ? '%'.$request->SEARCH.'%' : '';
    $SERACH_TEXT =  $request->SEARCH;
    $LINE = MachineLine::where('LINE_STATUS','=','9')->where('LINE_NAME','like','Line'.'%')->orderBy('LINE_NAME')->get();
    $MACHINE_LINE = isset($request->LINE) ? $request->LINE : '';
    $MONTH = isset($request->MONTH) ? $request->MONTH : 0 ;
    $DOC_STATUS = isset($request->DOC_STATUS) ? $request->DOC_STATUS : 0 ;
    $YEAR = isset($request->YEAR) ? $request->YEAR : date('Y') ;
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
                                                      $query->where('CLOSE_STATUS', '=', $DOC_STATUS);
                                                   }
                                                 })
                                            ->orderBy('DOC_YEAR','DESC')
                                            ->orderBy('DOC_MONTH','DESC')
                                            ->orderBy('DOC_NO','DESC')
                                            ->paginate(10);
    $MACHINE_ICON  = Machine::select('MACHINE_LINE','MACHINE_ICON','UNID')->first();
    $SEARCH = $SERACH_TEXT;
    return View('machine/repair/repairlist',compact('dataset','SEARCH','LINE',
    'MACHINE_LINE','MONTH','YEAR','DOC_STATUS','MACHINE_ICON'));
  }
  public function FetchData(Request $request){
    $SEARCH         = isset($request->SEARCH) ? '%'.$request->SEARCH.'%' : '';
    $SERACH_TEXT    = $request->SEARCH;
    $MACHINE_LINE   = isset($request->LINE) ? $request->LINE : '';
    $MONTH          = isset($request->MONTH) ? $request->MONTH : 0 ;
    $DOC_STATUS     = isset($request->DOC_STATUS) ? $request->DOC_STATUS : 0 ;
    $YEAR           = isset($request->YEAR) ? $request->YEAR : date('Y') ;
    $dataset        = MachineRepairREQ::select('*')->selectraw('dbo.decode_utf8(EMP_NAME) as EMP_NAME_TH')
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
                                                      $query->where('CLOSE_STATUS', '=', $DOC_STATUS);
                                                   }
                                                 })
                                            ->orderBy('DOC_YEAR','DESC')
                                            ->orderBy('DOC_MONTH','DESC')
                                            ->orderBy('DOC_NO','DESC')
                                            ->paginate(10);
    $SEARCH         = $SERACH_TEXT;
    $html = '';

    foreach ($dataset as $key => $row) {
      $MACHINE_STATUS = $row->MACHINE_STATUS == 1 ? 'หยุดทำงาน' : 'ทำงาน';
      $html.= '<tr>
                <td >'.date('d-m-Y',strtotime($row->DOC_DATE))."".date('H:i',strtotime($row->REPAIR_REQ_TIME)).'</td>
                <td >'.$row->DOC_NO.'</td>
                <td >'.$row->MACHINE_LINE.'</td>
                <td >'.$row->MACHINE_CODE.'</td>
                <td >'.$row->MACHINE_NAME.'</td>
                <td >'.$row->REPAIR_SUBSELECT_NAME.'</td>d
                <td >'.$MACHINE_STATUS.'</td>
                <td >
                  <button type="button"class="btn btn-success btn-block btn-sm my-1 ">
                    <span class="btn-label text-center" style="color:black">
                      รอรับงาน
                    </span>
                  </button>
                </td>';
                if (Gate::allows("isAdminandManager")) {
                  $html.='<td >
                      <button onclick="rec_work(this)" type="button"
                      data-unid="'.$row->UNID.'"
                      data-docno="'.$row->DOC_NO.'"
                      data-detail="'.$row->REPAIR_SUBSELECT_NAME.'"
                      class="btn btn-danger btn-block btn-sm my-1"
                     >
                       <span class="btn-label">
                       <i class="fas fa-clipboard-check mx-1"></i>สุบรรณ
                     </span>
                     </button></td>';
                   }else {
                  $html.='<td ></td>';
                  }


      // dd($html);
      $html.= '<td >'.date('d-m-Y H:i').'</td>
        </tr>';
      }
    return Response()->json(['html'=>$html]);
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
      //******************* Request parameter *******************//
            //Get the highest "id" in the table + 1
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
        $DATA_EMP = MachineEMP::where('EMP_CODE','=',$EMP_CODE)->where('REF_UNID','=',$MACHINE_UNID)->first();
      //******************* docno *******************//
      $DATA_MACHINEREPAIRREQ = MachineRepairREQ::selectraw('max(DOC_NO)DOC_NO,max(DOC_DATE)DOC_DATE')->first();
      $DATE_DOCNO            = Carbon::now()->addyears('543');
      $DATE_RESET_DOCNO      = Carbon::parse($DATA_MACHINEREPAIRREQ->DOC_DATE);
      $DOC_NO = 'RE' . $DATE_DOCNO->format('ym') . sprintf('-%04d', 1);

      if ($DATE_RESET_DOCNO->format('ym') == Carbon::now()->format('ym') ) {
        $EXPLOT = str_replace('RE'.$DATE_RESET_DOCNO->addyears('543')->format('ym').'-','',$DATA_MACHINEREPAIRREQ->DOC_NO)+1;
        $DOC_NO = 'RE' . $DATE_RESET_DOCNO->format('ym'). sprintf('-%04d', $EXPLOT);
      }

      //$DATE_DOCNO->format('y');
      //$DATE_DOCNO->format('m');
      //$DATE_DOCNO->format('d');
      // dd($DATE_DOCNO->format('d'));
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
        ,'EMP_UNID'              => $DATA_EMP->UNID
        ,'EMP_CODE'              => $DATA_EMP->EMP_CODE
        ,'EMP_NAME'              => $DATA_EMP->EMP_NAME
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
      return redirect()->route('repair.edit',$UNID);
  }
  public function Edit($UNID) {
    $data_repairreq = MachineRepairREQ::select('*')->selectraw('dbo.decode_utf8(EMP_NAME) as EMP_NAME')
                                                   ->where('UNID','=',$UNID)->first();
    $dataset = SelectMainRepair::where('STATUS','=','9')->get();
    $data_emp = MachineEMP::select('EMP_CODE','EMP_NAME')->selectraw('dbo.decode_utf8(EMP_NAME) as EMP_NAME')
                          ->where('EMP_STATUS','=','0')->where('REF_UNID','=',$data_repairreq->MACHINE_UNID)->get();

    $data_selectsubrepair = SelectSubRepair::where('REPAIR_MAINSELECT_UNID','=',$data_repairreq->REPAIR_MAINSELECT_UNID)->get();
    $cookie_array = array('empcode' => $data_repairreq->EMP_CODE,'selectmainrepair' => $data_repairreq->REPAIR_MAINSELECT_UNID
                         ,'selectsubrepair' => $data_repairreq->REPAIR_SUBSELECT_UNID,'priority' => $data_repairreq->PRIORITY );
    foreach ($cookie_array as $name => $value) {
      Cookie::queue($name, $value);
    }
    return view('machine/repair/edit',compact('data_repairreq','dataset','data_emp','data_selectsubrepair'));

  }
  public function Update(Request $request,$UNID){
    //******************* Request parameter *******************//
    $EMP_CODE = $request->cookie('empcode');
    $SELECT_MAIN_REPAIR_UNID = $request->cookie('selectmainrepair');
    $SELECT_SUB_REPAIR_UNID = $request->cookie('selectsubrepair');
    $PRIORITY = $request->cookie('priority');

    //******************* data *******************//
    $DATA_MACHINE = MachineRepairREQ::where('UNID','=',$UNID)->first();
    $DATA_SELECTMACHINEREPAIR = SelectMainRepair::where('UNID','=',$SELECT_MAIN_REPAIR_UNID)->first();
    $DATA_SELECTSUBREPAIR = SelectSubRepair::where('UNID','=',$SELECT_SUB_REPAIR_UNID)->first();
    $DATA_EMP = MachineEMP::where('EMP_CODE','=',$EMP_CODE)->where('REF_UNID','=',$DATA_MACHINE->MACHINE_UNID)->first();


    $request->CLOSE_STATUS = '9';
    $data_set = MachineRepairREQ::where('UNID','=',$UNID)->update([
         'MACHINE_STATUS'        => $DATA_SELECTSUBREPAIR->STATUS_MACHINE
        ,'REPAIR_MAINSELECT_UNID'=> $DATA_SELECTMACHINEREPAIR->UNID
        ,'REPAIR_MAINSELECT_NAME'=> $DATA_SELECTMACHINEREPAIR->REPAIR_MAINSELECT_NAME
        ,'REPAIR_SUBSELECT_UNID' => $DATA_SELECTSUBREPAIR->UNID
        ,'REPAIR_SUBSELECT_NAME' => $DATA_SELECTSUBREPAIR->REPAIR_SUBSELECT_NAME
        ,'EMP_UNID'              => $DATA_EMP->UNID
        ,'EMP_CODE'              => $DATA_EMP->EMP_CODE
        ,'EMP_NAME'              => $DATA_EMP->EMP_NAME

        ,'PRIORITY'              => $PRIORITY
        ,'MODIFY_BY'             => Auth::user()->name
        ,'MODIFY_TIME'           => Carbon::now()
      ]);
      $cookie_array = array('0' => 'empcode','1' => 'selectmainrepair','2' => 'selectsubrepair','3' => 'priority' );
      foreach ($cookie_array as $index => $row) {
        Cookie::queue(Cookie::forget($row));
      }
        alert()->success('อัพเดทรายการ สำเร็จ')->autoclose('1500');
            return Redirect()->route('repair.edit',[$UNID]);
          }
  public function Delete($UNID){

        $checkuser = Auth::user();
        if ($checkuser->role == 'user') {
          alert()->error('ไม่สิทธิ์การเข้าถึง')->autoclose('1500');
          return Redirect()->route('user.homepage');
        }
            $CLOSE_STATUS = '1';
              $data_set = MachineRepairREQ::where('UNID',$UNID)->update([
                      'CLOSE_STATUS'          => $CLOSE_STATUS,

                'MODIFY_BY'            => Auth::user()->name,
                'MODIFY_TIME'          => Carbon::now(),
                ]);
                alert()->success('ปิดเอกสารเสำเร็จ')->autoclose('1500');
              return Redirect()->back();
          }
  public function EMPCallAjax(Request $request){

    $REPAIR_REQ_UNID = isset($request->REPAIR_REQ_UNID) ? $request->REPAIR_REQ_UNID : '';
    $DATA_EMPNAME = EMPName::select('*')->selectraw("dbo.decode_utf8(EMP_NAME) as EMP_NAME_TH")->where('EMP_STATUS','=','9')->get();
    $html_select = '<select class="form-control form-control-sm col-9 REC_WORKER_NAME" id="REC_WORKER_NAME" name="REC_WORKER_NAME">
      <option value> กรุณาเลือก </option>';
      foreach ($DATA_EMPNAME as $index => $row){
        $html_select.= '<option value="'.$row->EMP_CODE.'">'.$row->EMP_CODE." ".$row->EMP_NAME_TH.'</option>';
      }
    $html_select.='</select>';
    $html_detail = "";
    if ($REPAIR_REQ_UNID != '') {
      $REPAIR = MachineRepairREQ::select('*')->selectraw("dbo.decode_utf8(EMP_NAME) as EMP_NAME_TH")
                                                  ->where('UNID','=',$REPAIR_REQ_UNID)->first();


      $PRIORITY_TEXT = $REPAIR->PRIORITY == '9' ? 'เร่งด่วน' : 'ไม่เร่งด่วน' ;
      $html_detail.= '<table class="table table-bordered table-bordered-bd-info">
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
              <input type="text" class="form-control form-control-sm" id="DETAIL_REPAIR"
                value="'.$REPAIR->REPAIR_SUBSELECT_NAME.'">
            </td>
          </tr>
          <tr>
            <td style="background:#aab7c1;color:black;"><h5 class="my-1">ระดับ</h5>  </td>
            <td  colspan="3">'.$PRIORITY_TEXT.'</td>
          </tr>
        </tbody>
      </table>';
    }



    return Response()->json(['html_detail'=>$html_detail,'html_select' => $html_select])
    ->withCookie(cookie('DETAIL', $REPAIR->REPAIR_SUBSELECT_NAME));
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
      $EMP_CODE = $request->EMP_CODE;
      $html = '';
      if (is_array($EMP_CODE)) {
        $DATA_EMP_NAME = EMPName::select('*')->selectraw('dbo.decode_utf8(EMP_NAME) as EMP_NAME_TH')->whereIn('EMP_CODE',$EMP_CODE)->get();
        foreach ($DATA_EMP_NAME as $index => $row) {
            $html.= '	<tr>
                <td>'.$index+1 .'</td>
                <td>'.$row->EMP_CODE.' '.$row->EMP_NAME_TH.'</td>
                <td><button type="button" class="btn btn-danger btn-sm btn-block my-1" onclick="deleteworker(this)"
                data-empcode="'.$row->EMP_CODE.'"
                data-empname="'.$row->EMP_NAME_TH.'">
                <i class="fas fa-trash"></i>ลบ</button></td>
              </tr>';
        }
      }
      return Response()->json(['html' => $html]);
  }
  public function AddSparePart(Request $request){
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
              $html.= '  <tr>
                  <td>
                    <button type="button" class="btn btn-warning btn-sm mx-1 my-1"
                    onclick="edittotal(this)"
                    data-unid="'.$row->UNID.'"><i class="fas fa-edit"></i></button>
                    <button type="button" class="btn btn-danger btn-sm mx-1 my-1"
                    onclick="removesparepart(this)"
                    data-unid="'.$row->UNID.'"><i class="fas fa-trash"></i></button>
                  </td>
                  <td>'.$row->SPAREPART_CODE.'</td>
                  <td>'.$row->SPAREPART_NAME.'</td>
                  <td>'.$row->SPAREPART_MODEL.'</td>
                  <td>'.$row->SPAREPART_SIZE.'</td>
                  <td>'.$row->SPAREPART_COST.'</td>
                  <td>'.$TOTAL[$row->UNID].'</td>

                </tr>';
          }
        }
      }




      return Response()->json(['html' => $html]);
  }
}
