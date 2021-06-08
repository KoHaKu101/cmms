<?php

namespace App\Http\Controllers\SettingMenu;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\SettingMenu\Menusubitem;
use Carbon\Carbon;
use Auth;

class MenuSubController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
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

  public function AddMenu(Request $request)
  {
    $validated = $request->validate([
      'SUBMENU_NAME' => 'required|max:255'],[
      'SUBMENU_NAME.required' => 'กรุณราใส่ชื่อ menu',
      'SUBMENU_NAME.max' => 'ใส่ได้ไม่เกิน 255'
      ]);
      Menusubitem::insert([
          'SUBMENU_NAME'=> $request->SUBMENU_NAME,
          'SUBMENU_EN'=> $request->SUBMENU_EN,
          'SUBUNID_REF'=> $request->SUBUNID_REF,
          'SUBMENU_INDEX' =>  $request->SUBMENU_INDEX,
          'SUBMENU_STATUS' => $request->SUBMENU_STATUS,
          'SUBMENU_ICON' => $request->SUBMENU_ICON,
          'SUBMENU_CLASS' => $request->SUBMENU_CLASS,
          'SUBMENU_LINK' => $request->SUBMENU_LINK,
          'UNID'=>$this->randUNID('PMCS_CMMS_MENUSUBITEM'),
          'CREATE_BY'=>Auth::user()->name,
          'CREATE_TIME'=> Carbon::now(),
          'MODIFY_BY' =>Auth::user()->name,
          'MODIFY_TIME' => Carbon::now(),
      ]);
      alert()->success('insert success')->autoclose('1500');
    return Redirect()->back();


  }


  public function Subhome($UNID){
      $mainmenu=array();
      $mainmenu["UNID"]=$UNID;
    $datasubmenu = Menusubitem::where('SUBUNID_REF','=',$UNID)->orderby('SUBMENU_INDEX','ASC')->get();
  return View('machine.setting.submenu.home',compact('datasubmenu','mainmenu'));
}
  public function Edit($UNID){
    $data_set = Menusubitem::find($UNID);
    return view('machine.setting.submenu.edit',compact('data_set'));

  }

  public function Update(Request $request,$UNID){


    $datasubunid = Menusubitem::find($UNID)->update([
      'SUBMENU_NAME'  => $request->SUBMENU_NAME,
      'SUBMENU_EN'    => $request->SUBMENU_EN,
      'SUBMENU_INDEX' =>  $request->SUBMENU_INDEX,
      'SUBMENU_STATUS' => $request->SUBMENU_STATUS,
      'SUBMENU_ICON' => $request->SUBMENU_ICON,
      'SUBMENU_CLASS' => $request->SUBMENU_CLASS,
      'SUBMENU_LINK' => $request->SUBMENU_LINK,
      'MODIFY_BY' =>Auth::user()->name,
      'MODIFY_TIME' => Carbon::now(),
    ]);
    $SUBUNID_REF=$request->SUBUNID_REF;
    $datasubmenu = Menusubitem::where('SUBUNID_REF','=',$SUBUNID_REF)->get();
    return Redirect()->route('submenu.home',[$SUBUNID_REF]);

  }
  // ส่วนลบ
    public function Delete($UNID){
      $delete = Menusubitem::where('UNID','=',$UNID)->delete();
      alert()->success('Confirm Delete Success')->autoclose('1500');
      return Redirect()->back();


    }
    // ส่วนลบ

}
