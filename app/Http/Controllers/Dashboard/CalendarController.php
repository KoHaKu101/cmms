<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Machine\Machine;
use App\Models\Machine\MasterIMPSGroup;
use App\Models\SettingMenu\MailSetup;




class CalendarController extends Controller
{
  public function __construct(){
    $this->middleware('auth');
  }

  public function Index(){
    $notify              = MailSetup::select('AUTOPLAN')->first();
    $datamasterimpsgroup = NULL;
    if (isset($notify)) {
      $dataset             = Carbon::parse()->startOfYear()->addDay($notify->AUTOPLAN);
      $datamasterimpsgroup = MasterIMPSGroup::where('PM_NEXT_DATE','<',$dataset)->get();
    }

    $datamachine         = Machine::select('UNID','MACHINE_CODE')->get();
    return View('machine/celendar/celendar',compact('datamasterimpsgroup','datamachine'));
  }

}
