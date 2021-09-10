<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Machine\Machine;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Http\Controllers\PDF\HeaderFooterPDF\MachinePDF as Machineheaderfooter;



class MachinePDFController extends Controller
{
  protected $pdf;
  protected $LINE;
  public function __construct(Machineheaderfooter $Machineheaderfooter){
    $this->middleware('auth');
      $this->pdf = $Machineheaderfooter;
  }

  public function MachinePDF(Request $request)
    {
      $LINE = $request->LINE;
      $group_LINE = Machine::select('MACHINE_LINE')->where('MACHINE_STATUS','=',9)->groupBy('MACHINE_LINE')->orderBy('MACHINE_LINE')->get();
      
      if ($LINE > 0) {
        $group_LINE = Machine::select('MACHINE_LINE')->where('MACHINE_STATUS','=',9)->where('MACHINE_LINE',$LINE)->groupBy('MACHINE_LINE')->orderBy('MACHINE_LINE')->get();
      }
      // font
      $this->pdf->AddFont('THSarabunNew','','THSarabunNew.php');
      $this->pdf->AddFont('THSarabunNew','B','THSarabunNew_b.php');
      //หน้ากระดาษ
      $this->pdf->SetFont('THSarabunNew','',14 );
        $this->pdf->AliasNbPages();
    //body ********************************************************************
    foreach ($group_LINE as $key => $row_line) {
      $this->pdf->AddPage('P','A4');
      $this->pdf->header($row_line->MACHINE_LINE);

      $dataset = Machine::select('*')->selectraw('dbo.decode_utf8(MACHINE_NAME) as MACHINE_NAME
                                                  ,dbo.decode_utf8(PURCHASE_FORM) as PURCHASE_FORM
                                                  ,dbo.decode_utf8(MACHINE_TYPE) as MACHINE_TYPE')
                                    ->where('MACHINE_LINE','=',$row_line->MACHINE_LINE)
                                    ->get();
        $this->pdf->SetFont('THSarabunNew','',12);
        $limit = 40;
        $i = 1;
          foreach($dataset as $index => $row){
            $this->pdf->Cell(16,6,iconv('UTF-8', 'cp874', $row->MACHINE_CODE),1,0,'');
            $this->pdf->Cell(34,6,iconv('UTF-8//IGNORE', 'cp874//IGNORE', $row->MACHINE_NAME),1,0,'');
            $this->pdf->Cell(10,6,iconv('UTF-8', 'cp874', $row->MACHINE_LINE),1,0,'C');
            $this->pdf->Cell(25,6,iconv('UTF-8', 'cp874', ($row->MACHINE_CHECK == '1' ? 'หยุดทำงาน'
                                          :( $row->MACHINE_CHECK == '2' ? 'เครื่องทำงาน'
                                          :( $row->MACHINE_CHECK == '3' ? 'เครื่องกำลังซ่อม'
                                          :( $row->MACHINE_CHECK == '4' ? 'เครื่องจำหน่าย' : 'สถานะไม่แน่ชัด' ))))
                                  ),1,0,'C');
            $this->pdf->Cell(22,6,iconv('UTF-8', 'cp874', $row->MACHINE_STARTDATE),1,0,'');
            $this->pdf->SetFont('THSarabunNew','',11);
            $this->pdf->Cell(53,6,iconv('UTF-8//IGNORE', 'cp874//IGNORE', $row->PURCHASE_FORM),1,0,'');
            $this->pdf->SetFont('THSarabunNew','',12);
            $this->pdf->Cell(34,6,iconv('UTF-8//IGNORE', 'cp874//IGNORE', $row->MACHINE_TYPE),1,0,'');
            $this->pdf->Ln();
          if ($i == $limit) {
            $limit = $limit+39;
            $this->pdf->AddPage('P','A4');
            $this->pdf->header($row_line->MACHINE_LINE);
          }
        }
    }
    //end body ****************************************************************

    // footer  ****************************************************************

   //end footer  **************************************************************

      $this->pdf->Output();

     exit;

  }
}
