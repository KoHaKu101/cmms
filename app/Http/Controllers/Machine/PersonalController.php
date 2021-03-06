<?php

namespace App\Http\Controllers\Machine;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
use File;
use Illuminate\Http\Request;
//******************** model ***********************
use App\Models\Machine\MachineLine;
use App\Models\Machine\EMPName;
use App\Models\Machine\EMPPAYTYPE;
use App\Models\Machine\EMPPOSTION;
//************** Package form github ***************
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
    $SEARCH   = isset($request->SEARCH) ?  $request->SEARCH : '';
    $dataset  = EMPName::select('PMCS_EMP_NAME.*','EMP_TYPE','POSITION_CODE')
                      ->selectraw('dbo.decode_utf8(EMP_NAME) as EMP_NAME_TH')
                      ->leftjoin('EMCS_EMPLOYEE','EMCS_EMPLOYEE.EMP_CODE','=','PMCS_EMP_NAME.EMP_CODE')
                      ->where(function($query) use ($SEARCH){
                         if ($SEARCH != '') {
                            $endcode = EMPName::selectRaw("dbo.encode_utf8('$SEARCH') as SEARCH")->first();
                            $query->where('PMCS_EMP_NAME.EMP_CODE', 'like', '%'.$SEARCH.'%')
                                  ->orwhere('PMCS_EMP_NAME.EMP_NAME', 'like', '%'.$SEARCH.'%')
                                  ->orwhere('PMCS_EMP_NAME.EMP_NAME','like' ,'%'.$endcode->SEARCH.'%') ;
                          }
                      })
                      ->orderBy('PMCS_EMP_NAME.EMP_CODE')->paginate(8);
    $data_emppaytype = EMPPAYTYPE::select('TYPE_CODE','TYPE_NAME')->selectraw('dbo.decode_utf8(TYPE_NAME) as TYPE_NAME')->where('TYPE_STATUS','=',9)->get();
    $data_emppostion = EMPPOSTION::select('POSITION_CODE','POSITION_NAME')->selectraw('dbo.decode_utf8(POSITION_NAME) as POSITION_NAME')->where('POSITION_STATUS','=',9)->get();

    return View('machine/personal/personallist',compact('dataset','SEARCH','data_emppaytype','data_emppostion'));
  }
  public function Create(){
    $datalineselect = MachineLine::select('LINE_CODE','LINE_NAME')->get();
    return View('machine/personal/form',compact('datalineselect'));
  }

  public function Store(Request $request){
    $validated = $request->validate([
      'EMP_CODE'           => 'required|max:50',
      'EMP_NAME'           => 'required|max:200',
      'EMP_ICON'           => 'mimes:jpeg,png,jpg',
      ],
      [
      'EMP_CODE.required'  => '????????????????????????????????????????????????????????????',
      'EMP_NAME.required'  => '????????????????????????????????????????????????????????????',
      'EMP_ICON.mimes'   => '??????????????????????????? jpeg, png, jpg',
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
    $ENCODE = EMPName::selectRaw("dbo.encode_utf8('$request->EMP_NAME') as EMP_NAME")->first();
    $EMP_NAME = $ENCODE->EMP_NAME;
    EMPName::insert([
      'EMP_CODE'         => $request->EMP_CODE,
      'EMP_NAME'         => $EMP_NAME,
      'EMP_ICON'         => $last_img,
      'EMP_LINE'         => $request->EMP_LINE,
      'EMP_NOTE'         => $request->EMP_NOTE,
      'EMP_STATUS'           => $request->EMP_STATUS,
      'POSITION'             => '',
      'CREATE_BY'            => Auth  ::user()->name,
      'CREATE_TIME'          => Carbon::now(),
      'MODIFY_BY'            => Auth::user()->name,
      'MODIFY_TIME'          => Carbon::now(),
      'UNID'                 => $UNID,

    ]);
    alert()->success('??????????????????????????? ??????????????????')->autoclose('1500');
    return Redirect()->route('personal.edit',$UNID);

  }
  public function Edit($UNID) {

    $EMCS_EMPLOYEE = 'EMCS_EMPLOYEE';
    $PMCS_EMP_NAME = 'PMCS_EMP_NAME';
    $dataset = EMPName::select($PMCS_EMP_NAME.'.*','EMP_TYPE','POSITION_CODE')
                        ->selectraw('dbo.decode_utf8(EMP_NAME) as EMP_NAME')
                        ->leftJoin($EMCS_EMPLOYEE, $EMCS_EMPLOYEE.'.EMP_CODE' , '=',$PMCS_EMP_NAME.'.EMP_CODE' )
                        ->where($PMCS_EMP_NAME.'.UNID','=',$UNID)->first();
    $data_emppaytype = EMPPAYTYPE::select('TYPE_NAME')->selectraw('dbo.decode_utf8(TYPE_NAME) as TYPE_NAME')
                                  ->where('TYPE_CODE','=',$dataset->EMP_TYPE)->where('TYPE_STATUS','=',9)->first();
    $data_emppostion = EMPPOSTION::select('POSITION_NAME')->selectraw('dbo.decode_utf8(POSITION_NAME) as POSITION_NAME')
                                  ->where('POSITION_CODE','=',$dataset->POSITION_CODE)->where('POSITION_STATUS','=',9)->first();
    $datalineselect = MachineLine::where('LINE_NAME','like','%'.'Line'.'%')->get();
    return view('machine/personal/edit',compact('dataset','datalineselect','data_emppaytype','data_emppostion'));


  }
  public function Update(Request $request,$UNID){
    $validated = $request->validate([
      'EMP_CODE' => 'required',
      'EMP_ICON' => 'mimes:jpeg,png,jpg',

      ],
      [
      'EMP_ICON.mimes'   => '??????????????????????????? jpeg, png, jpg',
      'EMP_CODE.required' => '?????????????????????????????????????????????????????????'
      ]);

      $DATA_EMPNAME = EMPName::where('UNID',$UNID)->first();
      $last_img = $DATA_EMPNAME->EMP_ICON;
        if ($request->hasFile('EMP_ICON')) {
              $image = $request->file('EMP_ICON');
              $new_name = rand() . '.' . $image->getClientOriginalExtension();
              $this->saveimg($image,$new_name);
                $last_img = $new_name;
              }

      $ENCODE = EMPName::selectRaw("dbo.encode_utf8('$request->EMP_NAME') as EMP_NAME")->first();
      $EMP_NAME = $ENCODE->EMP_NAME;
       EMPName::where('UNID',$UNID)->update([
        'EMP_CODE'         => $request->EMP_CODE,
        'EMP_NAME'         => $EMP_NAME,
        'EMP_ICON'         => $last_img,
        'EMP_LINE'         => $request->EMP_LINE,
        'EMP_NOTE'         => $request->EMP_NOTE,
        'POSITION'             => '',
        'EMP_STATUS'           => $request->EMP_STATUS,
        'MODIFY_BY'            => Auth::user()->name,
        'MODIFY_TIME'          => Carbon::now(),
      ]);

  alert()->success('??????????????????????????????????????????????????????')->autoclose('1500');
  return Redirect()->back();
  }

  public function Delete($UNID){
      $DATA_EMPNAME = EMPName::where('UNID','=',$UNID)->first();

      $DATA_EMPNAME->delete();
      alert()->success('??????????????????????????????????????????')->autoclose('1500');
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
