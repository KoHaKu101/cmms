<?php

namespace App\Http\Controllers\Machine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Middleware\VerifyCsrfToken;
use Carbon\Carbon;
use App\Services\PayUService\Exception;
use Auth;
use Cookie;
use Gate;
use Zxing;
use File;
//******************** model ***********************
use App\Models\MachineAddTable\SelectMainRepair;
use App\Models\MachineAddTable\SelectSubRepair;
use App\Models\Machine\Machine;
use App\Models\Machine\MachineEMP;
use App\Models\Machine\EMPName;
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
    $cookie_array = array('0' => 'empcode','1' => 'selectmainrepair','2' => 'selectsubrepair','3' => 'priority' );
    foreach ($cookie_array as $index => $row) {
      Cookie::queue(Cookie::forget($row));
    }

    if ($request->cookie('table_style') == NUll) {
      Cookie::queue('table_style','2');
    }

    $SEARCH       = isset($request->SEARCH) ? '%'.$request->SEARCH.'%' : '';
    $SERACH_TEXT  =  $request->SEARCH;
    $LINE         = MachineLine::where('LINE_STATUS','=','9')->where('LINE_NAME','like','Line'.'%')->orderBy('LINE_NAME')->get();
    $MACHINE_LINE = isset($request->LINE) ? $request->LINE : '';
    $MONTH        = isset($request->MONTH) ? $request->MONTH : date('m') ;
    $DOC_STATUS   = isset($request->DOC_STATUS) ? $request->DOC_STATUS : 0 ;
    $YEAR         = isset($request->YEAR) ? $request->YEAR : date('Y') ;
    $DATA_EMP     = EMPName::select('*')->selectraw('dbo.decode_utf8(EMP_NAME) as EMP_NAME_TH')->where('EMP_STATUS','=',9)->get();
    $dataset      = MachineRepairREQ::select('INSPECTION_CODE','CLOSE_STATUS','DOC_DATE','DOC_NO','MACHINE_LINE','MACHINE_CODE'
                                                ,'MACHINE_NAME','REPAIR_SUBSELECT_NAME','UNID','PRIORITY','REC_WORK_DATE')
                                                ->selectraw('dbo.decode_utf8(EMP_NAME) as EMP_NAME_TH
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
                                                      $query->where('CLOSE_STATUS', '=', $DOC_STATUS);
                                                   }
                                                 })
                                            ->orderBy('DOC_YEAR','DESC')
                                            ->orderBy('DOC_MONTH','DESC')
                                            ->orderBy('DOC_NO','DESC')
                                            ->paginate(10);
    $SEARCH = $SERACH_TEXT;
    return View('machine/repair/repairlist',compact('dataset','SEARCH','LINE','DATA_EMP',
    'MACHINE_LINE','MONTH','YEAR','DOC_STATUS'));
  }
  public function FetchData(Request $request){
    $SEARCH         = isset($request->SEARCH) ? '%'.$request->SEARCH.'%' : '';
    $SERACH_TEXT    = $request->SEARCH;
    $MACHINE_LINE   = isset($request->LINE) ? $request->LINE : '';
    $MONTH          = isset($request->MONTH) ? $request->MONTH : 0 ;
    $DOC_STATUS     = isset($request->DOC_STATUS) ? $request->DOC_STATUS : 0 ;
    $YEAR           = isset($request->YEAR) ? $request->YEAR : date('Y') ;
    $dataset        = MachineRepairREQ::select('INSPECTION_CODE','CLOSE_STATUS','DOC_DATE','DOC_NO','MACHINE_LINE','MACHINE_CODE'
                                                ,'MACHINE_NAME','REPAIR_SUBSELECT_NAME','UNID','PRIORITY','REC_WORK_DATE')
                                            ->selectraw('dbo.decode_utf8(EMP_NAME) as EMP_NAME_TH
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
                                                      $query->where('CLOSE_STATUS', '=', $DOC_STATUS);
                                                   }
                                                 })
                                            ->orderBy('DOC_YEAR','DESC')
                                            ->orderBy('DOC_MONTH','DESC')
                                            ->orderBy('DOC_NO','DESC')
                                            ->paginate(10);
    $SEARCH         = $SERACH_TEXT;
    $html = '';
    $html_style = '';
    foreach ($dataset as $key => $row) {
      $INSPECTION_CODE  = $row->INSPECTION_CODE;
      $CLOSE_STATUS     = $row->CLOSE_STATUS;
      $REC_WORK_STATUS  = !isset($INSPECTION_CODE) ? 'รอรับงาน'     : $row->INSPECTION_NAME_TH;
      $BTN_COLOR_STATUS = !isset($INSPECTION_CODE) ? 'btn-mute'    : ($CLOSE_STATUS == '1' ? 'btn-success' : 'btn-info') ;
      $BTN_COLOR 			  = !isset($INSPECTION_CODE) ? 'btn-danger'  : 'btn-secondary' ;
      $BTN_TEXT  			  = !isset($INSPECTION_CODE) ? 'รอรับงาน'     : ($CLOSE_STATUS == '1' ? 'ปิดเอกสาร' : 'การดำเนินงาน') ;
      $html.= '<tr>
                <td>'.$key+1 .'</td>
                <td >'.date('d-m-Y',strtotime($row->DOC_DATE)).'</td>
                <td >'.$row->DOC_NO.'</td>
                <td >'.$row->MACHINE_LINE.'</td>
                <td >'.$row->MACHINE_CODE.'</td>
                <td >'.$row->MACHINE_NAME.'</td>
                <td >'.$row->REPAIR_SUBSELECT_NAME.'</td>d

                <td >
                  <button type="button"class="btn '.$BTN_COLOR_STATUS.' btn-block btn-sm my-1 text-left"style="color:black;font-size:13px"
                  '.($CLOSE_STATUS == '1' ? 'onclick=pdfsaverepair("'.$row->UNID.'")' : '').'>
                    <i class="'.($CLOSE_STATUS == '1' ? 'fas fa-print' : '').'"></i>

                    <span class="btn-label " >
                      '.$BTN_TEXT.'
                    </span>
                  </button>
                </td>';
                if (Gate::allows("isAdminandManager")) {
                  $html.='<td >
                      <button onclick="rec_work(this)" type="button"
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
      $SUBROW_UNID = $sub_row->UNID;
      $DATA_EMP    = EMPName::where('EMP_CODE',$sub_row->INSPECTION_CODE)->first();
      $BG_COLOR    = $sub_row->PRIORITY == '9' ? 'bg-danger text-white' :  'bg-warning text-white';
      if ($sub_row->CLOSE_STATUS == '1') {
        $BG_COLOR = 'bg-success text-white';
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
                    id="IMG_'.$SUBROW_UNID.'">
                  </div>
                  <div class="info-user ml-3">
                    <div class="username" id="WORK_STATUS_'.$SUBROW_UNID.'">'.$WORK_STATUS.'</div>
                    <div class="status" >'.$sub_row->REPAIR_SUBSELECT_NAME.'</div>';
                    if ($sub_row->CLOSE_STATUS == '1'){
                    $html_style .='<div class="status" id="DATE_DIFF_'.$SUBROW_UNID.'" > ดำเนินงานสำเร็จ</div>';
                      }else {
                    $html_style .='<div class="status" id="DATE_DIFF_'.$SUBROW_UNID.'">'.$DATE_DIFF.'</div>';
                      }
                    $html_style .='</div>
                </div>
              </div>
              <div class="row ">
                <div class="col-md-12 text-center">
                  <button class="btn  btn-primary  btn-sm"
                  onclick="rec_work(this)"
                  data-unid="'.$SUBROW_UNID.'"
                  data-docno="'.$sub_row->DOC_NO.'"
                  data-detail="'.$sub_row->REPAIR_SUBSELECT_NAME.'">
                    SELECT
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>';
      }
    return Response()->json(['html'=>$html,'html_style' => $html_style]);
  }
  public function PrepareSearch(Request $request){
    $text = '';

    if (file_exists($request->QRCODE_FILE)) {
        $FILE        = $request->file('QRCODE_FILE');
        $new_name    = rand() . '.' . $FILE->getClientOriginalExtension();
        $img_ext     = $FILE->getClientOriginalExtension();
        $FILE_SIZE   = (filesize($FILE) / 1024);
        $image       = file_get_contents($FILE);
        $img_master  = imagecreatefromstring($image);
        $img_widht   = ImagesX($img_master);
        $img_height  = ImagesY($img_master);
        $width       = 689;
        $height      = 689;
        $img_create  = $img_master;
        if ($FILE_SIZE > 100) {
          $img_create  = ImageCreateTrueColor($width, $height);
          ImageCopyResampled($img_create, $img_master, 0, 0, 0, 0, $width+1, $height+1, $img_widht, $img_height);
        }
        $path = public_path('image/qrcode');
          if(!File::isDirectory($path)){
          File::makeDirectory($path, 0777, true, true);
          }
        $current_path = $path.'/'.$new_name;
        if (strtoupper($img_ext) == 'JPEG' || strtoupper($img_ext) == 'JPG') {
          $checkimg_saved = imagejpeg($img_create,$current_path);
        }elseif (strtoupper($img_ext) == 'PNG') {
          $checkimg_saved = imagepng($img_create,$current_path);
        }
        ImageDestroy($img_master);
        ImageDestroy($img_create);

        \Artisan::call('cache:clear && composer install');
        $qrcode = new Zxing\QrReader($current_path);
        dd($qrcode);
        $text = $qrcode->text();
        unlink($current_path);
        if (!$text) {
          alert()->error('ภาพไม่ชัด กรุณาลองใหม่')->autoClose(1500);
          return redirect()->back();
        }

    }

    // dd($text);
    $SEARCH = $text != '' ? $text : $request->search;
    $machine = NULL;
    if (isset($SEARCH)) {
      $MACHINE_CODE = '%'.$SEARCH.'%';
      $machine = Machine::select('*')->selectraw('dbo.decode_utf8(MACHINE_NAME) as MACHINE_NAME_TH')
                                     ->where('MACHINE_CODE','like',$MACHINE_CODE)->get();
    }
    return View('machine/repair/search',compact('machine','SEARCH'));
  }
  public function Create($UNID){

      $dataset = SelectMainRepair::where('STATUS','=','9')->get();
      $datamachine = Machine::where('UNID','=',$UNID)->first();
      $data_emp   = DB::select("select dbo.decode_utf8(EMP_TH_NAME_FIRST) as EMP_TH_NAME_FIRST,EMP_CODE,UNID
                                from EMCS_EMPLOYEE
                                where  POSITION_CODE IN ('LD','ASSTMGR','CF')
                                and EMP_STATUS = '9'
	                              and LINE_CODE NOT IN ('QA','QC','PC','FNL','EG','MK','HR','AC','QS') ");
    return View('machine/repair/formreq',compact('dataset','datamachine','data_emp'));
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
      if (Gate::allows('isManager_Pd')) {
      return redirect()->route('pd.repairlist');
      }else {
        return redirect()->route('repair.list');
      }

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


}
