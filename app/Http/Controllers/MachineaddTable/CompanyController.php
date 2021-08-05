<?php

namespace App\Http\Controllers\MachineaddTable;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;

//******************** model ***********************
use App\Models\Machine\Company;
//************** Package form github ***************
use RealRashid\SweetAlert\Facades\Alert;



class CompanyController extends Controller
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

  public function List(Request $request){
    $DATA_COMPANY = Company::paginate(15);
    return View('machine.add.company.list',compact('DATA_COMPANY'));
  }
  public function Save(Request $request){
    $STATUS = $request->STATUS != '' ? 9 : 1;
    Company::insert([
      'UNID'            => $this->randUNID('PMCS_CMMS_COMPANY')
      ,'COMPANY_CODE'   => $request->COMPANY_CODE
      ,'COMPANY_NAME'   => $request->COMPANY_NAME
      ,'NOTE'           => $request->NOTE
      ,'STATUS'         => $STATUS
      ,'CREATE_BY'      => Auth::user()->name
      ,'CREATE_TIME'    => Carbon::now()
      ,'MODIFY_BY'      => Auth::user()->name
      ,'MODIFY_TIME'    => Carbon::now()
    ]);
    alert()->success('เพิ่มรายการสำเร็จ')->autoclose(1500);
    return Redirect()->back();
  }
  public function Update(Request $request){
    $STATUS = $request->STATUS != '' ? 9 : 1;
    Company::where('UNID','=',$request->UNID)->update([
      'COMPANY_CODE'   => $request->COMPANY_CODE
      ,'COMPANY_NAME'   => $request->COMPANY_NAME
      ,'NOTE'           => $request->NOTE
      ,'STATUS'         => $STATUS
      ,'MODIFY_BY'      => Auth::user()->name
      ,'MODIFY_TIME'    => Carbon::now()
    ]);
    alert()->success('อัพเดทรายการสำเร็จ')->autoclose(1500);
    return Redirect()->back();
  }
  public function Delete(Request $request){
    Company::where('UNID','=',$request->UNID)->delete();
    alert()->success('ลบรายการสำเร็จ')->autoclose(1500);
    return Redirect()->back();

  }
  public function SwitchStatus(Request $request){
    $COMPANY = Company::where('UNID','=',$request->UNID)->first();
    if ($COMPANY->STATUS == 9) {
      Company::where('UNID','=',$request->UNID)->update([
        'STATUS'         => 1
      ]);
      return Response()->json(['result' => 'true']);
    }elseif ($COMPANY->STATUS == 1) {
      Company::where('UNID','=',$request->UNID)->update([
        'STATUS'         => 9
      ]);
      return Response()->json(['result' => 'true']);
    }else {
      return Response()->json(['result' => 'false']);
    }
  }

}
