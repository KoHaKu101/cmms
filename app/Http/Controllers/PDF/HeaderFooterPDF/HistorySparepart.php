<?php

namespace App\Http\Controllers\PDF\HeaderFooterPDF;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Machine\SparePart;
use Codedge\Fpdf\Fpdf\Fpdf;

class HistorySparepart extends Fpdf
{
  private $SPAREPART_UNID;
  function Header($UNID = NULL)
{
    if ($UNID) {
      $this->SPAREPART_UNID = $UNID;
      $SPAREPART = SparePart::where('UNID','=',$this->SPAREPART_UNID)->first();

    //head ********************************************************************
    $logo = "assets/img/logo13.jpg";
    $this->AddFont('THSarabunNew','','THSarabunNew.php');
    $this->AddFont('THSarabunNew','B','THSarabunNew_b.php');
    $this->SetFont('THSarabunNew','',14 );
    $this->setY(5);
      // Logo
      $this->Cell(26,11,$this->Image($logo,12,6,22),'LTR',0,'C');
      $this->SetFont('THSarabunNew','B',20 );
      // $this->Cell(140,11,iconv('UTF-8', 'cp874', 'ประวัติการรับเข้า / เบิกจ่าย'),'LTR',0,'C');
      $this->Cell(140,11,iconv('UTF-8', 'cp874', 'STOCK CARD วัสดุสิ้นเปลือง'),'LTR',0,'C');
      $this->Cell(20,11,iconv('UTF-8', 'cp874', 'หน้า'),'LTR',1,'C');
      $this->Cell(26,11,'','LBR',0,'C');
      $this->SetFont('THSarabunNew','',18 );
      $this->Cell(140,11,iconv('UTF-8', 'cp874', $SPAREPART->SPAREPART_NAME),'LBR',0,'C');
      $this->Cell(20,11,$this->PageNo().'/{nb}','LBR',1,'R');
      $this->SetFont('THSarabunNew','',14 );
      $this->Cell(22,11,iconv('UTF-8', 'cp874', 'รหัสสินค้า :'),'LTB',0,'C');
      $this->Cell(40,11,iconv('UTF-8', 'cp874', $SPAREPART->SPAREPART_CODE),'TB',0,'L');
      $this->Cell(12,11,iconv('UTF-8', 'cp874', 'รหัสรุ่น :'),'TB',0,'C');
      $this->Cell(52,11,iconv('UTF-8', 'cp874', $SPAREPART->SPAREPART_MODEL),'TB',0,'L');
      $this->Cell(26,11,iconv('UTF-8', 'cp874', 'SAFETY STOCK : '),'TB',0,'C');
      $this->Cell(34,11,iconv('UTF-8', 'cp874', $SPAREPART->STOCK_MIN),'TBR',1,'L');
      $this->Cell(21,6,'','TRL',0,'C');
      $this->Cell(36,6,iconv('UTF-8', 'cp874', 'รับเข้า'),'TBRL',0,'C');
      $this->Cell(36,6,iconv('UTF-8', 'cp874', 'เบิกจ่าย'),'TBRL',0,'C');
      $this->Cell(15,6,iconv('UTF-8', 'cp874', 'ยอด'),'TRL',0,'C');
      $this->Cell(31,6,'','TRL',0,'C');
      $this->Cell(47,6,'','TRL',1,'C');

      $this->Cell(21,6,iconv('UTF-8', 'cp874', ''),'BRL',0,'C');
      $this->Cell(20,6,iconv('UTF-8', 'cp874', 'จำนวน'),'TBRL',0,'C');
      $this->Cell(16,6,iconv('UTF-8', 'cp874', 'หน่วย'),'TBRL',0,'C');
      $this->Cell(20,6,iconv('UTF-8', 'cp874', 'จำนวน'),'TBRL',0,'C');
      $this->Cell(16,6,iconv('UTF-8', 'cp874', 'หน่วย'),'TBRL',0,'C');
      $this->Cell(15,6,iconv('UTF-8', 'cp874', 'คงเหลือ'),'BRL',0,'C');
      $this->Cell(31,6,iconv('UTF-8', 'cp874', ''),'BRL',0,'C');
      $this->Cell(47,6,iconv('UTF-8', 'cp874', ''),'BRL',1,'C');




      $this->text(30,34,'________________');
      $this->text(84,34,'________________');
      $this->text(160,34,'________________');

      $this->text(18,46,iconv('UTF-8', 'cp874', 'วันที่'));

      $this->text(128,46,iconv('UTF-8', 'cp874', 'ผู้บันทึก'));
      $this->text(166,46,iconv('UTF-8', 'cp874', 'หมายเหตุ'));
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
