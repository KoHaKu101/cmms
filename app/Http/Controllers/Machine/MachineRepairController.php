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
use File;

//******************** model ***********************
use App\Models\MachineAddTable\SelectMainRepair;
use App\Models\MachineAddTable\SelectSubRepair;
use App\Models\Machine\Machine;
use App\Models\Machine\MachineEMP;
use App\Models\Machine\EMPName;
use App\Models\Machine\MachineLine;
use App\Models\SettingMenu\MailSetup;
use App\Models\Machine\MachineRepairREQ;
//************** Package form github ***************
use Phattarachai\LineNotify\Facade\Line;
use App\Http\Controllers\QRCODE\lib\QrReader;

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
    $COOKIE_PAGE_TYPE = $request->cookie('PAGE_TYPE');
    if ($COOKIE_PAGE_TYPE != 'MA_REPAIR') {
      $DATA_COOKIE = $request->cookie();
      foreach ($DATA_COOKIE as $index => $row) {
        if ($index == 'XSRF-TOKEN' || str_contains($index,'session') == true || $index == 'table_style' || $index == 'table_style_pd') {
        }else {
          Cookie::queue(Cookie::forget($index));
        }
      }
    }

    $SEARCH       = isset($request->SEARCH_MACHINE)? $request->SEARCH_MACHINE     : '' ;
    $MACHINE_LINE = isset($request->LINE)       ? $request->LINE       : ($request->cookie('LINE')       != '' ? $request->cookie('LINE')       : 0 );
    $MONTH        = isset($request->MONTH)      ? $request->MONTH      : ($request->cookie('MONTH')      != '' ? $request->cookie('MONTH')      : date('m') ) ;
    $YEAR         = isset($request->YEAR)       ? $request->YEAR       : ($request->cookie('YEAR')       != '' ? $request->cookie('YEAR')       : date('Y') ) ;
    $DOC_STATUS   = isset($request->DOC_STATUS) ? $request->DOC_STATUS : ($request->cookie('DOC_STATUS') != '' ? $request->cookie('DOC_STATUS') : 9 );
    $MINUTES      = 30;
    Cookie::queue('PAGE_TYPE','MA_REPAIR',$MINUTES);
    Cookie::queue('LINE',$MACHINE_LINE,$MINUTES);
    Cookie::queue('MONTH',$MONTH,$MINUTES);
    Cookie::queue('DOC_STATUS',$DOC_STATUS,$MINUTES);
    Cookie::queue('YEAR',$YEAR,$MINUTES);

    $DATA_EMP     = EMPName::select('EMP_CODE','EMP_ICON')->selectraw('dbo.decode_utf8(EMP_NAME) as EMP_NAME_TH')->where('EMP_STATUS','=',9)->get();
    $dataset      = MachineRepairREQ::select('INSPECTION_CODE','CLOSE_STATUS','DOC_DATE','DOC_NO','MACHINE_LINE','MACHINE_CODE'
                                                ,'MACHINE_NAME','REPAIR_SUBSELECT_NAME','UNID','PRIORITY','REC_WORK_DATE','PD_CHECK_STATUS','CREATE_TIME')
                                            ->selectraw('dbo.decode_utf8(EMP_NAME) as EMP_NAME_TH
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
                                                  if ($DOC_STATUS == 'PD_CLOSE') {
                                                      $query->where('PD_CHECK_STATUS', '=', '1');
                                                   }elseif ($DOC_STATUS > 0) {
                                                      $query->where('CLOSE_STATUS', '=', $DOC_STATUS)->where('PD_CHECK_STATUS','!=','1');
                                                   }
                                                 })
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
    return View('machine/repair/repairlist',compact('dataset','SEARCH','LINE',
    'MACHINE_LINE','MONTH','YEAR','DOC_STATUS','array_EMP','array_IMG'));
  }
  public function FetchData(Request $request){
    $SEARCH         = isset($request->SEARCH_MACHINE) ? $request->SEARCH_MACHINE : '';
    $MACHINE_LINE   = isset($request->LINE)  ? $request->LINE : '';
    $MONTH          = isset($request->MONTH) ? $request->MONTH : 0 ;
    $DOC_STATUS     = isset($request->DOC_STATUS) ? $request->DOC_STATUS : 0 ;
    $YEAR           = isset($request->YEAR) ? $request->YEAR : date('Y') ;
    $page           = $request->page;
    $dataset        = MachineRepairREQ::select('INSPECTION_CODE','CLOSE_STATUS','DOC_DATE','DOC_NO','MACHINE_LINE','MACHINE_CODE'
                                                ,'MACHINE_NAME','REPAIR_SUBSELECT_NAME','UNID','PRIORITY','REC_WORK_DATE','PD_CHECK_STATUS','CREATE_TIME')
                                            ->selectraw('dbo.decode_utf8(EMP_NAME) as EMP_NAME_TH
                                                        ,dbo.decode_utf8(INSPECTION_NAME) as INSPECTION_NAME_TH
                                                        ,dbo.decode_utf8(MACHINE_NAME) as MACHINE_NAME_TH')
                                            ->where(function ($query) use ($MACHINE_LINE) {
                                                  if ($MACHINE_LINE > 0 ) {
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
                                                  if ($DOC_STATUS == 'PD_CLOSE') {
                                                      $query->where('PD_CHECK_STATUS', '=', '1');
                                                   }elseif ($DOC_STATUS > 0) {
                                                      $query->where('CLOSE_STATUS', '=', $DOC_STATUS)
                                                      ->where('PD_CHECK_STATUS','!=',1);
                                                   }
                                                 })
                                            ->orderBy('DOC_YEAR','DESC')
                                            ->orderBy('DOC_MONTH','DESC')
                                            ->orderBy('DOC_NO','DESC')
                                            ->paginate(10);
    $html = '';
    $html_style = '';
    foreach ($dataset->items($page) as $key => $row) {
      $INSPECTION_CODE  = $row->INSPECTION_CODE;
      $CLOSE_STATUS     = $row->CLOSE_STATUS;
      $PD_CHECK_STATUS  = $row->PD_CHECK_STATUS;
      $REC_WORK_STATUS  = isset($INSPECTION_CODE) ? '<i class="fas fa-wrench fa-lg mx-1"></i>'.$row->INSPECTION_NAME_TH : '????????????????????????';
      $BTN_COLOR_STATUS = $PD_CHECK_STATUS == '1' ? 'btn-success' : ($CLOSE_STATUS == '1' ? 'btn-primary': (isset($INSPECTION_CODE) ? 'btn-warning' : 'btn-danger text-center'));
      if ($PD_CHECK_STATUS == '1') {
        $REC_WORK_STATUS = '<i class="fas fa-clipboard-check fa-lg mx-1"></i> ??????????????????????????????????????????????????????????????????';
      }elseif ($CLOSE_STATUS == '1') {
        $REC_WORK_STATUS = '<i class="fas fa-clipboard fa-lg mx-1"></i> ?????????????????????????????????????????????';
      }
      $html.= '<tr>
                <td>'.$dataset->firstItem() + $key .'</td>
                <td width="10%">'.date('d-m-Y',strtotime($row->DOC_DATE)).'</td>
                <td width="10%">'.$row->DOC_NO.'</td>
                <td width="4%">'.$row->MACHINE_LINE.'</td>
                <td width="8%">'.$row->MACHINE_CODE.'</td>
                <td>'.$row->MACHINE_NAME_TH.'</td>
                <td>'.$row->REPAIR_SUBSELECT_NAME.'</td>
                <td width="15%">
                    <button onclick="rec_work(this)" type="button"
                    data-unid   ="'.$row->UNID.'"
                    data-docno  ="'.$row->DOC_NO.'"
                    data-detail ="'.$row->REPAIR_SUBSELECT_NAME.'"
                    class="btn '.$BTN_COLOR_STATUS.' btn-block btn-sm my-1 text-left">
                     <span class="btn-label">
                       '.$REC_WORK_STATUS.'
                     </span>
                   </button>
                 </td>
                 <td width="8%">'.date('d-m-Y').'</td>
              </tr>';
      }
    foreach ($dataset->items($page) as $index => $sub_row) {
      $INSPECTION_CODE= $sub_row->INSPECTION_CODE;
      $SUBROW_UNID    = $sub_row->UNID;
      $EMP_NAME       = EMPName::select('EMP_ICON')->where('EMP_CODE','=',$INSPECTION_CODE)->first();
      $BG_COLOR    		= $sub_row->CLOSE_STATUS == '1' ? 'bg-primary' : ($INSPECTION_CODE != ''   ? 'bg-warning' : 'bg-danger');
      $WORK_STATUS 		= $INSPECTION_CODE   != ''   ? $sub_row->INSPECTION_NAME_TH : '????????????????????????';
      $IMG_PRIORITY		= $sub_row->PRIORITY == '9'  ? '<img src="'.asset('assets/css/flame.png').'" class="mt--2" width="20px" height="20px">' : '';
      $IMG         	  = isset($EMP_NAME->EMP_ICON) ? asset('image/emp/'.$EMP_NAME->EMP_ICON) : asset('../assets/img/noemp.png');
      $DATE_DIFF   	  = $sub_row->REC_WORK_DATE != '1900-01-01 00:00:00.000' ? '????????????????????????:'.Carbon::parse($sub_row->REC_WORK_DATE)->diffForHumans() : '???????????????????????????:'.Carbon::parse($sub_row->CREATE_TIME)->diffForHumans();
      $TEXT_STATUS_WORK = $sub_row->PD_CHECK_STATUS == '1' ? '??????????????????????????????????????????????????????????????????' : ($sub_row->CLOSE_STATUS == '1' ? '?????????????????????????????????????????????' : (isset($sub_row->INSPECTION_CODE) ? '??????????????????????????????????????????' : '????????????????????????' ));
      $TEXT_STATUS_TIME = $sub_row->PD_CHECK_STATUS == '1' ? '??????????????????????????????????????????????????????'    : ($sub_row->CLOSE_STATUS == '1' ? '?????????????????????????????????????????????' : $DATE_DIFF);
      $HTML_STATUS    = '<div class="status" id="DATE_DIFF_'.$SUBROW_UNID.'">'.$TEXT_STATUS_TIME.'</div>';
      $HTML_BTN       = '<button class="btn  btn-primary  btn-sm"
                        onclick="rec_work(this)"
                        data-unid="'.$SUBROW_UNID.'"
                        data-docno="'.$sub_row->DOC_NO.'"
                        data-detail="'.$sub_row->REPAIR_SUBSELECT_NAME.'">
                          SELECT
                        </button>';
      $HTML_AVATAR    = '<img src="'.$IMG.'"id="IMG_'.$SUBROW_UNID.'"alt="..." class="avatar-img rounded-circle">';
      if ($sub_row->PD_CHECK_STATUS == '1') {
        $BG_COLOR  		= 'bg-success';
        $HTML_BTN     =	'<button class="btn btn-primary  btn-sm"
                        onclick=pdfsaverepair("'.$SUBROW_UNID.'")>
                          <i class="fas fa-print mx-1"></i>
                            PRINT
                        </button>';
         $HTML_AVATAR = '<div class="timeline-badge success rounded-circle text-center text-white" style="width: 100%;height: 100%;">
                         <i class="fas fa-check my-2" style="font-size: 35px;"></i></div>' ;
      }

      $html_style .=  '<div class="col-lg-3">
        <div class="card card-round">
          <div class="card-body">
            <div class="card-title  fw-mediumbold '.$BG_COLOR.' text-white "id="BG_'.$SUBROW_UNID.'">
              <div class="row text-center">
                <div class="col-lg-12">
                  '.$IMG_PRIORITY.'
                  '.$sub_row->MACHINE_CODE.'
                </div>
              </div>
              <div class="row text-center ">
                <div class="col-lg-12">
                  <h5>'.$TEXT_STATUS_WORK.'</h5>
                  </div>
              </div>
            </div>
            <div class="card-list">
              <div class="item-list">
                <div class="avatar">
                  '.$HTML_AVATAR.'
                </div>
                <div class="info-user ml-3">
                  <div class="username" style=""id="WORK_STATUS_'.$SUBROW_UNID.'">'.$WORK_STATUS.'</div>
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

    $last_data  = MachineRepairREQ::where('STATUS_NOTIFY','=',9)->count();
    $newrepair  = $last_data > 0 ? true : false;
    $datarepair = MachineRepairREQ::select('UNID','MACHINE_LINE','MACHINE_CODE','DOC_DATE')->where('CLOSE_STATUS','=',9)->get();
    $NUMBER     = count($datarepair);
    return Response()->json(['html'=>$html,'html_style' => $html_style,'newrepair' => $newrepair,'number' => $NUMBER,'datarepair'=>$datarepair]);
  }
  public function PrepareSearch(Request $request){
    $qrcode_text = '';
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
        if ($FILE_SIZE > 100) {
          $img_create  = ImageCreateTrueColor($width, $height);
          ImageCopyResampled($img_create, $img_master, 0, 0, 0, 0, $width+1, $height+1, $img_widht, $img_height);
          $img_master = $img_create;
        }
        $path = public_path('image/qrcode');
          if(!File::isDirectory($path)){
          File::makeDirectory($path, 0777, true, true);
          }
        $current_path = $path.'/'.$new_name;
        if (strtoupper($img_ext) == 'JPEG' || strtoupper($img_ext) == 'JPG') {
          $checkimg_saved = imagejpeg($img_master,$current_path);
        }elseif (strtoupper($img_ext) == 'PNG') {
          $checkimg_saved = imagepng($img_master,$current_path);
        }
        ImageDestroy($img_master);
        ImageDestroy($img_create);
        $qrcode     = new QrReader($current_path);
        $qrcode_text       = $qrcode->text();
        $count_file = count(scandir($path));
        if ($count_file > 3) {
          File::deleteDirectory($path);
        }else {
          File::delete($current_path);
        }

        if (!$qrcode_text) {
          alert()->error('??????????????????????????? ????????????????????????????????????')->autoClose(1500);
          return redirect()->back();
        }

    }

    $SEARCH       = $qrcode_text != '' ? $qrcode_text : $request->search;
    $DATA_MACHINE = NULL;
    if (isset($SEARCH)) {
      $DATA_MACHINE = Machine::select('MACHINE_CODE','MACHINE_LINE','UNID')
                              ->selectraw('dbo.decode_utf8(MACHINE_NAME) as MACHINE_NAME_TH')
                              ->where('MACHINE_CODE','like','%'.$SEARCH.'%')->get();
    }
    return View('machine/repair/search',compact('DATA_MACHINE','SEARCH'));
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
        $EMP_CODE     = $request->cookie('empcode');
        $SELECT_MAIN_REPAIR_UNID = $request->cookie('selectmainrepair');
        $SELECT_SUB_REPAIR_UNID  = $request->cookie('selectsubrepair');
        $PRIORITY = $request->cookie('priority');
        $UNID     = $this->randUNID('PMCS_CMMS_REPAIR_REQ');

      //******************* data *******************//
      $DATA_MACHINE = Machine::where('UNID','=',$MACHINE_UNID)->first();
        $DATA_SELECTMACHINEREPAIR = SelectMainRepair::where('UNID','=',$SELECT_MAIN_REPAIR_UNID)->first();
        $DATA_SELECTSUBREPAIR     = SelectSubRepair::where('UNID','=',$SELECT_SUB_REPAIR_UNID)->first();
        $DATA_EMP = DB::select("select EMP_TH_NAME_FIRST,EMP_CODE,UNID
                                        from EMCS_EMPLOYEE
                                        where POSITION_CODE IN ('LD','ASSTMGR','CF')
                                        and LINE_CODE NOT IN ('QA','QC','PC','FNL','EG','MK','HR','AC','QS')
                                        and EMP_STATUS = '9'
                                        and EMP_CODE = '".$EMP_CODE."'");
      //******************* docno *******************//
      $DATA_MACHINEREPAIRREQ = MachineRepairREQ::selectraw('max(DOC_NO)DOC_NO,max(DOC_DATE)DOC_DATE')->first();
      $DATE_DOCNO            = Carbon::now()->addyears('543');
      $DOC_NO                = 'RE' . $DATE_DOCNO->format('ym') . sprintf('-%04d', 1);
      $DATE_RESET_DOCNO      = Carbon::parse($DATA_MACHINEREPAIRREQ->DOC_DATE);
      if ($DATA_MACHINEREPAIRREQ->DOC_DATE != NULL && $DATE_RESET_DOCNO->format('m') == Carbon::now()->format('m') ) {
          $EXPLOT = str_replace('RE'.$DATE_RESET_DOCNO->addyears('543')->format('ym').'-','',$DATA_MACHINEREPAIRREQ->DOC_NO)+1;
          $DOC_NO = 'RE' . $DATE_RESET_DOCNO->format('ym'). sprintf('-%04d', $EXPLOT);
      }
      //******************* insert *******************//
      if (!isset($DATA_EMP[0]->UNID)) {
        alert()->error('??????????????????????????????????????????????????????????????????????????????')->autoclose('1500');
        return Redirect()->back();
      }
      MachineRepairREQ::insert([
        'UNID'                   => $UNID
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
        ,'STATUS_LINE_NOTIFY'    => 9
        ,'CLOSE_BY'              => ''
        ,'CREATE_BY'             => Auth::user()->name
        ,'CREATE_TIME'           => Carbon::now()
        ,'MODIFY_BY'             => Auth::user()->name
        ,'MODIFY_TIME'           => Carbon::now()
      ]);
      Machine::where('UNID','=',$MACHINE_UNID)->update([
        'MACHINE_CHECK' => '1'
      ]);
      //******************* Remove cookie *******************//
      $cookie_array = array('0' => 'empcode','1' => 'selectmainrepair','2' => 'selectsubrepair','3' => 'priority' );
      foreach ($cookie_array as $index => $row) {
        Cookie::queue(Cookie::forget($row));
      }
      $this->LineNotify();

      if (Gate::allows('isManager_Pd')) {
        return redirect()->route('pd.repairlist');
      }else {
        return redirect()->route('repair.list');
      }

  }
  public function Delete(Request $request){
    $UNID = $request->UNID;
      MachineRepairREQ::where('UNID','=',$UNID)->delete();
      alert()->success('????????????????????????')->autoClose('1500');
    return Redirect()->back();
   }
  public function ReadNotify(Request $request){
    $STATUS = 1;
    MachineRepairREQ::where('STATUS_NOTIFY','=',9)->update([
      'STATUS_NOTIFY' => $STATUS,
    ]);
  }

  public function LineNotify($UNID_REPAIR = null){
    $DATA_MAIL = MailSetup::select('*')->first();
    if (isset($DATA_MAIL->TOKEN_LINENOTIFY)) {
      $existing = config('line-notify');
      $new =array_merge(
          $existing, [
          'access_token' => $DATA_MAIL->TOKEN_LINENOTIFY,
          ]);
      config(['line-notify'=>$new]);
      if ($UNID_REPAIR != null) {
        $DATA_REPAIR  = MachineRepairREQ::select('UNID','MACHINE_CODE','MACHINE_LINE','MACHINE_STATUS','REPAIR_SUBSELECT_NAME')
                                        ->selectraw('dbo.decode_utf8(MACHINE_NAME) as MACHINE_NAME_TH')
                                        ->where('UNID','=',$UNID_REPAIR)->first();
      }else {
        $DATA_REPAIR  = MachineRepairREQ::select('UNID','MACHINE_CODE','MACHINE_LINE','MACHINE_STATUS','REPAIR_SUBSELECT_NAME')
                                        ->selectraw('dbo.decode_utf8(MACHINE_NAME) as MACHINE_NAME_TH')
                                        ->where('STATUS_LINE_NOTIFY','=',9)->first();
      }
          $MACHINE_LINE   = $DATA_REPAIR->MACHINE_LINE;
          $MACHINE_CODE   = $DATA_REPAIR->MACHINE_CODE;
          $MACHINE_NAME   = $DATA_REPAIR->MACHINE_NAME_TH;
          $MACHINE_STATUS = $DATA_REPAIR->MACHINE_STATUS == '1' ? '????????????????????????????????????' : '?????????????????????????????????';
          $REPAIR_SUBSELECT_NAME  = $DATA_REPAIR->REPAIR_SUBSELECT_NAME;
          $UNID    = $DATA_REPAIR->UNID;
          $MESSEN  = "\n".'Line : '.$MACHINE_LINE;
          $MESSEN .= "\n".'MC-CODE : '.$MACHINE_CODE;
          $MESSEN .= "\n".'MC-NAME : '.$MACHINE_NAME;
          $MESSEN .= "\n".'??????????????????????????? : '.$REPAIR_SUBSELECT_NAME;
          $MESSEN .= "\n".'??????????????? : '.$MACHINE_STATUS;
          $MESSEN .= "\n".'?????????????????? :'.route('confirm.repair').'?REPAIR_UNID='.$UNID;
          if (Line::send($MESSEN)) {
          MachineRepairREQ::where('UNID','=',$UNID)->update([
            'STATUS_LINE_NOTIFY' => 1,
          ]);
        }else {
          $MESSEN = '??????????????????????????????????????????????????????????????????';
          Line::send($MESSEN);
        }

    }

  }
}
// public function Edit($UNID) {
//   $data_repairreq = MachineRepairREQ::select('*')->selectraw('dbo.decode_utf8(EMP_NAME) as EMP_NAME')
//                                                  ->where('UNID','=',$UNID)->first();
//   $dataset = SelectMainRepair::where('STATUS','=','9')->get();
//   $data_emp = MachineEMP::select('EMP_CODE','EMP_NAME')->selectraw('dbo.decode_utf8(EMP_NAME) as EMP_NAME')
//                         ->where('EMP_STATUS','=','0')->where('REF_UNID','=',$data_repairreq->MACHINE_UNID)->get();
//
//   $data_selectsubrepair = SelectSubRepair::where('REPAIR_MAINSELECT_UNID','=',$data_repairreq->REPAIR_MAINSELECT_UNID)->get();
//   $cookie_array = array('empcode' => $data_repairreq->EMP_CODE,'selectmainrepair' => $data_repairreq->REPAIR_MAINSELECT_UNID
//                        ,'selectsubrepair' => $data_repairreq->REPAIR_SUBSELECT_UNID,'priority' => $data_repairreq->PRIORITY );
//   foreach ($cookie_array as $name => $value) {
//     Cookie::queue($name, $value);
//   }
//   return view('machine/repair/edit',compact('data_repairreq','dataset','data_emp','data_selectsubrepair'));
//
// }
// public function Update(Request $request,$UNID){
//   //******************* Request parameter *******************//
//   $EMP_CODE = $request->cookie('empcode');
//   $SELECT_MAIN_REPAIR_UNID = $request->cookie('selectmainrepair');
//   $SELECT_SUB_REPAIR_UNID = $request->cookie('selectsubrepair');
//   $PRIORITY = $request->cookie('priority');
//
//   //******************* data *******************//
//   $DATA_MACHINE = MachineRepairREQ::where('UNID','=',$UNID)->first();
//   $DATA_SELECTMACHINEREPAIR = SelectMainRepair::where('UNID','=',$SELECT_MAIN_REPAIR_UNID)->first();
//   $DATA_SELECTSUBREPAIR = SelectSubRepair::where('UNID','=',$SELECT_SUB_REPAIR_UNID)->first();
//   $DATA_EMP = MachineEMP::where('EMP_CODE','=',$EMP_CODE)->where('REF_UNID','=',$DATA_MACHINE->MACHINE_UNID)->first();
//
//
//   $request->CLOSE_STATUS = '9';
//   $data_set = MachineRepairREQ::where('UNID','=',$UNID)->update([
//        'MACHINE_STATUS'        => $DATA_SELECTSUBREPAIR->STATUS_MACHINE
//       ,'REPAIR_MAINSELECT_UNID'=> $DATA_SELECTMACHINEREPAIR->UNID
//       ,'REPAIR_MAINSELECT_NAME'=> $DATA_SELECTMACHINEREPAIR->REPAIR_MAINSELECT_NAME
//       ,'REPAIR_SUBSELECT_UNID' => $DATA_SELECTSUBREPAIR->UNID
//       ,'REPAIR_SUBSELECT_NAME' => $DATA_SELECTSUBREPAIR->REPAIR_SUBSELECT_NAME
//       ,'EMP_UNID'              => $DATA_EMP->UNID
//       ,'EMP_CODE'              => $DATA_EMP->EMP_CODE
//       ,'EMP_NAME'              => $DATA_EMP->EMP_NAME
//
//       ,'PRIORITY'              => $PRIORITY
//       ,'MODIFY_BY'             => Auth::user()->name
//       ,'MODIFY_TIME'           => Carbon::now()
//     ]);
//     $cookie_array = array('0' => 'empcode','1' => 'selectmainrepair','2' => 'selectsubrepair','3' => 'priority' );
//     foreach ($cookie_array as $index => $row) {
//       Cookie::queue(Cookie::forget($row));
//     }
//       alert()->success('???????????????????????????????????? ??????????????????')->autoclose('1500');
//           return Redirect()->route('repair.edit',[$UNID]);
//         }
