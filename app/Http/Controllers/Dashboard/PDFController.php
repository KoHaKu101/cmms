<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Machine\Machine;
use App\Models\Machine\MachineRepairREQ;
use App\Models\Machine\MachinePlanPm;
use App\Models\Machine\SparePartPlan;
use App\Models\Machine\Pmplanresult;
use App\Http\Controllers\PDF\HeaderFooterPDF\DowntimeHeader as DowntimeHeader;
use App\Http\Controllers\PDF\HeaderFooterPDF\MachineRepairHeader as MachineRepairHeader;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\DB;
class PDFController extends Controller
{
  public function __construct(){
    $this->middleware('auth');
  }
  public function PDFDowntime(Request $request,DowntimeHeader $DowntimeHeader){
    $TYPE = strtoupper($request->TYPE);
    $DATA_REPAIR = MachineRepairREQ::select('*')->selectraw('dbo.decode_utf8(CLOSE_BY) as CLOSE_BY,dbo.decode_utf8(MACHINE_NAME) as MACHINE_NAME_TH')
                                   ->where('DOC_YEAR','=',date('Y'))
                                   ->where('DOC_MONTH','=',date('n'))->where('CLOSE_STATUS','=',1)->orderBy('DOWNTIME','DESC')->get();

    $this->pdf = $DowntimeHeader;
    $this->pdf->AddFont('THSarabunNew','','THSarabunNew.php');
    $this->pdf->AddFont('THSarabunNew','B','THSarabunNew_b.php');
    $this->pdf->SetFont('THSarabunNew','',14 );
    $this->pdf->AliasNbPages();
    $this->pdf->AddPage(['L',['220', '300'],]);
    $this->pdf->setAutoPageBreak(false);
    $this->pdf->header($TYPE);

    if ($TYPE == 'DOWNTIME') {
      $this->pdf->SetType($TYPE);
      $index = 1;
      $this->pdf->SetWidths(array(8,20,39,45,50,25,25,20,20,35));
      $this->pdf->SetAligns(array('C','L','L','L','L','C','C','C','C','L'));
      foreach($DATA_REPAIR as $index => $row) {
        $index = $index + 1;
        $INSPECTION_RESULT_TIME = $row->INSPECTION_RESULT_TIME > 0 ? number_format($row->INSPECTION_RESULT_TIME) : '-';
        $SPAREPART_RESULT_TIME  = $row->SPAREPART_RESULT_TIME  > 0 ? number_format($row->SPAREPART_RESULT_TIME)  : '-';
        $WORK_RESULT_TIME 			= $row->WORKERIN_RESULT_TIME 	 > 0 ? number_format($row->WORKERIN_RESULT_TIME)   : number_format($row->WORKEROUT_RESULT_TIME);
        $WORK_RESULT_TIME 			= $WORK_RESULT_TIME != 0           ? $WORK_RESULT_TIME : '-';
        $CLOSE_BY               = isset($row->CLOSE_BY)            ? $row->CLOSE_BY    : '-';
        $MACHINE_NAME           = isset($row->MACHINE_NAME_TH)     ? $row->MACHINE_NAME_TH : '-';
        $REPAIR_SUBSELECT_NAME  = isset($row->REPAIR_SUBSELECT_NAME) ? $row->REPAIR_SUBSELECT_NAME : '-';
        $REPAIR_DETAIL          = isset($row->REPAIR_DETAIL)       ? $row->REPAIR_DETAIL : '-';
        $this->pdf->setX(5);
        $this->pdf->Row(array(
          $index
          ,$row->MACHINE_CODE
          ,iconv('UTF-8', 'cp874', $MACHINE_NAME)
          ,iconv('UTF-8', 'cp874', $REPAIR_SUBSELECT_NAME)
          ,iconv('UTF-8', 'cp874', $REPAIR_DETAIL.'เปลี่ยนสายพาน POLY FLEX :3/7M-1450 =1 pcs ราคา 1995บาท')
          ,$INSPECTION_RESULT_TIME
          ,$SPAREPART_RESULT_TIME
          ,$WORK_RESULT_TIME
          ,number_format($row->DOWNTIME)
          ,iconv('UTF-8', 'cp874', $CLOSE_BY)
        ));
        $GET_Y = $this->pdf->getY();
        if ($GET_Y > 180) {
          $this->pdf->AddPage(['L','A4',]);
          $this->pdf->setX(5);
          $this->pdf->header($TYPE);
        }
      }
    }
    elseif ($TYPE == 'SUMDOWNTIME') {
      $this->pdf->SetType($TYPE);
      $DATA_SUM_DOWNTIME  = MachineRepairREQ::select('MACHINE_CODE','MACHINE_UNID','MACHINE_NAME')
                                            ->selectraw('SUM(DOWNTIME) as DOWNTIME,dbo.decode_utf8(MACHINE_NAME) as MACHINE_NAME_TH')
                                            ->where('DOC_YEAR','=',date('Y'))
                                            ->where('DOC_MONTH','=',date('n'))->where('CLOSE_STATUS','=',1)->groupBy('MACHINE_CODE')
                                            ->groupBy('MACHINE_UNID')->groupBy('MACHINE_NAME')
                                            ->orderBy('DOWNTIME','DESC')->get();
      $this->pdf->SetWidths(array(8,20,39,60,65,30,30,35));
      $this->pdf->SetAligns(array('C','L','L','L','L','L','C','C'));
      foreach ($DATA_SUM_DOWNTIME as $index => $row){
          $REPAIR_SUM   				 = $DATA_REPAIR->where('MACHINE_UNID','=',$row->MACHINE_UNID);
          $ROW_SPAN   					 = count($REPAIR_SUM);
          $number 							 = 1;
          $NUMBER_SUBSELECT_NAME = 1;
          $NUMBER_REPAIR_DETAIL  = 1;
          foreach ($REPAIR_SUM  as $sub_index => $sub_row){
              $DOWNTIME = $sub_row->DOWNTIME == 0 ? '-' : number_format($sub_row->DOWNTIME);
              $this->pdf->setX(5);

              $GET_Y = $this->pdf->getY();
              $number_count = $number++;

              if($number_count == $ROW_SPAN && $number_count == number_format($ROW_SPAN/2)){
                $ONE         = $index+1;                                            $BORDERONE   = 'LBR';
                $TWO         = $sub_row->MACHINE_CODE;                              $BORDERTWO   = 'LBR';
                $THREE       = iconv('UTF-8', 'cp874', $sub_row->MACHINE_NAME_TH);  $BORDERTHREE = 'LBR';
                $Eigth       = number_format($row->DOWNTIME);                       $BORDEREigth = 'LBR';
              }elseif ($number_count == $ROW_SPAN) {
                $ONE         = ''; $BORDERONE    = 'LBR';
                $TWO         = ''; $BORDERTWO    = 'LBR';
                $THREE       = ''; $BORDERTHREE  = 'LBR';
                $Eigth       = ''; $BORDEREigth  = 'LBR';
              }elseif ($number_count == number_format($ROW_SPAN/2)) {
                $ONE         = $index+1;                                            $BORDERONE   = 'LR';
                $TWO         = $sub_row->MACHINE_CODE;                              $BORDERTWO   = 'LR';
                $THREE       = iconv('UTF-8', 'cp874', $sub_row->MACHINE_NAME_TH);  $BORDERTHREE = 'LR';
                $Eigth       = number_format($row->DOWNTIME);                       $BORDEREigth = 'LR';
              }else {
                $ONE         = ''; $BORDERONE    = 'LR';
                $TWO         = ''; $BORDERTWO    = 'LR';
                $THREE       = ''; $BORDERTHREE  = 'LR';
                $Eigth       = ''; $BORDEREigth  = 'LR';
              }
              $FOUR         = iconv('UTF-8', 'cp874', $NUMBER_SUBSELECT_NAME++ .'. '.$sub_row->REPAIR_SUBSELECT_NAME);
              $FIVE         = iconv('UTF-8', 'cp874', $NUMBER_REPAIR_DETAIL++ .'. '.$sub_row->REPAIR_DETAIL);
              $SIX          = iconv('UTF-8', 'cp874', $sub_row->CLOSE_BY);
              $SEVEN        = $DOWNTIME;
              $BORDERFOUR   = 'LR';
              $BORDERFIVE   = 'LR';
              $BORDERSIX    = 'LR';
              $BORDERSEVEN  = 'LR';

              $this->pdf->SetBorder(array(
                 $BORDERONE   ,$BORDERTWO   ,$BORDERTHREE
                ,$BORDERFOUR  ,$BORDERFIVE  ,$BORDERSIX
                ,$BORDERSEVEN ,$BORDEREigth
              ));
              $this->pdf->Row(array(
                 $ONE   ,$TWO   ,$THREE
                ,$FOUR  ,$FIVE  ,$SIX
                ,$SEVEN ,$Eigth
              ));
              if ($GET_Y > 160) {
                $this->pdf->AddPage(['L','A4',]);
                $this->pdf->header($TYPE);
                $this->pdf->setX(5);
              }

          }
      }
    }

    $this->pdf->Output();
    exit;
  }
  public function PDFMachineRepair(MachineRepairHeader $MachineRepairHeader){
    $this->pdf = $MachineRepairHeader;
    $this->pdf->AddFont('THSarabunNew','','THSarabunNew.php');
    $this->pdf->AddFont('THSarabunNew','B','THSarabunNew_b.php');
    $this->pdf->SetFont('THSarabunNew','',14 );
    $this->pdf->AliasNbPages();
    $this->pdf->AddPage(['L',['220', '300'],]);
    $this->pdf->setAutoPageBreak(false);
    $ORDER_BY_COUNT    = MachineRepairREQ::selectraw('MACHINE_UNID,Count(MACHINE_CODE) as MACHINE_CODE_COUNT')
                                          ->where('DOC_YEAR','=',date('Y'))->where('DOC_MONTH','=',date('n'))
                                          ->where('MACHINE_LINE','like','L'.'%')
                                          ->groupBy('MACHINE_UNID')->orderBy('MACHINE_CODE_COUNT','DESC')->get();
    $MACHINEREPAIRREQ  = MachineRepairREQ::select('*')->selectraw('dbo.decode_utf8(MACHINE_NAME) as MACHINE_NAME_TH
                                                                  ,dbo.decode_utf8(CLOSE_BY) as CLOSE_BY_TH')
                                          ->where('DOC_YEAR','=',date('Y'))->where('DOC_MONTH','=',date('n'))
                                          ->where('MACHINE_LINE','like','L'.'%')->orderBy('MACHINE_CODE','DESC')->get();
    $this->pdf->SetWidths(array(8,20,39,80,80,40,20));
    $this->pdf->SetAligns(array('C','L','L','L','L','L','C'));
    foreach ($ORDER_BY_COUNT as $index => $row) {

      $number 							 = 1;
      $NUMBER_SUBSELECT_NAME = 1;
      $NUMBER_REPAIR_DETAIL  = 1;
      foreach($MACHINEREPAIRREQ->where('MACHINE_UNID','=',$row->MACHINE_UNID) as $subindex => $subrow){
        $this->pdf->setX(5);

        $REPAIR_SUM            = $MACHINEREPAIRREQ->where('MACHINE_UNID','=',$row->MACHINE_UNID);
        $ROW_SPAN   					 = count($REPAIR_SUM);
        $number_count          = $number++;

        if($number_count == $ROW_SPAN && $number_count == number_format($ROW_SPAN/2)){
          $ONE         = $index+1;                                           $BORDERONE   = 'LBR';
          $TWO         = $subrow->MACHINE_CODE;                              $BORDERTWO   = 'LBR';
          $THREE       = iconv('UTF-8', 'cp874', $subrow->MACHINE_NAME_TH);  $BORDERTHREE = 'LBR';
          $FOUR        = $row->MACHINE_CODE_COUNT;                           $BORDERFOUR  = 'LBR';
        }elseif ($number_count == $ROW_SPAN) {
          $ONE         = ''; $BORDERONE    = 'LBR';
          $TWO         = ''; $BORDERTWO    = 'LBR';
          $THREE       = ''; $BORDERTHREE  = 'LBR';
          $FOUR        = ''; $BORDERFOUR   = 'LBR';
        }elseif ($number_count == number_format($ROW_SPAN/2)) {
          $ONE         = $index+1;                                           $BORDERONE   = 'LR';
          $TWO         = $subrow->MACHINE_CODE;                              $BORDERTWO   = 'LR';
          $THREE       = iconv('UTF-8', 'cp874', $subrow->MACHINE_NAME_TH);  $BORDERTHREE = 'LR';
          $FOUR        = $row->MACHINE_CODE_COUNT;                           $BORDERFOUR  = 'LR';
        }else {
          $ONE         = ''; $BORDERONE    = 'LR';
          $TWO         = ''; $BORDERTWO    = 'LR';
          $THREE       = ''; $BORDERTHREE  = 'LR';
          $FOUR        = ''; $BORDERFOUR   = 'LR';
        }
        $this->pdf->SetBorder(array(
          $BORDERONE
         ,$BORDERTWO
         ,$BORDERTHREE
         ,0
         ,0
         ,0
         ,$BORDERFOUR
        ));
        $REPAIR_SUBSELECT_NAME = isset($subrow->REPAIR_SUBSELECT_NAME) ? $subrow->REPAIR_SUBSELECT_NAME  : '-';
        $REPAIR_DETAIL         = isset($subrow->REPAIR_DETAIL)         ? $subrow->REPAIR_DETAIL          : '-';
        $this->pdf->Row(array(
           $ONE
          ,$TWO
          ,$THREE
          ,iconv('UTF-8', 'cp874', $NUMBER_SUBSELECT_NAME++.'. '.$REPAIR_SUBSELECT_NAME)
          ,iconv('UTF-8', 'cp874', $NUMBER_REPAIR_DETAIL++.'. '.$REPAIR_DETAIL)
          ,iconv('UTF-8', 'cp874', $subrow->CLOSE_BY_TH)
          ,iconv('UTF-8', 'cp874', $FOUR)
        ));
        $GET_Y = $this->pdf->getY();
        if ($GET_Y > 187) {
          $this->pdf->AddPage(['L','A4',]);
          $this->pdf->setX(5);
          $this->pdf->header();
        }
      }
    }

    $this->pdf->Output();
    exit;
  }
}
