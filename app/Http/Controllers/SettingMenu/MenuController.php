<?php

namespace App\Http\Controllers\SettingMenu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SettingMenu\Mainmenu;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Auth;


class MenuController extends Controller
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
        'MENU_NAME'           => 'required|max:255'],[
        'MENU_NAME.required'  => 'กรุณราใส่ชื่อ menu',
        'MENU_NAME.max'       => 'ใส่ได้ไม่เกิน 255'
        ]);
        Mainmenu::insert([
            'MENU_NAME'   => $request->MENU_NAME,
            'MENU_EN'     => $request->MENU_EN,
            'MENU_INDEX'  =>  $request->MENU_INDEX,
            'MENU_STATUS' => $request->MENU_STATUS,
            'MENU_ICON'   => $request->MENU_ICON,
            'MENU_CLASS'  => $request->MENU_CLASS,
            'MENU_LINK'   => $request->MENU_LINK,
            'UNID'        => $this->randUNID('PMCS_CMMS_MENU'),
            'CREATE_BY'   => Auth::user()->name,
            'CREATE_TIME' => Carbon::now(),
            'MODIFY_BY'   => Auth::user()->name,
            'MODIFY_TIME' => Carbon::now(),

        ]);
        alert()->success('insert success')->autoclose('1500');
          return Redirect()->back();
    }
    public function Home()
    {
      $data = Mainmenu::paginate(6);
      return View('machine.setting.menu.home',compact('data'));
    }
    public function Edit($UNID){
      $data = Mainmenu::where("UNID",$UNID)->first();
      return view('machine/setting/menu/edit',compact('data'));
    }
    public function Update(Request $request,$UNID){
      $dataunid = Mainmenu::find($UNID)->update([
        'MENU_NAME'   => $request->MENU_NAME,
        'MENU_EN'     => $request->MENU_EN,
        'MENU_INDEX'  =>    $request->MENU_INDEX,
        'MENU_STATUS' => $request->MENU_STATUS,
        'MENU_ICON'   => $request->MENU_ICON,
        'MENU_CLASS'  => $request->MENU_CLASS,
        'MENU_LINK'   => $request->MENU_LINK,

        'MODIFY_BY'   => Auth::user()->name,
        'MODIFY_TIME' => Carbon::now(),

      ]);
      alert()->success('Update Success')->autoclose('1500');
      return Redirect()->back();

    }


      public function Delete($UNID){
          $delete = Mainmenu::where('UNID','=',$UNID)->delete();
          alert()->success('Confirm Delete Success')->autoclose('1500');
          return Redirect()->back();
      }


      public function Logout(){
          Auth::logout();
          return Redirect()->route('login')->with('success','User Logout');
      }
}
