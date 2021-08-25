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
    $DATA_REPAIR = MachineRepairREQ::where('DOC_YEAR','=',date('Y'))->where('DOC_MONTH','=',date('n'))->orderBy('DOWNTIME','DESC')->get();
    $this->pdf = $DowntimeHeader;
    $this->pdf->AddFont('THSarabunNew','','THSarabunNew.php');
    $this->pdf->AddFont('THSarabunNew','B','THSarabunNew_b.php');
    $this->pdf->SetFont('THSarabunNew','',14 );
    $this->pdf->AliasNbPages();
    $this->pdf->AddPage(['L','A4',]);
    $this->pdf->Rect(5,5,287,200);
    $this->pdf->header($TYPE);


    for ($i=0; $i < 10; $i++) {
      $this->pdf->setX(5);
      $this->pdf->Cell(8,  7, iconv('UTF-8', 'cp874', 'No.')               ,1,0,'C',0);
      $this->pdf->Cell(20, 7, iconv('UTF-8', 'cp874', 'MC-CODE')           ,1,0,'C',0);
      $this->pdf->Cell(39, 7, iconv('UTF-8', 'cp874', 'MC-NAME')           ,1,0,'C',0);
      $this->pdf->Cell(50, 7, iconv('UTF-8', 'cp874', 'สาเหตุ / อาการที่เสีย') ,1,0,'C',0);
      $this->pdf->Cell(50, 7, iconv('UTF-8', 'cp874', 'วิธีแก้ไข')           ,1,0,'C',0);
      $this->pdf->Cell(25, 7, iconv('UTF-8', 'cp874', 'ตรวจสอบ (นาที)')    ,1,0,'C',0);
      $this->pdf->Cell(25, 7, iconv('UTF-8', 'cp874', 'ซื้ออะไหล่ (นาที)')    ,1,0,'C',0);
      $this->pdf->Cell(20, 7, iconv('UTF-8', 'cp874', 'ซ่อม (นาที)')        ,1,0,'C',0);
      $this->pdf->Cell(20, 7, iconv('UTF-8', 'cp874', 'รวม (นาที)')         ,1,0,'C',0);
      $this->pdf->Cell(30, 7, iconv('UTF-8', 'cp874', 'ผู้ดำเนินการ')         ,1,1,'C',0);
    }


    $this->pdf->Output();
  }
}
