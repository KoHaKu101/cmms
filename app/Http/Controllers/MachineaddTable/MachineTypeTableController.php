<?php

namespace App\Http\Controllers\MachineaddTable;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
use File;
//******************** model ***********************
use App\Models\MachineAddTable\MachineTypeTable;
use App\Models\Machine\Protected;
//************** Package form github ***************
use RealRashid\SweetAlert\Facades\Alert;
use App\Exports\MachineExport;
use Maatwebsite\Excel\Facades\Excel;



class MachineTypeTableController extends Controller
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

    $dataset = MachineTypeTable::paginate(10);

    return View('machine/add/typemachine/typemachinelist',compact('dataset'));
  }
  public function Create(){
    return View('machine/add/typemachine/form');
  }

  public function Store(Request $request){
    $UNID = $this->randUNID('PMCS_MACHINE_TYPE');
    $validated = $request->validate([
      'TYPE_CODE'           => 'required|unique:PMCS_MACHINE_TYPE|max:50',
      'TYPE_NAME'           => 'required|unique:PMCS_MACHINE_TYPE|max:200',
      ],
      [
      'TYPE_CODE.required'  => 'กรุณราใส่รหัสประเภทเครื่องจักร',
      'TYPE_CODE.unique'    => 'มีรหัสประเภทเครื่องจักรนี้แล้ว',
      'TYPE_NAME.required'  => 'กรุณาใสรายการประเภทครื่องจักร',
      'TYPE_NAME.unique'    => 'มีรายการประเภทเครื่องจักรนี้แล้ว'
      ]);

      if ($request->hasFile('TYPE_ICON')) {
        if ($request->file('TYPE_ICON')->isValid()) {
            $image = $request->file('TYPE_ICON');
            $new_name = rand() . '.' . $image->getClientOriginalExtension();
            $this->saveimg($image,$new_name);
            $last_img = $new_name;
        }
    } else {
        $last_img = "";
    }
    MachineTypeTable::insert([
      'TYPE_CODE'       => $request->TYPE_CODE,
      'TYPE_NAME'       => $request->TYPE_NAME,
      'TYPE_NOTE'       => $request->TYPE_NOTE,
      'TYPE_STATUS'     => $request->TYPE_STATUS,
      'TYPE_ICON'       => $last_img,
      'TYPE_STATUS'     => $request->TYPE_STATUS,
      'CREATE_BY'       => Auth::user()->name,
      'CREATE_TIME'     => Carbon::now(),
      'UNID'            => $UNID
    ]);
    $dataset = MachineTypeTable::paginate(10);
    return Redirect()->route('machinetypetable.list',compact('dataset'))->with('success','ลงทะเบียน สำเร็จ');
  }
  public function Edit($UNID) {
    $dataset = MachineTypeTable::where('UNID','=',$UNID)->first();
    return view('machine/add/typemachine/edit',compact('dataset'));
}
public function Update(Request $request,$UNID) {
  $DATA_MACHINE_TYPE = MachineTypeTable::where('UNID','=',$UNID)->first();
  $last_img = $DATA_MACHINE_TYPE->TYPE_ICON;
  if ($request->hasFile('TYPE_ICON')) {
      $pathfile = public_path('image/machinetype/'.$last_img);
      File::delete($pathfile);
      $image = $request->file('TYPE_ICON');
      $new_name = rand() . '.' . $image->getClientOriginalExtension();
      $this->saveimg($image,$new_name);

      $last_img = $new_name;
}
  MachineTypeTable::where('UNID',$UNID)->update([
    'TYPE_CODE'       => $request->TYPE_CODE,
    'TYPE_NAME'       => $request->TYPE_NAME,
    'TYPE_NOTE'       => $request->TYPE_NOTE,
    'TYPE_STATUS'     => $request->TYPE_STATUS,
    'TYPE_ICON'       => $last_img,
    'TYPE_STATUS'     => $request->TYPE_STATUS,
    'MODIFY_BY'       => Auth::user()->name,
    'MODIFY_TIME'     => Carbon::now(),

  ]);

  return Redirect()->back()->with('success','อัพเดทรายการสำเร็จ');

}  public function Delete($UNID) {


    $dataset = MachineTypeTable::where('UNID','=',$UNID)->delete();

    return Redirect()->back()->with('success','ลบสำเร็จ สำเร็จ');
}
  function saveimg($image = NUll,$new_name = NULL){


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
    $path = public_path('image/machinetype/');
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
