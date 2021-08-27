<?php

namespace App\Http\Controllers\PDF\HeaderFooterPDF;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Machine\Machine;
use Codedge\Fpdf\Fpdf\Fpdf;

class MachineRepairHeader extends Fpdf
{
  var $widths;
  var $aligns;
  // private $TYPE_DOWNTIME;
  function Header($TYPE = NULL)
{
    $logo = "assets/img/logo13.jpg";
    $this->AddFont('THSarabunNew','','THSarabunNew.php');
    $this->AddFont('THSarabunNew','B','THSarabunNew_b.php');
    $this->SetFont('THSarabunNew','',18 );

    //head ********************************************************************
    $this->text(278, 12, iconv('UTF-8', 'cp874', 'หน้า'));
    $this->text(279, 20, iconv('UTF-8', 'cp874', $this->PageNo().'/{nb}'));
    $this->setXY(5,5);
      // Logo
      $this->Cell(20,18,$this->Image($logo,6,6,18),1,0,'C',0);
      // header
      $this->SetFont('THSarabunNew','b',20);
        $this->Cell(247, 18, iconv('UTF-8', 'cp874', 'รายละเอียด เครื่องจักร ที่เสียบ่อย '),1,0,'C');
        $this->Cell(20, 18, iconv('UTF-8', 'cp874', ''),1,1,'C',0);
        $this->SetFont('THSarabunNew','',14);
      $this->SetFillColor(206,206,206);
        $this->setX(5);

        $this->Cell(8,  7, 'No.'                                       ,1,0,'C',1);
        $this->Cell(20, 7, 'MC-CODE'                                   ,1,0,'C',1);
        $this->Cell(39, 7, 'MC-NAME'                                   ,1,0,'C',1);
        $this->Cell(80, 7, iconv('UTF-8', 'cp874', 'สาเหตุ / อาการที่เสีย') ,1,0,'C',1);
        $this->Cell(80, 7, iconv('UTF-8', 'cp874', 'วิธีแก้ไข')           ,1,0,'C',1);
        $this->Cell(40, 7, iconv('UTF-8', 'cp874', 'ผู้ดำเนินการ')         ,1,0,'C',1);
        $this->Cell(20, 7, iconv('UTF-8', 'cp874', 'รวมจำนวน')         ,1,1,'C',1);

    //end head ****************************************************************

}


function SetBorder($b){
  $this->border = $b;
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
    $h=7*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row

      for($i=0;$i<count($data);$i++){
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        $b=isset($this->border[$i]) ? $this->border[$i] : 0;
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        if ($i == 3 || $i == 4 ||$i == 5) {
          $this->Rect($x,$y,$w,$h);
          $this->MultiCell($w,7,$data[$i],$b,$a);
        }elseif ($i == 0 || $i == 1 ||$i == 2||$i == 6) {
          $this->MultiCell($w,$h,$data[$i],$b,$a);
        }

        //Print the text

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

function NbLines($w,$txt){
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
