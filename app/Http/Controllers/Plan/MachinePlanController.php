<?php

namespace App\Http\Controllers\plan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
use File;
use Gate;
//******************** model ***********************
use App\Models\Machine\Machine;
use App\Models\Machine\MachinePlanPm;
use App\Models\Machine\MasterIMPSGroup;
use App\Models\Machine\Pmplanresult;
use App\Models\Machine\MachineLine;
use App\Models\Machine\Uploadimg;
use App\Models\Machine\MasterIMPS;
//******************** model addtable ***********************
use App\Models\MachineAddTable\MachinePmTemplateDetail;
use App\Models\MachineAddTable\MachinePmTemplateList;
use App\Models\MachineAddTable\MachinePmTemplate;
//******************** model setting ***********************
use App\Models\SettingMenu\MailSetup;

//***************** Controller ************************
use App\Http\Controllers\Machine\UploadImgController;

class MachinePlanController extends Controller
{
  protected  $paging =10;

  public function __construct(){
    $this->middleware('auth');
    $this->middleware(function ($request, $next) {
        $checkuser = Auth::user();

        if ($checkuser->role == 'user') {
          alert()->error('ไม่สิทธิ์การเข้าถึง')->autoclose('1500');
          return Redirect()->route('user.homepage');
        }else {
          return $next($request);
        }

    });

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
  public function PMPlanList(Request $request){

    $PLAN_YEAR = $request->PLAN_YEAR > 0 ? $request->PLAN_YEAR : date('Y');
    $MACHINE_CODE = $request->MACHINE_CODE;
    $MACHINE_LINE = $request->MACHINE_LINE;
    $PLAN_STATUS = $request->PLAN_STATUS;
    $PLAN_MONTH = $request->PLAN_MONTH > 0 ? $request->PLAN_MONTH : date('n');

    $MACHINE_CODE = $MACHINE_CODE != '' ? '%'.$MACHINE_CODE.'%' : '%';
    $MACHINE_LINE = $MACHINE_LINE != '' ? '%'.$MACHINE_LINE.'%' : '%';

      $machinepmplan = MachinePlanPm::select('*')->selectraw("
       CASE
       WHEN PLAN_STATUS = 'COMPLETE' THEN 'icon-success'
       WHEN PLAN_STATUS != 'COMPLETE' and DATEDIFF(DAY, GETDATE(),PLAN_DATE ) > ( SELECT PLAN_CHECK FROM PMCS_CMMS_SETUP_MAIL) THEN 'icon-mute'
    　 WHEN PLAN_STATUS != 'COMPLETE' and DATEDIFF(DAY, GETDATE(),PLAN_DATE ) <= -( SELECT PLAN_CHECK FROM PMCS_CMMS_SETUP_MAIL) THEN 'icon-danger'
    　 WHEN PLAN_STATUS != 'COMPLETE' and DATEDIFF(DAY, GETDATE(),PLAN_DATE ) > -( SELECT PLAN_CHECK FROM PMCS_CMMS_SETUP_MAIL) THEN 'icon-warning'
    　    END AS classtext")->where('PLAN_YEAR','=',$PLAN_YEAR)
                            ->where(function ($query) use ($MACHINE_CODE) {
                                $query->where('MACHINE_CODE', 'like', $MACHINE_CODE)
                                      ->orWhere('PM_MASTER_NAME', 'like', $MACHINE_CODE);})
                        ->where('MACHINE_LINE','like',$MACHINE_LINE)
                        ->where('PLAN_STATUS','like',$PLAN_STATUS != '' ? $PLAN_STATUS :'%')
                        ->where('PLAN_MONTH','like',$PLAN_MONTH > 0 ? $PLAN_MONTH : '%')
                        ->orderBy('PLAN_STATUS','DESC')
                        ->orderby('PLAN_DATE','ASC')
                        ->orderBy('MACHINE_CODE','ASC')
                        ->paginate(16);

    $MACHINE_CODE = str_replace('%','',$MACHINE_CODE);
    $MACHINE_LINE = str_replace('%','',$MACHINE_LINE);


    $machineline = MachineLine::select('LINE_NAME','LINE_CODE')->where('LINE_NAME','like','%'.'Line'.'%')->get();

    return view('machine.plan.pmplanlist',compact('machinepmplan','machineline','MACHINE_CODE','MACHINE_LINE','PLAN_YEAR','PLAN_STATUS','PLAN_MONTH'));

  }

  public function CreatePlan($pm_lastdate,$machine_unid,$masterimpsunid){
    $UNID =  $this->randUNID('PMCS_MACHINE_PLAN_PM');
    $machine = Machine::where('UNID',$machine_unid)->first();
    $pmnext_date = Carbon::parse($pm_lastdate)->addmonth($machine->MACHINE_RANK_MONTH);
    $masterplandata = MachinePmTemplate::where('UNID',$masterimpsunid)->first();
    MachinePlanPm::insert([
      'UNID'            => $UNID,
      'PLAN_YEAR'       => $pm_lastdate->format('Y'),
      'PLAN_MONTH'      => $pm_lastdate->format('m'),
      'PLAN_DATE'       => $pm_lastdate,
      'PLAN_NEXTDATE'   => $pmnext_date,
      'PLAN_DOCNO'      => "",
      'MACHINE_UNID'    => $machine->UNID,
      'MACHINE_NAME'    => $machine->MACHINE_NAME,
      'MACHINE_CODE'    => $machine->MACHINE_CODE,
      'MACHINE_LINE'    => $machine->MACHINE_LINE,
      'PLAN_PERIOD'     => $machine->MACHINE_RANK_MONTH,
      'PLAN_RANK'       => $machine->MACHINE_RANK_CODE,
      'PM_TYPE'         => 'PLAN',
      'PM_MASTER_NAME'  => $masterplandata->PM_TEMPLATE_NAME,
      'PM_MASTER_UNID'  => $masterplandata->UNID,
      'PLAN_STATUS'     => 'NEW',
      'PLAN_RE_MARK'    =>  "",
      'CREATE_BY'       =>   Auth::user()->name,
      'CREATE_TIME'     => Carbon::now(),
      'MODIFY_BY'       =>   Auth::user()->name,
      'MODIFY_TIME'     => Carbon::now(),
    ]);

  }
  public function PMPlanCheckForm($UNID){

    $PM_PLANSHOW = MachinePlanPm::where('UNID','=',$UNID)->first();
    $PM_PLAN = MachinePlanPm::where('UNID','=',$UNID)->get();

    $PLAN_DATE_DIFF       = Carbon::now()->diffInDays(Carbon::parse($PM_PLAN[0]->PLAN_DATE),false);

    $PLAN_NEXT            = MailSetup::select('PLAN_CHECK')->first();
    $PLAN_NEXT_INTEVAL            = $PLAN_NEXT->PLAN_CHECK > 0 ? $PLAN_NEXT->PLAN_CHECK : 1;
    if ($PLAN_DATE_DIFF > $PLAN_NEXT_INTEVAL) {

      alert()->warning('แผนงานยังไม่ถึงกำหนด')->autoclose('1500');
      return redirect(route('pm.planlist'));
    }

    $PM_LIST = MasterIMPSGroup::where('MACHINE_UNID','=',$PM_PLAN[0]->MACHINE_UNID)
                              ->where('PM_TEMPLATE_UNID_REF',$PM_PLAN[0]->PM_MASTER_UNID)
                              ->orderBy('PM_TEMPLATELIST_INDEX','ASC')
                              ->get();
    $PM_DETAIL = MachinePmTemplateDetail::orderBy('PM_DETAIL_INDEX','ASC')->get();
    $PMPLANRESULT = Pmplanresult::where('PM_PLAN_UNID',$UNID)->count();
    $PLAN_UPLOAD_IMG = Uploadimg::where('UNID_REF','=',$UNID)->get();

    $PM_USER_AND_NOTE = Pmplanresult::where('PM_PLAN_UNID',$UNID)->get();
      if ($PM_PLANSHOW->PLAN_STATUS != 'NEW') {
        $PM_LIST = Pmplanresult::select('PM_MASTER_LIST_UNID','PM_MASTER_LIST_NAME','PM_MASTER_LIST_INDEX')->where('PM_PLAN_UNID',$UNID)
                              ->groupBy('PM_MASTER_LIST_NAME')
                              ->groupBy('PM_MASTER_LIST_UNID')
                              ->groupBy('PM_MASTER_LIST_INDEX')
                              ->orderBy('PM_MASTER_LIST_INDEX','ASC')
                              ->get();
        $PM_DETAIL = Pmplanresult::where('PM_PLAN_UNID',$UNID)->orderBy('PM_MASTER_DETAIL_INDEX','ASC')->get();


        $PM_USER_AND_NOTE = Pmplanresult::where('PM_PLAN_UNID',$UNID)->first();
        return view('machine.plan.pmplancheck',
        compact('PM_PLAN','PM_LIST','PM_DETAIL','PM_PLANSHOW','PLAN_UPLOAD_IMG','PM_USER_AND_NOTE'));
      }


   return view('machine.plan.pmplancheck',
   compact('PM_PLAN','PM_LIST','PM_DETAIL','PM_PLANSHOW','PLAN_UPLOAD_IMG','PM_USER_AND_NOTE'));
  }
  public function PMPlanEditForm($UNID){
    Pmplanresult::where('PM_PLAN_UNID','=',$UNID)->update([
      'PM_MASTER_STATUS' => 'EDIT',
    ]);
    MachinePlanPm::where('UNID','=',$UNID)->update([
      'PLAN_STATUS' => 'EDIT',
    ]);
    $PM_PLANSHOW = MachinePlanPm::where('UNID','=',$UNID)->first();
    $PM_PLAN = MachinePlanPm::where('UNID','=',$UNID)->get();


    $PLAN_UPLOAD_IMG = Uploadimg::where('UNID_REF','=',$UNID)->get();
    $pmplanresult = Pmplanresult::where('PM_PLAN_UNID',$UNID)->get();

        $PM_LIST = Pmplanresult::select('PM_MASTER_LIST_UNID','PM_MASTER_LIST_NAME','PM_MASTER_LIST_INDEX')->where('PM_PLAN_UNID',$UNID)
                              ->groupBy('PM_MASTER_LIST_NAME')
                              ->groupBy('PM_MASTER_LIST_UNID')
                              ->groupBy('PM_MASTER_LIST_INDEX')
                              ->orderBy('PM_MASTER_LIST_INDEX','ASC')
                              ->get();
        $PM_DETAIL = Pmplanresult::where('PM_PLAN_UNID',$UNID)->orderBy('PM_MASTER_DETAIL_INDEX','ASC')->get();
        $pmplanresult = Pmplanresult::where('PM_PLAN_UNID',$UNID)->get();
        $PM_USER_AND_NOTE = Pmplanresult::where('PM_PLAN_UNID',$UNID)->first();
        return view('machine.plan.pmplanedit',
        compact('PM_PLAN','PM_LIST','PM_DETAIL','PM_PLANSHOW','PLAN_UPLOAD_IMG','pmplanresult','PM_USER_AND_NOTE'));

  }

  public function PMPlanListSave(Request $request){
    //validated
      $validated = $request->validate([
          'PM_USER_CHECK' => 'required|max:255',
          'CHECK_DATE' => 'required',
        ],[
          'PM_USER_CHECK.required' => 'กรุณากรอกชื่อผู้ทำการตรวจเช็ค',
          'PM_USER_CHECK.max' =>  'ไม่สามารถใส่ตัวอักษรมากกว่า 255 ตัวได้',
          'CHECK_DATE.required'  => 'กรุณาใส่วันที่ทำการตรวจเช็ค',
        ]);
    // Paramiter
      $PM_PLAN_UNID   = $request->PM_PLAN_UNID;
      $PM_PLAN_DATE   = $request->PLAN_DATE;
      $PM_MASTER_UNID = $request->PM_MASTER_UNID;
      $PM_MASTER_NAME = $request->PM_MASTER_NAME;
      $PM_USER_CHECK  = $request->PM_USER_CHECK;
      $CHECK_DATE     = $request->CHECK_DATE;
      $MACHINE_UNID   = $request->MACHINE_PLAN_UNID;
      $LIMIT_RETURN_DATE = Carbon::parse($PM_PLAN_DATE)->diffInMonths(Carbon::parse($CHECK_DATE),false);

      if ($LIMIT_RETURN_DATE < 0) {

        alert()->error('เกิดข้อผิดพลาด','ไม่สามารถบันทึกเวลาย้อนหลังได้ไม่เกิน 1 เดือน')->autoclose('1500');
        return redirect()->back();
      }

      $machine = Machine::where('UNID','=',$MACHINE_UNID)->first();
      $countpmplaresult = Pmplanresult::where('PM_PLAN_UNID','=',$PM_PLAN_UNID)->count();
        if ($countpmplaresult > 0) {
          $pmplanresult = Pmplanresult::where('PM_PLAN_UNID',$PM_PLAN_UNID)->get();

          alert()->error('เกิดข้อผิดพลาด','ไม่สามารถบันบึกได้')->autoclose('1500');
          return redirect('machine/pm/plancheck/'.$PM_PLAN_UNID)->with(compact('pmplanresult'));
        }else {
          DB::beginTransaction();
            try {
              foreach ($request->INPUT as $key => $value) {
                $detail_name = MachinePmTemplateDetail::where('UNID',$key)->first();
                $template_list = MachinePmTemplateList::where('UNID',$detail_name->PM_TEMPLATELIST_UNID_REF)->first();
                  if (!$detail_name) {
                    $detail_name = '';
                  }
                  $STATUS_MIN = $detail_name->PM_DETAIL_STATUS_MIN;
                  $STATUS_MAX = $detail_name->PM_DETAIL_STATUS_MAX;
                  $INPUT_TYPE = $detail_name->PM_TYPE_INPUT;
                  $VALUE_INPUT = $value;
                  $VALUE_STD = $detail_name->PM_DETAIL_STD;
                  $VALUE_MIN = $detail_name->PM_DETAIL_STD_MIN != NULL ? $detail_name->PM_DETAIL_STD_MIN : 0 ;
                  $VALUE_MAX = $detail_name->PM_DETAIL_STD_MAX != NULL ? $detail_name->PM_DETAIL_STD_MAX : 0 ;
                  $PM_MASTER_DETAIL_RESULT = $this->CheckResult($INPUT_TYPE,$VALUE_INPUT,$VALUE_STD,$VALUE_MIN,$VALUE_MAX,$STATUS_MIN,$STATUS_MAX);
                  $REMARK = $request->PM_MASTERPLPAN_REMARK != '' ? $request->PM_MASTERPLPAN_REMARK : '';
                    Pmplanresult::insert([
                      'UNID'                            => $this->randUNID('PMCS_CMMS_PM_RESULT'),
                      'PM_PLAN_UNID'                    => $PM_PLAN_UNID,
                      'PLAN_DATE'                       => $PM_PLAN_DATE,
                      'MACHINE_PLAN_UNID'               => $machine->UNID,
                      'MACHINE_CODE'                    => $machine->MACHINE_CODE,
                      'MACHINE_LINE'                    => $machine->MACHINE_LINE,
                      'MACHINE_NAME'                    => $machine->MACHINE_NAME,
                      'PM_MASTER_UNID'                  => $PM_MASTER_UNID,
                      'PM_MASTER_NAME'                  => $PM_MASTER_NAME,
                      'PM_MASTER_DETAIL_NAME'           => $detail_name->PM_DETAIL_NAME,
                      'PM_MASTER_DETAIL_UNID'           => $detail_name->UNID,
                      'PM_MASTER_DETAIL_INPUT'          => $VALUE_INPUT,
                      'PM_MASTER_DETAIL_VALUE_STD'      => $VALUE_STD,
                      'PM_MASTER_DETAIL_VALUE_STD_MIN'  => $VALUE_MIN,
                      'PM_MASTER_DETAIL_VALUE_STD_MAX'  => $VALUE_MAX,
                      'PM_MASTER_DETAIL_TYPE_INPUT'     => $INPUT_TYPE,
                      'PM_MASTER_DETAIL_INDEX'          => $detail_name->PM_DETAIL_INDEX,
                      'PM_MASTER_DETAIL_RESULT'         => $PM_MASTER_DETAIL_RESULT,
                      'PM_MASTER_STATUS'                => 'COMPLETE',
                      'PM_MASTERPLPAN_REMARK'           => $REMARK,
                      'PM_USER_CHECK'                   => $PM_USER_CHECK,
                      'PM_MASTER_LIST_UNID'             => $template_list->UNID,
                      'PM_MASTER_LIST_NAME'             => $template_list->PM_TEMPLATELIST_NAME,
                      'PM_MASTER_LIST_INDEX'            => $template_list->PM_TEMPLATELIST_INDEX,
                      'PM_STATUS_STD_MAX'               => $STATUS_MAX,
                      'PM_STATUS_STD_MIN'               => $STATUS_MIN,
                      'CHECK_DATE'                      => $CHECK_DATE,
                      'CREATE_BY'                       => Auth::user()->name,
                      'CREATE_TIME'                     => Carbon::now(),
                      'MODIFY_BY'                       => Auth::user()->name,
                      'MODIFY_TIME'                     => Carbon::now(),
                    ]);
                  }
                  $PLAN_PERIOD = $machine->MACHINE_RANK_MONTH;
                  $this->IMPSandPlanUpdate($PM_PLAN_UNID,$CHECK_DATE,$MACHINE_UNID,$PM_MASTER_UNID);
                  $this->LoopUpdatePlan($PLAN_PERIOD,$CHECK_DATE,$MACHINE_UNID,$PM_MASTER_UNID);
                  DB::commit();
                }
                catch (Exception $e) {
                  DB::rollback();
                  Alert::error('เกิดข้อผิดพลาด', 'ระบบไม่สามารถบันทึกข้อมูลได้')->autoclose('1500');
                  return redirect()->back();
                }
                $pmplanresult = Pmplanresult::where('PM_PLAN_UNID',$PM_PLAN_UNID)->get();
                return redirect('machine/pm/plancheck/'.$PM_PLAN_UNID)->with(compact('pmplanresult'));
              }
            }
  public function PMPlanListUpdate(Request $request){

    $validated = $request->validate([
        'PM_USER_CHECK' => 'required|max:255',
        'CHECK_DATE' => 'required',
      ],[
        'PM_USER_CHECK.required' => 'กรุณากรอกชื่อผู้ทำการตรวจเช็ค',
        'PM_USER_CHECK.max' =>  'ไม่สามารถใส่ตัวอักษรมากกว่า 255 ตัวได้',
        'CHECK_DATE.required'  => 'กรุณาใส่วันที่ทำการตรวจเช็ค',
    ]);
    $PM_PLAN_DATE   =  $request->PLAN_DATE;
    $PM_PLAN_UNID   = $request->PM_PLAN_UNID;
    $PM_USER_CHECK  = $request->PM_USER_CHECK;
    $CHECK_DATE     = $request->CHECK_DATE;
    $PM_PLAN = MachinePlanPm::where('UNID','=',$PM_PLAN_UNID)->first();
    $LIMIT_RETURN_DATE = Carbon::parse($PM_PLAN_DATE)->diffInMonths(Carbon::parse($CHECK_DATE),false);

      if ($LIMIT_RETURN_DATE < 0) {

        alert()->error('เกิดข้อผิดพลาด','ไม่สามารถบันทึกเวลาย้อนหลังได้ไม่เกิน 1 เดือน')->autoclose('1500');
        return redirect()->back();
      }
      DB::beginTransaction();
        try {
          foreach ($request->INPUT as $key => $value) {
            $detail_name = Pmplanresult::where('PM_PLAN_UNID','=',$PM_PLAN_UNID)->where('PM_MASTER_DETAIL_UNID','=',$key)->first();
              if (!$detail_name) {
                $detail_name = '';
              }
            $INPUT_TYPE = $detail_name->PM_MASTER_DETAIL_TYPE_INPUT;
            $VALUE_INPUT = $value;
            $VALUE_STD = $detail_name->PM_MASTER_DETAIL_VALUE_STD;
            $VALUE_MIN = $detail_name->PM_MASTER_DETAIL_VALUE_STD_MIN != NULL ? $detail_name->PM_MASTER_DETAIL_VALUE_STD_MIN : 0 ;
            $VALUE_MAX = $detail_name->PM_MASTER_DETAIL_VALUE_STD_MAX != NULL ? $detail_name->PM_MASTER_DETAIL_VALUE_STD_MAX : 0 ;
            $STATUS_MIN = $detail_name->PM_STATUS_STD_MIN;
            $STATUS_MAX = $detail_name->PM_STATUS_STD_MAX;
            $PM_MASTER_DETAIL_RESULT = $this->CheckResult($INPUT_TYPE,$VALUE_INPUT,$VALUE_STD,$VALUE_MIN,$VALUE_MAX,$STATUS_MIN,$STATUS_MAX);
            $REMARK = $request->PM_MASTERPLPAN_REMARK != '' ? $request->PM_MASTERPLPAN_REMARK : '';
            Pmplanresult::where('PM_PLAN_UNID','=',$PM_PLAN_UNID)->where('PM_MASTER_DETAIL_UNID','=',$key)->update([
              'PM_MASTER_DETAIL_INPUT'          => $VALUE_INPUT,
              'PM_MASTER_DETAIL_RESULT'         => $PM_MASTER_DETAIL_RESULT,
              'PM_MASTER_STATUS'                => 'COMPLETE',
              'PM_MASTERPLPAN_REMARK'           => $REMARK,
              'PM_USER_CHECK'                   => $PM_USER_CHECK,
              'PM_STATUS_STD_MAX'               => $STATUS_MAX,
              'PM_STATUS_STD_MIN'               => $STATUS_MIN,
              'CHECK_DATE'                      => $CHECK_DATE,
              'MODIFY_BY'                       => Auth::user()->name,
              'MODIFY_TIME'                     => Carbon::now(),
            ]);
          }
            $PLAN_PERIOD = $PM_PLAN->PLAN_PERIOD;
            $MACHINE_UNID = $PM_PLAN->MACHINE_UNID;
            $PM_MASTER_UNID = $PM_PLAN->PM_MASTER_UNID;

            $this->IMPSandPlanUpdate($PM_PLAN_UNID,$CHECK_DATE,$MACHINE_UNID,$PM_MASTER_UNID);
            $this->LoopUpdatePlan($PLAN_PERIOD,$CHECK_DATE,$MACHINE_UNID,$PM_MASTER_UNID);
            DB::commit();
          }
           catch (Exception $e) {
              DB::rollback();
              Alert::error('เกิดข้อผิดพลาด', 'ระบบไม่สามารถบันทึกข้อมูลได้')->autoclose('1500');
              return redirect()->back();
          }
          $pmplanresult = Pmplanresult::where('PM_PLAN_UNID',$PM_PLAN_UNID)->get();
          alert()->success('อัพเดทข้อมูลสำเร็จ')->autoclose('1500');
          return redirect('machine/pm/plancheck/'.$PM_PLAN_UNID);

        }
  public function PMPlanListUpload(Request $request){
    $validated = $request->validate([
      'FILE_NAME' => 'mimes:jpeg,png,jpg',
      ],
      [
      'FILE_NAME.mimes'   => 'เฉพาะไฟล์ jpeg, png, jpg',
      ]);
      $plan_unid = $request->IMG_PLAN_UNID;

        $image = $request->file('FILE_NAME');
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
        $path = public_path('image/planresult/'.$plan_unid);
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

      if ($checkimg_saved) {
        $saveimg = new UploadImgController;
        $dataimgshow = $saveimg->SaveImg($plan_unid,$new_name,$img_ext);
        alert()->success('บันทึกภาพสำเร็จ')->autoclose('1500');

      }
      $dataimg = Uploadimg::where('UNID_REF','=',$plan_unid)->get();
      $PMPLANRESULT = Pmplanresult::where('PM_PLAN_UNID',$plan_unid)->count();
      if ($PMPLANRESULT > 0) {
        return redirect('machine/pm/plancheck/'.$plan_unid)->with('autofocus','BTN_UPLOAD');
      }
      return redirect()->back()->with('autofocus','BTN_UPLOAD');


  }
  public function DeleteImg(Request $request){
    $imgunid = $request->imgunid;

      $deletestep = Uploadimg::where('UNID','=',$imgunid)->first();
      $plan_unid = $deletestep->UNID_REF;
      $filename = $deletestep->FILE_NAME;
      $data  = array();
    if ($plan_unid != '') {
       $deteletimg = Uploadimg::where('UNID','=',$imgunid)->delete();
       if ($deteletimg) {
         $pathfile = public_path('image/planresult/'.$plan_unid.'/'.$filename);
         File::delete($pathfile);

         $data['result'] = true;
         $data['imgunid'] = $imgunid;
       }
    }else {
      $data['result'] = false;
      $data['imgunid'] = '';
    }
    return response()->json($data);

  }
  public function CheckResult($TYPE = '',$INPUT = 0,$STD = 0,$MIN = 0 ,$MAX = 0,$STATUS_MIN = 'true',$STATUS_MAX = 'true'){
    $STATUS_CAL = 0;
    if ($STATUS_MIN == 'true' && $STATUS_MAX == 'true') {
      $STATUS_CAL = 1 ;
    }elseif($STATUS_MIN == 'true' && $STATUS_MAX == 'false') {
      $STATUS_CAL = 2;
    }elseif($STATUS_MIN == 'false' && $STATUS_MAX == 'true') {
      $STATUS_CAL = 3;
    }elseif($STATUS_MIN == 'false' && $STATUS_MAX == 'false') {
      $STATUS_CAL = 4;
    }
    $result_status = 'FAIL';
    if (strtoupper($TYPE) == 'RADIO') {
      switch ($INPUT) {
        case 1:
            $result_status = 'PASS';
            return $result_status;
          break;
        case 0:
            $result_status = 'FAIL';
            return $result_status;
          break;
        default:
          $result_status = 'FAIL';
        return $result_status;
      }
    }else {
      switch ($STATUS_CAL) {
        case 1:
            if ($INPUT >= $MIN && $INPUT <= $MAX) {
              $result_status = 'PASS';
            }
            return $result_status;
          break;
        case 2:
              if ($INPUT >= $MIN && $INPUT <= $STD) {
                $result_status = 'PASS';
              }
              return $result_status;
            break;
          case 3:
                if ($INPUT >= $STD && $INPUT <= $MAX) {
                  $result_status = 'PASS';
                }
                return $result_status;
              break;
            case 4:
                if ($INPUT == $STD) {
                  $result_status = 'PASS';
                }
                return $result_status;
              break;
            default:
              return $result_status;
            break;
          }
    }
  }
  public function IMPSandPlanUpdate($PM_PLAN_UNID = NULL,$CHECK_DATE = NULL,$MACHINE_UNID = NULL,$PM_MASTER_UNID = NULL){
    $MACHINE_RANK = Machine::select('MACHINE_RANK_MONTH')->where('UNID','=',$MACHINE_UNID)->first();
    $NEXT_DATE = Carbon::parse($CHECK_DATE)->addmonth($MACHINE_RANK->MACHINE_RANK_MONTH);
    MachinePlanPm::where('UNID',$PM_PLAN_UNID)->update([
      'PLAN_STATUS'                     => 'COMPLETE',
      'COMPLETE_DATE'                   => $CHECK_DATE,
      'MODIFY_BY'                       => Auth::user()->name,
      'MODIFY_TIME'                     => Carbon::now(),
    ]);
    MasterIMPS::where('MACHINE_UNID','=',$MACHINE_UNID)->where('PM_TEMPLATE_UNID_REF','=',$PM_MASTER_UNID)->update([
      'PM_LAST_DATE'                    => $CHECK_DATE,
      'PM_NEXT_DATE'                    => $NEXT_DATE,
      'MODIFY_BY'                       => Auth::user()->name,
      'MODIFY_TIME'                     => Carbon::now(),
    ]);
     Machine::where('UNID','=',$MACHINE_UNID)->where('PLAN_LAST_DATE','<=',$CHECK_DATE)->update([
      'PLAN_LAST_DATE'                  => $CHECK_DATE,
      'MODIFY_BY'                       => Auth::user()->name,
      'MODIFY_TIME'                     => Carbon::now(),
    ]);

  }
  public function LoopUpdatePlan($PLAN_PERIOD = NULL,$CHECK_DATE = NULL,$MACHINE_UNID = NULL,$PM_MASTER_UNID = NULL ){
    $totalloop          = 0;
    $totalmonth         = MailSetup::select('AUTOPLAN')->first();
    $preiodmonth        = $PLAN_PERIOD;
    $pm_lastdate        = $CHECK_DATE;
    MachinePlanPm::Where('MACHINE_UNID','=',$MACHINE_UNID)
                ->where('PM_MASTER_UNID','=',$PM_MASTER_UNID)
                ->where('PLAN_STATUS','!=','COMPLETE')
                ->where('PLAN_DATE','>',Carbon::parse($pm_lastdate)->addMonth($preiodmonth))->delete();
    for ($i = 0; $i < $totalmonth->AUTOPLAN ; $i++) {
        if (($i%$preiodmonth == 0)) {
          $totalloop++;
          $pm_lastdate    = Carbon::parse($pm_lastdate)->addMonth($preiodmonth);
          $pm_plandate    = $pm_lastdate;
          if ($MACHINE_UNID != "" && $PM_MASTER_UNID != "") {
            $this->UpdateDatePlan($pm_plandate,$MACHINE_UNID,$PM_MASTER_UNID);
          }
        }
    }
  }
  public function UpdateDatePlan($pm_plandate,$machine_unid,$pmmaster_template_unid) {

    $UNID =  $this->randUNID('PMCS_MACHINE_PLAN_PM');
    $machine = Machine::where('UNID',$machine_unid)->first();
    $pmnext_date = Carbon::parse($pm_plandate)->addmonth($machine->MACHINE_RANK_MONTH);

    $masterplandata = MachinePmTemplate::where('UNID',$pmmaster_template_unid)->first();

    MachinePlanPm::insert([
      'UNID'            => $UNID,
      'PLAN_YEAR'       => $pm_plandate->format('Y'),
      'PLAN_MONTH'      => $pm_plandate->format('m'),
      'PLAN_DATE'       => $pm_plandate,
      'PLAN_NEXTDATE'   => $pmnext_date,
      'PLAN_DOCNO'      => "",
      'MACHINE_UNID'    => $machine->UNID,
      'MACHINE_NAME'    => $machine->MACHINE_NAME,
      'MACHINE_CODE'    => $machine->MACHINE_CODE,
      'MACHINE_LINE'    => $machine->MACHINE_LINE,
      'PLAN_PERIOD'     => $machine->MACHINE_RANK_MONTH,
      'PLAN_RANK'       => $machine->MACHINE_RANK_CODE,
      'PM_TYPE'         => 'PLAN',
      'PM_MASTER_NAME'  => $masterplandata->PM_TEMPLATE_NAME,
      'PM_MASTER_UNID'  => $masterplandata->UNID,
      'PLAN_STATUS'     => 'NEW',
      'PLAN_RE_MARK'    =>  "",
      'CREATE_BY'       =>   Auth::user()->name,
      'CREATE_TIME'     => Carbon::now(),
      'MODIFY_BY'       =>   Auth::user()->name,
      'MODIFY_TIME'     => Carbon::now(),
    ]);
  }
  public function PMPlanPrint(){
    return view('machine.plan.planpm');
  }
}
