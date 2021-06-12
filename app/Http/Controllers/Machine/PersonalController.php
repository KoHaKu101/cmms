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
use App\Models\Machine\PositionEMP;
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

  public function Index(Request $request){
    $SEARCH = isset($request->SEARCH) ?  '%'.$request->SEARCH.'%' : '';

    $encode = EMPName::selectRaw("dbo.encode_utf8('$SEARCH') as SEARCH")->first();

    $data_position = PositionEMP::select('*')->selectraw('dbo.decode_utf8(EMP_POSITION_NAME) as EMP_POSITION_NAME')
                                ->where('STATUS','=','9')->get();
    $dataset = EMPName::select('*')->selectraw('dbo.decode_utf8(EMP_NAME) as EMP_NAME_TH')
                                   ->where(function($query) use ($SEARCH,$encode){
                                      if ($SEARCH != '') {
                                         $query->where('EMP_CODE', 'like', $SEARCH)
                                               ->orwhere('EMP_NAME', 'like', $SEARCH)
                                               ->orwhere('EMP_NAME','like' ,$encode->SEARCH) ;
                                       }
                                   })
                                   ->orderBy('EMP_CODE')->paginate(8);
    $SEARCH = str_replace('%','',$SEARCH);
    return View('machine/personal/personallist',compact('dataset','SEARCH','data_position'));
  }
  public function Create(){

    $datalineselect = MachineLine::all();
    $data_position = PositionEMP::select('*')->selectraw('dbo.decode_utf8(EMP_POSITION_NAME) as EMP_POSITION_NAME')
                                ->whereraw('EMP_POSITION_LIMIT != LITMIT_STOCK')->where('STATUS','=','9')->get();
    return View('machine/personal/form',compact('datalineselect','data_position'));
  }

  public function Store(Request $request){
    $validated = $request->validate([
      'EMP_CODE'           => 'required|max:50',
      'EMP_NAME'           => 'required|max:200',
      'EMP_ICON' => 'mimes:jpeg,png,jpg',
      'POSITION' => 'required',

      ],
      [
      'EMP_CODE.required'  => 'กรุณราใส่รหัสพนักงาน',
      'EMP_NAME.required'  => 'กรุณราใส่ชื่อพนักงาน',
      'EMP_ICON.mimes'   => 'เฉพาะไฟล์ jpeg, png, jpg',
      'POSITION.required'=> 'กรุณาเลือกตำแหน่งงาน',
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

      $POSITION = $request->POSITION;
      $DATA_POSITIONEMP = PositionEMP::where('EMP_POSITION_CODE','=',$POSITION)->first();
        if ($DATA_POSITIONEMP->LITMIT_STOCK > $DATA_POSITIONEMP->EMP_POSITION_LIMIT) {
          alert()->error('ตำแหน่งงานเต็ม')->autoclose('1000');
          return Redirect()->back();
        }
    $ENCODE = EMPName::selectRaw("dbo.encode_utf8('$request->EMP_NAME') as EMP_NAME")->first();
    $EMP_NAME = $ENCODE->EMP_NAME;
    EMPName::insert([
      'EMP_CODE'         => $request->EMP_CODE,
      'EMP_NAME'         => $EMP_NAME,
      'EMP_ICON'         => $last_img,
      'EMP_LINE'         => $request->EMP_LINE,
      'EMP_NOTE'         => $request->EMP_NOTE,
      'EMP_STATUS'           => $request->EMP_STATUS,
      'POSITION'             => $POSITION,
      'CREATE_BY'            => Auth  ::user()->name,
      'CREATE_TIME'          => Carbon::now(),
      'MODIFY_BY'            => Auth::user()->name,
      'MODIFY_TIME'          => Carbon::now(),
      'UNID'                 => $UNID,

    ]);

        $LITMIT_STOCK = $DATA_POSITIONEMP->LITMIT_STOCK > 0 ? $DATA_POSITIONEMP->LITMIT_STOCK+1 : 1;
        $DATA_POSITIONEMP->update([
          'LITMIT_STOCK' => $LITMIT_STOCK
        ]);

    alert()->success('ลงทะเบียน สำเร็จ')->autoclose('1500');
    return Redirect()->route('personal.edit',$UNID);

  }
  public function Edit($UNID) {

    $data_position = PositionEMP::select('*')->selectraw('dbo.decode_utf8(EMP_POSITION_NAME) as EMP_POSITION_NAME')
                                ->whereraw('EMP_POSITION_LIMIT != LITMIT_STOCK')->where('STATUS','=','9')->get();

    $EMP_POSTION = 'PMCS_EMP_POSITION';
    $EMP_NAME = 'PMCS_EMP_NAME';
    $dataset = EMPName::select($EMP_NAME.'.*','EMP_POSITION_CODE')
                        ->selectraw('dbo.decode_utf8(EMP_NAME) as EMP_NAME,
                                    dbo.decode_utf8(EMP_POSITION_NAME) as EMP_POSITION_NAME')
                        ->leftJoin($EMP_POSTION, $EMP_NAME.'.POSITION' , '=',$EMP_POSTION.'.EMP_POSITION_CODE' )
                        ->where($EMP_NAME.'.UNID','=',$UNID)->first();
    $datalineselect = MachineLine::where('LINE_NAME','like','%'.'Line'.'%')->get();
    return view('machine/personal/edit',compact('dataset','datalineselect','data_position'));

  }
  public function Update(Request $request,$UNID){
    $validated = $request->validate([
      'EMP_ICON' => 'mimes:jpeg,png,jpg',
      'POSITION' => 'required',
      ],
      [
      'EMP_ICON.mimes'   => 'เฉพาะไฟล์ jpeg, png, jpg',
      'POSITION.required' => 'กรุณาเลือกตำแหน่งงาน',
      ]);
      $POSITION = $request->POSITION;
      $DATA_POSITIONEMP = PositionEMP::where('EMP_POSITION_CODE','=',$POSITION)->first();
        if ($DATA_POSITIONEMP->LITMIT_STOCK > $DATA_POSITIONEMP->EMP_POSITION_LIMIT) {
          alert()->error('ตำแหน่งงานเต็ม')->autoclose('1000');
          return Redirect()->back();
        }
      $DATA_EMPNAME = EMPName::where('UNID',$UNID)->first();
      $last_img = $DATA_EMPNAME->EMP_ICON;
        if ($request->hasFile('EMP_ICON')) {
              $image = $request->file('EMP_ICON');
              $new_name = rand() . '.' . $image->getClientOriginalExtension();
              $this->saveimg($image,$new_name);
                $last_img = $new_name;
              }
      $DATA_POSITIONEMP_CHANGE = PositionEMP::where('EMP_POSITION_CODE','=',$DATA_EMPNAME->POSITION)->first();
      $ENCODE = EMPName::selectRaw("dbo.encode_utf8('$request->EMP_NAME') as EMP_NAME")->first();
      $EMP_NAME = $ENCODE->EMP_NAME;
       EMPName::where('UNID',$UNID)->update([
        'EMP_CODE'         => $request->EMP_CODE,
        'EMP_NAME'         => $EMP_NAME,
        'EMP_ICON'         => $last_img,
        'EMP_LINE'         => $request->EMP_LINE,
        'EMP_NOTE'         => $request->EMP_NOTE,
        'POSITION'             => $POSITION,
        'EMP_STATUS'           => $request->EMP_STATUS,
        'MODIFY_BY'            => Auth::user()->name,
        'MODIFY_TIME'          => Carbon::now(),
      ]);
      $LITMIT_STOCK = $DATA_POSITIONEMP->LITMIT_STOCK > 0 ? $DATA_POSITIONEMP->LITMIT_STOCK+1 : 1 ;
      $DATA_POSITIONEMP->update([
        'LITMIT_STOCK' => $LITMIT_STOCK
      ]);
      if (isset($DATA_POSITIONEMP_CHANGE->LITMIT_STOCK)) {
        $LITMIT_STOCK_CHANGE =  $DATA_POSITIONEMP_CHANGE->LITMIT_STOCK > 0 ? $DATA_POSITIONEMP_CHANGE->LITMIT_STOCK-1 : 0;
        $DATA_POSITIONEMP_CHANGE->update([
          'LITMIT_STOCK' => $LITMIT_STOCK_CHANGE
        ]);
      }
  alert()->success('อัพเดทรายการสำเร็จ')->autoclose('1500');
  return Redirect()->back();
  }

  public function Delete($UNID){
      $DATA_EMPNAME = EMPName::where('UNID','=',$UNID)->first();
      $DATA_POSITIONEMP = PositionEMP::where('EMP_POSITION_CODE','=',$DATA_EMPNAME->POSITION)->first();
    if (isset($DATA_POSITIONEMP->LITMIT_STOCK)){
      $LITMITE_STOCK = $DATA_POSITIONEMP->LITMIT_STOCK > 0 ? $DATA_POSITIONEMP->LITMIT_STOCK-1 : 0 ;
      $DATA_POSITIONEMP->update([
        'LITMIT_STOCK' => $LITMITE_STOCK,
      ]);}
      $DATA_EMPNAME->delete();
      alert()->success('ลบรายการสำเร็จ')->autoclose('1500');
      return Redirect()->back();

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
