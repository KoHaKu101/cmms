<?php

namespace App\Http\Controllers\Machine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
use File;
//******************** model ***********************
use App\Models\Machine\Machine;
use App\Models\Machine\MachineCheckSheet;
use App\Models\Machine\Uploadimg;
//***************** Controller ************************
use App\Http\Controllers\Machine\UploadImgController;

//************** Package form github ***************
use RealRashid\SweetAlert\Facades\Alert;
// use Intervention\Image\ImageManagerStatic as Image;


class DailyCheckController extends Controller
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

  //อยู่ใน machine edit
  public function DailyList(Request $request){
     $MACHINE_LINE = $request->MACHINE_LINE != '' ? '%'.$request->MACHINE_LINE.'%' : '%' ;
     $MACHINE_CODE = $request->SEARCH_MACHINE != '' ? '%'.$request->SEARCH_MACHINE.'%' : '%' ;
     $YEAR         = $request->YEAR != '' ? $request->YEAR : date('Y') ;
     $MONTH        = $request->MONTH != '' ? $request->MONTH : date('n') ;


     $DATA_MACHINE = Machine::select('*')->selectRaw('dbo.decode_utf8(MACHINE_NAME) as MACHINE_NAME_V2')
                                         ->where('MACHINE_CODE','like',$MACHINE_CODE)
                                         ->where('MACHINE_LINE','like',$MACHINE_LINE)
                                         ->where('MACHINE_STATUS','=','9')
                                         ->orderBy('MACHINE_CODE')
                                         ->paginate(10);

    $DATA_CHECKSHEET = MachineCheckSheet::where('CHECK_MONTH','=',$MONTH)
                                          ->where('CHECK_YEAR','=',$YEAR)
                                          ->get();
      $MACHINE_LINE = str_replace('%','',$MACHINE_LINE);
      $MACHINE_CODE = str_replace('%','',$MACHINE_CODE);

      return view('machine.dailycheck.dailylist',compact('DATA_MACHINE','DATA_CHECKSHEET','MONTH','YEAR','MACHINE_LINE','MACHINE_CODE'));
  }

  public function CheckSheetUpload(Request $request) {
    $validated = $request->validate([
      'FILE_NAME' => 'mimes:jpeg,png,jpg',
      ],
      [
      'FILE_NAME.mimes'   => 'เฉพาะไฟล์ jpeg, png, jpg',
      ]);

    $MACHINE_UNID = $request->MACHINE_UNID;
    $MACHINE_CODE = $request->MACHINE_CODE;
    $CHECK_MONTH = $request->CHECK_MONTH;
    $CHECK_YEAR =  $request->CHECK_YEAR;
    $MACHINE_LINE = $request->MACHINE_LINE;
    $count_machine = MachineCheckSheet::where('MACHINE_UNID','=',$MACHINE_UNID)
                      ->where('CHECK_MONTH','=',$CHECK_MONTH)
                      ->where('CHECK_YEAR','=',$CHECK_YEAR)
                      ->count();
    if ($count_machine > 0) {
      $DATA_CHECKSHEET = MachineCheckSheet::where('MACHINE_UNID','=',$MACHINE_UNID)
                        ->where('CHECK_MONTH','=',$CHECK_MONTH)
                        ->where('CHECK_YEAR','=',$CHECK_YEAR);
      $pathfile = public_path('image/checksheet/'.$CHECK_YEAR.'/'.$CHECK_MONTH.'/'.$DATA_CHECKSHEET->first()->FILE_NAME);
        File::delete($pathfile);
          $DATA_CHECKSHEET->delete();
      }
    $image = $request->file('FILE_NAME');
    $new_name = rand() . '.' . $image->getClientOriginalExtension();
    $img_ext = $image->getClientOriginalExtension();
    $width = 800;
    $height = 500;
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
    $path = public_path('image/checksheet/'.$CHECK_YEAR.'/'.$CHECK_MONTH.'/');
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
      MachineCheckSheet::insert([
        'UNID' => $this->randUNID('PMCS_CMMS_MACHINE_CHECKSHEET'),
        'MACHINE_UNID' => $MACHINE_UNID,
        'MACHINE_CODE' => $MACHINE_CODE,
        'FILE_NAME' => $new_name,
        'FILE_EXT' => $img_ext,
        'CHECK_YEAR'   => $CHECK_YEAR,
        'CHECK_MONTH'  => $CHECK_MONTH,
        'CREATE_BY'    => Auth::user()->name,
        'CREATE_TIME'  => Carbon::now()
      ]);
      alert()->success('บันทึกภาพสำเร็จ');
    }
      return Redirect()->back()->with( ['MONTH' => $CHECK_MONTH,'YEAR' =>
      $CHECK_YEAR,'MACHINE_LINE' => $MACHINE_LINE,'MACHINNE_CODE' => $request->SEARCH_MACHINE] );
  }

  public function DeleteImg(Request $request ){
    $UNID = $request->UNID;
    $DATA_CHECKSHEET = MachineCheckSheet::Where('UNID','=',$UNID)->count();
    if ($DATA_CHECKSHEET > 0) {
      $DATA_CHECKSHEET = MachineCheckSheet::Where('UNID','=',$UNID)->first();
      $response_array = 'pass';
    }else {
      $response_array = 'fail';
      return Response()->json(['status' => $response_array]);

    }
    $CHECK_YEAR  = $DATA_CHECKSHEET->CHECK_YEAR;
    $CHECK_MONTH = $DATA_CHECKSHEET->CHECK_MONTH;
    $FILE_NAME = $DATA_CHECKSHEET->FILE_NAME;
    $pathfile = public_path('image/checksheet/'.$CHECK_YEAR.'/'.$CHECK_MONTH.'/'.$FILE_NAME);

    if (File::delete($pathfile) == true) {
      File::delete($pathfile);
      MachineCheckSheet::where('UNID',$UNID)->delete();
    }else {
      $response_array = 'fail';
      return Response()->json(['status' => $response_array]);
    }


    return Response()->json(['status' => $response_array]);
  }




  }
