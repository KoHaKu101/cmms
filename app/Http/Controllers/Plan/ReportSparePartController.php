<?php

namespace App\Http\Controllers\plan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
use File;
//******************** model ***********************
use App\Models\Machine\Machine;
use App\Models\Machine\SparePartPlan;
use App\Models\Machine\SparePart;
use App\Models\Machine\MachineSparePart;
use App\Models\Machine\SparePartPlanIMG;

//******************** model setting ***********************
use App\Models\SettingMenu\MailSetup;

//***************** Controller ************************
use App\Http\Controllers\MachineAddTable\SparPartController;
use App\Http\Controllers\Machine\HistoryController;
use App\Http\Controllers\Machine\SparepartController;

use App\Http\Controllers\Plan\headerandfooter\PlanMonthHeaderFooter as planmonthheaderfooter;
class ReportSparePartController extends Controller
{
  protected $pdf;
  public function __construct(planmonthheaderfooter $PlanMonthHeaderFooter){
    $this->middleware('auth');
    $this->pdf = $PlanMonthHeaderFooter;
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
    
    $DOC_YEAR  = $request->DOC_YEAR > 0 ? $request->DOC_YEAR : date('Y');

    $MACHINE_SEARCH = $request->MACHINE_SEARCH != '' ? '%'.$request->MACHINE_SEARCH.'%' : '%';
    $STATUS = $request->STATUS;

    $DOC_MONTH = date('n');
    if ($request->DOC_MONTH != NULL) {
      $DOC_MONTH = $request->DOC_MONTH;
    }

    $MACHINE_LINE = isset($request->MACHINE_LINE) ? $request->MACHINE_LINE : '%';
      if ($STATUS == 'NEW') {
              $DATA_SPAREPLAN = SparePartPlan::select('*')->selectraw("
              CASE
              WHEN DOC_MONTH > MONTH(getdate()) and DOC_YEAR > YEAR(getdate()) THEN 'FALSE'
              WHEN DOC_MONTH > MONTH(getdate()) THEN 'FALSE'
           ??? else 'TRUE'
            ???    END AS classtext")->where('DOC_YEAR','=',$DOC_YEAR)
                                    ->where(function($query) use ($DOC_MONTH){
                                      if ($DOC_MONTH > 0) {
                                        $query->where('DOC_MONTH','=',$DOC_MONTH);
                                      }
                                    })
                                    ->where(function ($query) use ($MACHINE_SEARCH) {
                                        $query->where('MACHINE_CODE', 'like' , $MACHINE_SEARCH)
                                              ->orWhere('SPAREPART_NAME', 'like' , $MACHINE_SEARCH)
                                              ->orwhere('SPAREPART_CODE', 'like' , $MACHINE_SEARCH);})
                                       ->where('MACHINE_LINE','like',$MACHINE_LINE)
                                       ->where('STATUS','!=','COMPLETE')
                                       ->where('STATUS_OPEN','=','9')
                                       ->orderBy('PLAN_DATE')
                                       ->orderBy('MACHINE_LINE')
                                       ->orderBy('MACHINE_CODE')
                                       ->paginate(10);
      }elseif($STATUS == 'COMPLETE'){
        $DATA_SPAREPLAN = SparePartPlan::
        select('*')->selectraw("
        CASE
        WHEN DOC_MONTH > MONTH(getdate()) and DOC_YEAR > YEAR(getdate()) THEN 'FALSE'
        WHEN DOC_MONTH > MONTH(getdate()) THEN 'FALSE'
     ??? else 'TRUE'
      ???    END AS classtext")->where('DOC_YEAR','=',$DOC_YEAR)
                                    ->where(function($query) use ($DOC_MONTH){
                                      if ($DOC_MONTH > 0) {
                                        $query->where('DOC_MONTH','=',$DOC_MONTH);
                                      }
                                    })
                                    ->where(function ($query) use ($MACHINE_SEARCH) {
                                        $query->where('MACHINE_CODE', 'like', $MACHINE_SEARCH)
                                              ->orWhere('SPAREPART_NAME', 'like', $MACHINE_SEARCH)
                                              ->orwhere('SPAREPART_CODE','like',$MACHINE_SEARCH);})
                                      ->where('MACHINE_LINE','like',$MACHINE_LINE)
                                       ->where('STATUS','=','COMPLETE')
                                       ->where('STATUS_OPEN','=','9')
                                       ->orderBy('PLAN_DATE')
                                       ->orderBy('MACHINE_LINE')
                                       ->orderBy('MACHINE_CODE')
                                       ->paginate(10);
      }else {
        $DATA_SPAREPLAN = SparePartPlan::select('*')->selectraw("
        CASE
        WHEN DOC_MONTH > MONTH(getdate()) and DOC_YEAR > YEAR(getdate()) THEN 'FALSE'
        WHEN DOC_MONTH > MONTH(getdate()) THEN 'FALSE'
     ??? else 'TRUE'
      ???    END AS classtext")->where('DOC_YEAR','=',$DOC_YEAR)
                                    ->where(function($query) use ($DOC_MONTH){
                                      if ($DOC_MONTH > 0) {
                                        $query->where('DOC_MONTH','=',$DOC_MONTH);
                                      }
                                    })
                                    ->where(function ($query) use ($MACHINE_SEARCH) {
                                        $query->where('MACHINE_CODE', 'like', $MACHINE_SEARCH)
                                              ->orWhere('SPAREPART_NAME', 'like', $MACHINE_SEARCH)
                                              ->orwhere('SPAREPART_CODE','like',$MACHINE_SEARCH);})
                                       ->where('MACHINE_LINE','like',$MACHINE_LINE)
                                       ->where('STATUS_OPEN','=','9')
                                       ->orderBy('PLAN_DATE')
                                       ->orderBy('MACHINE_LINE')
                                       ->orderBy('MACHINE_CODE')
                                       ->paginate(10);

      }

      $MACHINE_SEARCH = str_replace('%','',$MACHINE_SEARCH);
      $STATUS = str_replace('%','',$STATUS);
    return view('machine.sparepart.report.index',compact('DATA_SPAREPLAN','DOC_YEAR','DOC_MONTH','MACHINE_SEARCH','STATUS','MACHINE_LINE'));

  }
  public function Form(Request $request){
    $PLAN_UND  = $request->PLAN_UNID;
    $BTN_STATUS = $request->BTN_STATUS;

    $PLAN = SparePartPlan::where('UNID','=',$PLAN_UND)->first();
    $DETAIL_SPAREPART = SparePart::where('UNID','=',$PLAN->SPAREPART_UNID)->first();
    $COMPLETE_DATE = $PLAN->COMPLETE_DATE != '1900-01-01' ? $PLAN->COMPLETE_DATE : date('Y-m-d') ;
    $COST_ACT = isset($PLAN->COST_ACT) ? $PLAN->COST_ACT : "" ;
    $ACT_QTY = isset($PLAN->ACT_QTY) ? $PLAN->ACT_QTY : "" ;
    $USER_CHECK = isset($PLAN->USER_CHECK) ? $PLAN->USER_CHECK : "" ;
    $REMARK = isset($PLAN->REMARK) ? $PLAN->REMARK : "" ;
    $SPAREPART_UNID = $PLAN->SPAREPART_UNID;
    $MACHINE_UNID = $PLAN->MACHINE_UNID;
    if ($BTN_STATUS == 'VOID') {
        SparePartPlan::where('UNID','=',$PLAN_UND)->update([
          'STATUS' => 'EDIT'
        ]);
    }
    $htmlfooter = '<button type="button" class="btn btn-info" data-dismiss="modal" onclick="imgform(this)"
                  data-plan_sparepartunid="'.$PLAN_UND.'" data-btn_status="" >??????????????????</button>
                   <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                   <button type="button" class="btn btn-primary btn-saveform"id="BTN_SAVEFORM" name="BTN_SAVEFORM"
                    onclick="saveform(this)" >Save</button>' ;
    $htmlconform = '
     <div class="input-group">
     											<div class="input-group-prepend">
     												<span class="text-white input-group-text bg-info" id="basic-addon3" >???????????????????????????</span>
     											</div>
     											<input type="text" class="text-black col-md-8 form-control form-control-sm bg-bluelight"
                          id="PLAN_CHANGE" name="PLAN_CHANGE" autocomplete="off">
     										</div>
    										<button type="button" class="btn btn-warning btn-sm " onclick="btnconfirm()">Confirm</button>';
    if ($BTN_STATUS == 'VIEW') {
      $htmlconform = '' ;
      $htmlfooter = '	<button type="button" class="btn btn-info" data-dismiss="modal" onclick="imgform(this)"
                    data-plan_sparepartunid="'.$PLAN_UND.'" data-btn_status="'.$BTN_STATUS.'">??????????????????</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
    }

    $html = '<div class="row">
    <input type="hidden" id="PLAN_UNID" name="PLAN_UNID" value="'.$PLAN_UND.'">
      <div class="col-md-4">
        <div class="form-group">
          <label for="SPAREPART_CODE">??????????????????????????????</label>
          <input type="text" class="text-black form-control-sm bg-bluelight form-control-plaintext" value="'.$PLAN->SPAREPART_CODE.'"  readonly>
        </div>
      </div>
      <div class="col-md-8">
        <div class="form-group">
          <label for="SPAREPART_CODE">??????????????????????????????</label>
          <input type="text" class="text-black form-control-sm bg-bluelight form-control-plaintext" value="'.$PLAN->SPAREPART_NAME.'"  readonly>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label for="SPAREPART_CODE">????????????????????????????????????</label>
          <input type="text" class="text-black form-control-sm bg-bluelight form-control-plaintext" value="'.date('d/m/Y',strtotime($PLAN->PLAN_DATE)).'" readonly>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group has-error">
          <label for="SPAREPART_CODE">???????????????????????????????????????</label>
          <input type="date" class="text-black form-control form-control-sm bg-bluelight"
          id="ACT_DATE" name="ACT_DATE" value="'.$COMPLETE_DATE.'"required>
        </div>
      </div>
      <div class="col-md-4">
        <div class="row">
          <div class="col-md-5 form-group has-error">
            <label for="SPAREPART_CODE">???????????????????????????</label>
            <input type="time" class="text-black form-control form-control-sm bg-bluelight "
            id="START_TIME" name="START_TIME" value="'.date('H:i').'"required>
          </div>
          <div class="col-md-6 form-group has-error">
            <label for="SPAREPART_CODE">??????????????? ????????????</label>
            <input type="time" class="text-black form-control form-control-sm bg-bluelight "
            id="END_TIME" name="END_TIME" value="'.date('H:i').'"required>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label for="SPAREPART_CODE">????????????</label>
          <input type="text" class="text-black form-control-sm bg-bluelight form-control-plaintext"
          value="'.$DETAIL_SPAREPART->SPAREPART_MODEL.'"  readonly>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label for="SPAREPART_CODE">size/????????????</label>
          <input type="text" class="text-black form-control-sm bg-bluelight form-control-plaintext"
          value="'.$DETAIL_SPAREPART->SPAREPART_SIZE.'" readonly>
        </div>
      </div>
      <div class="col-md-4">
        <div class="row">
        <div class="col-md-5 form-group">
          <label for=" SPAREPART_CODE">?????????????????????????????????</label>
          <input type="number" class="text-black form-control-sm bg-bluelight form-control-plaintext"
          value="'.$PLAN->PLAN_QTY.'"  readonly >
        </div>
        <div class="col-md-6 form-group has-error">
          <label for=" SPAREPART_CODE">?????????????????????????????????????????????</label>
          <input type="number" class="text-black form-control form-control-sm bg-bluelight"
          id="ACT_QTY" name="ACT_QTY" value="'.$ACT_QTY.'" min=0  required>
        </div>
        </div>

      </div>

    </div>

    <div class="row">
      <div class="col-md-8">
        <div class="form-group">
          <label for="comment">????????????????????????/??????????????????????????????</label>
          <textarea class="text-black form-control form-control-sm bg-bluelight" id="REMARK" name="REMARK"
           rows="2">'.$REMARK.'</textarea>
        </div>
      </div>
      <div class="col-md-4">
        <div class="row">
          <div class="col-md-5 form-group">
            <label for="SPAREPART_CODE">????????????</label>
            <input type="text" class="text-black form-control-sm bg-bluelight form-control-plaintext"
            value="'.number_format($PLAN->COST_STD).'" readonly >
          </div>
          <div class="col-md-6 form-group">
            <label for="SPAREPART_CODE">?????????????????????????????????</label>
            <input type="text" class="text-black form-control-sm bg-bluelight form-control-plaintext"
            value="'.number_format($COST_ACT).'" readonly >
          </div>
        </div>

        </div>
    </div>
      ';
    return Response()->json(['html' => $html,'btn_status' => $htmlfooter,'btn_confirm' => $htmlconform]);

  }
  public function FormImg(Request $request){
    $BTN_STATUS = $request->BTN_STATUS;
    $SPAREPART_PLAN_UNID = $request->SPAREPART_PLAN_UNID;
    $DATA_IMG_SPAREPART = SparePartPlanIMG::where('PLAN_SPAREPART_UNID','=',$SPAREPART_PLAN_UNID)->get();
    $CHECK_STATUS = !isset($BTN_STATUS) ? 'onclick="image_gallery(this)"' : 'onclick="image_gallery_view(this)"' ;
    $html = '<div class="row image-gallery">';
    foreach ($DATA_IMG_SPAREPART as $index => $row) {

    $html.='<div class="mb-4 col-6 col-md-3"><a href="#" data-imgunid="'.$row->UNID.'" '.$CHECK_STATUS.'>
            <img src="'.asset($row->FILE_PATH).'" class="img-fluid" id="IMGLOCATION'.$row->UNID.'"> </a>';
    if (!isset($BTN_STATUS)) {
     $html.='<button type="button" class="mx-1 my-1 btn btn-danger btn-sm btn-block" onclick="deleteimg(this)" data-imgunid="'.$row->UNID.'"><i class="fas fa-trash fa-lg"></i></button> </div>';
    }
    }
    $html.='</div>' ;
    return Response()->json(['html'=>$html]);
  }
  public function SaveImg(Request $request){
    $validated = $request->validate([
      'IMG_SPAREPART_FILE_NAME' => 'mimes:jpeg,png,jpg',
      ],
      [
      'IMG_SPAREPART_FILE_NAME.mimes'   => '??????????????????????????? jpeg, png, jpg',
      ]);
    $PLAN_UNID = $request->IMG_SPAREPART_UNID;
    $DATA_SPAREPART_PLAN = SparePartPlan::where('UNID','=',$PLAN_UNID)->first();

    $image = $request->file('IMG_SPAREPART_FILE_NAME');
    $new_name = rand() . '.' . $image->getClientOriginalExtension();
    $img_ext = $image->getClientOriginalExtension();
    $width = 450;
    $height = 300;
    $image = file_get_contents($image);
    $img_master  = imagecreatefromstring($image);
    $img_widht   = ImagesX($img_master);
    $img_height  = ImagesY($img_master);
    $img_create  = $img_master;
    if ($img_widht < $img_height ) {
      $img_master = imagerotate($img_master,90,0,true);
      $img_widht = ImagesX($img_master);
      $img_height = ImagesY($img_master);
      $img_create  = $img_master;
    }
    if ($img_widht > $width) {
      $img_create  = ImageCreateTrueColor($width, $height);
      ImageCopyResampled($img_create, $img_master, 0, 0, 0, 0, $width+1, $height+1, $img_widht, $img_height);
    }
    $DOC_YEAR = $DATA_SPAREPART_PLAN->DOC_YEAR;
    $DOC_MONTH = $DATA_SPAREPART_PLAN->DOC_MONTH;
    $path = public_path('image/plansparepart/'.$DOC_YEAR.$DOC_MONTH.'/'.$PLAN_UNID);
    $locationfile = 'image/plansparepart/'.$DOC_YEAR.$DOC_MONTH.'/'.$PLAN_UNID.'/'.$new_name;
      if(!File::isDirectory($path)){
      File::makeDirectory($path, 0777, true, true);
      }

      if (strtoupper($img_ext) == 'JPEG' || strtoupper($img_ext) == 'JPG') {
        $checkimg_saved = imagejpeg($img_create,$path.'/'.$new_name);
      }elseif (strtoupper($img_ext) == 'PNG') {
        $checkimg_saved = imagepng($img_create,$path.'/'.$new_name);
      }
      ImageDestroy($img_master);
      ImageDestroy($img_create);

   //?????????????????? H 3840 ????????????????????? V 2160 ??????????????? Rotate ???????????? ????????????????????? resize
    if ($img_widht < $img_height ) {
      if ($img_height > $new_height ) {
        $image_resize->rotate(-90);
       $image_resize->resize($new_widht,$new_height);
      }
    }
    if ($checkimg_saved) {
      SparePartPlanIMG::insert([
        'UNID' =>                  $this->randUNID('PMCS_CMMS_SPAREPART_PLAN_IMG')
        ,'PLAN_SPAREPART_UNID' =>  $PLAN_UNID
        ,'FILE_NAME' =>            $new_name
        ,'FILE_EXT' =>             $img_ext
        ,'FILE_PATH' =>            $locationfile
        ,'DOC_YEAR' =>             $DOC_YEAR
        ,'DOC_MONTH' =>            $DOC_MONTH
        ,'CREATE_BY' =>             Auth::user()->name
        ,'CREATE_TIME' =>           Carbon::now()
        ,'MODIFY_BY' =>             Auth::user()->name
        ,'MODIFY_TIME' =>           Carbon::now()
      ]);

      alert()->success('?????????????????????????????????????????????')->autoclose('1500');
    }

      return Response()->json(['res'=>true,'planunid' => $PLAN_UNID ]);
  }
  public function DeleteImg(Request $request){

    $IMG_UNID = $request->IMGUNID;
    $DATA_SPAREPLAN_IMG = SparePartPlanIMG::where('UNID','=',$IMG_UNID)->first();
    $pathfile = public_path($DATA_SPAREPLAN_IMG->FILE_PATH);
    if (File::delete($pathfile)) {
      $deteletimg = SparePartPlanIMG::where('UNID','=',$IMG_UNID)->delete();
    }else {
    return Response()->json(['res'=>false]);
    }
    $PLAN_SPAREPART_UNID = $DATA_SPAREPLAN_IMG->PLAN_SPAREPART_UNID;

    return Response()->json(['res'=>true,'planunidref' => $PLAN_SPAREPART_UNID ]);
  }
  public function Save(Request $request){
    if ($request->USER_CHECK == '') {
      return Response()->json(['res' => false,'name'=>'?????????????????????????????????????????????????????????']);
    }elseif ($request->ACT_QTY == 0) {
      return Response()->json(['res' => false,'name'=>'?????????????????????????????????????????????????????????????????????']);
    }
    $PLAN_UNID = $request->PLAN_UNID;
    $PLAN = SparePartPlan::where('UNID','=',$PLAN_UNID)->first();
    $SPAREPART = SparePart::where('UNID','=',$PLAN->SPAREPART_UNID)->first();
    $COST_STD = $PLAN->COST_STD;
    $ACT_QTY = $request->ACT_QTY;
    $ACT_DATE = $request->ACT_DATE;
    $COST_ACT = 0;
    if ($ACT_QTY > 0 && $COST_STD != '') {
      $COST_ACT = $ACT_QTY * $COST_STD;
    }
    $START_TIME = $request->START_TIME;
    $END_TIME = $request->END_TIME;
    $DOWNTIME = Carbon::parse($START_TIME)->diffInRealMinutes($END_TIME);
    $USER_CHECK = $request->USER_CHECK;
    SparePartPlan::where('UNID','=',$PLAN_UNID)->update([
      'STATUS'              => 'COMPLETE'
      ,'REMARK'             => $request->REMARK
      ,'ACT_QTY'            => $ACT_QTY
      ,'SPAREPART_PAY_TYPE' => 'CUT'
      ,'COMPLETE_DATE'      => $ACT_DATE
      ,'COST_ACT'           => $COST_ACT
      ,'START_TIME'         => $START_TIME
      ,'END_TIME'           => $END_TIME
      ,'DOWNTIME'           => $DOWNTIME
      ,'USER_CHECK'         => $USER_CHECK
      ,'MODIFY_BY'          => Auth::user()->name
      ,'MODIFY_TIME'        => Carbon::now()
    ]);
    $MACHINE_UNID = $PLAN->MACHINE_UNID;
    $SPAREPART_UNID = $PLAN->SPAREPART_UNID ;
    SparePartPlan::where('MACHINE_UNID','=',$MACHINE_UNID)
                  ->where('SPAREPART_UNID','=',$SPAREPART_UNID)
                  ->where("STATUS",'!=','COMPLETE')
                  ->where('PLAN_DATE','>',$ACT_DATE)->delete();
      $UPDATE_PLAN = new SparPartController;
      $UPDATE_PLAN->PlanSave($MACHINE_UNID,$PLAN->PERIOD,$ACT_DATE,
                              $SPAREPART_UNID,$PLAN->PLAN_QTY,$SPAREPART->SPAREPART_COST);
    $NEXT_PLAN_DATE = Carbon::parse($ACT_DATE)->addMonths($PLAN->PERIOD);
    MachineSparePart::where('MACHINE_UNID','=',$MACHINE_UNID)
                     ->where('SPAREPART_UNID','=',$SPAREPART_UNID)
                     ->where('LAST_CHANGE','<',$ACT_DATE)
                     ->update(['LAST_CHANGE'    => $ACT_DATE
                              ,'NEXT_PLAN_DATE' => $NEXT_PLAN_DATE
                              ,'MODIFY_BY'     => Auth::user()->name
                              ,'MODIFY_TIME'   => Carbon::now()
                              ]);
    Machine::where('UNID','=',$MACHINE_UNID)->where('SPAR_PART_DATE','<=',$ACT_DATE)
                                            ->update(['SPAR_PART_DATE'    => $ACT_DATE
                                                     ,'MODIFY_BY'     => Auth::user()->name
                                                     ,'MODIFY_TIME'   => Carbon::now()
                                          ]);
    $SAVE_HISTORY = New HistoryController;
    $SAVE_HISTORY->SaveHistoryPDM($PLAN_UNID,$DOWNTIME);
    $SAVE_HISTORY_SPAREPART = New SparepartController;
    $DOC_NO = '';
    $TYPE   = 'PLAN_PDM';

    $SAVE_HISTORY_SPAREPART->SaveHistory($PLAN_UNID,$MACHINE_UNID,$DOC_NO,$TYPE,$USER_CHECK);
      return Response()->json(['res' => true]);

  }
  public function PlanChange(Request $request){
    if ($request->CHANGE_DATE == '') {
      return Response()->json(['res' => false,'name'=>'?????????????????????????????????????????????????????????']);
    }
    $PLAN_CHANGE = $request->CHANGE_DATE;
    SparePartPlan::where('UNID','=',$request->PLAN_UNID)->update([
      'DOC_YEAR'  => date('Y',strtotime($PLAN_CHANGE)),
      'DOC_MONTH' => date('n',strtotime($PLAN_CHANGE)),
      'PLAN_DATE' => $PLAN_CHANGE,
      'MODIFY_BY'     => Auth::user()->name,
      'MODIFY_TIME'   => Carbon::now()
    ]);
    $PLAN = SparePartPlan::where('UNID','=',$request->PLAN_UNID)->first();
    SparePartPlan::where('MACHINE_UNID','=',$PLAN->MACHINE_UNID)
                  ->where('SPAREPART_UNID','=',$PLAN->SPAREPART_UNID)
                  ->where("STATUS",'=','NEW')
                  ->where('PLAN_DATE','>',$PLAN_CHANGE)->delete();
      $UPDATE_PLAN = new SparPartController;
      $UPDATE_PLAN->PlanSave($PLAN->MACHINE_UNID,$PLAN->PERIOD,$PLAN_CHANGE,
                              $PLAN->SPAREPART_UNID,$PLAN->PLAN_QTY,$PLAN->COST_STD);
      return Response()->json(['res' => true]);

  }
  public function PlanMonthPrint(Request $request){

    $DOC_YEAR  = $request->DOC_YEAR > 0 ? $request->DOC_YEAR : date('Y');
    $DOC_MONTH = $request->DOC_MONTH > 0 ? $request->DOC_MONTH : 0;
    $MACHINE_SEARCH = $request->MACHINE_SEARCH != '' ? '%'.$request->MACHINE_SEARCH.'%' : '%';
    $where =  [['DOC_YEAR', '=', $DOC_YEAR],['DOC_MONTH','=',$DOC_MONTH]];
    if ($DOC_MONTH == 0) {
      $where =  [['DOC_YEAR', '=', $DOC_YEAR]];
    }
    $DATA_MACHINE_LINE = SparePartPlan::select('MACHINE_LINE')
                                    ->where($where)
                                    ->where(function ($query) use ($MACHINE_SEARCH) {
                                        $query->where('MACHINE_CODE', 'like', $MACHINE_SEARCH)
                                              ->orWhere('SPAREPART_NAME', 'like', $MACHINE_SEARCH);})
                                    ->groupBy('MACHINE_LINE')
                                    ->orderBy('MACHINE_LINE')
                                    ->get();
    if(count($DATA_MACHINE_LINE) == 0) {
      return '<body style="background-color:powderblue;">
              <br/><h1 align="center" style="color:red;"> No Data </h1>
              <div align="center">
              <button onclick="javascript:window.close()"
              style="background: #1572e8!important;border-color:#1572e8!important;font-size:14px;
              padding:.65rem 1.4rem;font-size:14px;opacity:1;border-radius: 3px;
              padding: 5px 9px;color:white">
              close </button></div></body>';
    }
    $this->pdf->AddFont('THSarabunNew','','THSarabunNew.php');
    $this->pdf->AddFont('THSarabunNew','B','THSarabunNew_b.php');
    $this->pdf->SetFont('Arial','B',16);
    //??????????????????????????????
    $this->pdf->AliasNbPages();
    foreach ($DATA_MACHINE_LINE as $index => $row) {
      $this->pdf->AddPage('P',['210', '300']);
      $this->pdf->header($row->MACHINE_LINE,$DOC_YEAR,$DOC_MONTH);
      $this->pdf->SetFont('THSarabunNew','',10);
        $cel=array(8,18,8,13,20,49,10,10,12,15,15,15);
      $rHigeht=8;
      $i = 1 ;
      $limit = 31;
      $DETAIL_SPAREPLAN = SparePartPlan::where($where)
                                      ->where('MACHINE_LINE','=',$row->MACHINE_LINE)
                                      ->where(function ($query) use ($MACHINE_SEARCH) {
                                          $query->where('MACHINE_CODE', 'like', $MACHINE_SEARCH)
                                                ->orWhere('SPAREPART_NAME', 'like', $MACHINE_SEARCH);})
                                      ->where('STATUS_OPEN','=',9)
                                      ->orderBy('DOC_YEAR')
                                      ->orderBy('DOC_MONTH')
                                      ->orderBy('PLAN_DATE')
                                      ->orderBy('MACHINE_CODE')
                                      ->get();
      foreach ($DETAIL_SPAREPLAN as $index => $subrow) {
        $this->pdf->Cell($cel[0],$rHigeht,$this->normalize($i++),1,0,'C');
        $this->pdf->Cell($cel[1],$rHigeht,$this->normalize(date('d-m-Y',strtotime($subrow->PLAN_DATE))),1,0,'C');
        $this->pdf->Cell($cel[2],$rHigeht,$this->normalize($subrow->MACHINE_LINE),1,0,'C');
        $this->pdf->Cell($cel[3],$rHigeht,$this->normalize($subrow->MACHINE_CODE),1,0,'C');
        $this->pdf->Cell($cel[4],$rHigeht,$this->normalize($subrow->SPAREPART_CODE),1,0,'C');
        $this->pdf->Cell($cel[5],$rHigeht,iconv('UTF-8', 'cp874',$subrow->SPAREPART_NAME),1,0,'L');
        $this->pdf->Cell($cel[6],$rHigeht,$this->normalize($subrow->PLAN_QTY),1,0,'C');
        $this->pdf->Cell($cel[7],$rHigeht,$this->normalize($subrow->ACT_QTY),1,0,'C');
        $this->pdf->Cell($cel[8],$rHigeht,iconv('UTF-8', 'cp874',$subrow->UNIT),1,0,'C');
        $this->pdf->Cell($cel[9],$rHigeht,$this->normalize(number_format($subrow->TOTAL_COST)),1,0,'R');
        $this->pdf->Cell($cel[10],$rHigeht,$this->normalize(number_format($subrow->COST_ACT)),1,0,'R');
        $this->pdf->Cell($cel[11],$rHigeht,$this->normalize($subrow->STATUS == 'COMPLETE' ? date('d-m-Y',strtotime($subrow->COMPLETE_DATE)) : ''),1,1,'C');
        if ($i == $limit) {
          $limit = $limit+30;
          $this->pdf->AddPage('P',['210', '300']);
          $this->pdf->header($row->MACHINE_LINE,$DOC_YEAR,$DOC_MONTH);
        }
      }

    }

    $this->pdf->Output('I','planmonth.pdf');
    exit;

  }
  public function PlanPDMList(){
    return view('machine.sparepart.report.reprotpdf');
  }
  protected function normalize($word)
     {
       // Thanks to: http://stackoverflow.com/questions/3514076/special-characters-in-fpdf-with-php

       $word = str_replace("???","%42",$word);
       $word = str_replace("@","%40",$word);
       $word = str_replace("`","%60",$word);
       $word = str_replace("??","%A2",$word);
       $word = str_replace("??","%A3",$word);
       $word = str_replace("??","%A5",$word);
       $word = str_replace("|","%A6",$word);
       $word = str_replace("??","%AB",$word);
       $word = str_replace("??","%AC",$word);
       $word = str_replace("??","%AD",$word);
       $word = str_replace("??","%B0",$word);
       $word = str_replace("??","%B1",$word);
       $word = str_replace("??","%B2",$word);
       $word = str_replace("??","%B5",$word);
       $word = str_replace("??","%BB",$word);
       $word = str_replace("??","%BC",$word);
       $word = str_replace("??","%BD",$word);
       $word = str_replace("??","%BF",$word);
       $word = str_replace("??","%C0",$word);
       $word = str_replace("??","%C1",$word);
       $word = str_replace("??","%C2",$word);
       $word = str_replace("??","%C3",$word);
       $word = str_replace("??","%C4",$word);
       $word = str_replace("??","%C5",$word);
       $word = str_replace("??","%C6",$word);
       $word = str_replace("??","%C7",$word);
       $word = str_replace("??","%C8",$word);
       $word = str_replace("??","%C9",$word);
       $word = str_replace("??","%CA",$word);
       $word = str_replace("??","%CB",$word);
       $word = str_replace("??","%CC",$word);
       $word = str_replace("??","%CD",$word);
       $word = str_replace("??","%CE",$word);
       $word = str_replace("??","%CF",$word);
       $word = str_replace("??","%D0",$word);
       $word = str_replace("??","%D1",$word);
       $word = str_replace("??","%D2",$word);
       $word = str_replace("??","%D3",$word);
       $word = str_replace("??","%D4",$word);
       $word = str_replace("??","%D5",$word);
       $word = str_replace("??","%D6",$word);
       $word = str_replace("??","%D8",$word);
       $word = str_replace("??","%D9",$word);
       $word = str_replace("??","%DA",$word);
       $word = str_replace("??","%DB",$word);
       $word = str_replace("??","%DC",$word);
       $word = str_replace("??","%DD",$word);
       $word = str_replace("??","%DE",$word);
       $word = str_replace("??","%DF",$word);
       $word = str_replace("??","%E0",$word);
       $word = str_replace("??","%E1",$word);
       $word = str_replace("??","%E2",$word);
       $word = str_replace("??","%E3",$word);
       $word = str_replace("??","%E4",$word);
       $word = str_replace("??","%E5",$word);
       $word = str_replace("??","%E6",$word);
       $word = str_replace("??","%E7",$word);
       $word = str_replace("??","%E8",$word);
       $word = str_replace("??","%E9",$word);
       $word = str_replace("??","%EA",$word);
       $word = str_replace("??","%EB",$word);
       $word = str_replace("??","%EC",$word);
       $word = str_replace("??","%ED",$word);
       $word = str_replace("??","%EE",$word);
       $word = str_replace("??","%EF",$word);
       $word = str_replace("??","%F0",$word);
       $word = str_replace("??","%F1",$word);
       $word = str_replace("??","%F2",$word);
       $word = str_replace("??","%F3",$word);
       $word = str_replace("??","%F4",$word);
       $word = str_replace("??","%F5",$word);
       $word = str_replace("??","%F6",$word);
       $word = str_replace("??","%F7",$word);
       $word = str_replace("??","%F8",$word);
       $word = str_replace("??","%F9",$word);
       $word = str_replace("??","%FA",$word);
       $word = str_replace("??","%FB",$word);
       $word = str_replace("??","%FC",$word);
       $word = str_replace("??","%FD",$word);
       $word = str_replace("??","%FE",$word);
       $word = str_replace("??","%FF",$word);

       return urldecode($word);
     }




}
