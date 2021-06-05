<?php

namespace App\Http\Controllers\Machine;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
use File;
use Illuminate\Http\Request;
//******************** model ***********************
use App\Models\Machine\EMPName;
use App\Models\Machine\MachineEMP;
use App\Models\Machine\MachineLine;
//************** Package form github ***************
use App\Exports\MachineExport;
use Maatwebsite\Excel\Facades\Excel;



class PersonalController extends Controller
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

    $dataset = EMPName::select('*')->selectraw('dbo.decode_utf8(EMP_NAME) as EMP_NAME2')->paginate(6);

    return View('machine/personal/personallist',compact('dataset'));
  }
  public function Create(){

    $datalineselect = MachineLine::all();

    return View('machine/personal/form',compact('datalineselect'));
  }

  public function Store(Request $request){

    $validated = $request->validate([
      'EMP_CODE'           => 'required|max:50',
      'EMP_NAME'           => 'required|max:200'
      ],
      [
      'EMP_CODE.required'  => 'กรุณราใส่รหัสพนักงาน',
      'EMP_NAME.required'  => 'กรุณราใส่ชื่อพนักงาน'
      ]);

      $UNID = $this->randUNID('PMCS_EMP_NAME');
      if ($request->hasFile('EMP_ICON')) {
        if ($request->file('EMP_ICON')->isValid()) {
            $image = $request->file('EMP_ICON');
            $new_name = rand() . '.' . $image->getClientOriginalExtension();
            $this->saveimg($image,$new_name);
            $last_img = $new_name;
        }
    } else {
        $last_img = "";
    }
    EMPName::insert([

      'EMP_CODE'         => $request->EMP_CODE,
      'EMP_NAME'         => $request->EMP_NAME,
      'EMP_ICON'         => $last_img,
      'EMP_GROUP'        => $request->EMP_GROUP,
      'EMP_NOTE'         => $request->EMP_NOTE,
      'EMP_STATUS'           => $request->EMP_STATUS,
      'POSITION'             => $request->POSITION,
      'CREATE_BY'            => Auth  ::user()->name,
      'CREATE_TIME'          => Carbon::now(),
      'MODIFY_BY'            => Auth::user()->name,
      'MODIFY_TIME'          => Carbon::now(),
      'UNID'                 => $UNID,

    ]);
    return Redirect()->route('personal.edit',$UNID)->with('success','ลงทะเบียน สำเร็จ');

  }
  public function Edit($UNID) {
    $dataset = EMPName::select('*')->selectraw('dbo.decode_utf8(EMP_NAME) as EMP_NAME')->where('UNID','=',$UNID)->first();
    $datalineselect = MachineLine::where('LINE_NAME','like','%'.'Line'.'%')->get();
    return view('machine/personal/edit',compact('dataset','datalineselect'));

  }
  public function Update(Request $request,$UNID){

      $data_EMPNAME = EMPName::where('UNID',$UNID)->first();
      $last_img = $data_EMPNAME->EMP_ICON;
    if ($request->hasFile('EMP_ICON')) {
      if ($request->file('EMP_ICON')->isValid()) {
          $image = $request->file('EMP_ICON');
          $new_name = rand() . '.' . $image->getClientOriginalExtension();
          $this->saveimg($image,$new_name);
            $last_img = $new_name;
      }
  }
   EMPName::where('UNID',$UNID)->update([

    'EMP_CODE'         => $request->EMP_CODE,
    'EMP_NAME'         => $request->EMP_NAME,
    'EMP_ICON'         => $last_img,
    'EMP_GROUP'        => $request->EMP_GROUP,
    'EMP_NOTE'         => $request->EMP_NOTE,
    'POSITION'             => $request->POSITION,
    'EMP_STATUS'           => $request->EMP_STATUS,
    'MODIFY_BY'            => Auth::user()->name,
    'MODIFY_TIME'          => Carbon::now(),

  ]);

  return Redirect()->back()->with('success','อัพเดทรายการสำเร็จ');
  }
  public function Delete($UNID){
      $data_up = EMPName::where('UNID','=',$UNID)->delete();

      return Redirect()->back()-> with('success','Confirm Delete Success');

  }
  public function saveimg($image=NULL,$new_name=NULL){
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
    $path = public_path('image/emp');
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
