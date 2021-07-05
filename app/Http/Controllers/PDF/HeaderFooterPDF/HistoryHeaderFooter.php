<?php

namespace App\Http\Controllers\PDF\HeaderFooterPDF;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Machine\Machine;
use Codedge\Fpdf\Fpdf\Fpdf;

class HistoryHeaderFooter extends Fpdf
{
  var $widths;
  var $aligns;
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
      $this->Cell(20,18,$this->Image($logo,6,6,18),1,0,'C',0);
      // header
      $this->SetFont('THSarabunNew','b',20);
        $this->Cell(248, 18, iconv('UTF-8', 'cp874', 'รายงาน ใบประวัติการซ่อมเครื่องจักร / อุปกรณ์ '.$dataset->MACHINE_CODE),1,0,'C');
        $this->Cell(19, 18, iconv('UTF-8', 'cp874', ''),1,1,'C',0);
        $this->SetFont('THSarabunNew','',12);
      $this->setY(25);
        $this->Cell(22, 6, iconv('UTF-8', 'cp874', 'Machine No : '),0,0,'R',0);
        $this->Cell(35, 6, iconv('UTF-8', 'cp874', $dataset->MACHINE_CODE),'B',0,'L',0);
        $this->setX(75);
          $this->Cell(27, 6, iconv('UTF-8', 'cp874', 'Machine Name : '),0,0,'R',0);
          $this->Cell(50, 6, iconv('UTF-8', 'cp874', $dataset->MACHINE_NAME_TH),'B',0,'L',0);
        $this->setX(160);
          $this->Cell(23, 6, iconv('UTF-8', 'cp874', 'Receive Date : '),0,0,'R',0);
          $this->Cell(23, 6, iconv('UTF-8', 'cp874', date('d-m-Y',strtotime($dataset->MACHINE_STARTDATE))),'B',0,'L',0);
      $this->setY(31);
        $this->Cell(22, 6, iconv('UTF-8', 'cp874', 'Model : '),0,0,'R',0);
        $this->Cell(35, 6, iconv('UTF-8', 'cp874', $dataset->MACHINE_MODEL),'B',0,'L',0);
        $this->setX(75);
          $this->Cell(27, 6, iconv('UTF-8', 'cp874', 'Serial : '),0,0,'R',0);
          $this->Cell(50, 6, iconv('UTF-8', 'cp874', $dataset->MACHINE_SERIAL),'B',0,'L',0);
        $this->setX(160);
          $this->Cell(23, 6, iconv('UTF-8', 'cp874', 'Supplier : '),0,0,'R',0);
          $this->Cell(83, 6, iconv('UTF-8', 'cp874', $dataset->PURCHASE_FORM),'B',1,'L',0);
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
    $this->SetY(-10);

    // footer
    $this->SetX(264);
    $this->Cell(24, 5, iconv('UTF-8', 'cp874', 'PQM-F-MA-06 Rev.5 -13/03/49'),0,0,'C',0);

  }


function SetWidths($w)
{
    //Set the array of column widths
    $this->widths=$w;
}

function SetAligns($a)
{
    //Set the array of column alignments
    $this->aligns=$a;
}

function Row($data)
{
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    $h=5*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        $this->Rect($x,$y,$w,$h);
        //Print the text
        $this->MultiCell($w,5,$data[$i],0,$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}

function CheckPageBreak($h)
{
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
}

function NbLines($w,$txt)
{
    //Computes the number of lines a MultiCell of width w will take
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
}

}
