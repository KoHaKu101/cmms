<?php

namespace App\Http\Controllers\Machine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
use Response;
use File;
//******************** model ***********************
use App\Models\Machine\Machine;
use App\Models\Machine\MachineLine;
use App\Models\Machine\MachineUpload;
//************** Package form github ***************
use RealRashid\SweetAlert\Facades\Alert;



class MachineManualController extends Controller
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
    $LINE = MachineLine::where('LINE_STATUS','=','9')->where('LINE_NAME','like','Line'.'%')->orderBy('LINE_NAME')->get();
    $MACHINE_LINE = isset($request->MACHINE_LINE) ? $request->MACHINE_LINE : '';
    $SEARCH = isset($request->SEARCH) ? $request->SEARCH : '';
    $dataset = Machine::select('*')->selectRaw('dbo.decode_utf8(MACHINE_NAME) as MACHINE_NAME_TH')
                      ->where(function ($query) use ($MACHINE_LINE) {
                             if ($MACHINE_LINE != '') {
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
                      ->where('MACHINE_TYPE_STATUS','=','9')
                      ->orderBy('MACHINE_LINE','ASC')
                      ->orderBy('MACHINE_CODE')->paginate(10);
        $data_upload = MachineUpload::select('UPLOAD_UNID_REF')->get();
    return View('machine/manual/manuallist',compact('dataset','MACHINE_LINE','SEARCH','LINE','data_upload'));
  }

  public function Show($UNID) {
    $dataset = Machine::where('UNID','=',$UNID)->first();
    $dataupload = MachineUpload::where('UPLOAD_UNID_REF','=',$UNID)->get();
    return view('machine/manual/show',compact('dataset','dataupload'));

  }
  public function StoreUpload(Request $request){
    $validated = $request->validate([
      'FILE_UPLOAD' => 'mimes:pdf',
      ],
      [
      'FILE_UPLOAD.mimes'   => 'เฉพาะไฟล์ jpeg, png, jpg',
      ]);
    $TOPIC_NAME = $request->TOPIC_NAME;
    $DATA_MACHINE = Machine::where('UNID','=',$request->MACHINE_UNID)->first();
    $MACHINE_CODE = $DATA_MACHINE->MACHINE_CODE;
    $UPLOAD_UNID_REF = $DATA_MACHINE->UNID;

    $FILE_UPLOADDATETIME = Carbon::now()->format('Y-m-d');

    $FILE_UPLOAD = request()->file('FILE_UPLOAD');

     $FILE_NAME = basename($FILE_UPLOAD->getClientOriginalName());
     $filenamemaster = uniqid().$FILE_NAME;
     $FILE_EXTENSION = $FILE_UPLOAD->getClientOriginalExtension();

     $FILE_SIZE = 0;
     $FILE_SIZE = $FILE_UPLOAD->getSize();
     if ($FILE_SIZE >0 ) {
       $FILE_SIZE = number_format($FILE_SIZE /100000, 2);
     }

       $path_file = public_path('upload/manual/'.$MACHINE_CODE);
       $FILE_UPLOAD->move($path_file,$filenamemaster);

    if(!empty($TOPIC_NAME)) {
        $TOPIC_NAME = $TOPIC_NAME;
    } else {
        $TOPIC_NAME = $FILE_NAME;
    }
    //สิ้นสุดชื่อ
    MachineUpload::insert([
      'UPLOAD_UNID_REF'      => $UPLOAD_UNID_REF,
      'MACHINE_CODE'         => $MACHINE_CODE,
      'TOPIC_NAME'           => $TOPIC_NAME,
      'FILE_UPLOAD'          => $filenamemaster,
      'FILE_SIZE'            => $FILE_SIZE,
      'FILE_NAME'           => $FILE_NAME,
      'FILE_EXTENSION'      => $FILE_EXTENSION,
      'FILE_UPLOADDATETIME'    => $FILE_UPLOADDATETIME,
      'CREATE_BY'            => Auth::user()->name,
      'CREATE_TIME'          => Carbon::now(),
      'MODIFY_BY'            => Auth::user()->name,
      'MODIFY_TIME'          => Carbon::now(),
      'UNID'                 => $this->randUNID('PMCS_MACHINES_UPLOAD'),
    ]);
    return Redirect()->back();
  }
  public function Update(Request $request){
    $validated = $request->validate([
      'FILE_UPLOAD' => 'mimes:pdf',
      ],
      [
      'FILE_UPLOAD.mimes'   => 'เฉพาะไฟล์ jpeg, png, jpg',
      ]);
      $UNID = $request->UPLOAD_MANUAL_UNID;
      $DATA_UPLOAD = MachineUpload::where('UNID','=',$UNID)->first();
      $TOPIC_NAME = $request->TOPIC_NAME;
      $MACHINE_CODE = $DATA_UPLOAD->MACHINE_CODE;
    if ($request->hasFile('FILE_UPLOAD')) {
         $path_delete = public_path('upload/manual/'.$MACHINE_CODE.'/'.$DATA_UPLOAD->FILE_UPLOAD);
         File::delete($path_delete);

         $FILE_UPLOADDATETIME = Carbon::now()->format('Y-m-d');

         $FILE_UPLOAD = $request->file('FILE_UPLOAD');
         $FILE_NAME = basename($FILE_UPLOAD->getClientOriginalName());
         $FILE_EXTENSION = $FILE_UPLOAD->getClientOriginalExtension();
         $filenamemaster = uniqid().$FILE_NAME;

         $FILE_SIZE = 0;
         $FILE_SIZE = $FILE_UPLOAD->getSize();
         if ($FILE_SIZE > 0 ) {
           $FILE_SIZE = number_format($FILE_SIZE /100000, 2);
         }

         $path = public_path('upload/manual/'.$MACHINE_CODE);
         $FILE_UPLOAD->move($path,$filenamemaster);
       }else {
        $filenamemaster  = $DATA_UPLOAD->FILE_UPLOAD;
        $FILE_SIZE    = $DATA_UPLOAD->FILE_SIZE;
        $FILE_NAME     =  $DATA_UPLOAD->FILE_NAME;
        $FILE_EXTENSION =  $DATA_UPLOAD->FILE_EXTENSION;
        $FILE_UPLOADDATETIME =  $DATA_UPLOAD->FILE_UPLOADDATETIME;
      }
    MachineUpload::where('UNID',$UNID)->update([
      'TOPIC_NAME'         => $TOPIC_NAME,
      'FILE_UPLOAD'          => $filenamemaster,
      'FILE_SIZE'          => $FILE_SIZE,
      'FILE_NAME'          => $FILE_NAME,
      'FILE_EXTENSION'      => $FILE_EXTENSION,
      'FILE_UPLOADDATETIME'    => $FILE_UPLOADDATETIME,
      'MODIFY_BY'            => Auth::user()->name,
      'MODIFY_TIME'          => Carbon::now(),
    ]);
    alert()->success('อัพเดทรายการสำเร็จ')->autoclose('1500');
    return Redirect()->back();
  }
  public function Delete($UNID){
      $data_set = MachineUpload::where('UNID','=',$UNID)->first();
      $path = public_path('upload/manual/'.$data_set->MACHINE_CODE.'/'.$data_set->FILE_UPLOAD);
      File::delete($path);
      MachineUpload::where('UNID','=',$UNID)->delete();
      alert()->success('ลบรายการสำเร็จ')->autoclose('1500');
      return Redirect()->back();
  }
  public static function Download($UNID){

      $dataset = MachineUpload::find($UNID);
      $FILE_UPLOAD = $dataset->FILE_UPLOAD;
      $path = public_path('upload/manual/'.$dataset->MACHINE_CODE);
      $headers = array(
             'Content-Type: application/pdf',
           );

       return Response::download($path.'/'.$FILE_UPLOAD,$FILE_UPLOAD,$headers);

  }
  public function View($UNID){
      $dataupload = MachineUpload::where('UNID',$UNID)->first();
      $path = public_path('upload/manual/'.($dataupload->MACHINE_CODE).'/'.$dataupload->FILE_UPLOAD);
      $filename = $dataupload->FILE_UPLOAD;
      header('Content-type: application/pdf');
      header('Content-Disposition: inline; filename="' . $filename . '"');
      header('Content-Transfer-Encoding: binary');
      header('Accept-Ranges: bytes');
      echo file_get_contents($path);
  }
}
