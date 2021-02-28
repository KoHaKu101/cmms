<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;



class MachineRepairPDFController extends Controller
{
  protected $pdf;

  public function __construct(\App\Models\PDF\Pdf $pdf){

    $this->middleware('auth');

      $this->fpdf = $pdf;

  }


  public function HtmlToPDF()
  {
     //add font
     $this->fpdf->AddFont('THSarabunNew','','THSarabunNew.php');
     $this->fpdf->AddFont('THSarabunNew','B','THSarabunNew_b.php');
     //หน้ากระดาษ
     $this->fpdf->SetFont('Arial','B',16);
     $this->fpdf->AddPage('L','A5');
     //data header
     $this->fpdf->SetFont('THSarabunNew','',13 );
      $this->fpdf->Text(158.5,16,iconv('UTF-8', 'cp874', 'RE6312-0033'));
      $this->fpdf->Text(158.5,22,iconv('UTF-8', 'cp874', Carbon::now()->format('Y-m-d')));
      $this->fpdf->Text(158.5,28,iconv('UTF-8', 'cp874', Carbon::now()->format('H:i:s')));
     //ช่องกรอกข้อมูล 1
     $this->fpdf->SetFont('THSarabunNew','',13);
      $this->fpdf->Cell(190, 37, iconv('UTF-8', 'cp874', ''),1,0,'C');
      $this->fpdf->Text(15,43,iconv('UTF-8', 'cp874','หมายเลขเครื่องจักร :'));
      $this->fpdf->Text(100,43,iconv('UTF-8', 'cp874','ชื่อเครื่องจักร :'));
      $this->fpdf->Text(23.6,48,iconv('UTF-8', 'cp874','สถานที่ติดตั้ง :'));
      $this->fpdf->Text(25,53,iconv('UTF-8', 'cp874','อาการที่เสีย :'));
     //data
     $this->fpdf->Text(45,43,'MC-001');//หมายเลขเครื่องจักร
      $this->fpdf->Text(120,43,'TAKAMAZ-Oi-TC');//ชื่อเครื่องจักร
      $this->fpdf->Text(45,48,'L6');//สถานที่ติดตั้ง
      $this->fpdf->SetY(49.2);
      $this->fpdf->SetX(44.5);
      $this->fpdf->MultiCell(132,5,'TURRET ALARM ',0,0,"",false);//อาการที่เสีย
     //line 1
     $this->fpdf->SetFont('THSarabunNew','B',13);
      $this->fpdf->Text(45,43,'________________________________');//หมายเลขเครื่องจักร
      $this->fpdf->Text(120,43,'___________________________________');//ชื่อเครื่องจักร
      $this->fpdf->Text(45,48,'_______________________________________________________________________________');//สถานที่ติดตั้ง
      $this->fpdf->Text(45,53,'_______________________________________________________________________________');//อาการที่เสีย
      $this->fpdf->Text(45,58,'_______________________________________________________________________________');//อาการที่เสีย
     //ช่องเซ็น
     $this->fpdf->SetFont('THSarabunNew','',13);
      $this->fpdf->SetY(61);
      $this->fpdf->SetX(22);
      $this->fpdf->Cell(40, 5.8  , iconv('UTF-8', 'cp874', 'ผู้แจ้งซ่อม'),1,0,'C');
      $this->fpdf->Cell(40, 5.8  , iconv('UTF-8', 'cp874', 'ผู้รับแจ้งซ่อม'),1,0,'C');
      $this->fpdf->Cell(80, 5.8  , iconv('UTF-8', 'cp874', 'วัน / เวลาที่จะตรวจสอบเบื้องต้น'),1,0,'C');
      $this->fpdf->Ln(6);
     $this->fpdf->SetX(22);
      $this->fpdf->Cell(40, 5.8  , iconv('UTF-8', 'cp874', ''),1,0,'C');//ผู้แจ้งซ่อม
      $this->fpdf->Cell(40, 5.8  , iconv('UTF-8', 'cp874', ''),1,0,'C');//ผู้รับแจ้งซ่อม
      $this->fpdf->Cell(80, 5.8  , iconv('UTF-8', 'cp874', ''),1,0,'C');//วัน / เวลาที่จะตรวจสอบเบื้องต้น
      $this->fpdf->Text(103,72,iconv('UTF-8', 'cp874','วันที่ :'));
      $this->fpdf->Text(143,72,iconv('UTF-8', 'cp874','เวลา :'));
      $this->fpdf->Text(111,72,'....................................');//วัน
      $this->fpdf->Text(151,72,'....................................');//เวลาที่จะตรวจสอบเบื้องต้น
     $this->fpdf->Ln(8);

     //ช่องกรอกข้อมูล 2
     $this->fpdf->SetFont('THSarabunNew','b',14);
     $this->fpdf->Cell(190, 6, iconv('UTF-8', 'cp874', 'ผลการตรวจสอบเบื้องต้น'),1,0,'C');
     $this->fpdf->Ln(6);

     $this->fpdf->SetFont('THSarabunNew','b',14);
      $this->fpdf->Cell(190, 26, iconv('UTF-8', 'cp874', ''),1,0,'C');
      $this->fpdf->SetFont('THSarabunNew','',13);
      $this->fpdf->Text(15,87,iconv('UTF-8', 'cp874','รายละเอียดสาเหตุ : '));
      $this->fpdf->Text(14.6,97,iconv('UTF-8', 'cp874','ดำเนินการซ่อมโดย : '));
      $this->fpdf->Rect(42,94,4,4,'');
      $this->fpdf->Text(50,97,iconv('UTF-8', 'cp874','ช่างซ่อมภายนอก / จ้างซ่อม'));
      $this->fpdf->Rect(102,94,4,4,'');
      $this->fpdf->Text(110,97,iconv('UTF-8', 'cp874','ช่างซ่อมภายในบริษัท'));
      $this->fpdf->Text(15,104, iconv('UTF-8', 'cp874', 'วัน / เวลาที่จะดำเนินการซ่อม : '),1,0,'C');
      $this->fpdf->Text(58,104,iconv('UTF-8', 'cp874','วันที่ '));
      $this->fpdf->Text(110,104,iconv('UTF-8', 'cp874','เวลา '));
      $this->fpdf->SetY(83);
      $this->fpdf->SetX(39);
      $this->fpdf->MultiCell(145,5,'',0,0,"",false);
      $this->fpdf->SetFont('THSarabunNew','B',13);
     //line 2
     $this->fpdf->Text(64,104,'_________________________');//วันที่
      $this->fpdf->Text(117,104,'________________________');//เวลา
      $this->fpdf->Text(40,87,'____________________________________________________________________________________');//รายละเอียดสาเหตุ
      $this->fpdf->Text(40,92,'____________________________________________________________________________________');//รายละเอียดสาเหตุ
     $this->fpdf->Ln(19);

     $this->fpdf->SetFont('THSarabunNew','b',14);
      $this->fpdf->Cell(110, 20, iconv('UTF-8', 'cp874', ''),1,0,'C');
      $this->fpdf->Text(15,111.2,iconv('UTF-8', 'cp874','รายละเอียดสาเหตุ'));
      $this->fpdf->SetFont('THSarabunNew','',13);
      $this->fpdf->Rect(25,113,4,4,'');
      $this->fpdf->Text(38,116,iconv('UTF-8', 'cp874','สามารถใช้งานได้ตามปกติ '));
      $this->fpdf->Rect(25,118,4,4,'');
      $this->fpdf->Text(38,120.8,iconv('UTF-8', 'cp874','ไม่สามารถใช้งานได้ตามปกติ '));
      $this->fpdf->Text(40,125.2,iconv('UTF-8', 'cp874','เพราะ : '));
      $this->fpdf->Text(50,125,iconv('UTF-8', 'cp874',Carbon::now()->format('H:i:s')));
      $this->fpdf->SetFont('THSarabunNew','b',14);
      $this->fpdf->Text(50,125,'___________________________');
      //ช่างซ่อมบำรุง
     $this->fpdf->SetFont('THSarabunNew','b',14);
      $this->fpdf->Cell(80, 10, iconv('UTF-8', 'cp874', ''),1,0,'C');
      $this->fpdf->Text(150,111.2,iconv('UTF-8', 'cp874','ช่างซ่อมบำรุง'));
      $this->fpdf->Text(120,111.8,'___________________________________________');
     $this->fpdf->Ln(10);

     $this->fpdf->SetX(120);
     $this->fpdf->SetFont('THSarabunNew','',13);
     $this->fpdf->Cell(80, 10, iconv('UTF-8', 'cp874', ''),1,0,'C');
     $this->fpdf->Text(122,121,iconv('UTF-8', 'cp874','วันที่ : '));
     $this->fpdf->Text(130,120.2,iconv('UTF-8', 'cp874',Carbon::now()->format('Y-m-d')));
     $this->fpdf->Text(130,121,'....................................................................');
     $this->fpdf->Text(122,125.2,iconv('UTF-8', 'cp874','เวลา : '));
     $this->fpdf->Text(130,125,iconv('UTF-8', 'cp874',Carbon::now()->format('H:i:s')));
     $this->fpdf->Text(130,125.5,'....................................................................');
     $this->fpdf->Output();

     exit;

  }
}
