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
    $DATA_REPAIR = MachineRepairREQ::select('*')->selectraw('dbo.decode_utf8(CLOSE_BY) as CLOSE_BY')->where('DOC_YEAR','=',date('Y'))
                                   ->where('DOC_MONTH','=',date('n'))->where('CLOSE_STATUS','=',1)->orderBy('DOWNTIME','DESC')->get();

    $this->pdf = $DowntimeHeader;
    $this->pdf->AddFont('THSarabunNew','','THSarabunNew.php');
    $this->pdf->AddFont('THSarabunNew','B','THSarabunNew_b.php');
    $this->pdf->SetFont('THSarabunNew','',14 );
    $this->pdf->AliasNbPages();
    $this->pdf->AddPage(['L',['220', '300'],]);
    $this->pdf->setAutoPageBreak(false);
    $this->pdf->Rect(5,5,287,200);
    $this->pdf->header($TYPE);

    if ($TYPE == 'DOWNTIME') {
      dd($DATA_REPAIR,'DOWNTIME');
      $index = 1;
      foreach($DATA_REPAIR as $index => $row) {
        $index = $index + 1;
        $INSPECTION_RESULT_TIME = $row->INSPECTION_RESULT_TIME > 0 ? $row->INSPECTION_RESULT_TIME : '-';
        $SPAREPART_RESULT_TIME  = $row->SPAREPART_RESULT_TIME  > 0 ? $row->SPAREPART_RESULT_TIME : '-';
        $WORK_RESULT_TIME 			= $row->WORKERIN_RESULT_TIME 	 > 0 ? $row->WORKERIN_RESULT_TIME : $row->WORKEROUT_RESULT_TIME;
        $WORK_RESULT_TIME 			= $WORK_RESULT_TIME != 0 ? $WORK_RESULT_TIME : '-';
        $this->pdf->setX(5);
        $GET_Y = $this->pdf->getY();
        $this->pdf->Cell(8,  7, $index                                                ,1,0,'C',0);
        $this->pdf->Cell(20, 7, $row->MACHINE_CODE                                    ,1,0,'L',0);
        $this->pdf->Cell(39, 7, iconv('UTF-8', 'cp874', $row->MACHINE_NAME)           ,1,0,'L',0);
        $this->pdf->Cell(50, 7, iconv('UTF-8', 'cp874', $row->REPAIR_SUBSELECT_NAME)  ,1,0,'L',0);
        $this->pdf->Cell(50, 7, iconv('UTF-8', 'cp874', $row->REPAIR_DETAIL)          ,1,0,'L',0);
        $this->pdf->Cell(25, 7, $INSPECTION_RESULT_TIME                               ,1,0,'C',0);
        $this->pdf->Cell(25, 7, $SPAREPART_RESULT_TIME                                ,1,0,'C',0);
        $this->pdf->Cell(20, 7, $WORK_RESULT_TIME                                     ,1,0,'C',0);
        $this->pdf->Cell(20, 7, $row->DOWNTIME                                        ,1,0,'C',0);
        $this->pdf->Cell(30, 7, iconv('UTF-8', 'cp874', $row->CLOSE_BY)               ,1,1,'L',0);
        if ($GET_Y == 198) {
          $this->pdf->AddFont('THSarabunNew','','THSarabunNew.php');
          $this->pdf->AddFont('THSarabunNew','B','THSarabunNew_b.php');
          $this->pdf->SetFont('THSarabunNew','',14 );
          $this->pdf->AliasNbPages();
          $this->pdf->AddPage(['L','A4',]);
          $this->pdf->Rect(5,5,287,200);
          $this->pdf->header($TYPE);
        }
      }
    }elseif ($TYPE == 'SUMDOWNTIME') {
      dd($DATA_REPAIR,'SUMDOWNTIME');
      $DATA_SUM_DOWNTIME  = MachineRepairREQ::select('MACHINE_CODE','MACHINE_UNID','MACHINE_NAME')->selectraw('SUM(DOWNTIME) as DOWNTIME')->where('DOC_YEAR','=',date('Y'))
                                            ->where('DOC_MONTH','=',date('n'))->where('CLOSE_STATUS','=',1)->groupBy('MACHINE_CODE')
                                            ->groupBy('MACHINE_UNID')->groupBy('MACHINE_NAME')
                                            ->orderBy('DOWNTIME','DESC')->get();

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
              $this->pdf->Cell(8,  7, iconv('UTF-8', 'cp874', $index+1)               ,'BR',0,'C',0);
              $this->pdf->Cell(20, 7, iconv('UTF-8', 'cp874', $sub_row->MACHINE_CODE) ,'BR',0,'L',0);
              $this->pdf->Cell(39, 7, iconv('UTF-8', 'cp874', $sub_row->MACHINE_NAME) ,'BR',0,'L',0);
              $END_DOWNTIME = 1;
            }elseif ($number_count == $ROW_SPAN) {
              $this->pdf->Cell(8,  7, iconv('UTF-8', 'cp874', '')   ,'BR',0,'C',0);
              $this->pdf->Cell(20, 7, iconv('UTF-8', 'cp874', '')   ,'BR',0,'L',0);
              $this->pdf->Cell(39, 7, iconv('UTF-8', 'cp874', '')   ,'BR',0,'L',0);
              $END_DOWNTIME = 2;
            }elseif ($number_count == number_format($ROW_SPAN/2)) {
              $this->pdf->Cell(8,  7, iconv('UTF-8', 'cp874', $index+1)                ,'R',0,'C',0);
              $this->pdf->Cell(20, 7, iconv('UTF-8', 'cp874', $sub_row->MACHINE_CODE)  ,'R',0,'L',0);
              $this->pdf->Cell(39, 7, iconv('UTF-8', 'cp874', $sub_row->MACHINE_NAME)  ,'R',0,'L',0);
              $END_DOWNTIME = 3;
            }else {
              $this->pdf->Cell(8,  7, iconv('UTF-8', 'cp874', '')  ,'R',0,'C',0);
              $this->pdf->Cell(20, 7, iconv('UTF-8', 'cp874', '')  ,'R',0,'L',0);
              $this->pdf->Cell(39, 7, iconv('UTF-8', 'cp874', '')  ,'R',0,'L',0);
              $END_DOWNTIME = 4;
            }

            $this->pdf->Cell(60, 7, iconv('UTF-8', 'cp874', $NUMBER_SUBSELECT_NAME++ .'. '.$sub_row->REPAIR_SUBSELECT_NAME)  ,1,0,'L',0);
            $this->pdf->Cell(65, 7, iconv('UTF-8', 'cp874', $NUMBER_REPAIR_DETAIL++ .'. '.$sub_row->REPAIR_DETAIL)              ,1,0,'L',0);
            $this->pdf->Cell(30, 7, iconv('UTF-8', 'cp874', 'ผู้ดำเนินการ')          ,1,0,'L',0);
            $this->pdf->Cell(30, 7, iconv('UTF-8', 'cp874', $END_DOWNTIME)     ,1,0,'C',0);
            if ($END_DOWNTIME == 1) {
              $this->pdf->Cell(35, 7, iconv('UTF-8', 'cp874', $row->DOWNTIME) ,'BR',1,'C',0);
            }elseif ($END_DOWNTIME == 2) {
              $this->pdf->Cell(35, 7, iconv('UTF-8', 'cp874','') ,'BR',1,'C',0);
            }elseif ($END_DOWNTIME == 3) {
              $this->pdf->Cell(35, 7, iconv('UTF-8', 'cp874', $row->DOWNTIME) ,'R',1,'C',0);
            }else {
              $this->pdf->Cell(35, 7, iconv('UTF-8', 'cp874', '') ,'R',1,'C',0);
            }
            if ($GET_Y == 198) {
              $this->pdf->AddFont('THSarabunNew','','THSarabunNew.php');
              $this->pdf->AddFont('THSarabunNew','B','THSarabunNew_b.php');
              $this->pdf->SetFont('THSarabunNew','',14 );
              $this->pdf->AliasNbPages();
              $this->pdf->AddPage(['L','A4',]);
              $this->pdf->Rect(5,5,287,200);
              $this->pdf->header($TYPE);
            }

        }
      }
    }

    $this->pdf->Output();
  }
}