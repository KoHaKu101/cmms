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
use App\Exports\MachineExport;

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
    $datamail = MailSetup::get();
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

    if (MailSetup::count() == 0) {
      MailSetup::insert([
        'UNID'            => $this->randUNID('PMCS_CMMS_SETUP_MAIL_ALERT'),
        'MAILHOST'        =>  $request->MAILHOST,
        'EMAILADDRESS'    =>  $request->EMAILADDRESS,
        'MAILPASSWORD'    =>  $request->MAILPASSWORD,
        'MAILPORT'        =>  $request->MAILPORT,
        'MAILPROTOCOL'    =>  $request->MAILPROTOCOL,
        'AUTOPLAN'        =>  24,
        'CREATE_BY'       => Auth::user()->name,
        'CREATE_TIME'     => Carbon::now(),
      ]);
      return Redirect()->back()->with('success','บันทึกข้อมูลสำเร็จ');
    }elseif (MailSetup::count() == 1) {

      MailSetup::where('UNID',$request->UNID)->Update([
        'MAILHOST'        =>  $request->MAILHOST,
        'EMAILADDRESS'    =>  $request->EMAILADDRESS,
        'MAILPASSWORD'    =>  $request->MAILPASSWORD,
        'MAILPORT'        =>  $request->MAILPORT,
        'MAILPROTOCOL'    =>  $request->MAILPROTOCOL,

        'MODIFY_BY'       => Auth::user()->name,
        'MODIFY_TIME'     => Carbon::now(),
      ]);
      return Redirect()->back()->with('success','บันทึกข้อมูลสำเร็จ');
    }

  }
  public function SaveAlert(Request $request){


    $validated = $request->validate([
      'EMAILADDRESS1'    => 'email',
      'EMAILADDRESS2'    => 'email',
      'EMAILADDRESS3'    => 'email',
      'EMAILADDRESS4'    => 'email',
      'EMAILADDRESS5'    => 'email',
      ],
      [
        'EMAILADDRESS1'     => 'กรุณากรอกช่อง MAILHOST',
        'EMAILADDRESS2'     => 'กรุณากรอกช่อง EMAILADDRESS',
        'EMAILADDRESS3'     => 'กรุณากรอกช่อง MAILPASSWORD',
        'EMAILADDRESS4'     => 'กรุณากรอกตามตัวอย่าง 25, 586 ,456',
        'EMAILADDRESS5'     => 'กรุณากรอกช่อง MAILPROTOCOL',
    ]);
    if (MailAlert::count() == 0) {
      MailAlert::insert([
        'UNID'            => $this->randUNID('PMCS_CMMS_SETUP_MAIL_ALERT'),
        'EMAILADDRESS1'   => $request->MAILALEAT1,
        'EMAILADDRESS2'   => $request->MAILALEAT2,
        'EMAILADDRESS3'   => $request->MAILALEAT3,
        'EMAILADDRESS4'   => $request->MAILALEAT4,
        'EMAILADDRESS5'   => $request->MAILALEAT5,
        'CREATE_BY'       => Auth::user()->name,
        'CREATE_TIME'     => Carbon::now(),
      ]);
    }elseif (MailSetup::count() == 1) {
      MailAlert::where('UNID')->update([
        'EMAILADDRESS1'   => $request->MAILALEAT1,
        'EMAILADDRESS2'   => $request->MAILALEAT2,
        'EMAILADDRESS3'   => $request->MAILALEAT3,
        'EMAILADDRESS4'   => $request->MAILALEAT4,
        'EMAILADDRESS5'   => $request->MAILALEAT5,
        'MODIFY_BY'       => Auth::user()->name,
        'MODIFY_TIME'     => Carbon::now(),
      ]);
    }
    return Redirect()->back()->with('success','บันทึกข้อมูลสำเร็จ');
  }

  public function Update(Request $request){

      if (MailSetup::count() == 0) {
        MailSetup::Insert([
          'AUTOMAIL'        =>  $request->AUTOMAIL,
          'AUTOPLAN'        =>  $request->AUTOPLAN,
            ]);
            return Redirect()->back()->with('success','บันทึกข้อมูลสำเร็จ');
          }elseif (MailSetup::count() == 1) {

            MailSetup::where('UNID',$request->UNID)->Update([
              'AUTOMAIL'        =>  $request->AUTOMAIL,
              'AUTOPLAN'        =>  $request->AUTOPLAN,
              'MODIFY_BY'       => Auth::user()->name,
              'MODIFY_TIME'     => Carbon::now(),
              ]);
              return Redirect()->back()->with('success','บันทึกข้อมูลสำเร็จ');
            }
  }
  public function Delete(){

    return View('machine/setting/config/home');
  }





}
