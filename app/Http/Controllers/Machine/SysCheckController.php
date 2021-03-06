<?php

namespace App\Http\Controllers\Machine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
//******************** model ***********************
use App\Models\Machine\Machine;
use App\Models\Machine\MasterIMPS;
use App\Models\Machine\MachinePlanPm;
use App\Models\Machine\MasterIMPSGroup;
use App\Models\SettingMenu\MailSetup;

use App\Models\MachineAddTable\MachinePmTemplateList;
use App\Models\MachineAddTable\MachinePmTemplate;
//***************** Controller ************************
use App\Http\Controllers\Plan\MachinePlanController;

//************** Package form github ***************
use RealRashid\SweetAlert\Facades\Alert;



class SysCheckController extends Controller
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

  //อยู่ใน machine edit
  public function StoreList(Request $request){
    $totalmonth         = MailSetup::select('AUTOPLAN')->first();

    if (!isset($totalmonth->AUTOPLAN)) {
        alert()->error('กรุณาระบุระยะเวลา แผน','ใน ตั้งค่า -> CMMS');
        return redirect()->back();
    }
    $machine = Machine::select('MACHINE_RANK_MONTH','MACHINE_RANK_CODE','UNID')->where('MACHINE_CODE',$request->MACHINE_CODE)->first();
    if ($machine->MACHINE_RANK_CODE) {
      if ($request->PM_TEMPLATE_UNID_REF == Null) {
        alert()->warning('กรุณาเลือกรายการ');
        return redirect()->back();
      }
      foreach ($request->PM_TEMPLATE_UNID_REF as $dataset => $value) {
        $masterimpsunid = $request->PM_TEMPLATE_UNID_REF[$dataset];
        $datapmtemplate = MachinePmTemplate::select('PM_TEMPLATE_NAME')
                                            ->where('UNID',$masterimpsunid)
                                            ->first();
        $rowcount = MasterIMPS::where('MACHINE_UNID','=',$machine->UNID)->where('PM_TEMPLATE_UNID_REF','=',$masterimpsunid)->count();
        if ($rowcount == 0) {
      DB::beginTransaction();
        try {
          $datamasterimps = array(
            'UNID'                  => $this->randUNID('PMCS_CMMS_MASTER_IMPS'),
            'MACHINE_UNID'          => $machine->UNID,
            'PM_TEMPLATE_UNID_REF'  => $masterimpsunid,
            'MACHINE_CODE'          => $request->MACHINE_CODE,
            'PM_TEMPLATE_NAME'      => $datapmtemplate->PM_TEMPLATE_NAME,
            'PM_LAST_DATE'          => Carbon::now(),
            'CREATE_BY'             => Auth::user()->name,
            'CREATE_TIME'           => Carbon::now(),
            );
          $saveresult =  MasterIMPS::insert($datamasterimps);
          if ($saveresult) {
            $machinepmtemplatelist = MachinePmTemplateList::where('PM_TEMPLATE_UNID_REF',$masterimpsunid)->get();
            foreach ($machinepmtemplatelist as $key => $row) {
              $datamasterimpsgroup = array(
                'UNID'                      => $this->randUNID('PMCS_CMMS_MASTER_IMPS_GP'),
                'PM_TEMPLATELIST_INDEX'     => $row->PM_TEMPLATELIST_INDEX,
                'MACHINE_UNID'              => $machine->UNID,
                'PM_TEMPLATELIST_UNID_REF'  => $row->UNID,
                'MACHINE_CODE'              => $request->MACHINE_CODE,
                'PM_TEMPLATE_UNID_REF'      => $row->PM_TEMPLATE_UNID_REF,
                'PM_TEMPLATELIST_NAME'      => $row->PM_TEMPLATELIST_NAME,
                'PM_TEMPLATELIST_DAY'       => $row->PM_TEMPLATELIST_DAY,
                'PM_TEMPLATELIST_IMPS'      => $row->PM_TEMPLATELIST_POINT,
                'PM_TEMPLATELIST_STATUS'    => '1',
                'CREATE_BY'                 => Auth::user()->name,
                'CREATE_TIME'               => Carbon::now(),
                );
              MasterIMPSGroup::insert($datamasterimpsgroup);
          }
          if ($saveresult) {
            $totalloop          = 0;
            $totalmonth         = MailSetup::select('AUTOPLAN')->first();
            $preiodmonth        = $machine->MACHINE_RANK_MONTH;
            $pm_lastdate        = Carbon::now();
            $machine_unid       = $machine->UNID;
            for ($i = 0; $i < $totalmonth->AUTOPLAN ; $i++) {
                if (($i%$preiodmonth == 0)) {
                  $totalloop++;
                  $pm_lastdate    = Carbon::parse($pm_lastdate)->addMonth($preiodmonth);
                  $pm_plandate    = $pm_lastdate;
                  if ($machine_unid != "" && $masterimpsunid != "") {
                    $saveplanpm   = new MachinePlanController;
                    $saveplanpm->CreatePlan($pm_plandate,$machine_unid,$masterimpsunid);
                  }
                }
            }
          }

          }
          DB::commit();
          } catch (Exception $e) {
              DB::rollback();

              Alert::error('เกิดข้อผิดพลาด', 'ระบบไม่สามารถบันทึกข้อมูลได้')->autoclose('1500');
              return redirect()->back();
          }
        }
      }
      return redirect()->back()->with('success','เพิ่มรายการตรวจเช็คสำเร็จ');

    }else {
      return redirect()->back()->with('warning','กรุณาระบุ Rank');
    }
  }

  public function DeletePMMachine($masterpm_template_unid,$machine_unid) {
    $array_pm_unid = explode(',',$masterpm_template_unid);
    if (count($array_pm_unid) >= 1){
      foreach ($array_pm_unid as $index => $datarow){
        $plan_lastdate = MasterIMPS::where('PM_TEMPLATE_UNID_REF',$datarow)->where('MACHINE_UNID',$machine_unid)->first();

        $test = MachinePlanPm::where('PLAN_DATE','>',$plan_lastdate->PM_LAST_DATE)
                      ->where('PM_TYPE','=','PLAN')
                      ->where('PM_MASTER_UNID',$datarow)
                      ->where('MACHINE_UNID',$machine_unid)
                      ->delete();
        $plan_lastdate->delete();
        MasterIMPSGroup::where('PM_TEMPLATE_UNID_REF',$datarow)->where('MACHINE_UNID',$machine_unid)->delete();
      }
      return Redirect()->back()->with('success','ลบข้อมูลสำเร็จ');
    }else {
      return Redirect()->back()->with('warning','กรุณาเลือกข้อมูลที่จะทำการลบ');
    }
  }

  public function StoreDate(Request $request){

    if($request->ajax()){
        $PM_UNID = $request->pmmaster_template_unid;
        $machine_unid = $request->machine_unid;
        $updateresult = MasterIMPS::select('PM_LAST_DATE')->where('PM_TEMPLATE_UNID_REF',$PM_UNID)
                                    ->where('MACHINE_UNID',$machine_unid)
                                    ->update(['PM_LAST_DATE' => $request->pmmaster_template_lastdate]);
        if ($updateresult == 1) {
          $datamasterimps     = MasterIMPS::select('PM_LAST_DATE')->where('PM_TEMPLATE_UNID_REF',$PM_UNID)
                                          ->where('MACHINE_UNID',$machine_unid)->first();
          $totalloop          = 0;
          $totalmonth         = MailSetup::select('AUTOPLAN')->first();
          $preiodmonth        = $request->machine_rank_month;
          $pm_lastdate        = $datamasterimps->PM_LAST_DATE;

          MachinePlanPm::Where('MACHINE_UNID','=',$machine_unid)
                      ->where('PM_MASTER_UNID','=',$PM_UNID)
                      ->where('PLAN_DATE','>',Carbon::now())->delete();
          for ($i = 0; $i < $totalmonth->AUTOPLAN ; $i++) {
              if (($i%$preiodmonth == 0)) {
                $totalloop++;
                $pm_lastdate    = Carbon::parse($pm_lastdate)->addMonth($preiodmonth);
                $pm_plandate    = $pm_lastdate;
                if ($machine_unid != "" && $PM_UNID != "") {
                  $saveplanpm   = new MachinePlanController;
                  $saveplanpm->updateDatePlan($pm_plandate,$machine_unid,$PM_UNID);
                }
              }
          }
        }
      return response()->json();
    }

  }




  }
