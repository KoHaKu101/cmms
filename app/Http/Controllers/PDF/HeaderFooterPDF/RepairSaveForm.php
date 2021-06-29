<?php

namespace App\Http\Controllers\PDF\HeaderFooterPDF;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Machine\MachineRepairREQ;
use Codedge\Fpdf\Fpdf\Fpdf;

class RepairSaveForm extends Fpdf
{
  // private $REPAIR_REQ_UNID;
  function Header()
{
    // if ($UNID) {
    //   $this->REPAIR_REQ_UNID = $UNID;
    //   $dataset = MachineRepairREQ::where('MACHINE_LINE','=',$this->MACHINE_LINE)->count();

    //head ********************************************************************
    $logo = "assets/img/logo13.jpg";
    $this->AddFont('THSarabunNew','','THSarabunNew.php');
    $this->AddFont('THSarabunNew','B','THSarabunNew_b.php');
    $this->SetFont('THSarabunNew','',14 );
    $this->setY(5);
      // Logo
      $this->Cell(26,22,$this->Image($logo,12,6,22),1,0,'C',false);
      // header
      $this->SetFont('THSarabunNew','b',20);

        $this->Cell(100, 22, iconv('UTF-8', 'cp874', 'บันทึกการซ่อมเครื่องจักร / อุปกรณ์  '),1,0,'C');
      $this->SetFont('THSarabunNew','',12 );
        $this->MultiCell(68, 22, iconv('UTF-8', 'cp874', ''),1,0,'',false);
        $this->Text(140,10,iconv('UTF-8', 'cp874', 'เลขที่ :'));
        $this->Text(140,16,iconv('UTF-8', 'cp874', 'วันที่แจ้งซ่อม :'));
        $this->Text(140,21,iconv('UTF-8', 'cp874', 'เวลาแจ้งซ่อม :'));
      //page NO
      $this->SetFont('THSarabunNew','B',14 );
        $this->Text(149,10,iconv('UTF-8', 'cp874', '___________________________'));
        $this->Text(158,16,iconv('UTF-8', 'cp874', '______________________'));
        $this->Text(158,21,iconv('UTF-8', 'cp874', '______________________'));
        $this->Ln(0);
        $this->Text(149,10,iconv('UTF-8', 'cp874', 'MRP6406-0018'   ));
        $this->Text(158,16,iconv('UTF-8', 'cp874', date('d-m-Y')   ));
        $this->Text(158,21,iconv('UTF-8', 'cp874', date('h:i:s')   ));
      //data header
     // }
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
