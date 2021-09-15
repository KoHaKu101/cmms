<?php

namespace App\Http\Controllers\Machine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Models\Machine\DocItemOutDetail;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Http\Controllers\PDF\HeaderFooterPDF\PRHeader as PRHeader;

class ReportPRController extends Controller
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
  public function PrintDoc(Request $request){
  $UNID = $request->UNID;
  $DATA_ITEMOUT_DETAIL = DocItemOutDetail::where('DOC_ITEMOUT_UNID','=',$UNID)->get();

  $this->pdf   = new PRHeader('L','cm',array('13.97','24'));
  $this->pdf->AddFont('THSarabunNew','','THSarabunNew.php');
  $this->pdf->AddFont('THSarabunNew','B','THSarabunNew_b.php');
  $this->pdf->SetFont('THSarabunNew','',14 );
  for ($i=1; $i < 3; $i++) {
    $TEXT_HEADER = $i == 1 ? 'ต้นฉบับ' : 'สำเนา';
    $this->pdf->AddPage();
    $this->pdf->header($UNID);
    $this->pdf->SetFont('THSarabunNew','b',16);
    $this->pdf->text(21.2 ,1.15,iconv('UTF-8', 'cp874',$TEXT_HEADER));
    $this->pdf->setY(4.6);
    foreach ($DATA_ITEMOUT_DETAIL as $key => $row) {
      $this->pdf->SetFont('THSarabunNew','',14 );
        $BORDER_TABLE = $this->pdf->getY() == '9.1' ? 0 : 1;
        $TEXT_UNIT    = $row->SPAREPART_UNIT != '' ? $row->SPAREPART_UNIT : '-';
        $this->pdf->setx(0.8);
        $this->pdf->Cell(1,   0.5, $key+1,$BORDER_TABLE,0,'C');
        $this->pdf->Cell(3,   0.5, iconv('UTF-8', 'cp874', '-'),$BORDER_TABLE,0,'C');
        $this->pdf->Cell(12,  0.5, iconv('UTF-8', 'cp874', $row->SPAREPART_NAME.' '.$row->MACHINE_CODE.' '.$row->NOTE),$BORDER_TABLE,0,'L');
        $this->pdf->Cell(1.5, 0.5, iconv('UTF-8', 'cp874', number_format($row->TOTAL_OUT,2, '.', '')),$BORDER_TABLE,0,'R');
        $this->pdf->Cell(2,   0.5, iconv('UTF-8', 'cp874', $TEXT_UNIT),$BORDER_TABLE,0,'C');
        $this->pdf->Cell(2.5, 0.5, iconv('UTF-8', 'cp874', date('d-m-Y',strtotime($row->DATE_REC))),$BORDER_TABLE,1,'C');
      if ($this->pdf->getY() > '9.1') {
        $this->pdf->AddPage();
        $this->pdf->header($UNID);
        $this->pdf->SetFont('THSarabunNew','b',16);
        $this->pdf->text(20.9 ,1.15,iconv('UTF-8', 'cp874','ต้นฉบับ'));
      }
    }
  }


  $this->pdf->Output('I','Plan.pdf');
  }


}
