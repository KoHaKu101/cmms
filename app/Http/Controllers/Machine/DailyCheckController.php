<?php

namespace App\Http\Controllers\Machine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
use File;
use Cookie;
//******************** model ***********************
use App\Models\Machine\Machine;
use App\Models\Machine\MachineCheckSheet;
use App\Models\Machine\Uploadimg;

//************** Package form github ***************
use RealRashid\SweetAlert\Facades\Alert;


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

  public function DailyList(Request $request){

    $COOKIE_PAGE_TYPE     = $request->cookie('PAGE_TYPE');
    if ($COOKIE_PAGE_TYPE != 'DAILYCHECK') {
      $COOKIE_PAGE_TYPE   = $request->cookie();
      foreach ($COOKIE_PAGE_TYPE as $index => $row) {
        if ($index == 'XSRF-TOKEN' || str_contains($index,'session') == true) {
        }else {
          Cookie::queue(Cookie::forget($index));
        }
      }
    }
     $MACHINE_LINE = $request->MACHINE_LINE     != '' ? $request->MACHINE_LINE    : ($request->cookie('MACHINE_LINE') != '' ? $request->cookie('MACHINE_LINE') : 0) ;
     $MACHINE_CODE = $request->SEARCH_MACHINE   != '' ? $request->SEARCH_MACHINE  : '';
     $YEAR         = $request->YEAR             != '' ? $request->YEAR            : ($request->cookie('YEAR')         != '' ? $request->cookie('YEAR')         : date('Y')) ;
     $MONTH        = $request->MONTH            != '' ? $request->MONTH           : ($request->cookie('MONTH')        != '' ? $request->cookie('MONTH')        : date('n')) ;
     $MINUTES      = 30;
     Cookie::queue('PAGE_TYPE','DAILYCHECK',$MINUTES);
     Cookie::queue('MACHINE_LINE' ,$MACHINE_LINE  ,$MINUTES);
     Cookie::queue('YEAR'         ,$YEAR          ,$MINUTES);
     Cookie::queue('MONTH'        ,$MONTH         ,$MINUTES);

     $DATA_MACHINE = Machine::select('UNID','MACHINE_CODE','MACHINE_LINE')->selectRaw('dbo.decode_utf8(MACHINE_NAME) as MACHINE_NAME_V2')
                                         ->where(function($query) use ($MACHINE_CODE){
                                           if ($MACHINE_CODE != '') {
                                             $query->where('MACHINE_CODE','like','%'.$MACHINE_CODE.'%');
                                           }
                                         })
                                         ->where(function($query) use ($MACHINE_LINE){
                                           if ($MACHINE_LINE > 0) {
                                             $query->where('MACHINE_LINE','=',$MACHINE_LINE);
                                           }
                                          })
                                         ->where('MACHINE_STATUS','=','9')
                                         ->orderBy('MACHINE_CODE')
                                         ->paginate(10);

    $DATA_CHECKSHEET = MachineCheckSheet::where('CHECK_MONTH','=',$MONTH)
                                          ->where('CHECK_YEAR','=',$YEAR)
                                          ->get();

      return view('machine.dailycheck.dailylist',compact('DATA_MACHINE','DATA_CHECKSHEET','MONTH','YEAR','MACHINE_LINE','MACHINE_CODE'));
  }

  public function CheckSheetUpload(Request $request) {
    $validated = $request->validate([
      'FILE_NAME'         => 'mimes:pdf',
      ],
      [
      'FILE_NAME.mimes'   => '??????????????????????????? pdf',
      ]);

    $MACHINE_UNID  = $request->MACHINE_UNID;
    $MACHINE_CODE  = $request->MACHINE_CODE;
    $CHECK_MONTH   = $request->CHECK_MONTH;
    $CHECK_YEAR    = $request->CHECK_YEAR;
    $MACHINE_LINE  = $request->MACHINE_LINE;
    $count_machine = MachineCheckSheet::where('MACHINE_UNID','=',$MACHINE_UNID)
                      ->where('CHECK_MONTH','=',$CHECK_MONTH)
                      ->where('CHECK_YEAR','=',$CHECK_YEAR)
                      ->count();
    if ($count_machine > 0) {
      $DATA_CHECKSHEET = MachineCheckSheet::where('MACHINE_UNID','=',$MACHINE_UNID)
                        ->where('CHECK_MONTH','=',$CHECK_MONTH)
                        ->where('CHECK_YEAR','=',$CHECK_YEAR);
      $pathfile = public_path('file/checksheet/'.$CHECK_YEAR.'/'.$CHECK_MONTH.'/'.$DATA_CHECKSHEET->first()->FILE_NAME);
        File::delete($pathfile);
          $DATA_CHECKSHEET->delete();
      }
      $FILE_UPLOAD = request()->file('FILE_NAME');
       $FILE_NAME = rand() . '.' . $FILE_UPLOAD->getClientOriginalExtension();
       $FILE_EXTENSION = $FILE_UPLOAD->getClientOriginalExtension();
       $path = public_path('file/checksheet/'.$CHECK_YEAR.'/'.$CHECK_MONTH.'/');
       if(!File::isDirectory($path)){
         File::makeDirectory($path, 0777, true, true);
        }
      $checkimg_saved = $FILE_UPLOAD->move($path,$FILE_NAME);

    if ($checkimg_saved) {
      MachineCheckSheet::insert([
        'UNID'         => $this->randUNID('PMCS_CMMS_MACHINE_CHECKSHEET'),
        'MACHINE_UNID' => $MACHINE_UNID,
        'MACHINE_CODE' => $MACHINE_CODE,
        'FILE_NAME'    => $FILE_NAME,
        'FILE_EXT'     => $FILE_EXTENSION,
        'CHECK_YEAR'   => $CHECK_YEAR,
        'CHECK_MONTH'  => $CHECK_MONTH,
        'CREATE_BY'    => Auth::user()->name,
        'CREATE_TIME'  => Carbon::now()
      ]);
      alert()->success('???????????????????????????????????????????????????')->autoClose(1500);
    }else {
      alert()->error('??????????????????????????????????????????????????????????????????????????????????????????')->autoclose(2500);
    }
      return Redirect()->back();
  }

  public function DeleteImg(Request $request ){

    $UNID = $request->UNID;
    $DATA_CHECKSHEET = MachineCheckSheet::Where('UNID','=',$UNID)->count();

    if ($DATA_CHECKSHEET > 0) {
      $DATA_CHECKSHEET = MachineCheckSheet::Where('UNID','=',$UNID)->first();
      $CHECK_YEAR   = $DATA_CHECKSHEET->CHECK_YEAR;
      $CHECK_MONTH  = $DATA_CHECKSHEET->CHECK_MONTH;
      $FILE_NAME    = $DATA_CHECKSHEET->FILE_NAME;
      $pathfile     = public_path('file/checksheet/'.$CHECK_YEAR.'/'.$CHECK_MONTH.'/'.$FILE_NAME);
      if (File::delete($pathfile) == true) {
        File::delete($pathfile);
        MachineCheckSheet::where('UNID',$UNID)->delete();
        $response_array = 'pass';
        return Response()->json(['status' => 'pass']);
      }
        return Response()->json(['status' => 'fail']);
    }
      return Response()->json(['status' => 'fail']);

  }

  public function View($UNID){
      $MachineCheckSheet = MachineCheckSheet::where('UNID',$UNID)->first();
      $path              = public_path('file/checksheet/'.$MachineCheckSheet->CHECK_YEAR.'/'.$MachineCheckSheet->CHECK_MONTH.'/'.$MachineCheckSheet->FILE_NAME);
      $filename          = $MachineCheckSheet->FILE_NAME;
      header('Content-type: application/pdf');
      header('Content-Disposition: inline; filename="' . $filename . '"');
      header('Content-Transfer-Encoding: binary');
      header('Accept-Ranges: bytes');
      echo file_get_contents($path);
  }


  }
