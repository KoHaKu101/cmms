<?php

namespace App\Http\Controllers\Machine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Auth;
use File;
use Cookie;
use Illuminate\Http\Response;

//******************** model ***********************
use App\Models\Machine\Machine;
use App\Models\Machine\Protected;
use App\Models\Machine\MachineUpload;
use App\Models\Machine\MachineLine;
use App\Models\Machine\MachineEMP;
use App\Models\Machine\MachineRepairREQ;
use App\Models\Machine\MasterIMPS;
use App\Models\Machine\MasterIMPSGroup;
use App\Models\Machine\MachineSparePart;
use App\Models\Machine\BomMachine;

use App\Models\MachineaddTable\MachinePmTemplate;
use App\Models\MachineaddTable\MachinePmTemplateDetail;
use App\Models\MachineaddTable\MachineTypeTable;
use App\Models\MachineAddTable\MachineStatusTable;
use App\Models\MachineAddTable\MachineSysTemTable;
use App\Models\MachineaddTable\MachinePmTemplateList;
use App\Models\MachineAddTable\MachineRankTable;



//************** Package form github ***************
use RealRashid\SweetAlert\Facades\Alert;
use App\Exports\MachineExport;
use Maatwebsite\Excel\Facades\Excel;




class MachineController extends Controller
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

  public function Index(){
    $dataset = MachineLine::where('LINE_NAME','like','%'.'Line'.'%')->get();
    return View('machine/assets/machinemenu',compact(['dataset']),['dataset' => $dataset]);
  }

  public function All(Request $request) {

    $COOKIE_MACHINE_CHECK     = $request->MACHINE_CHECK     != '' ? $request->MACHINE_CHECK     : $request->cookie('MACHINE_CHECK');
    $COOKIE_LINE              = $request->LINE              != '' ? $request->LINE              : $request->cookie('LINE');
    $COOKIE_MACHINE_RANK_CODE = $request->MACHINE_RANK_CODE != '' ? $request->MACHINE_RANK_CODE : $request->cookie('MACHINE_RANK_CODE');
    $COOKIE_MACHINE_STATUS    = $request->MACHINE_STATUS    != '' ? $request->MACHINE_STATUS    : $request->cookie('MACHINE_STATUS');
    $MINUTES = 30;

    Cookie::queue('MACHINE_CHECK',$COOKIE_MACHINE_CHECK,$MINUTES);
    Cookie::queue('LINE',$COOKIE_LINE,$MINUTES);
    Cookie::queue('MACHINE_RANK_CODE',$COOKIE_MACHINE_RANK_CODE,$MINUTES);
    Cookie::queue('MACHINE_STATUS',$COOKIE_MACHINE_STATUS,$MINUTES);


    $LINE = MachineLine::where('LINE_STATUS','=','9')->where('LINE_NAME','like','Line'.'%')->orderBy('LINE_NAME')->get();
    $RANK = MachineRankTable::where('MACHINE_RANK_STATUS','=','9')->orderBy('MACHINE_RANK_CODE')->get();

    $MACHINE_CHECK = $COOKIE_MACHINE_CHECK;
    $SEARCH = $request->SEARCH ;

    $MACHINE_LINE = $COOKIE_LINE;
    $MACHINE_RANK_CODE = $COOKIE_MACHINE_RANK_CODE;
    $MACHINE_STATUS = $COOKIE_MACHINE_STATUS;
      $machine = Machine::select('*')->selectRaw('dbo.decode_utf8(MACHINE_NAME) as MACHINE_NAME_TH,dbo.decode_utf8(MACHINE_TYPE) as MACHINE_TYPE_TH')
                        ->where(function ($query) use ($MACHINE_LINE) {
                               if ($MACHINE_LINE > 0) {
                                  $query->where('MACHINE_LINE', '=', $MACHINE_LINE);
                                }
                               })
                        ->where(function ($query) use ($SEARCH) {
                              if ($SEARCH != "") {
                                 $query->where('MACHINE_CODE', 'like', '%'.$SEARCH.'%')
                                       ->orwhere('MACHINE_TYPE','like','%'.$SEARCH.'%')
                                       ->orwhere('MACHINE_NAME','like','%'.$SEARCH.'%')
                                       ->orwhere('MACHINE_MODEL','like','%'.$SEARCH.'%');
                               }
                              })
                        ->where(function ($query) use ($MACHINE_RANK_CODE) {
                             if ($MACHINE_RANK_CODE > 0){
                               $query->where('MACHINE_RANK_CODE', '=', $MACHINE_RANK_CODE);
                             }
                           })
                           ->where(function ($query) use ($MACHINE_CHECK) {
                                if ($MACHINE_CHECK > 0){
                                  $query->where('MACHINE_CHECK', '=', $MACHINE_CHECK);
                                }
                              })
                        ->where('MACHINE_TYPE_STATUS','=','9')
                        ->where('MACHINE_STATUS','=',$MACHINE_STATUS == '' ? 9 :$MACHINE_STATUS )
                        ->orderBy('MACHINE_LINE','ASC')
                        ->orderBy('MACHINE_CODE')->paginate(10);

    return view('machine/assets/machinelist',compact('MACHINE_LINE','machine','SEARCH','LINE','RANK','MACHINE_RANK_CODE','MACHINE_STATUS','MACHINE_CHECK'));
  }

  public function Create(){
    $machineline = MachineLine::select('LINE_CODE','LINE_NAME')->where('LINE_STATUS','=','9')->get();
    $machinetype = MachineTypeTable::where('TYPE_STATUS','=','9')->get();
    $machinestatus = MachineStatusTable::where('STATUS','=','9')->get();
    $machinerank   = MachineRankTable::where('MACHINE_RANK_STATUS','!=','1')->get();
    return View('machine/assets/form',compact('machineline','machinetype','machinestatus','machinerank'));
  }
  public function Store(Request $request){

    $validated = $request->validate([
      'MACHINE_CODE'           => 'required|unique:PMCS_MACHINE|max:50',
      'MACHINE_ICON' => 'mimes:jpeg,png,jpg',
      ],
      [
      'MACHINE_CODE.required'  => 'กรุณราใส่รหัสเครื่องจักร',
      'MACHINE_CODE.unique'    => 'มีรหัสเครื่องแล้ว',
      'MACHINE_ICON.mimes'   => 'เฉพาะไฟล์ jpeg, png, jpg',
      ]);
      if ($request->hasFile('MACHINE_ICON')) {
        if ($request->file('MACHINE_ICON')->isValid()) {
          $image = $request->file('MACHINE_ICON');
          $new_name = rand() . '.' . $image->getClientOriginalExtension();
          $this->SaveImg($image,$new_name,$request->MACHINE_LINE);
          $last_img = $new_name;
        }
      } else {
        $last_img = "";
      }
      $UNID = $this->randUNID('PMCS_MACHINE');
      $MACHINE_CODE = strtoupper($request->MACHINE_CODE);
      $rankcode = MachineRankTable::select('MACHINE_RANK_CODE')->where('MACHINE_RANK_MONTH',$request->MACHINE_RANK_MONTH)->first();
      Machine::insert([
          'MACHINE_CODE'         => $MACHINE_CODE,
          'MACHINE_NAME'         => $request->MACHINE_NAME,
          'MACHINE_CHECK'        => $request->MACHINE_CHECK,
          'MACHINE_MANU'         => $request->MACHINE_MANU,
          'MACHINE_TYPE'         => $request->MACHINE_TYPE,
          'MACHINE_TYPE_STATUS'  => $request->MACHINE_TYPE_STATUS,
          'MACHINE_STARTDATE'    => $request->MACHINE_STARTDATE,
          'MACHINE_RVE_DATE'     => $request->MACHINE_RVE_DATE,
          'MACHINE_ICON'         => $last_img,
          'MACHINE_PRICE'        => $request->MACHINE_PRICE,
          'MACHINE_LINE'         => $request->MACHINE_LINE,
          'MACHINE_MA_COST'      => $request->MACHINE_MA_COST,
          'MACHINE_SPEED_UNIT'   => $request->MACHINE_SPEED_UNIT,
          'MACHINE_PARTNO'       => $request->MACHINE_PARTNO,
          'MACHINE_MODEL'        => $request->MACHINE_MODEL,
          'MACHINE_SERIAL'       => $request->MACHINE_SERIAL,
          'MACHINE_SPEED'        => $request->MACHINE_SPEED,
          'MACHINE_MTBF'         => $request->MACHINE_MTBF,
          'MACHINE_POWER'        => $request->MACHINE_POWER,
          'MACHINE_WEIGHT'       => $request->MACHINE_WEIGHT,
          'MACHINE_TARGET'       => $request->MACHINE_TARGET,
          'MACHINE_STATUS'       => $request->MACHINE_STATUS,
          'MACHINE_POSTED'       => $request->MACHINE_POSTED,
          'PCDS_MACHINE_CODE'    => $request->PCDS_MACHINE_CODE,
          'WAREHOUSE_CODE'       => $request->WAREHOUSE_CODE,
          'GROUP_CODE'           => $request->GROUP_CODE,
          'LOCATION_CODE'        => $request->LOCATION_CODE,
          'SECTION_CODE'         => $request->SECTION_CODE,
          'SUPPLIER_CODE'        => $request->SUPPLIER_CODE,
          'SUPPLIER_NAME'        => $request->SUPPLIER_NAME,
          'PURCHASE_FORM'        => $request->PURCHASE_FORM,
          'PLAN_LAST_DATE'       => '',
          'REPAIR_LAST_DATE'     => '',
          'SPAR_PART_DATE'       => '',
          'CREATE_BY'            => Auth::user()->name,
          'CREATE_TIME'          => Carbon::now(),
          'MODIFY_BY'            => Auth::user()->name,
          'MODIFY_TIME'          => Carbon::now(),
          'UNID'                 => $UNID,
          'MACHINE_RANK_MONTH'   => $request->MACHINE_RANK_MONTH,
          'MACHINE_RANK_CODE'    => $rankcode->MACHINE_RANK_CODE,
      ]);
      alert()->success('ลงทะเบียน สำเร็จ')->autoclose('1500');
      return Redirect()->route('machine.edit',$UNID);
  }
  public function Edit($UNID) {
    //ใช้
    $dataset                     = Machine::select('*')->selectraw('dbo.decode_utf8(MACHINE_NAME) as MACHINE_NAME
                                                                    ,dbo.decode_utf8(PURCHASE_FORM) as PURCHASE_FORM')
                                                       ->where('UNID',$UNID)->first();

    $machineupload               = MachineUpload::where('UPLOAD_UNID_REF',$UNID)->get();
    $machinetype                 = MachineTypeTable::where('TYPE_STATUS','=','9')->get();
    $machinestatus               = MachineStatusTable::where('STATUS','=','9')->get();
    $machineemp                  = MachineEMP::select('*')->selectRaw('dbo.decode_utf8(EMP_NAME) as EMP_NAME,
                                                                      dbo.decode_utf8(EMP_NAME_LAST) as EMP_NAME_LAST')
                                                          ->where('MACHINE_CODE','=',$dataset->MACHINE_CODE)->get();
    $machineline                 = MachineLine::select('LINE_CODE','LINE_NAME')
                                              ->where('LINE_STATUS','=','9')
                                              ->get();
    $machinerepair               = MachineRepairREQ::where('MACHINE_UNID','=',$UNID)
                                                ->where('CLOSE_STATUS','=','9')
                                                ->get();

    $machinepmtemplate           = MachinePmTemplate::whereNotIn('PM_TEMPLATE_NAME',MasterIMPS::select('PM_TEMPLATE_NAME')
                                                    ->where('MACHINE_CODE',$dataset->MACHINE_CODE))
                                                    ->orderBy('CREATE_TIME','ASC')->paginate(6);
    $machinepmtemplateremove     = MachinePmTemplate::whereIn('PM_TEMPLATE_NAME',MasterIMPS::select('PM_TEMPLATE_NAME')
                                                    ->where('MACHINE_CODE',$dataset->MACHINE_CODE))
                                                    ->orderBy('CREATE_TIME','ASC')->paginate(6);
    $machinerank                 = MachineRankTable::select('MACHINE_RANK_MONTH','MACHINE_RANK_CODE')
                                                    ->where('MACHINE_RANK_STATUS','!=','1')->get();

    $masterimps                  =  MasterIMPS::where('MACHINE_UNID',$UNID)->orderBy('CREATE_TIME','ASC')->get();

    $masterimpsgroup             =  MasterIMPSGroup::orderBy('PM_TEMPLATELIST_INDEX','ASC')->get();
    $pmlistdetail                =  MachinePmTemplateDetail::orderBy('PM_DETAIL_INDEX','ASC')->get();
    $machinesparepart            =  MachineSparePart::where('MACHINE_UNID','=',$UNID)->where('STATUS','=','9')
                                             ->get();
    $machinepmtemplateremove     = MachinePmTemplate::whereIn('PM_TEMPLATE_NAME',MasterIMPS::select('PM_TEMPLATE_NAME')->where('MACHINE_UNID',$UNID))->orderBy('CREATE_TIME','ASC')->paginate(6);
    $machinepmtemplate           = MachinePmTemplate::whereNotIn('PM_TEMPLATE_NAME',MasterIMPS::select('PM_TEMPLATE_NAME')->where('MACHINE_UNID',$UNID))->orderBy('CREATE_TIME','ASC')->paginate(6);


    $DATA_PRODUCT             = BomMachine::select('MACHINE_CODE','MACHINE_NAME','PDCS_BOM_MACHINE.PRODUCT_CODE','PDCS_BOM_MACHINE.FORMULA_CODE'
                                                      ,'BASE_PRODUCTS.PRODUCT_NAME_TH','PROCESS_NO','PROCESS_CODE'
                                                      ,'ON_CT','ON_CT_HR','ON_CT_DAY','ON_PLAN_STATUS','WORKING_HR')
                                            ->selectRaw('dbo.decode_utf8(PROCESS_NAME) as PROCESS_NAME')
                                            ->leftjoin('PDCS_BOM_MASTER','PDCS_BOM_MASTER.PRODUCT_CODE','=','PDCS_BOM_MACHINE.PRODUCT_CODE')
                                            ->leftjoin('BASE_PRODUCTS','PDCS_BOM_MACHINE.PRODUCT_CODE','=','BASE_PRODUCTS.PRODUCT_CODE')
                                            ->where('MACHINE_CODE','=',$dataset->MACHINE_CODE)
                                            ->where('BOM_STATUS','=','9')
                                            ->orderBy('PDCS_BOM_MACHINE.PRODUCT_CODE')
                                            ->orderBy('PROCESS_NO')
                                            ->get();

    return view('machine/assets/edit',compact('DATA_PRODUCT','machinepmtemplate','machinepmtemplateremove','masterimps','masterimpsgroup','pmlistdetail','machinerank'
    ,'dataset','machineupload','machinetype','machineline','machinestatus','machineemp','machinerepair','machinesparepart'));
  }
  public function Update(Request $request,$UNID){
    $update = $request->MACHINE_UPDATE;
    $validated = $request->validate([
      'MACHINE_ICON' => 'mimes:jpeg,png,jpg',
      ],
      [
      'MACHINE_ICON.mimes'   => 'เฉพาะไฟล์ jpeg, png, jpg',
      ]);
    if ($request->hasFile('MACHINE_ICON')) {
      if ($request->file('MACHINE_ICON')->isValid()) {
        $image = $request->file('MACHINE_ICON');
        $new_name = rand() . '.' . $image->getClientOriginalExtension();
        $this->SaveImg($image,$new_name,$request->MACHINE_LINE);
        $last_img = $new_name;
      }
    } else {
      $last_img = $update;
    }
    $rankcode = MachineRankTable::select('MACHINE_RANK_CODE')->where('MACHINE_RANK_MONTH',$request->MACHINE_RANK_MONTH)->first();

    $MACHINE_CODE = strtoupper($request->MACHINE_CODE);
     Machine::where('UNID',$UNID)->update([

      'MACHINE_CODE'         => $MACHINE_CODE,
      'MACHINE_NAME'         => $request->MACHINE_NAME,
      'MACHINE_CHECK'        => $request->MACHINE_CHECK,
      'MACHINE_MANU'         => $request->MACHINE_MANU,
      'MACHINE_TYPE'         => $request->MACHINE_TYPE,
      'MACHINE_TYPE_STATUS'  => $request->MACHINE_TYPE_STATUS,
      'MACHINE_STARTDATE'    => $request->MACHINE_STARTDATE,
      'MACHINE_RVE_DATE'     => $request->MACHINE_RVE_DATE,
      'MACHINE_ICON'         => $last_img,
      'MACHINE_PRICE'        => $request->MACHINE_PRICE,
      'MACHINE_LINE'         => $request->MACHINE_LINE,
      'MACHINE_MA_COST'      => $request->MACHINE_MA_COST,
      'MACHINE_SPEED_UNIT'   => $request->MACHINE_SPEED_UNIT,
      'MACHINE_PARTNO'       => $request->MACHINE_PARTNO,
      'MACHINE_MODEL'        => $request->MACHINE_MODEL,
      'MACHINE_SERIAL'       => $request->MACHINE_SERIAL,
      'MACHINE_SPEED'        => $request->MACHINE_SPEED,
      'MACHINE_MTBF'         => $request->MACHINE_MTBF,
      'MACHINE_POWER'        => $request->MACHINE_POWER,
      'MACHINE_WEIGHT'       => $request->MACHINE_WEIGHT,
      'MACHINE_TARGET'       => $request->MACHINE_TARGET,
      'MACHINE_STATUS'       => $request->MACHINE_STATUS,
      'MACHINE_POSTED'       => $request->MACHINE_POSTED,
      'PCDS_MACHINE_CODE'    => $request->PCDS_MACHINE_CODE,
      'WAREHOUSE_CODE'       => $request->WAREHOUSE_CODE,
      'GROUP_CODE'           => $request->GROUP_CODE,
      'LOCATION_CODE'        => $request->LOCATION_CODE,
      'SECTION_CODE'         => $request->SECTION_CODE,
      'SUPPLIER_CODE'        => $request->SUPPLIER_CODE,
      'SUPPLIER_NAME'        => $request->SUPPLIER_NAME,
      'PURCHASE_FORM'        => $request->PURCHASE_FORM,
      'MACHINE_RANK_MONTH'   => $request->MACHINE_RANK_MONTH,
      'MACHINE_RANK_CODE'    => $rankcode->MACHINE_RANK_CODE,
      'MODIFY_BY'            => Auth::user()->name,
      'MODIFY_TIME'          => Carbon::now(),


    ]);
    alert()->success('อัพเดทรายการสำเร็จ')->autoclose('1500');
    return Redirect()->back();
  }

  public function Delete($UNID){

    $MACHINE_CHECK = '4';
    $MACHINE_STATUS = '1';

      $data_set = Machine::where('UNID',$UNID)->update([
        'MACHINE_CHECK'        => $MACHINE_CHECK,
        'MACHINE_STATUS'       => $MACHINE_STATUS,
        'MODIFY_BY'            => Auth::user()->name,
        'MODIFY_TIME'          => Carbon::now(),
        ]);

      alert()->success('จำหน่ายเครื่องสำเร็จ')->autoclose('1500');
      return Redirect()->back();

  }

  public function UserHomePage(){
    return View('machine.userpage.userhomepage');
  }

  public function SaveImg($image = NULL,$new_name = NULL,$MACHINE_LINE = NULL){
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
    $path = public_path('image/machine/'.$MACHINE_LINE);
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
  }


}
