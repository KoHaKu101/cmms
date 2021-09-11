<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Machine\Machine;
use App\Models\Machine\MachineRepair;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Codedge\Fpdf\Fpdf\Fpdf;
use Auth;




// class MachineSystemCheckPDFController extends Controller
class MachineSystemCheckPDFController extends Fpdf
{


  public function SystemCheckPdf($UNID)
    {

      // $dataset = MachineRepair::leftJoin('PMCS_MACHINE','PMCS_MACHINE.MACHINE_CODE','PMCS_REPAIR_MACHINE.MACHINE_CODE')
      //                           ->where('PMCS_REPAIR_MACHINE.UNID',$UNID)->first();
     //add font
     $pdf = new FPDF();
     $pdf->AddFont('THSarabunNew','','THSarabunNew.php');
     $pdf->AddFont('THSarabunNew','B','THSarabunNew_b.php');
     //หน้ากระดาษ
     $pdf->SetFont('Arial','B',16);

     $pdf->AddPage('L','A5');
     //data header
     $pdf->SetFont('THSarabunNew','',13 );
      // $pdf->Text(158.5,16,iconv('UTF-8', 'cp874', $dataset->MACHINE_DOCNO  ));
      // $pdf->Text(158.5,22,iconv('UTF-8', 'cp874', $dataset->MACHINE_DOCDATE  ));
      // $pdf->Text(158.5,28,iconv('UTF-8', 'cp874', $dataset->MACHINE_TIME  ));
     //ช่องกรอกข้อมูล 1
     $pdf->SetFont('THSarabunNew','',13);
      $pdf->SetTitle('รายการตรวจเช็คเครื่องจักร','isUTF8');
      $pdf->Cell(190, 37, iconv('UTF-8', 'cp874', ''),1,0,'C');
      $pdf->Text(15,43,iconv('UTF-8', 'cp874','หมายเลขเครื่องจักร :'));
      $pdf->Text(100,43,iconv('UTF-8', 'cp874','ชื่อเครื่องจักร :'));
      $pdf->Text(23.6,48,iconv('UTF-8', 'cp874','สถานที่ติดตั้ง :'));
      $pdf->Text(25,53,iconv('UTF-8', 'cp874','อาการที่เสีย :'));
     //data

     //line 1
     $pdf->SetFont('THSarabunNew','B',13);
      $pdf->Text(45,43,'________________________________');//หมายเลขเครื่องจักร
      $pdf->Text(120,43,'___________________________________');//ชื่อเครื่องจักร
      $pdf->Text(45,48,'_______________________________________________________________________________');//สถานที่ติดตั้ง
      $pdf->Text(45,53,'_______________________________________________________________________________');//อาการที่เสีย
      $pdf->Text(45,58,'_______________________________________________________________________________');//อาการที่เสีย
     //ช่องเซ็น
     $pdf->SetFont('THSarabunNew','',13);
      $pdf->SetY(61);
      $pdf->SetX(22);
      $pdf->Cell(40, 5.8  , iconv('UTF-8', 'cp874', 'ผู้แจ้งซ่อม'),1,0,'C');
      $pdf->Cell(40, 5.8  , iconv('UTF-8', 'cp874', 'ผู้รับแจ้งซ่อม'),1,0,'C');
      $pdf->Cell(80, 5.8  , iconv('UTF-8', 'cp874', 'วัน / เวลาที่จะตรวจสอบเบื้องต้น'),1,0,'C');
      $pdf->Ln(6);
     $pdf->SetX(22);
      $pdf->Cell(40, 5.8  , iconv('UTF-8', 'cp874', ''),1,0,'C');//ผู้แจ้งซ่อม
      $pdf->Cell(40, 5.8  , iconv('UTF-8', 'cp874', ''),1,0,'C');//ผู้รับแจ้งซ่อม
      $pdf->Cell(80, 5.8  , iconv('UTF-8', 'cp874', ''),1,0,'C');//วัน / เวลาที่จะตรวจสอบเบื้องต้น
      $pdf->Text(103,72,iconv('UTF-8', 'cp874','วันที่ :'));
      $pdf->Text(143,72,iconv('UTF-8', 'cp874','เวลา :'));
      $pdf->Text(111,72,'....................................');//วัน
      $pdf->Text(151,72,'....................................');//เวลาที่จะตรวจสอบเบื้องต้น
     $pdf->Ln(8);

     //ช่องกรอกข้อมูล 2
     $pdf->SetFont('THSarabunNew','b',14);
     $pdf->Cell(190, 6, iconv('UTF-8', 'cp874', 'ผลการตรวจสอบเบื้องต้น'),1,0,'C');
     $pdf->Ln(6);

     $pdf->SetFont('THSarabunNew','b',14);
      $pdf->Cell(190, 26, iconv('UTF-8', 'cp874', ''),1,0,'C');
      $pdf->SetFont('THSarabunNew','',13);
      $pdf->Text(15,87,iconv('UTF-8', 'cp874','รายละเอียดสาเหตุ : '));
      $pdf->Text(14.6,97,iconv('UTF-8', 'cp874','ดำเนินการซ่อมโดย : '));
      $pdf->Rect(42,94,4,4,'');
      $pdf->Text(50,97,iconv('UTF-8', 'cp874','ช่างซ่อมภายนอก / จ้างซ่อม'));
      $pdf->Rect(102,94,4,4,'');
      $pdf->Text(110,97,iconv('UTF-8', 'cp874','ช่างซ่อมภายในบริษัท'));
      $pdf->Text(15,104, iconv('UTF-8', 'cp874', 'วัน / เวลาที่จะดำเนินการซ่อม : '),1,0,'C');
      $pdf->Text(58,104,iconv('UTF-8', 'cp874','วันที่ '));
      $pdf->Text(110,104,iconv('UTF-8', 'cp874','เวลา '));
      $pdf->SetY(83);
      $pdf->SetX(39);
      $pdf->MultiCell(145,5,'',0,0,"",false);
      $pdf->SetFont('THSarabunNew','B',13);
     //line 2
     $pdf->Text(64,104,'_________________________');//วันที่
      $pdf->Text(117,104,'________________________');//เวลา
      $pdf->Text(40,87,'____________________________________________________________________________________');//รายละเอียดสาเหตุ
      $pdf->Text(40,92,'____________________________________________________________________________________');//รายละเอียดสาเหตุ
     $pdf->Ln(19);

     $pdf->SetFont('THSarabunNew','b',14);
      $pdf->Cell(110, 20, iconv('UTF-8', 'cp874', ''),1,0,'C');
      $pdf->Text(15,111.2,iconv('UTF-8', 'cp874','รายละเอียดสาเหตุ'));
      $pdf->SetFont('THSarabunNew','',13);
      $pdf->Rect(25,113,4,4,'');
      $pdf->Text(38,116,iconv('UTF-8', 'cp874','สามารถใช้งานได้ตามปกติ '));
      $pdf->Rect(25,118,4,4,'');
      $pdf->Text(38,120.8,iconv('UTF-8', 'cp874','ไม่สามารถใช้งานได้ตามปกติ '));
      $pdf->Text(40,125.2,iconv('UTF-8', 'cp874','เพราะ : '));
      $pdf->Text(50,125,iconv('UTF-8', 'cp874',' '));
      $pdf->SetFont('THSarabunNew','b',14);
      $pdf->Text(50,125,'___________________________');
      //ช่างซ่อมบำรุง
     $pdf->SetFont('THSarabunNew','b',14);
      $pdf->Cell(80, 10, iconv('UTF-8', 'cp874', ''),1,0,'C');
      $pdf->Text(150,111.2,iconv('UTF-8', 'cp874','ช่างซ่อมบำรุง'));
      $pdf->Text(120,111.8,'___________________________________________');
     $pdf->Ln(10);

     $pdf->SetX(120);
     $pdf->SetFont('THSarabunNew','',13);
     $pdf->Cell(80, 10, iconv('UTF-8', 'cp874', ''),1,0,'C');
     $pdf->Text(122,121,iconv('UTF-8', 'cp874','วันที่ : '));
     $pdf->Text(130,120.2,iconv('UTF-8', 'cp874',''));
     $pdf->Text(130,121,'....................................................................');
     $pdf->Text(122,125.2,iconv('UTF-8', 'cp874','เวลา : '));
     $pdf->Text(130,125,iconv('UTF-8', 'cp874',''));
     $pdf->Text(130,125.5,'....................................................................');
     $pdf->Output();

     exit;

  }
}
