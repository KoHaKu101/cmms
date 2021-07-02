<?php

namespace App\Http\Controllers\PDF\HeaderFooterPDF;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Machine\Machine;
use Codedge\Fpdf\Fpdf\Fpdf;

class HistoryHeaderFooter extends Fpdf
{
  private $MACHINE_UNID;
  function Header($UNID = NULL)
{
    if ($UNID) {
      $this->MACHINE_UNID = $UNID;
      $dataset = Machine::select('*')->selectraw('dbo.decode_utf8(MACHINE_NAME) as MACHINE_NAME_TH')->where('UNID','=',$this->MACHINE_UNID)->first();

    //head ********************************************************************
    $logo = "assets/img/logo13.jpg";
    $this->AddFont('THSarabunNew','','THSarabunNew.php');
    $this->AddFont('THSarabunNew','B','THSarabunNew_b.php');
    $this->SetFont('THSarabunNew','',18 );
    $this->text(278, 12, iconv('UTF-8', 'cp874', 'หน้า'));
    $this->text(278, 20, iconv('UTF-8', 'cp874', $this->PageNo().'/{nb}'));
    $this->setXY(5,5);
      // Logo
      $this->Cell(20,18,$this->Image($logo,6,6,18),1,0,'C',false);
      // header
      $this->SetFont('THSarabunNew','b',20);
        $this->Cell(248, 18, iconv('UTF-8', 'cp874', 'รายงาน ใบประวัติการซ่อมเครื่องจักร   '),1,0,'C');
        $this->Cell(19, 18, iconv('UTF-8', 'cp874', ''),1,1,'C',false);
        $this->SetFont('THSarabunNew','',12);
      $this->setY(25);
        $this->Cell(22, 6, iconv('UTF-8', 'cp874', 'Machine No : '),0,0,'R',false);
        $this->Cell(35, 6, iconv('UTF-8', 'cp874', $dataset->MACHINE_CODE),'B',0,'L',false);
        $this->setX(75);
          $this->Cell(27, 6, iconv('UTF-8', 'cp874', 'Machine Name : '),0,0,'R',false);
          $this->Cell(50, 6, iconv('UTF-8', 'cp874', $dataset->MACHINE_NAME_TH),'B',0,'L',false);
        $this->setX(160);
          $this->Cell(23, 6, iconv('UTF-8', 'cp874', 'Addmit Date : '),0,0,'R',false);
          $this->Cell(23, 6, iconv('UTF-8', 'cp874', date('d-m-Y',strtotime($dataset->MACHINE_STARTDATE))),'B',0,'L',false);
        $this->setX(215);
          $this->Cell(16, 6, iconv('UTF-8', 'cp874', 'Location : '),0,0,'L',false);
          $this->Cell(35, 6, iconv('UTF-8', 'cp874', $dataset->MACHINE_LINE),'B',0,'L',false);
      $this->setY(31);
        $this->Cell(22, 6, iconv('UTF-8', 'cp874', 'Model : '),0,0,'R',false);
        $this->Cell(35, 6, iconv('UTF-8', 'cp874', $dataset->MACHINE_MODEL),'B',0,'L',false);
        $this->setX(75);
          $this->Cell(27, 6, iconv('UTF-8', 'cp874', 'Serial : '),0,0,'R',false);
          $this->Cell(50, 6, iconv('UTF-8', 'cp874', $dataset->MACHINE_SERIAL),'B',0,'L',false);
        $this->setX(160);
          $this->Cell(23, 6, iconv('UTF-8', 'cp874', 'Supplier : '),0,0,'R',false);
          $this->Cell(83, 6, iconv('UTF-8', 'cp874', $dataset->PURCHASE_FORM),'B',1,'L',false);
          $this->SetFont('THSarabunNew','',10);
      $this->SetFillColor(206,206,206);
      $this->setXY(5,39);
      $this->Cell(10, 5, iconv('UTF-8', 'cp874', 'ครั้งที่'),1,0,'C',1);
      $this->Cell(16, 5, iconv('UTF-8', 'cp874', 'วันที่แจ้ง'),1,0,'C',1);
      $this->Cell(22, 5, iconv('UTF-8', 'cp874', 'เลขที่เอกสาร'),1,0,'C',1);
      $this->Cell(40, 5, iconv('UTF-8', 'cp874', 'อาการเสีย'),1,0,'C',1);
      $this->Cell(40, 5, iconv('UTF-8', 'cp874', 'วิธีการแก้ไข'),1,0,'C',1);
      $this->Cell(16, 5, iconv('UTF-8', 'cp874', 'วันที่ซ่อมเสร็จ'),1,0,'C',1);
      $this->Cell(16, 5, iconv('UTF-8', 'cp874', 'เวลาเครื่องหยุด'),1,0,'C',1);
      $this->Cell(35, 5, iconv('UTF-8', 'cp874', 'เปลี่ยนอะไหล่'),1,0,'C',1);
      $this->Cell(20, 5, iconv('UTF-8', 'cp874', 'ค่าใช้จ่าย'),1,0,'C',1);
      $this->Cell(24, 5, iconv('UTF-8', 'cp874', 'ผู้รายงาน'),1,0,'C',1);
      $this->Cell(24, 5, iconv('UTF-8', 'cp874', 'ผู้ตรวจสอบ'),1,0,'C',1);
      $this->Cell(24, 5, iconv('UTF-8', 'cp874', 'ผู้อนุมัติ'),1,0,'C',1);

      //page NO
      $this->SetFont('THSarabunNew','',14 );

        $this->Ln(0);

      //data header
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
  function MultiCellRow($cells, $width, $height, $data, $pdf)
{
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $maxheight = 0;

    for ($i = 0; $i < $cells; $i++) {
        $pdf->MultiCell($width, $height, $data[$i]);
        if ($pdf->GetY() - $y > $maxheight) $maxheight = $pdf->GetY() - $y;
        $pdf->SetXY($x + ($width * ($i + 1)), $y);
    }

    for ($i = 0; $i < $cells + 1; $i++) {
        $pdf->Line($x + $width * $i, $y, $x + $width * $i, $y + $maxheight);
    }

    $pdf->Line($x, $y, $x + $width * $cells, $y);
    $pdf->Line($x, $y + $maxheight, $x + $width * $cells, $y + $maxheight);
}

}
