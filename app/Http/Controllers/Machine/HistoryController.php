<?php

namespace App\Http\Controllers\Machine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Middleware\VerifyCsrfToken;
use Carbon\Carbon;
use Auth;
use Cookie;
use Gate;
//******************** model ***********************
use App\Models\Machine\Machine;
use App\Models\Machine\SparePart;
use App\Models\Machine\RepairSparepart;
use App\Models\Machine\History;
use App\Models\Machine\MachineRepairREQ;
use App\Models\Machine\HistoryPlanPM;
use App\Models\Machine\MachinePlanPm;
use App\Models\Machine\Pmplanresult;
use App\Models\Machine\PmPlanSparepart;
use App\Models\Machine\SparePartPlan;
use App\Http\Controllers\PDF\HeaderFooterPDF\HistoryHeaderFooterRepair as HistoryHeaderFooterRepair;
use App\Http\Controllers\PDF\HeaderFooterPDF\HistoryHeaderFooterPM as HistoryHeaderFooterPM;
//************** Package form github ***************

class HistoryController extends Controller
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


  public function RepairList(Request $request){
    $DOC_TYPE   = isset($request->DOC_TYPE)   ? $request->DOC_TYPE  : ($request->cookie('DOC_TYPE')   != '' ? $request->cookie('DOC_TYPE')  : 'REPAIR');
    $DOC_YEAR   = isset($request->DOC_YEAR)   ? $request->DOC_YEAR  : ($request->cookie('DOC_YEAR')   != '' ? $request->cookie('DOC_YEAR')  : date('Y')) ;
    $DOC_MONTH  = isset($request->DOC_MONTH)  ? $request->DOC_MONTH : ($request->cookie('DOC_MONTH')  != '' ? $request->cookie('DOC_MONTH') : date('n')) ;
    $SEARCH     = isset($request->SEARCH_MACHINE)     ? $request->SEARCH_MACHINE    : '' ;

    $MINUTES = 30;
    Cookie::queue('DOC_TYPE'  ,$DOC_TYPE  ,$MINUTES);
    Cookie::queue('DOC_YEAR'  ,$DOC_YEAR  ,$MINUTES);
    Cookie::queue('DOC_MONTH' ,$DOC_MONTH ,$MINUTES);
    $DATA_REPAIR_HEADER = History::select('MACHINE_UNID','MACHINE_CODE','MACHINE_NAME')
                                            ->where(function($query) use($DOC_TYPE){
                                              $query->where('DOC_TYPE','=',$DOC_TYPE);
                                            })
                                            ->where(function($query) use($DOC_YEAR){
                                              if ($DOC_YEAR > 0) {
                                                $query->where('DOC_YEAR','=',$DOC_YEAR);
                                              }
                                            })
                                            ->where(function($query) use($DOC_MONTH){
                                              if ($DOC_MONTH > 0) {
                                                $query->where('DOC_MONTH','=',$DOC_MONTH);
                                              }
                                            })
                                            ->where(function($query) use ($SEARCH){
                                              if ($SEARCH != '') {
                                                $query->where('MACHINE_CODE','like','%'.$SEARCH.'%');
                                              }
                                            })
                                            ->groupBy('MACHINE_UNID','MACHINE_CODE','MACHINE_NAME')
                                            ->orderBy('MACHINE_CODE')->paginate(5);

    if ($DOC_TYPE == 'REPAIR') {
      $DATA_REPAIR        = History::select('*')->selectraw('dbo.decode_utf8(INSPECTION_BY) as INSPECTION_BY_TH')
                                          ->where('DOC_TYPE','=','REPAIR')
                                          ->orderBy('MACHINE_CODE')->get();
      $DATA_SPAREPART     = RepairSparepart::orderBy('SPAREPART_NAME')->get();
      $COMPACT_NAME = compact('DOC_TYPE','DATA_REPAIR_HEADER','DOC_YEAR','DOC_MONTH','SEARCH','DATA_SPAREPART','DATA_REPAIR');

    }elseif ($DOC_TYPE == 'PLAN_PM') {
      $DATA_PLANPM        = History::select('PM_PLAN_UNID','DOC_DATE','REPAIR_DATE','DOWN_TIME')
                                    ->selectraw('dbo.decode_utf8(INSPECTION_BY) as INSPECTION_BY_TH')
                                    ->where('DOC_TYPE','=','PLAN_PM')
                                    ->orderBy('MACHINE_CODE')->get();
      $DATA_SPAREPART     = RepairSparepart::orderBy('SPAREPART_NAME')->get();
      $DATA_MACHINE_PLAN  = MachinePlanPm::select('PLAN_RANK','PLAN_PERIOD','UNID','PM_MASTER_NAME')->get();
      $DATA_MASTERTEMPLAT = Pmplanresult::select('PM_MASTER_LIST_NAME','PM_MASTER_LIST_INDEX','PM_PLAN_UNID')
                                        ->groupBy('PM_MASTER_LIST_NAME')
                                        ->groupBy('PM_PLAN_UNID')
                                        ->groupBy('PM_MASTER_LIST_INDEX')
                                        ->orderBy('PM_MASTER_LIST_INDEX')->get();

      $COMPACT_NAME = compact('DOC_TYPE','DATA_REPAIR_HEADER','DOC_YEAR','DOC_MONTH','SEARCH','DATA_MASTERTEMPLAT','DATA_PLANPM','DATA_MACHINE_PLAN');
    }elseif ($DOC_TYPE == 'PLAN_PDM') {
      $DATA_PLAN_PDM        = History::select('SPAREPART_PLAN_UNID','DOC_DATE','REPAIR_DATE','DOWN_TIME','TOTAL_COST')
                                      ->where('DOC_TYPE','=','PLAN_PDM')
                                      ->orderBy('MACHINE_CODE')->get();
      $DATA_SPAREPART_PLAN  = SparePartPlan::orderBy('SPAREPART_NAME')->get();
      $COMPACT_NAME = compact('DOC_TYPE','DATA_REPAIR_HEADER','DOC_YEAR','DOC_MONTH','SEARCH','DATA_PLAN_PDM','DATA_SPAREPART_PLAN');
    }

    return view('machine.history.list',$COMPACT_NAME);
  }
  public function RepairPDF(HistoryHeaderFooterRepair $HistoryHeaderFooterRepair,$MACHINE_UNID = NULL){
    $this->pdf = $HistoryHeaderFooterRepair;
    $MACHINE_UNID =  isset($MACHINE_UNID) ? $MACHINE_UNID : 0 ;
     $GROUP_HISTORY_REPAIR = Machine::select('MACHINE_CODE','UNID')
                                          ->where(function($query) use ($MACHINE_UNID){
                                            if ($MACHINE_UNID > 0) {
                                              $query->where('UNID','=',$MACHINE_UNID);
                                            }
                                          })->groupBy('UNID')->groupBy('MACHINE_CODE')
                                            ->orderBy('MACHINE_CODE')->get();

    $DATA_REPAIR_SPAREPART = RepairSparepart::orderBy('SPAREPART_NAME')->get();
    $DATA_PM_SPAREPART     = PmPlanSparepart::orderBy('SPAREPART_NAME')->get();

    $this->pdf->AddFont('THSarabunNew','','THSarabunNew.php');
    $this->pdf->AddFont('THSarabunNew','B','THSarabunNew_b.php');
    $this->pdf->SetFont('THSarabunNew','',14 );

    foreach ($GROUP_HISTORY_REPAIR as $groupindex => $grouprow) {
      $this->pdf->AliasNbPages();
      $this->pdf->AddPage(['L','A4',]);
      $this->pdf->header($grouprow->UNID);
      $this->pdf->setXY(5,44);
      $this->pdf->setAutoPageBreak(false);
      $this->pdf->Rect(5,5,287,194);
      $this->pdf->SetFont('THSarabunNew','',10);

      $DATA_HISTORY_REPAIR = History::select('*')->selectraw(
                              'dbo.decode_utf8(INSPECTION_BY) as INSPECTION_BY_TH,
                               dbo.decode_utf8(REPORT_BY)     as REPORT_BY_TH,
                               dbo.decode_utf8(APPROVED_BY)   as APPROVED_BY_TH '
                              )->where('MACHINE_UNID','=',$grouprow->UNID)->orderBy('DOC_DATE')->get();
      foreach ($DATA_HISTORY_REPAIR as $index => $row) {
        $this->pdf->setX(5);
        $array_text_sparepart = array();
        $no_sparepart = 1;
        $no_woker = 1;
        $no_master = 1;
        //************************************* foreach sparepart and more
        if ($row->DOC_TYPE == 'REPAIR') {
          $SPAREPART = $DATA_REPAIR_SPAREPART->where('REPAIR_REQ_UNID','=',$row->REPAIR_REQ_UNID);
        }elseif($row->DOC_TYPE == 'PLAN_PM') {
          $DATA_PLAN_REUSLT = Pmplanresult::select('PM_MASTER_LIST_NAME','PM_MASTER_LIST_INDEX','PM_PLAN_UNID')
                                              ->groupBy('PM_MASTER_LIST_NAME')
                                              ->groupBy('PM_PLAN_UNID')
                                              ->groupBy('PM_MASTER_LIST_INDEX')
                                              ->orderBy('PM_MASTER_LIST_INDEX')->get();
          $array_text_master = array();
          $SPAREPART = $DATA_PM_SPAREPART->where('PM_PLAN_UNID','=',$row->PM_PLAN_UNID);
          foreach ($DATA_PLAN_REUSLT->where('PM_PLAN_UNID','=',$row->PM_PLAN_UNID) as $subindex => $row_sparepart) {
                $array_text_master[] = $no_master++.'. '.$row_sparepart->PM_MASTER_LIST_NAME;
              }
         $TEXT_MASTER = implode("\n",$array_text_master);
       }elseif($row->DOC_TYPE == 'PLAN_PDM') {
         $SPAREPART = $DATA_REPAIR_SPAREPART->where('REPAIR_REQ_UNID','=',$row->SPAREPART_PLAN_UNID);
          $DATA_PLAN_REUSLT = SparePartPlan::select('SPAREPART_NAME','UNID')->where('UNID','=',$row->SPAREPART_PLAN_UNID)->first();
        }

        //***************************************** foreach main data
        foreach ( $SPAREPART as $subindex => $row_sparepart) {
          $array_text_sparepart[] = $no_sparepart++.'. '.$row_sparepart->SPAREPART_NAME;
        }
        $TEXT = implode("\n",$array_text_sparepart);
        $X  = $this->pdf->getX();
        $this->pdf->SetWidths(array(10,16,22,40,40,16,16,35,20,24,24,24));
        $this->pdf->SetAligns(array('C','C','L','L','L','C','R','L','R','L','L','L'));
        //******************************************** main data show
        if ($row->DOC_TYPE == 'REPAIR') {
          $TEXT = implode("\n",$array_text_sparepart);
          $this->pdf->Row(array($index+1,date('d-m-Y',strtotime($row->DOC_DATE)),$row->DOC_NO,iconv('UTF-8','cp874', $row->REPAIR_REQ_DETAIL)
          ,iconv('UTF-8', 'cp874',$row->REPAIR_DETAIL),date('d-m-Y',strtotime($row->REPAIR_DATE)),iconv('UTF-8','cp874',number_format($row->DOWN_TIME).' นาที')
          ,iconv('UTF-8', 'cp874',$TEXT),iconv('UTF-8','cp874',number_format($row->TOTAL_COST).' บาท'),iconv('UTF-8','cp874',$row->REPORT_BY_TH)
          ,iconv('UTF-8','cp874',$row->INSPECTION_BY_TH),iconv('UTF-8','cp874',$row->APPROVED_BY_TH)
          )) ;
        }elseif ($row->DOC_TYPE == 'PLAN_PM') {
          $this->pdf->Row(array($index+1,date('d-m-Y',strtotime($row->DOC_DATE)),'-',iconv('UTF-8','cp874', $row->REPAIR_REQ_DETAIL)
          ,iconv('UTF-8', 'cp874','รายการตรวจเช็ค'."\n".$TEXT_MASTER),date('d-m-Y',strtotime($row->REPAIR_DATE)),iconv('UTF-8','cp874',number_format($row->DOWN_TIME).' นาที')
          ,iconv('UTF-8', 'cp874',$TEXT),iconv('UTF-8','cp874',number_format($row->TOTAL_COST).' บาท'),iconv('UTF-8','cp874',$row->REPORT_BY_TH)
          ,iconv('UTF-8','cp874',$row->INSPECTION_BY_TH),iconv('UTF-8','cp874',$row->APPROVED_BY_TH)
          )) ;
        }elseif ($row->DOC_TYPE == 'PLAN_PDM') {
          $this->pdf->Row(array($index+1
          ,date('d-m-Y',strtotime($row->DOC_DATE))
          ,'-'
          ,iconv('UTF-8','cp874', $row->REPAIR_REQ_DETAIL)
          ,iconv('UTF-8', 'cp874','-')
          ,date('d-m-Y',strtotime($row->REPAIR_DATE))
          ,iconv('UTF-8','cp874',number_format($row->DOWN_TIME).' นาที')
          ,iconv('UTF-8', 'cp874',$no_sparepart++.'. '.$DATA_PLAN_REUSLT->SPAREPART_NAME)
          ,iconv('UTF-8','cp874',number_format($row->TOTAL_COST).' บาท')
          ,iconv('UTF-8','cp874',$row->REPORT_BY_TH)
          ,iconv('UTF-8','cp874',$row->INSPECTION_BY_TH)
          ,iconv('UTF-8','cp874',$row->APPROVED_BY_TH)
          )) ;
        }
        //********************************************************* new page
        $Y= $this->pdf->getY();
        if ($Y > 190) {
          $this->pdf->AddPage(['L','A4',]);
          $this->pdf->header($grouprow->MACHINE_UNID);
          $this->pdf->setXY(5,44);
          $this->pdf->Rect(5,5,287,194);
          $this->pdf->SetFont('THSarabunNew','',10);
        }
      }

    }

    $this->pdf->Output();
  }
  public function SaveHistory($UNID_REPAIR,$MACHINE_REPORT_NO,$REPAIR_DATE,$TOTAL_COST_REPAIR,$DOWNTIME){
    $DATA_REPAIR =  MachineRepairREQ::where('UNID','=',$UNID_REPAIR)->first();
    $APPROVED_BY = DB::table('PMCS_EMP_NAME')->select('EMP_NAME')->leftJoin('EMCS_EMPLOYEE','PMCS_EMP_NAME.EMP_CODE','=','EMCS_EMPLOYEE.EMP_CODE')
                     ->where('POSITION_CODE','=','ASSTMGR')->where('EMCS_EMPLOYEE.EMP_STATUS','=','9')->first();
    History::insert([
      'UNID'               => $this->randUNID('PMCS_CMMS_HISTORY_REPAIR')
     ,'REPAIR_REQ_UNID'    => $UNID_REPAIR
     ,'MACHINE_UNID'       => $DATA_REPAIR->MACHINE_UNID
     ,'MACHINE_CODE'       => $DATA_REPAIR->MACHINE_CODE
     ,'MACHINE_NAME'       => $DATA_REPAIR->MACHINE_NAME
     ,'DOC_NO'             => $MACHINE_REPORT_NO
     ,'DOC_DATE'           => $DATA_REPAIR->DOC_DATE
     ,'DOC_YEAR'           => $DATA_REPAIR->DOC_YEAR
     ,'DOC_MONTH'          => $DATA_REPAIR->DOC_MONTH
     ,'DOC_TYPE'           => 'REPAIR'
     ,'REPAIR_REQ_DETAIL'  => $DATA_REPAIR->REPAIR_SUBSELECT_NAME
     ,'REPAIR_DETAIL'      => $DATA_REPAIR->REPAIR_DETAIL
     ,'REPAIR_DATE'        => $REPAIR_DATE
     ,'TOTAL_COST'         => $TOTAL_COST_REPAIR
     ,'REPORT_BY'          => $DATA_REPAIR->INSPECTION_NAME
     ,'INSPECTION_BY'      => Auth::user()->name
     ,'APPROVED_BY'        => $APPROVED_BY->EMP_NAME
     ,'DOWN_TIME'          => $DOWNTIME
     ,'CREATE_BY'          => Auth::user()->name
     ,'CREATE_TIME'        => Carbon::now()
     ,'MODIFY_BY'          => Auth::user()->name
     ,'MODIFY_TIME'        => Carbon::now()
    ]);
  }
  public function SaveHistoryPM($PM_PLAN_UNID,$DOWNTIME,$REMARK,$CHECK_DATE,$PM_USER_CHECK,$TOTAL_COST_SPAREPART){

    $checkrow = History::where('PM_PLAN_UNID','=',$PM_PLAN_UNID);
    if ($checkrow->count() > 0) {
     $checkrow->delete();
    }
    $PLAN = MachinePlanPm::where('UNID','=',$PM_PLAN_UNID)->first();
    $MACHINE = Machine::where('UNID','=',$PLAN->MACHINE_UNID)->first();
    $REMARK = $REMARK != '' ? $REMARK : '' ;
    $APPROVED_BY = DB::table('PMCS_EMP_NAME')->select('EMP_NAME')->leftJoin('EMCS_EMPLOYEE','PMCS_EMP_NAME.EMP_CODE','=','EMCS_EMPLOYEE.EMP_CODE')
                     ->where('POSITION_CODE','=','ASSTMGR')->where('EMCS_EMPLOYEE.EMP_STATUS','=','9')->first();
    History::insert([
      'UNID'               => $this->randUNID('PMCS_CMMS_HISTORY_REPAIR')
     ,'PM_PLAN_UNID'       => $PLAN->UNID
     ,'MACHINE_UNID'       => $PLAN->MACHINE_UNID
     ,'MACHINE_CODE'       => $MACHINE->MACHINE_CODE
     ,'MACHINE_NAME'       => $MACHINE->MACHINE_NAME
     ,'DOC_NO'             => ''
     ,'DOC_DATE'           => $PLAN->PLAN_DATE
     ,'DOC_YEAR'           => $PLAN->PLAN_YEAR
     ,'DOC_MONTH'          => $PLAN->PLAN_MONTH
     ,'DOC_TYPE'           => 'PLAN_PM'
     ,'REPAIR_REQ_DETAIL'  => 'ตรวจเช็คเครื่องจักรประจำเดือน'
     ,'REPAIR_DETAIL'      => $REMARK
     ,'REPAIR_DATE'        => $CHECK_DATE
     ,'TOTAL_COST'         => $TOTAL_COST_SPAREPART
     ,'REPORT_BY'          => $PM_USER_CHECK
     ,'INSPECTION_BY'      => Auth::user()->name
     ,'APPROVED_BY'        => $APPROVED_BY->EMP_NAME
     ,'DOWN_TIME'          => $DOWNTIME
     ,'CREATE_BY'          => Auth::user()->name
     ,'CREATE_TIME'        => Carbon::now()
     ,'MODIFY_BY'          => Auth::user()->name
     ,'MODIFY_TIME'        => Carbon::now()
    ]);
  }
  
  public function SaveHistoryPDM($SPAREPART_PLAN_UNID,$DOWNTIME){
    $DATA_SPAREPART_PLAN = SparePartPlan::where('UNID','=',$SPAREPART_PLAN_UNID)->first();
    $DATA_MACHINE = Machine::where('UNID','=',$DATA_SPAREPART_PLAN->MACHINE_UNID)->first();
    $APPROVED_BY = DB::table('PMCS_EMP_NAME')->select('EMP_NAME')->leftJoin('EMCS_EMPLOYEE','PMCS_EMP_NAME.EMP_CODE','=','EMCS_EMPLOYEE.EMP_CODE')
                     ->where('POSITION_CODE','=','ASSTMGR')->where('EMCS_EMPLOYEE.EMP_STATUS','=','9')->first();
    History::insert([
      'UNID'                    => $this->randUNID('PMCS_CMMS_HISTORY_REPAIR')
     ,'SPAREPART_PLAN_UNID'     => $DATA_SPAREPART_PLAN->UNID
     ,'MACHINE_UNID'            => $DATA_MACHINE->UNID
     ,'MACHINE_CODE'            => $DATA_MACHINE->MACHINE_CODE
     ,'MACHINE_NAME'            => $DATA_MACHINE->MACHINE_NAME
     ,'DOC_NO'                  => ''
     ,'DOC_DATE'                => $DATA_SPAREPART_PLAN->PLAN_DATE
     ,'DOC_YEAR'                => $DATA_SPAREPART_PLAN->DOC_YEAR
     ,'DOC_MONTH'               => $DATA_SPAREPART_PLAN->DOC_MONTH
     ,'DOC_TYPE'                => 'PLAN_PDM'
     ,'REPAIR_REQ_DETAIL'       => 'เปลี่ยนอะไหล่เครื่องจักรประจำเดือน'
     ,'REPAIR_DETAIL'           => ''
     ,'REPAIR_DATE'             => $DATA_SPAREPART_PLAN->COMPLETE_DATE
     ,'TOTAL_COST'              => $DATA_SPAREPART_PLAN->COST_ACT
     ,'REPORT_BY'               => $DATA_SPAREPART_PLAN->USER_CHECK
     ,'INSPECTION_BY'           => Auth::user()->name
     ,'APPROVED_BY'             => $APPROVED_BY->EMP_NAME
     ,'DOWN_TIME'               => $DOWNTIME
     ,'CREATE_BY'               => Auth::user()->name
     ,'CREATE_TIME'             => Carbon::now()
     ,'MODIFY_BY'               => Auth::user()->name
     ,'MODIFY_TIME'             => Carbon::now()
    ]);

  }
}
