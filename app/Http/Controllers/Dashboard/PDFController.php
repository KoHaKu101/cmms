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
    $this->pdf->Rect(5,5,287,193);
    $this->pdf->header($TYPE);

    if ($TYPE == 'DOWNTIME') {

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
          ,iconv('UTF-8', 'cp874', $REPAIR_DETAIL)
          ,$INSPECTION_RESULT_TIME
          ,$SPAREPART_RESULT_TIME
          ,$WORK_RESULT_TIME
          ,number_format($row->DOWNTIME)
          ,iconv('UTF-8', 'cp874', $CLOSE_BY)
        ));
        $GET_Y = $this->pdf->getY();
        if ($GET_Y > 193) {
          $this->pdf->AddPage(['L','A4',]);
          $this->pdf->Rect(5,5,287,193);
          $this->pdf->header($TYPE);
        }
      }

    }
    elseif ($TYPE == 'SUMDOWNTIME') {

      $DATA_SUM_DOWNTIME  = MachineRepairREQ::select('MACHINE_CODE','MACHINE_UNID','MACHINE_NAME')
                                            ->selectraw('SUM(DOWNTIME) as DOWNTIME,dbo.decode_utf8(MACHINE_NAME) as MACHINE_NAME_TH')
                                            ->where('DOC_YEAR','=',date('Y'))
                                            ->where('DOC_MONTH','=',date('n'))->where('CLOSE_STATUS','=',1)->groupBy('MACHINE_CODE')
                                            ->groupBy('MACHINE_UNID')->groupBy('MACHINE_NAME')
                                            ->orderBy('DOWNTIME','DESC')->get();
      $this->pdf->SetWidths(array(8,20,39,45,50,25,25,20,20,35));
      $this->pdf->SetAligns(array('C','L','L','L','L','C','C','C','C','L'));
      foreach ($DATA_SUM_DOWNTIME as $index => $row){
          $REPAIR_SUM   				 = $DATA_REPAIR->where('MACHINE_UNID','=',$row->MACHINE_UNID);
          $ROW_SPAN   					 = count($REPAIR_SUM);
          $number 							 = 1;
          $NUMBER_SUBSELECT_NAME = 1;
          $NUMBER_REPAIR_DETAIL  = 1;
          foreach ($REPAIR_SUM  as $sub_index => $sub_row){
              $DOWNTIME = $sub_row->DOWNTIME == 0 ? '-' : $sub_row->DOWNTIME;
              $this->pdf->setX(5);
              $GET_Y = $this->pdf->getY();
              $number_count = $number++;


              if($number_count == $ROW_SPAN && $number_count == number_format($ROW_SPAN/2)){
                // $this->pdf->Cell(8,  7, $index+1               ,'BR',0,'C',0);
                // $this->pdf->Cell(20, 7, $sub_row->MACHINE_CODE ,'BR',0,'L',0);
                // $this->pdf->Cell(39, 7, iconv('UTF-8', 'cp874', $sub_row->MACHINE_NAME_TH) ,'BR',0,'L',0);
              $ONE    =  $index+1;
              $TWO    =  $sub_row->MACHINE_CODE;
              $THREE  =  iconv('UTF-8', 'cp874', $sub_row->MACHINE_NAME_TH);
              }elseif ($number_count == $ROW_SPAN) {
                // $this->pdf->Cell(8,  7, ''   ,'BR',0,'C',0);
                // $this->pdf->Cell(20, 7, ''   ,'BR',0,'L',0);
                // $this->pdf->Cell(39, 7, ''   ,'BR',0,'L',0);
              $ONE    =  '';
              $TWO    =  '';
              $THREE  =  '';
              }elseif ($number_count == number_format($ROW_SPAN/2)) {
                // $this->pdf->Cell(8,  7, $index+1               ,'R',0,'C',0);
                // $this->pdf->Cell(20, 7, $sub_row->MACHINE_CODE ,'R',0,'L',0);
                // $this->pdf->Cell(39, 7, iconv('UTF-8', 'cp874', $sub_row->MACHINE_NAME_TH)  ,'R',0,'L',0);
                $ONE    =  $index+1;
                $TWO    =  $sub_row->MACHINE_CODE;
                $THREE  =  iconv('UTF-8', 'cp874', $sub_row->MACHINE_NAME_TH);
              }else {
                // $this->pdf->Cell(8,  7, ''  ,'R',0,'C',0);
                // $this->pdf->Cell(20, 7, ''  ,'R',0,'L',0);
                // $this->pdf->Cell(39, 7, ''  ,'R',0,'L',0);
                $ONE    =  '';
                $TWO    =  '';
                $THREE  =  '';
              }

              // $this->pdf->Cell(60, 7, iconv('UTF-8', 'cp874', $NUMBER_SUBSELECT_NAME++ .'. '.$sub_row->REPAIR_SUBSELECT_NAME)  ,1,0,'L',0);
              // $this->pdf->Cell(65, 7, iconv('UTF-8', 'cp874', $NUMBER_REPAIR_DETAIL++ .'. '.$sub_row->REPAIR_DETAIL)              ,1,0,'L',0);
              // $this->pdf->Cell(30, 7, iconv('UTF-8', 'cp874', 'ผู้ดำเนินการ')          ,1,0,'L',0);
              // $this->pdf->Cell(30, 7, $DOWNTIME     ,1,0,'C',0);
               $FOUR  = iconv('UTF-8', 'cp874', $NUMBER_SUBSELECT_NAME++ .'. '.$sub_row->REPAIR_SUBSELECT_NAME);
               $FIVE  = iconv('UTF-8', 'cp874', $NUMBER_REPAIR_DETAIL++ .'. '.$sub_row->REPAIR_DETAIL);
               $SIX   = iconv('UTF-8', 'cp874', 'ผู้ดำเนินการ');
               $SEVEN = $DOWNTIME;
              if ($number_count == $ROW_SPAN && $number_count == number_format($ROW_SPAN/2)) {
                // $this->pdf->Cell(35, 7, $row->DOWNTIME ,'BR',1,'C',0);
                $eight = $row->DOWNTIME;
              }elseif ($number_count == $ROW_SPAN) {
                // $this->pdf->Cell(35, 7, '' ,'BR',1,'C',0);
                $eight = '';
              }elseif ($number_count == number_format($ROW_SPAN/2)) {
                // $this->pdf->Cell(35, 7, $row->DOWNTIME ,'R',1,'C',0);
                $eight = $row->DOWNTIME;
              }else {
                // $this->pdf->Cell(35, 7, '' ,'R',1,'C',0);
                $eight = '';
              }
              $this->pdf->Row(array($ONE,$TWO,$THREE,$FOUR,$FIVE,$SIX,$SEVEN,$eight));
              if ($GET_Y > 193) {
                $this->pdf->AddPage(['L','A4',]);
                $this->pdf->Rect(5,5,287,193);
                $this->pdf->header($TYPE);
                $this->pdf->setX(5);
              }

          }
      }
    }

    $this->pdf->Output();
    exit;
  }
}
