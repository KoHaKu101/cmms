<?php

namespace App\Http\Controllers\PDF\HeaderFooterPDF;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Machine\Machine;
use Codedge\Fpdf\Fpdf\Fpdf;

class MachinePDF extends Fpdf
{
  private $MACHINE_LINE;
  function Header($LINE = NULL)
{
    if ($LINE) {
      $this->MACHINE_LINE = $LINE;
      $dataset = Machine::where('MACHINE_LINE','=',$this->MACHINE_LINE)->count();

    //head ********************************************************************
    $logo = "assets/img/logo13.jpg";
    $this->AddFont('THSarabunNew','','THSarabunNew.php');
    $this->AddFont('THSarabunNew','B','THSarabunNew_b.php');
    $this->SetFont('THSarabunNew','',14 );
      // Logo
      $this->Cell(26,22,$this->Image($logo,12,11,22),1,0,'C',false);
      // header
      $this->SetFont('THSarabunNew','b',20);
        $this->Cell(124, 22, iconv('UTF-8', 'cp874', 'เครื่องจักร LINE : '.$this->MACHINE_LINE),1,0,'C');
      $this->SetFont('THSarabunNew','',13 );
        $this->MultiCell(44, 22, iconv('UTF-8', 'cp874', ''),1,0,'',false);
        $this->Text(163.9,18,iconv('UTF-8', 'cp874', 'จำนวนหน้า :'));
        $this->Text(162,27,iconv('UTF-8', 'cp874', 'จำนวนเครื่อง :'));
      //page NO
      $this->SetFont('THSarabunNew','B',14 );
        $this->Text(180.5,18,iconv('UTF-8', 'cp874', '___________'));
        $this->Text(180.5,27,iconv('UTF-8', 'cp874', '___________'));
        $this->Ln(0);
        $this->Text(185,18,iconv('UTF-8', 'cp874', $this->PageNo().'/{nb}'   ));
        $this->Text(185,27,iconv('UTF-8', 'cp874', $dataset));
      //data header
      $this->SetFont('THSarabunNew','B',13);
       $this->Cell(16,8,iconv('UTF-8', 'cp874', 'MC-CODE'),1,0,'C');
       $this->Cell(34,8,iconv('UTF-8', 'cp874', 'MC-NAME'),1,0,'C');
       $this->Cell(10,8,iconv('UTF-8', 'cp874', 'Line'),1,0,'C');
       $this->Cell(25,8,iconv('UTF-8', 'cp874', 'สถานะเครื่องจักร'),1,0,'C');
       $this->Cell(22,8,iconv('UTF-8', 'cp874', 'วันที่เริ่มใช้งาน'),1,0,'C');
       $this->Cell(53,8,iconv('UTF-8', 'cp874', 'ซื้อจากบริษัท'),1,0,'C');
       $this->Cell(34,8,iconv('UTF-8', 'cp874', 'ชนิดเครื่องจักร'),1,0,'C');
       $this->Ln();
     }
    //end head ****************************************************************

}
// Page footer
function Footer()
  {
    $this->AddFont('THSarabunNew','','THSarabunNew.php');
    $this->AddFont('THSarabunNew','B','THSarabunNew_b.php');
    $this->SetY(-20);
    // footer
    $this->SetX(15);


  }

}
