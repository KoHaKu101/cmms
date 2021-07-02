<?php

namespace App\Models\PDF;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Models\Machine\MachinePlanPm;
use App\Models\Machine\Pmplanresult;
use App\Models\Machine\Machine;
use Carbon\Carbon;


class PDFPlanForm extends Fpdf
{
  private $UNID;
  function Header($title = NULL){
    if ($title){
       $this->UNID = $title ;
     }
if($this->UNID){
    $PM_PLAN = MachinePlanPm::where('UNID',$this->UNID)->first();
    $PM_PLAN_RESULT_FIRST   = Pmplanresult::where('PM_PLAN_UNID',$this->UNID)->first();
    $Machine          = Machine::where('UNID','=',$PM_PLAN->MACHINE_UNID)->first();
      $PLAN_PERIOD    = $PM_PLAN->PLAN_PERIOD ;
      $PM_MASTER_NAME = $PM_PLAN_RESULT_FIRST->PM_MASTER_NAME;


  $logo = "assets/img/logo13.jpg";
  $checkpass = "assets/img/pass.png";
  $checkfail = "assets/img/fail.png";
  $this->AddFont('THSarabunNew','','THSarabunNew.php');
  $this->AddFont('THSarabunNew','B','THSarabunNew_b.php');


  //data header

  // Logo
  $this->Cell(20,18,$this->Image($logo,12,12,15),1,0,'C',false);

  // Arial bold 15
  $this->SetFont('THSarabunNew','b',20);
  $this->SetTitle('รายการตรวจเช็คเครื่องจักร','isUTF8');
  $this->Rect(10, 10, 195, 262, 'D');
  $this->Cell(75, 18, iconv('UTF-8', 'cp874', 'รายการตรวจเช็คเครื่องจักร'),1,0,'C');
    $this->setFillColor(168,168,168);
  // header
  $this->SetFont('THSarabunNew','B',13 );

  $this->Cell(40,8, $this->normalize('MACHINE TYPE'),'LTBR',0,'C',1);
  $this->Cell(20,8, $this->normalize('APPROVED'),'LTBR',0,'C',1);
  $this->Cell(20,8, $this->normalize('CHECKED'),'LTBR',0,'C',1);
  $this->Cell(20,8, $this->normalize('REPORTED'),'LTBR',1,'C',1);
  $this->Cell(95,8, $this->normalize(''),'',0,'C',);
  $this->Cell(40,10, $this->normalize($Machine->MACHINE_TYPE),'LBR',0,'C',);//ระยะการตรวจ

  $this->Cell(20,10, $this->normalize(''),'LBR',0,'C',);//APPROVED
  $this->Cell(20,10, $this->normalize(''),'LBR',0,'C',);//CHECKED
  $this->Cell(20,10, $this->normalize(''),'LBR',1,'C',);//REPORTED

  $this->SetFont('THSarabunNew','B',13 );
  $this->Cell(25,8, $this->normalize('MACHINE NO : '),'LTBR',0,'L',1);
  $this->Cell(90,8, iconv('UTF-8', 'cp874',$PM_PLAN_RESULT_FIRST->MACHINE_CODE.' '.$PM_PLAN_RESULT_FIRST->MACHINE_NAME),'LTBR',0,'L',);
  // $this->Cell(67.5,8, $this->normalize(''),'LBR',0,'L',);
  $this->Cell(20,8,$this->normalize('PERIOD'),1,0,'C',1);
  $this->SetFont('THSarabunNew','BU',14);

  $this->Cell(60.5,8,$this->normalize('SYMBOL'),'LR',1,'L',);
  $this->SetFont('THSarabunNew','B',13 );
  $this->Cell(25,8, $this->normalize('PLAN DATE : '),'LTBR',0,'L',1);
  $this->Cell(25,8, $this->normalize(Carbon::parse($PM_PLAN_RESULT_FIRST->PLAN_DATE)->format('d-m-Y')),'LTBR',0,'C',);
  $this->Cell(35,8, $this->normalize('INSPECTION DATE : '),'LTBR',0,'L',1);
  $this->Cell(30,8, $this->normalize(Carbon::parse($PM_PLAN_RESULT_FIRST->CHECK_DATE)->format('d-m-Y')),'LTBR',0,'C',);
  $this->Cell(20,8,iconv('UTF-8', 'cp874',$PLAN_PERIOD.' เดือน'),1,0,'C',);

  $this->Cell(60,8, $this->normalize(''),'LBR',1,'L',);

  $this->Cell(195,8, iconv('UTF-8', 'cp874','รายละเอียดการตรวจเช็ค'),'LTBR',1,'C',1);
  $this->SetFont('THSarabunNew','B',14 );
  // $this->Text(125,24,iconv('UTF-8', 'cp874', $Machine->MACHINE_TYPE));
  $this->Text(22,278,iconv('UTF-8', 'cp874', $PM_PLAN_RESULT_FIRST->PM_MASTERPLPAN_REMARK));
  $this->Text(168,276.5,iconv('UTF-8', 'cp874', $PM_PLAN_RESULT_FIRST->CREATE_BY));
  $this->Text(168,283.5,iconv('UTF-8', 'cp874', $PM_PLAN_RESULT_FIRST->PM_USER_CHECK));
  $this->Text(170,38.5,iconv('UTF-8', 'cp874', 'PASS'));
  $this->Text(190,38.5,iconv('UTF-8', 'cp874', 'FAIL'));
  $this->Text(190,33,$this->Image($checkpass,160,32.5,10));
  $this->Text(11,33,$this->Image($checkfail,180,33,9));

}
}
function Footer(){
  // Position at 1.5 cm from bottom
 $this->SetY(-25);
 // Arial italic 8
 $this->SetFont('Arial','B',8);
 // Page number
 $this->Cell(125,10,'NOTE :','LTR',0,'L');
 $this->Cell(30,7, $this->normalize('REPORT BY'),'BR',0,'L',);
 $this->Cell(40,7, $this->normalize(''),'BR',1,'L',);

  $this->Cell(125,7,'','LBR',0,'L');
  $this->Cell(30,7, $this->normalize('INSPETOR BY'),'TBR',0,'L',);
  $this->Cell(40,7, $this->normalize(''),'TBR',1,'L',);
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
