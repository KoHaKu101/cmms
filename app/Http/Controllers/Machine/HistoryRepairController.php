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
use App\Models\Machine\RepairWorker;
use App\Models\Machine\RepairSparepart;
use App\Models\Machine\HistoryRepair;

use App\Http\Controllers\PDF\HeaderFooterPDF\HistoryHeaderFooter as HistoryHeaderFooter;
//************** Package form github ***************

class HistoryRepairController extends Controller
{
  public function __construct(HistoryHeaderFooter $HistoryHeaderFooter){
    $this->middleware('auth');
    $this->pdf = $HistoryHeaderFooter;
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
    $DATA_REPAIR_HEADER = HistoryRepair::select('MACHINE_UNID','MACHINE_CODE','MACHINE_NAME')
                                            ->groupBy('MACHINE_UNID','MACHINE_CODE','MACHINE_NAME')
                                            ->orderBy('MACHINE_CODE')->get();
    $DATA_REPAIR = HistoryRepair::select('*')->selectraw('dbo.decode_utf8(INSPECTION_BY) as INSPECTION_BY_TH')
                                   ->orderBy('MACHINE_CODE')->get();
    $DATA_SPAREPART = RepairSparepart::orderBy('SPAREPART_NAME')->get();
    return view('machine.history.list',compact('DATA_REPAIR','DATA_REPAIR_HEADER','DATA_SPAREPART'));
  }
  public function RepairPDF($MACHINE_UNID){
    $DATA_HISTORY_REPAIR = HistoryRepair::select('*')->selectraw(
                            'dbo.decode_utf8(INSPECTION_BY) as INSPECTION_BY_TH,
                             dbo.decode_utf8(REPAIR_BY)     as REPAIR_BY_TH,
                             dbo.decode_utf8(APPROVED_BY)   as APPROVED_BY_TH '
                            )->where('MACHINE_UNID','=',$MACHINE_UNID)->orderBy('DOC_DATE')->get();
    $DATA_REPAIR_SPAREPART = RepairSparepart::orderBy('SPAREPART_NAME')->get();
    $this->pdf->AddFont('THSarabunNew','','THSarabunNew.php');
    $this->pdf->AddFont('THSarabunNew','B','THSarabunNew_b.php');
    $this->pdf->SetFont('THSarabunNew','',14 );
    $this->pdf->AliasNbPages();
    $this->pdf->AddPage(['L','A4',]);
    $this->pdf->header($MACHINE_UNID);
    $this->pdf->Rect(5,5,287,194);
    $this->pdf->SetFont('THSarabunNew','',10);
    $this->pdf->setXY(5,44);
    foreach ($DATA_HISTORY_REPAIR as $index => $row) {
      $Y= $this->pdf->getY();
      $this->pdf->Cell(10, 5, iconv('UTF-8', 'cp874', $index+1),1,0,'C',0);
      $this->pdf->Cell(16, 5, iconv('UTF-8', 'cp874', date('d-m-Y',strtotime($row->DOC_DATE)) ),1,0,'C',0);
      $this->pdf->Cell(22, 5, iconv('UTF-8', 'cp874', $row->DOC_NO),1,0,'L',0);
      $this->pdf->Cell(40, 5, iconv('UTF-8', 'cp874', $row->REPAIR_REQ_DETAIL),1,0,'L',0);
      $this->pdf->Cell(40, 5, iconv('UTF-8', 'cp874', $row->REPAIR_DETAIL),1,0,'L',0);
      $this->pdf->Cell(16, 5, iconv('UTF-8', 'cp874', date('d-m-Y',strtotime($row->REPAIR_DATE)) ),1,0,'C',0);
      $this->pdf->Cell(16, 5, iconv('UTF-8', 'cp874', number_format($row->DOWN_TIME).' นาที'),1,0,'R',0);
      $X= $this->pdf->getX();
      $this->pdf->MultiCell(35, 5, iconv('UTF-8', 'cp874',"adasd\r\nasdasd\r\nasdasd"),1);
      $this->pdf->setXY($X+35,$Y);

      $height= $this->pdf->MultiCellRow(1,35,5,[$X."adasd\r\nasdasd\r\nasdasd"],$this->pdf);
      $X= $this->pdf->getX();
      $this->pdf->setXY($X,$Y);
      $X= $this->pdf->getX();

      $this->pdf->MultiCell(35, 5, iconv('UTF-8', 'cp874', $X."adasd"),1);
      $this->pdf->setXY($X+35,$Y);
      $this->pdf->Cell(20, 5, iconv('UTF-8', 'cp874', number_format($row->TOTAL_COST).' บาท'),1,0,'R',0);
      $this->pdf->Cell(24, 5, iconv('UTF-8', 'cp874', $row->INSPECTION_BY_TH),1,0,'L',0);
      $this->pdf->Cell(24, 5, iconv('UTF-8', 'cp874', $row->REPAIR_BY_TH),1,0,'L',0);
      $this->pdf->Cell(24, 5, iconv('UTF-8', 'cp874', $row->APPROVED_BY_TH),1,0,'L',0);
    }

    $this->pdf->Output();
  }
}
