<?php

namespace App\Http\Controllers\PDF\HeaderFooterPDF;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Machine\DocItemOut;
use Codedge\Fpdf\Fpdf\Fpdf;

class PRHeader extends Fpdf
{
  public $UNID;
  function header($DOC_ITEM_UNID = NULL){
    $this->UNID = $DOC_ITEM_UNID;
    // dd($this->UNID);
    if ($this->UNID != '') {
      $DOC_ITEM = DocItemOut::select('*')->selectRaw('dbo.decode_utf8(EMP_NAME) as EMP_NAME_TH')->where('UNID','=',$this->UNID)->first();
      $this->AddFont('THSarabunNew','','THSarabunNew.php');
      $this->AddFont('THSarabunNew','B','THSarabunNew_b.php');
      $this->SetFont('THSarabunNew','',14 );
      $Uncheck  = asset('assets/img/Uncheck.png');
      $Checked  = asset('assets/img/checkBox.png');
      $SELL     = $DOC_ITEM->DOC_TYPE == '1' ? $Checked : $Uncheck;
      $OUT      = $DOC_ITEM->DOC_TYPE == '9' ? $Checked : $Uncheck;
        $this->SetFont('THSarabunNew','b',14);
          $this->setXY(0.8,0.5);
          $this->Cell(19.9, 0.5, iconv('UTF-8', 'cp874', 'บริษัท พี ควอลิตี้ แมชชีน พาร์ท จำกัด'),0,0,'C');
          $this->Cell(2, 0.5, iconv('UTF-8', 'cp874', ''),'LTR',1,'C');
            $this->SetFont('THSarabunNew','b',15);
          $this->setXY(0.8,1);
          $this->Cell(19.9, 0.5, iconv('UTF-8', 'cp874', 'ใบขออนุญาตินำของออกจากบริษัท'),0,0,'C');
          $this->Cell(2, 0.5, iconv('UTF-8', 'cp874',''),'LBR',1,'C');
          $this->setXY(0.8,2);
          $this->Cell(2, 0.7, iconv('UTF-8', 'cp874', 'วัตถุประสงค์'),0,0,'L');
          $this->Cell(3.2, 0.7, iconv('UTF-8', 'cp874', $this->Image($SELL,3.8, 1.8,0.8).'เพื่อขาย'),0,0,'R');
          $this->Cell(7.5, 0.7, iconv('UTF-8', 'cp874', $this->Image($OUT,7.3, 1.8,0.8).'ขอยืม/ส่งซ่อม(ให้ระบุวันกำหนดคืน)'),0,0,'R');
          $this->Cell(9.3, 0.7, iconv('UTF-8', 'cp874', 'วันที่......................................'),0,1,'R');

          $this->setXY(0.8,2.7);
          $this->Cell(6.5, 0.7, iconv('UTF-8', 'cp874', 'ผู้นำออก...............................................'),0,0,'L');
          $this->Cell(9, 0.7, iconv('UTF-8', 'cp874', 'บริษัท..................................................................................'),0,0,'L');
          $this->Cell(6.5, 0.7, iconv('UTF-8', 'cp874', 'เลขที่เอกสาร....................................'),0,1,'R');

          $this->setXY(0.8,3.4);
          $this->Cell(2, 0.7, iconv('UTF-8', 'cp874', 'รายละเอียด'),0,1,'L');
          $this->SetFont('THSarabunNew','b',14);
          $this->setXY(0.8,4.1);
          $this->Cell(1, 0.5, iconv('UTF-8', 'cp874', 'ลำดับ'),0,0,'L');
          $this->Cell(3, 0.5, iconv('UTF-8', 'cp874', 'รหัสสินค้า'),0,0,'C');
          $this->Cell(12, 0.5, iconv('UTF-8', 'cp874', 'รายละเอียด'),0,0,'C');
          $this->Cell(1.5, 0.5, iconv('UTF-8', 'cp874', 'จำนวน'),0,0,'C');
          $this->Cell(2, 0.5, iconv('UTF-8', 'cp874', 'หน่วย'),0,0,'C');
          $this->Cell(2.5, 0.5, iconv('UTF-8', 'cp874', 'กำหนดส่งคืน'),0,1,'C');
          $this->text(2.3 ,3.1,iconv('UTF-8', 'cp874',$DOC_ITEM->EMP_NAME_TH));
          $this->text(8.5 ,3.1,iconv('UTF-8', 'cp874',$DOC_ITEM->COMPANY_NAME));
          $this->text(19.8 ,3.1,iconv('UTF-8', 'cp874',$DOC_ITEM->DOC_NO));
          $this->text(19.8 ,2.4,iconv('UTF-8', 'cp874',$DOC_ITEM->DOC_DATE));

    }




  }
  function footer(){
    // if ($UNID != '') {
    $this->SetFont('THSarabunNew','b',12);

    $this->setXY(0.8,12.4);

    $this->Cell(1.5, 0.7, iconv('UTF-8', 'cp874', 'หมายเหตุ'),0,0,'L');
    $this->Cell(18, 0.7, iconv('UTF-8', 'cp874', 'ผู้นำของออกจะต้องตรวจสอบของให้ถูกต้องตรงกับเอกสารก่อนการเซ็นรับ'),0,1,'L');
    $this->Cell(1.3, 0.7, iconv('UTF-8', 'cp874', ''),0,0,'L');
    $this->Cell(18, 0.5, iconv('UTF-8', 'cp874', 'เอกสาร : ต้นฉบับ ให้ผู้จ่าย,สำเนา 1 ให้ผู้รับของ , สำเนา 2 ให้แผนการตลาด'),0,1,'L');

    $this->RoundedRect(0.8,    4, 1,   5.6, 0.5, '14', 'D');
    $this->RoundedRect(1.8,    4, 3,   5.6, 0.5, '0', 'D');
    $this->RoundedRect(4.8,    4, 12,  5.6, 0.5, '0', 'D');
    $this->RoundedRect(16.8,   4, 1.5, 5.6, 0.5, '0', 'D');
    $this->RoundedRect(18.3,     4, 2,   5.6, 0.5, '0', 'D');
    $this->RoundedRect(20.3,     4, 2.5, 5.6, 0.5, '23', 'D');

    $this->RoundedRect(0.8,    9.7, 5.5,   2, 0.5, '14', 'D');
    $this->RoundedRect(6.3,      9.7, 5.5,   2, 0.5, '0', 'D');
    $this->RoundedRect(11.8,   9.7, 5.5,   2, 0.5, '0', 'D');
    $this->RoundedRect(17.3,     9.7, 5.5,   2, 0.5, '23', 'D');


    $this->SetFont('THSarabunNew','b',14);
    $this->text(1     ,11,iconv('UTF-8', 'cp874','......................................................'));
    $this->text(6.5   ,11,iconv('UTF-8', 'cp874','.......................................................'));
    $this->text(12    ,11,iconv('UTF-8', 'cp874','.......................................................'));
    $this->text(17.5  ,11,iconv('UTF-8', 'cp874','.....................................................'));

    $this->text(2.4   ,11.5,iconv('UTF-8', 'cp874','ผู้ขออนุญาต'));
    $this->text(8.3   ,11.5,iconv('UTF-8', 'cp874','ผู้อนุมัติ'));
    $this->text(12.9  ,11.5,iconv('UTF-8', 'cp874','ผู้รับของ/ผู้นำของออก'));
    $this->text(17.8  ,11.5,iconv('UTF-8', 'cp874','เจ้าหน้าที่รักษาความปลอดภัย'));
  // }
  }
   function RoundedRect($x, $y, $w, $h, $r, $corners = '1234', $style = ''){
    $k = $this->k;
    $hp = $this->h;
    if($style=='F')
        $op='f';
    elseif($style=='FD' || $style=='DF')
        $op='B';
    else
        $op='S';
    $MyArc = 20/9 * (sqrt(2) - 1);
    $this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));

    $xc = $x+$w-$r;
    $yc = $y+$r;
    $this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));
    if (strpos($corners, '2')===false)
        $this->_out(sprintf('%.2F %.2F l', ($x+$w)*$k,($hp-$y)*$k ));
    else
        $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);

    $xc = $x+$w-$r;
    $yc = $y+$h-$r;
    $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
    if (strpos($corners, '3')===false)
        $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-($y+$h))*$k));
    else
        $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);

    $xc = $x+$r;
    $yc = $y+$h-$r;
    $this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
    if (strpos($corners, '4')===false)
        $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-($y+$h))*$k));
    else
        $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);

    $xc = $x+$r ;
    $yc = $y+$r;
    $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
    if (strpos($corners, '1')===false)
    {
        $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$y)*$k ));
        $this->_out(sprintf('%.2F %.2F l',($x+$r)*$k,($hp-$y)*$k ));
    }
    else
        $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
    $this->_out($op);
  }

   function _Arc($x1, $y1, $x2, $y2, $x3, $y3){
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
            $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
    }

}
