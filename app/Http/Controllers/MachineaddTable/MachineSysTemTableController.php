<?php

namespace App\Http\Controllers\MachineaddTable;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
//******************** model ***********************
use App\Models\Machine\Machine;
use App\Models\MachineAddTable\MachinePmTemplate;
use App\Models\MachineAddTable\MachinePmTemplateList;
use App\Models\MachineAddTable\MachinePmTemplateDetail;
use App\Models\Machine\MachinePlanPm;
use App\Models\Machine\MasterIMPS;
use App\Models\Machine\MasterIMPSGroup;
use App\Models\Machine\Protected;
//************** Package form github ***************
use RealRashid\SweetAlert\Facades\Alert;



class MachineSysTemTableController extends Controller
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

  public function Index(Request $request,$UNID = NULL){

    $datapmtemplate           = MachinePmTemplate::orderBy('PM_TEMPLATE_NAME','ASC')->get();
    $countdetail = 0;
    $datapmtemplatelist       = NULL;
    $datapmtemplatefirst      = NULL;
    $datamachine              = NULL;
    $arraymachine             = NULL;
    if($UNID){
      $datapmtemplatelist       = MachinePmTemplateList::where('PM_TEMPLATE_UNID_REF','=',$UNID)->orderBy('PM_TEMPLATELIST_INDEX','ASC')->get();
      $datapmtemplatefirst      = MachinePmTemplate::select('PM_TEMPLATE_NAME','UNID')->where('UNID',$UNID)->first();
      $datamachine                = MasterIMPS::where('PM_TEMPLATE_UNID_REF',$UNID)
                                      ->orderBy('MACHINE_CODE','ASC')
                                      ->paginate(10);
      $countdetail = $datapmtemplatefirst->count();
      $arraymachine = Machine::select('MACHINE_LINE','UNID')->selectRaw('dbo.decode_utf8(MACHINE_NAME) as MACHINE_NAME_TH')
                               ->where('MACHINE_STATUS','!=','4')->get();

    }
    return View('machine/add/system/systemlist',compact('datamachine','datapmtemplate','datapmtemplatelist','countdetail','datapmtemplatefirst','arraymachine'));
  }
  public function StoreTemplate(Request $request){
    $validated = $request->validate([
      'PM_TEMPLATE_NAME'           => 'required|unique:PMCS_CMMS_PM_TEMPLATE|max:200',
      ],
      [
      'PM_TEMPLATE_NAME.required'  => 'กรุณาใส่ชื่อกลุ่ม',
      'PM_TEMPLATE_NAME.unique'    => 'มีชื่อกลุ่มนี้แล้ว',
      'PM_TEMPLATE_NAME.max'  => 'ชื่อยาวเกินไป',
      ]);
    MachinePmTemplate::insert([
      'PM_TEMPLATE_NAME'       => $request->PM_TEMPLATE_NAME,
      'CREATE_BY'              => Auth::user()->name,
      'CREATE_TIME'            => Carbon::now(),
      'UNID'                   => $this->randUNID('PMCS_CMMS_PM_TEMPLATE'),
    ]);
    alert()->success('เพิ่มระบบ สำเร็จ')->autoclose('1500');
    return Redirect()->back();
  }
  public function UpdateTemplate(Request $request) {
   MachinePmTemplate::where('UNID',$request->UNID)->update([
        'PM_TEMPLATE_NAME'      => $request->PM_TEMPLATE_NAME,
        'MODIFY_BY'              => Auth::user()->name,
        'MODIFY_TIME'            => Carbon::now(),
    ]);
    MasterIMPS::where('PM_TEMPLATE_UNID_REF',$request->UNID)->update(['PM_TEMPLATE_NAME' => $request->PM_TEMPLATE_NAME]);
    alert()->success('อัพเดทรายการสำเร็จ')->autoclose('1500');
    return Redirect()->back();
  }
  public function DeleteTemplate($UNID) {
      $datamachinepmtemplatelist =  MachinePmTemplateList::where('PM_TEMPLATE_UNID_REF',$UNID)->get();

      foreach ($datamachinepmtemplatelist as $key => $dataset) {
          $data = MachinePmTemplateDetail::where('PM_TEMPLATELIST_UNID_REF',$dataset->UNID)->delete();
      }
      MachinePlanPm::where('PM_MASTER_UNID','=',$UNID)->where('PLAN_DATE','>',Carbon::now())->delete();
      MasterIMPSGroup::where('PM_TEMPLATE_UNID_REF',$UNID)->delete();
      MasterIMPS::where('PM_TEMPLATE_UNID_REF',$UNID)->delete();
      MachinePmTemplateList::where('PM_TEMPLATE_UNID_REF',$UNID)->delete();
      MachinePmTemplate::where('UNID',$UNID)->delete();


      alert()->success('ลบข้อมูลสำเร็จ')->autoclose('1500');
      return Redirect(url('machine/pm/template/list'));

  }
  public function DeleteMachinePm($MC,$UNID) {

    MachinePlanPm::where('MACHINE_UNID','=',$MC)
                  ->where('PM_MASTER_UNID','=',$UNID)
                  ->where('PLAN_DATE','>',Carbon::now()->format('Y-m-d'))
                  ->delete();
    MasterIMPSGroup::where('MACHINE_UNID',$MC)->where('PM_TEMPLATE_UNID_REF',$UNID)->delete();
    MasterIMPS::where('MACHINE_UNID',$MC)->where('PM_TEMPLATE_UNID_REF',$UNID)->delete();
    alert()->success('ลบข้อมูลสำเร็จ')->autoclose('1500');
      return Redirect()->back();

  }

  public function PmTemplateAdd($UNID) {
      $datapmtemplate = MachinePmTemplate::select('PM_TEMPLATE_NAME','UNID')->where('UNID',$UNID)->first();
      return view('machine/add/system/add',compact('datapmtemplate'));
      }

  public function StoreList(Request $request){
    $validated = $request->validate([
      'PM_TEMPLATELIST_NAME'            => 'required|max:200',
      ],
      [
      'PM_TEMPLATELIST_NAME.required'   => 'กรุณาใส่รายการ Inspection Item',
      'PM_TEMPLATELIST_NAME.max'        => 'ชื่อInspection Item ยาวเกินไป',
      ]);
      $count = 1;
      $rowcount = MachinePmTemplateList::selectraw('max(PM_TEMPLATELIST_INDEX)count')
                                       ->where('PM_TEMPLATE_UNID_REF','=',$request->PM_TEMPLATE_UNID_REF)
                                       ->first();
      if ($rowcount->count() > 0 ) {
        $count = $rowcount->count()+1;
      }
    $UNID = $this->randUNID('PMCS_CMMS_PM_TEMPLATE_LIST');
    MachinePmTemplateList::insert([
      'PM_TEMPLATE_UNID_REF'         => $request->PM_TEMPLATE_UNID_REF,
      'PM_TEMPLATELIST_NAME'         => $request->PM_TEMPLATELIST_NAME,
      'PM_TEMPLATELIST_DAY'          => '',
      'PM_TEMPLATELIST_STATUS'       => '1',
      'PM_TEMPLATELIST_CHECK'        => '',
      'PM_TEMPLATELIST_INDEX'        => $count,
      'CREATE_BY'                    => Auth::user()->name,
      'CREATE_TIME'                  => Carbon::now(),
      'UNID'                         => $UNID,
    ]);
    $DATA_MASTERIMPS  = MasterIMPS::select('MACHINE_CODE','MACHINE_UNID')
                                  ->where('PM_TEMPLATE_UNID_REF','=',$request->PM_TEMPLATE_UNID_REF)
                                  ->get();
    if (count($DATA_MASTERIMPS) > 0) {
      foreach ($DATA_MASTERIMPS as $key => $dataset) {
        MasterIMPSGroup::insert([
          'UNID'                      => $this->randUNID('PMCS_CMMS_MASTER_IMPS_GP'),
          'PM_TEMPLATELIST_UNID_REF'  => $UNID,
          'MACHINE_CODE'              => $dataset->MACHINE_CODE,
          'MACHINE_UNID'              => $dataset->MACHINE_UNID,
          'PM_TEMPLATE_UNID_REF'      => $request->PM_TEMPLATE_UNID_REF,
          'PM_TEMPLATELIST_NAME'      => $request->PM_TEMPLATELIST_NAME,
          'PM_TEMPLATELIST_DAY'       => '',
          'CREATE_BY'                 => Auth::user()->name,
          'CREATE_TIME'               => Carbon::now(),
          'MODIFY_BY'                 => Auth::user()->name,
          'MODIFY_TIME'               => Carbon::now(),
        ]);
      }
    }
    if ($request->save == "new") {
      alert()->success('เพิ่มระบบ สำเร็จ')->autoclose('1500');
      return Redirect()->back();
    }else {
      $data = MachinePmTemplateList::where('PM_TEMPLATE_UNID_REF',$request->PM_TEMPLATE_UNID_REF)->orderBy('CREATE_TIME','DESC')->first();
      return Redirect('machine/pm/templatelist/edit/'.$data->UNID);
    }
  }
  public function PmTemplateListEdit($UNID){
    $datapmtemplatelist   = MachinePmTemplateList::where('UNID',$UNID)->first();
    $datapmtemplate       = MachinePmTemplate::select('UNID','PM_TEMPLATE_NAME')->where('UNID',$datapmtemplatelist->PM_TEMPLATE_UNID_REF)->first();
    $datapmtemplatedetail = MachinePmTemplateDetail::select('*')->selectraw("Case When PM_TYPE_INPUT = 'number' then 'ข้อมูลตัวเลข'
	  When PM_TYPE_INPUT = 'text' then 'ข้อมูลเป็นตัวอักษร'
	  When PM_TYPE_INPUT = 'radio' then 'ข้อมูลเป็น ผ่าน ไม่ผ่าน'
	  ELSE 'ไม่พบข้อมูล' END AS PM_TYPE")->where('PM_TEMPLATELIST_UNID_REF',$UNID)->orderBy('PM_DETAIL_INDEX','ASC')->get();

    return View('machine/add/system/edit',compact('datapmtemplatelist','datapmtemplatedetail','datapmtemplate'));
  }
  public function UpdatePMList(Request $request,$UNID) {
    $validated = $request->validate([
      'PM_TEMPLATELIST_NAME'           => 'required|max:200',
      'PM_TEMPLATELIST_DAY'            => 'integer|min:1|max:12'
      ],
      [
      'PM_TEMPLATELIST_NAME.required'  => 'กรุณาใส่รายการ PM',
      'PM_TEMPLATELIST_NAME.max'       => 'ชื่อยาวเกินไป',
      'PM_TEMPLATELIST_DAY.integer'    => 'กรุณาใส่จำนวนวันเป็นตัวเลขและไม่มีจุดทศนิยม',
      'PM_TEMPLATELIST_DAY.min'        => 'ใส่จำนวนเดือนต่ำสุดได้ 1',
      'PM_TEMPLATELIST_DAY.max'        => 'ใส่จำนวนเดือนสูงสุดได้ 12'
      ]);
    MachinePmTemplateList::where('UNID',$UNID)->update([
        'PM_TEMPLATELIST_NAME'      => $request->PM_TEMPLATELIST_NAME,
        'PM_TEMPLATELIST_POINT'     => $request->PM_TEMPLATELIST_POINT,
        'PM_TEMPLATELIST_DAY'       => ($request->PM_TEMPLATELIST_DAY*30),
        'PM_TEMPLATELIST_STATUS'    => $request->PM_TEMPLATELIST_STATUS,
        'MODIFY_BY'              => Auth::user()->name,
        'MODIFY_TIME'            => Carbon::now(),
    ]);
    MasterIMPSGroup::where('PM_TEMPLATELIST_UNID_REF',$UNID)->update([
      'PM_TEMPLATELIST_NAME'      => $request->PM_TEMPLATELIST_NAME,
      'PM_TEMPLATELIST_DAY'       => ($request->PM_TEMPLATELIST_DAY*30),
      'PM_TEMPLATELIST_STATUS'    => $request->PM_TEMPLATELIST_STATUS,
    ]);
    if (isset($request->save)) {
      $UNID_TEMPLATE = MachinePmTemplateList::select('PM_TEMPLATE_UNID_REF')->where('UNID','=',$UNID)->first();
      return Redirect('machine/pm/template/add/'.$UNID_TEMPLATE->PM_TEMPLATE_UNID_REF);
    }else {
      alert()->success('อัพเดทรายการสำเร็จ')->autoclose('1500');
      return Redirect()->back();
    }
  }
  public function DeletePMList($UNID) {
      MachinePmTemplateList::where('UNID',$UNID)->delete();
      MachinePmTemplateDetail::where('PM_TEMPLATELIST_UNID_REF',$UNID)->delete();
      alert()->success('ลบข้อมูลสำเร็จ')->autoclose('1500');
    return Redirect()->back();
  }
  public function DeletePMListAll($UNID) {
        MachinePmTemplateList::where('UNID',$UNID)->delete();
        MachinePmTemplateDetail::where('PM_TEMPLATELIST_UNID_REF',$UNID)->delete();
        MasterIMPSGroup::where('PM_TEMPLATELIST_UNID_REF',$UNID)->delete();
        alert()->success('ลบข้อมูลทั้งหมดสำเร็จ')->autoclose('1500');
      return Redirect()->back();
  }

  public function PmTemplateDetailStore(Request $request){

    $validated = $request->validate([
      'PM_DETAIL_NAME'           => 'required|max:200',
      ],
      [
      'PM_DETAIL_NAME.required'  => 'กรุณาใส่ชื่อกลุ่ม',
      'PM_DETAIL_NAME.max'  => 'ไม่สามารถใส่ตัวอักษรเกิน 200 ตัวอักษร',
      ]);
    $count = 1;
    $rowcount = MachinePmTemplateDetail::selectraw('max(PM_DETAIL_INDEX)count')->where('PM_TEMPLATELIST_UNID_REF',$request->PM_TEMPLATELIST_UNID_REF)->first();
    if ($rowcount->count() > 0) {
      $count = $rowcount->count()+1;
    }
    $PM_DETAIL_STD_MAX = $request->PM_DETAIL_STD_MAX != NULL ? $request->PM_DETAIL_STD_MAX : 0;
    $PM_DETAIL_STD_MIN = $request->PM_DETAIL_STD_MIN != NULL ? $request->PM_DETAIL_STD_MIN : 0;
    $PM_DETAIL_STATUS_MAX = $request->PM_DETAIL_STATUS_MAX == 'true' ? 'true' : 'false' ;
    $PM_DETAIL_STATUS_MIN = $request->PM_DETAIL_STATUS_MIN == 'true' ? 'true' : 'false' ;
    MachinePmTemplateDetail::insert([
      'PM_DETAIL_NAME'           => $request->PM_DETAIL_NAME,
      'PM_DETAIL_STD'            => $request->PM_DETAIL_STD,
      'PM_TYPE_INPUT'            => $request->PM_TYPE_INPUT,
      'PM_TEMPLATELIST_UNID_REF' => $request->PM_TEMPLATELIST_UNID_REF,
      'PM_DETAIL_INDEX'          => $count,
      'PM_DETAIL_STD_MIN'        => $PM_DETAIL_STD_MIN,
      'PM_DETAIL_STD_MAX'        => $PM_DETAIL_STD_MAX,
      'PM_DETAIL_UNIT'           => $request->PM_DETAIL_UNIT,
      'PM_DETAIL_STATUS_MAX'     => $PM_DETAIL_STATUS_MAX,
      'PM_DETAIL_STATUS_MIN'     => $PM_DETAIL_STATUS_MIN,
      'CREATE_BY'                => Auth::user()->name,
      'CREATE_TIME'              => Carbon::now(),
      'MODIFY_BY'                => Auth::user()->name,
      'MODIFY_TIME'              => Carbon::now(),
      'UNID'                     => $this->randUNID('PMCS_CMMS_PM_TEMPLATE_DETAIL'),
    ]);
    alert()->success('เพิ่มระบบ สำเร็จ')->autoclose('1500');
    return Redirect()->back();
  }
  public function PmTemplateDetailUpdate(Request $request){
    $PM_DETAIL_STD_MAX    = $request->PM_DETAIL_STD_MAX != NULL ? $request->PM_DETAIL_STD_MAX : 0;
    $PM_DETAIL_STD_MIN    = $request->PM_DETAIL_STD_MIN != NULL ? $request->PM_DETAIL_STD_MIN : 0;
    $PM_DETAIL_STATUS_MAX = $request->PM_DETAIL_STATUS_MAX == 'true' ? 'true' : 'false' ;
    $PM_DETAIL_STATUS_MIN = $request->PM_DETAIL_STATUS_MIN == 'true' ? 'true' : 'false' ;
    MachinePmTemplateDetail::where('UNID',$request->DETAIL_UNID)->update([
      'PM_DETAIL_NAME'         => $request->PM_DETAIL_NAME,
      'PM_DETAIL_STD'          => $request->PM_DETAIL_STD,
      'PM_TYPE_INPUT'          => $request->PM_TYPE_INPUT,
      'PM_DETAIL_UNIT'         => $request->PM_DETAIL_UNIT,
      'PM_DETAIL_STD_MIN'      => $PM_DETAIL_STD_MIN,
      'PM_DETAIL_STD_MAX'      => $PM_DETAIL_STD_MAX,
      'PM_DETAIL_STATUS_MAX'   => $PM_DETAIL_STATUS_MAX,
      'PM_DETAIL_STATUS_MIN'   => $PM_DETAIL_STATUS_MIN,
      'MODIFY_BY'              => Auth::user()->name,
      'MODIFY_TIME'            => Carbon::now(),
    ]);
    alert()->success('เพิ่มระบบ สำเร็จ')->autoclose('1500');
    return Redirect()->back();
  }
  public function DeletePMDetail($UNID) {
    $dataset = MachinePmTemplateDetail::where('UNID','=',$UNID)->delete();
    alert()->success('ลบสำเร็จ')->autoclose('1500');
    return Redirect()->back();
  }


}
