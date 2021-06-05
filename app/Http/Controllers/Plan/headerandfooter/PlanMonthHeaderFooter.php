<?php

namespace App\Http\Controllers\Plan\headerandfooter;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Models\Machine\MachinePlanPm;
use App\Models\Machine\Pmplanresult;
use App\Models\Machine\Machine;
use Carbon\Carbon;


class PlanMonthHeaderFooter extends Fpdf
{
  private $MACHINE_LINE;
  private $DOC_YEAR;
  private $DOC_MONTH;
  function Header($LINE = NULL,$YEAR = 0,$MONTH = 0){
    if ($LINE & $YEAR ){
       $this->MACHINE_LINE = $LINE ;
       $this->DOC_YEAR = $YEAR ;
       $this->DOC_MONTH = $MONTH ;

  $cel=array(8,20,35,10,15,10,10,10,8,8);
  $array_month = array(0=>"",1 => "มกราคม",2 => "กุมภาพันธ์",3 =>"มีนาคม",4 => "เมษายน",5 =>"พฤษภาคม",6 =>"มิถุนายน",
                   7 =>"กรกฎาคม",8 =>"สิงหาคม",9 =>"กันยายน",10 =>"ตุลาคม",11 => "พฤศจิกายน",12 =>"ธันวาคม");
  $RowH=4;
  // img

  $logo = "assets/img/logo13.jpg";
  // font
  $this->AddFont('THSarabunNew','','THSarabunNew.php');
  $this->AddFont('THSarabunNew','B','THSarabunNew_b.php');
  $this->Rect(10, 10, 193, 267, 'D');

  if ($this->DOC_MONTH == 0) {
    $title = ' ปี '.$this->DOC_YEAR;
    $header = ' ปี '.$this->DOC_YEAR.' LINE : '.$this->MACHINE_LINE.'';
  }else {
    $title = 'รายงานประจำเดือน '.$array_month[$this->DOC_MONTH].' ปี '.$this->DOC_YEAR;
    $header = 'ประจำเดือน '.$array_month[$this->DOC_MONTH].' ปี '.$this->DOC_YEAR.' LINE : '.$this->MACHINE_LINE.'';
  }
  $this->SetTitle($title,'isUTF8');
  $this->SetFont('THSarabunNew','b',18);

  $this->Cell(26,11,$this->Image($logo,$this->GetX()+5, $this->GetY()+3,16),'LTR',0,'C',false);
  $this->Cell(90, 11, $this->normalize('Predictive Maintenance Plan'),'LTR',0,'C');

  $this->SetFont('THSarabunNew','b',14);
  $this->Cell(25,8, $this->normalize('Approved'),'LTBR',0,'C');
  $this->Cell(26,8, $this->normalize('Checked'),'LTBR',0,'C');
  $this->Cell(26,8, $this->normalize('Issued'),'LTBR',1,'C');
  $this->Cell(26,11,'','LBR',0,'C',false);

  $this->SetFont('THSarabunNew','b',18);
  $this->Cell(90, 11, iconv('UTF-8', 'cp874',$header ),'LBR',0,'C');
  $this->Cell(25,11, '','LTBR',0,'C');
  $this->Cell(26,11, '','LTBR',0,'C');
  $this->Cell(26,11, '','LTBR',1,'C');

  $this->SetFont('THSarabunNew','b',10);
  $cel=array(8,18,8,13,20,49,10,10,12,15,15,15);
  $rHigeht=8;
  $this->Cell($cel[0],$rHigeht,$this->normalize('NO.'),1,0,'C');
  $this->Cell($cel[1],$rHigeht,$this->normalize('Plan Date'),1,0,'C');
  $this->Cell($cel[2],$rHigeht,$this->normalize('LINE'),1,0,'C');
  $this->Cell($cel[3],$rHigeht,$this->normalize('Machine'),1,0,'C');
  $this->Cell($cel[4],$rHigeht,$this->normalize('Code'),1,0,'C');
  $this->Cell($cel[5],$rHigeht,$this->normalize('Name'),1,0,'C');
  $this->Cell($cel[6],$rHigeht,$this->normalize('Plan'),1,0,'C');
  $this->Cell($cel[7],$rHigeht,$this->normalize('Actual'),1,0,'C');
  $this->Cell($cel[8],$rHigeht,$this->normalize('Unit'),1,0,'C');
  $this->Cell($cel[9],$rHigeht,$this->normalize('Plan Cost'),1,0,'C');
  $this->Cell($cel[10],$rHigeht,$this->normalize('Actual  Cost'),1,0,'C');
  $this->Cell($cel[11],$rHigeht,$this->normalize('Complete'),1,0,'C');

  $this->Ln($rHigeht);
  }
}
function Footer(){
  // Position at 1.5 cm from bottom
 $this->SetY(-20);
 // Arial italic 8
 $this->SetFont('Arial','B',8);
 // Page number
 // $this->Cell(125,10,'NOTE :','LTR',0,'L');
 // $this->Cell(30,7, $this->normalize('REPORT BY'),'BR',0,'L',);
 // $this->Cell(40,7, $this->normalize(''),'BR',1,'L',);
 //
 //  $this->Cell(125,7,'','LBR',0,'L');
 //  $this->Cell(30,7, $this->normalize('INSPETOR BY'),'TBR',0,'L',);
 //  $this->Cell(40,7, $this->normalize(''),'TBR',1,'L',);
  $this->SetFont('THSarabunNew','B',12);
  $this->Cell(60,7, iconv('UTF-8', 'cp874','อายุการจัดเก็บ : 2 ปี'),'',0,'L',);
  $this->Cell(60,7,'Page '.$this->PageNo().'/{nb}',0,0,'C');
  $this->Cell(75,7, iconv('UTF-8', 'cp874','FM-MA-04 REV.7 : 1 Nov 09'),'',1,'R',);


}
protected function normalize($word)
   {
     // Thanks to: http://stackoverflow.com/questions/3514076/special-characters-in-fpdf-with-php

     $word = str_replace("✓","%42",$word);
     $word = str_replace("@","%40",$word);
     $word = str_replace("`","%60",$word);
     $word = str_replace("¢","%A2",$word);
     $word = str_replace("£","%A3",$word);
     $word = str_replace("¥","%A5",$word);
     $word = str_replace("|","%A6",$word);
     $word = str_replace("«","%AB",$word);
     $word = str_replace("¬","%AC",$word);
     $word = str_replace("¯","%AD",$word);
     $word = str_replace("º","%B0",$word);
     $word = str_replace("±","%B1",$word);
     $word = str_replace("ª","%B2",$word);
     $word = str_replace("µ","%B5",$word);
     $word = str_replace("»","%BB",$word);
     $word = str_replace("¼","%BC",$word);
     $word = str_replace("½","%BD",$word);
     $word = str_replace("¿","%BF",$word);
     $word = str_replace("À","%C0",$word);
     $word = str_replace("Á","%C1",$word);
     $word = str_replace("Â","%C2",$word);
     $word = str_replace("Ã","%C3",$word);
     $word = str_replace("Ä","%C4",$word);
     $word = str_replace("Å","%C5",$word);
     $word = str_replace("Æ","%C6",$word);
     $word = str_replace("Ç","%C7",$word);
     $word = str_replace("È","%C8",$word);
     $word = str_replace("É","%C9",$word);
     $word = str_replace("Ê","%CA",$word);
     $word = str_replace("Ë","%CB",$word);
     $word = str_replace("Ì","%CC",$word);
     $word = str_replace("Í","%CD",$word);
     $word = str_replace("Î","%CE",$word);
     $word = str_replace("Ï","%CF",$word);
     $word = str_replace("Ð","%D0",$word);
     $word = str_replace("Ñ","%D1",$word);
     $word = str_replace("Ò","%D2",$word);
     $word = str_replace("Ó","%D3",$word);
     $word = str_replace("Ô","%D4",$word);
     $word = str_replace("Õ","%D5",$word);
     $word = str_replace("Ö","%D6",$word);
     $word = str_replace("Ø","%D8",$word);
     $word = str_replace("Ù","%D9",$word);
     $word = str_replace("Ú","%DA",$word);
     $word = str_replace("Û","%DB",$word);
     $word = str_replace("Ü","%DC",$word);
     $word = str_replace("Ý","%DD",$word);
     $word = str_replace("Þ","%DE",$word);
     $word = str_replace("ß","%DF",$word);
     $word = str_replace("à","%E0",$word);
     $word = str_replace("á","%E1",$word);
     $word = str_replace("â","%E2",$word);
     $word = str_replace("ã","%E3",$word);
     $word = str_replace("ä","%E4",$word);
     $word = str_replace("å","%E5",$word);
     $word = str_replace("æ","%E6",$word);
     $word = str_replace("ç","%E7",$word);
     $word = str_replace("è","%E8",$word);
     $word = str_replace("é","%E9",$word);
     $word = str_replace("ê","%EA",$word);
     $word = str_replace("ë","%EB",$word);
     $word = str_replace("ì","%EC",$word);
     $word = str_replace("í","%ED",$word);
     $word = str_replace("î","%EE",$word);
     $word = str_replace("ï","%EF",$word);
     $word = str_replace("ð","%F0",$word);
     $word = str_replace("ñ","%F1",$word);
     $word = str_replace("ò","%F2",$word);
     $word = str_replace("ó","%F3",$word);
     $word = str_replace("ô","%F4",$word);
     $word = str_replace("õ","%F5",$word);
     $word = str_replace("ö","%F6",$word);
     $word = str_replace("÷","%F7",$word);
     $word = str_replace("ø","%F8",$word);
     $word = str_replace("ù","%F9",$word);
     $word = str_replace("ú","%FA",$word);
     $word = str_replace("û","%FB",$word);
     $word = str_replace("ü","%FC",$word);
     $word = str_replace("ý","%FD",$word);
     $word = str_replace("þ","%FE",$word);
     $word = str_replace("ÿ","%FF",$word);

     return urldecode($word);
   }
}
// Page footer
