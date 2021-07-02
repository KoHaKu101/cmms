<?php

namespace App\Http\Controllers\Plan\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Machine\MachinePlanPm;
use App\Models\Machine\Pmplanresult;
use App\Models\Machine\MasterIMPSGroup;
use App\Models\MachineAddTable\MachinePmTemplateDetail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Codedge\Fpdf\Fpdf\Fpdf;
use Auth;
use App\Models\PDF\PDFPlanForm as planpdf ;



// class MachineSystemCheckPDFController extends Controller
class FormPMMachine extends Controller
{
  protected $pdf;
  protected $title;
  public function __construct(planpdf $PlanheaderFooter){
    $this->middleware('auth');
      $this->pdf = $PlanheaderFooter;
  }

  public function PDFForm(Request $request,$UNID = NULL)
    {

      $PM_PLAN = MachinePlanPm::where('UNID',$UNID)->first();
      $PM_PLAN_RESULT_FIRST   = Pmplanresult::where('PM_PLAN_UNID',$UNID)->first();
      $PM_PLAN_RESULT_CHECKTYPE = MachinePmTemplateDetail::first();
      $PM_PLAN_RESULT_LOOP    = Pmplanresult::select('PM_MASTER_LIST_UNID','PM_MASTER_LIST_NAME','PM_MASTER_LIST_INDEX')
                                         ->where('PM_PLAN_UNID',$UNID)
                                         ->groupBy('PM_MASTER_LIST_NAME')
                                         ->groupBy('PM_MASTER_LIST_INDEX')
                                         ->groupBy('PM_MASTER_LIST_UNID')
                                         ->orderBy('PM_MASTER_LIST_INDEX','ASC')
                                         ->get();
      $PM_PLAN_RESULT_LOOPSUB = Pmplanresult::where('PM_PLAN_UNID',$UNID)
                                            ->get();
     //add font
     $checkpass = "assets/img/pass.png";
     $checkfail = "assets/img/fail.png";
     $this->pdf->AddFont('THSarabunNew','','THSarabunNew.php');
     $this->pdf->AddFont('THSarabunNew','B','THSarabunNew_b.php');
     $this->pdf->SetFont('Arial','B',16);
     //หน้ากระดาษ
     $this->pdf->AddPage('P','A4');
     $this->pdf->header($UNID);

     $this->pdf->AliasNbPages();

     $this->pdf->SetFont('THSarabunNew','B',14 );
     $rowheight = 5;
     foreach ($PM_PLAN_RESULT_LOOP as $index => $dataset) {
       $i = 1;
       $this->pdf->SetFont('THSarabunNew','B',12 );
       $this->pdf->setFillColor(200,200,200);
       $this->pdf->Cell(110,$rowheight, iconv('UTF-8', 'cp874',$dataset->PM_MASTER_LIST_INDEX.'. '.$dataset->PM_MASTER_LIST_NAME),'LBTR',0,'L',1);
       $this->pdf->Cell(15,$rowheight,iconv('UTF-8', 'cp874', 'เกณฑ์'),'LBTR',0,'C',1);
       $this->pdf->Cell(15,$rowheight,iconv('UTF-8', 'cp874', 'MAX'),'LBTR',0,'C',1);
       $this->pdf->Cell(15,$rowheight,iconv('UTF-8', 'cp874', 'MIN'),'LBTR',0,'C',1);
       $this->pdf->Cell(20,$rowheight,iconv('UTF-8', 'cp874', 'ACTUAL'),'LBTR',0,'C',1);
       $this->pdf->Cell(20,$rowheight,iconv('UTF-8', 'cp874', 'RESULT'),'LBTR',1,'C',1);
       $this->pdf->SetFont('THSarabunNew','B',10 );
    foreach ($PM_PLAN_RESULT_LOOPSUB->where('PM_MASTER_LIST_UNID','',$dataset->PM_MASTER_LIST_UNID) as $index => $dataitem) {
      $this->pdf->Cell(5,$rowheight, '','LB',0,'L',);
      $this->pdf->Cell(105,$rowheight, iconv('UTF-8', 'cp874',$dataset->PM_MASTER_LIST_INDEX.'.'.$dataitem->PM_MASTER_DETAIL_INDEX.'. '.$dataitem->PM_MASTER_DETAIL_NAME),'BR',0,'L',);
      $this->pdf->setFillColor(250,69,69);
        if (strtoupper($dataitem->PM_MASTER_DETAIL_TYPE_INPUT) == "RADIO") {
          $this->pdf->Cell(15,$rowheight,'PASS','BR',0,'C',);
          $this->pdf->Cell(15,$rowheight,'-','BR',0,'C',);
          $this->pdf->Cell(15,$rowheight,'-','BR',0,'C',);
          $this->pdf->SetFont('Arial','B',8 );
          if ($dataitem->PM_MASTER_DETAIL_INPUT == 1) {
            $this->pdf->Cell(20,$rowheight, iconv('UTF-8', 'cp874','PASS'),'BR',0,'R',);
          }else {
            $this->pdf->Cell(20,$rowheight, iconv('UTF-8', 'cp874','FAIL'),'BR',0,'R',);
          }

       }else {
         $this->pdf->Cell(15,$rowheight,iconv('UTF-8', 'cp874',$dataitem->PM_MASTER_DETAIL_VALUE_STD),'BR',0,'C',);
         $this->pdf->Cell(15,$rowheight,(double)$dataitem->PM_MASTER_DETAIL_VALUE_STD_MAX,'BR',0,'C',);
         $this->pdf->Cell(15,$rowheight,(double)$dataitem->PM_MASTER_DETAIL_VALUE_STD_MIN,'BR',0,'C',);
         $this->pdf->SetFont('Arial','B',8 );
         $this->pdf->Cell(20,$rowheight, iconv('UTF-8', 'cp874',$dataitem->PM_MASTER_DETAIL_INPUT),'BR',0,'R',);
       }
       $this->pdf->SetFont('THSarabunNew','B',10 );
       $color = 0;
       if ($dataitem->PM_MASTER_DETAIL_RESULT == "PASS") {
         $result = $this->pdf->Image($checkpass,$this->pdf->GetX()+8, $this->pdf->GetY(),5);
       }elseif ($dataitem->PM_MASTER_DETAIL_RESULT == "FAIL") {
         $result = $this->pdf->Image($checkfail,$this->pdf->GetX()+8, $this->pdf->GetY(),5);
       }else {
         $result = 'Nodata';
         $color = 1 ;
       }
        $this->pdf->Cell(20,5, $result,'1',1,'C',$color);
     }
   }
   // $this->pdf->Cell(67.5,8, iconv('UTF-8', 'cp874',''),'T',0,'C',);
     $this->pdf->Output('I','Plan.pdf');
     exit;

}


}
