<?php

namespace App\Http\Controllers\Machine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Auth;
//******************** model ***********************
use App\Models\SettingMenu\MailAlert;
use App\Models\SettingMenu\MailSetup;
//************** Package form github ***************
use RealRashid\SweetAlert\Facades\Alert;

class MailConfigController extends Controller
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
    $datamail      = MailSetup::get();
    $dataalertmail = MailAlert::get();
    return View('machine/setting/config/home',compact('datamail','dataalertmail'));
  }
  public function Save(Request $request){
    $validated = $request->validate([
      'MAILHOST'        => 'required',
      'EMAILADDRESS'    => 'required|email',
      'MAILPASSWORD'    => 'required|min:4|regex:/[0-9]/|regex:/[a-z]/',
      'MAILPORT'        => 'required|numeric',
      'MAILPROTOCOL'    => 'required',
      ],
      [
        'MAILHOST'         => 'กรุณากรอกช่อง MAILHOST',
        'EMAILADDRESS'     => 'กรุณากรอกช่อง EMAILADDRESS',
        'MAILPASSWORD.min'     => 'กรุณาใส่รหัสผ่าน อย่างหน่อย 4 ตัว',
        'MAILPASSWORD.regex'     => 'กรุณาใส่รหัสผ่าน อย่างหน่อย 4 ตัว ประกอบด้วยตัวอักษรและตัวเลข',
        'MAILPORT.numeric' => 'กรุณากรอกตามตัวอย่าง 25, 586 ,456',
        'MAILPROTOCOL'     => 'กรุณากรอกช่อง MAILPROTOCOL',
    ]);
    $MAILHOST      =  $request->MAILHOST;
    $EMAILADDRESS  =  $request->EMAILADDRESS;
    $MAILPASSWORD  =  $request->MAILPASSWORD;
    $MAILPORT      =  $request->MAILPORT;
    $MAILPROTOCOL  =  $request->MAILPROTOCOL;
    if (MailSetup::count() == 0) {
      MailSetup::insert([
        'UNID'            =>  $this->randUNID('PMCS_CMMS_SETUP_MAIL_ALERT'),
        'MAILHOST'        =>  $MAILHOST,
        'EMAILADDRESS'    =>  $EMAILADDRESS,
        'MAILPASSWORD'    =>  $MAILPASSWORD,
        'MAILPORT'        =>  $MAILPORT,
        'MAILPROTOCOL'    =>  $MAILPROTOCOL,
        'AUTOPLAN'        =>  24,
        'CREATE_BY'       =>  Auth::user()->name,
        'CREATE_TIME'     =>  Carbon::now(),
      ]);
      alert()->success('บันทึกข้อมูลสำเร็จ')->autoclose('1500');
      return Redirect()->back();
    }elseif (MailSetup::count() == 1) {
      MailSetup::where('UNID',$request->UNID)->Update([
        'MAILHOST'        =>  $MAILHOST,
        'EMAILADDRESS'    =>  $EMAILADDRESS,
        'MAILPASSWORD'    =>  $MAILPASSWORD,
        'MAILPORT'        =>  $MAILPORT,
        'MAILPROTOCOL'    =>  $MAILPROTOCOL,
        'MODIFY_BY'       =>  Auth::user()->name,
        'MODIFY_TIME'     =>  Carbon::now(),
      ]);
      alert()->success('บันทึกข้อมูลสำเร็จ')->autoclose('1500');
      return Redirect()->back();
    }
  }
  public function SaveAlert(Request $request){
    $validated = $request->validate([
      'EMAILADDRESS1'  => 'email',
      'EMAILADDRESS2'  => 'email',
      'EMAILADDRESS3'  => 'email',
      'EMAILADDRESS4'  => 'email',
      'EMAILADDRESS5'  => 'email',
      ],
      [
      'EMAILADDRESS1.email'  => 'กรุณากรอกเป็น Email',
      'EMAILADDRESS2.email'  => 'กรุณากรอกเป็น Email',
      'EMAILADDRESS3.email'  => 'กรุณากรอกเป็น Email',
      'EMAILADDRESS4.email'  => 'กรุณากรอกเป็น Email',
      'EMAILADDRESS5.email'  => 'กรุณากรอกเป็น Email',
    ]);
    $MAILALEAT1 = $request->MAILALEAT1;
    $MAILALEAT2 = $request->MAILALEAT2;
    $MAILALEAT3 = $request->MAILALEAT3;
    $MAILALEAT4 = $request->MAILALEAT4;
    $MAILALEAT5 = $request->MAILALEAT5;
    if (MailAlert::count() == 0) {
      MailAlert::insert([
        'UNID'            => $this->randUNID('PMCS_CMMS_SETUP_MAIL_ALERT'),
        'EMAILADDRESS1'   => $MAILALEAT1,
        'EMAILADDRESS2'   => $MAILALEAT2,
        'EMAILADDRESS3'   => $MAILALEAT3,
        'EMAILADDRESS4'   => $MAILALEAT4,
        'EMAILADDRESS5'   => $MAILALEAT5,
        'CREATE_BY'       => Auth::user()->name,
        'CREATE_TIME'     => Carbon::now(),
      ]);
    }elseif (MailSetup::count() == 1) {
      MailAlert::where('UNID')->update([
        'EMAILADDRESS1'   => $MAILALEAT1,
        'EMAILADDRESS2'   => $MAILALEAT2,
        'EMAILADDRESS3'   => $MAILALEAT3,
        'EMAILADDRESS4'   => $MAILALEAT4,
        'EMAILADDRESS5'   => $MAILALEAT5,
        'MODIFY_BY'       => Auth::user()->name,
        'MODIFY_TIME'     => Carbon::now(),
      ]);
    }
    alert()->success('บันทึกข้อมูลสำเร็จ')->autoclose('1500');
    return Redirect()->back();
  }
  public function Update(Request $request){
    $AUTOMAIL = $request->AUTOMAIL;
    $AUTOPLAN = $request->AUTOPLAN;
    if (MailSetup::count() == 0) {
      MailSetup::Insert([
        'AUTOMAIL'     => $AUTOMAIL,
        'AUTOPLAN'     => $AUTOPLAN,
          ]);
    }elseif (MailSetup::count() == 1) {
      MailSetup::where('UNID',$request->UNID)->Update([
        'AUTOMAIL'     => $AUTOMAIL,
        'AUTOPLAN'     => $AUTOPLAN,
        'MODIFY_BY'    => Auth::user()->name,
        'MODIFY_TIME'  => Carbon::now(),
        ]);
    }
    alert()->success('บันทึกข้อมูลสำเร็จ')->autoclose('1500');
    return Redirect()->back();
  }

}
