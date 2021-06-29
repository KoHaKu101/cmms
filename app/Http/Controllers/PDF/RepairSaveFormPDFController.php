<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Machine\Machine;
use App\Models\Machine\MachineRepairREQ;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Codedge\Fpdf\Fpdf\Fpdf;
use Auth;
use App\Http\Controllers\PDF\HeaderFooterPDF\RepairSaveForm as RepairSaveForm;



class RepairSaveFormPDFController extends Controller
{
  public function __construct(RepairSaveForm $RepairSaveForm){
    $this->middleware('auth');
      $this->pdf = $RepairSaveForm;
  }

  public function RepairSaveForm($UNID = NULL){
    $Check = "assets/img/checkBox.png";
    $UnCheck = "assets/img/Uncheck.png";
    $DATA_REPAIR_REQ = MachineRepairREQ::where('UNID','=',$UNID)->first();
    if (!$DATA_REPAIR_REQ) {
        return '<body style="background-color:powderblue;">
                <br/><h1 align="center" style="color:red;"> No Data </h1>
                <div align="center">
                <button onclick="javascript:window.close()"
                style="background: #1572e8!important;border-color:#1572e8!important;font-size:14px;
                padding:.65rem 1.4rem;font-size:14px;opacity:1;border-radius: 3px;
                padding: 5px 9px;color:white">
                close </button></div></body>';
    }
    $this->pdf->AddFont('THSarabunNew','','THSarabunNew.php');
    $this->pdf->AddFont('THSarabunNew','B','THSarabunNew_b.php');
    $this->pdf->SetFont('THSarabunNew','',14 );
    $this->pdf->AliasNbPages();
    $this->pdf->AddPage(['P','mm',['220', '297']]);
    $this->pdf->SetFont('THSarabunNew','',14 );
    $this->pdf->Rect(10,5,194,283);
    $this->pdf->SetAutoPageBreak(true, 5);
    $height = array(5,6,10,17,34,49,59,4,6);
    //********************************  box A ************************************************************
    $this->pdf->Rect(150,69,54,14);
      $this->pdf->Rect(150,69,6,6);
      $this->pdf->text(152,73,iconv('UTF-8', 'cp874', 'A'));
      $this->pdf->text(164,74,iconv('UTF-8', 'cp874', 'รวมระยะเวลาที่ใช้'));
      $this->pdf->text(162,80,iconv('UTF-8', 'cp874', '0 วัน 0 ชั่วโมง 0 นาที'));
    //********************************  box B ************************************************************
    $this->pdf->Rect(150,103,54,14);
      $this->pdf->Rect(150,103,6,6);
      $this->pdf->text(152,107,iconv('UTF-8', 'cp874', 'B'));
      $this->pdf->text(164,108,iconv('UTF-8', 'cp874', 'รวมระยะเวลาที่ใช้'));
      $this->pdf->text(162,114,iconv('UTF-8', 'cp874', '0 วัน 0 ชั่วโมง 0 นาที'));
    //********************************  box C ************************************************************
    $this->pdf->Rect(150,140,54,14);
      $this->pdf->Rect(150,140,6,6);
      $this->pdf->text(152,144,iconv('UTF-8', 'cp874', 'C'));
      $this->pdf->text(164,145,iconv('UTF-8', 'cp874', 'รวมระยะเวลาที่ใช้'));
      $this->pdf->text(162,151,iconv('UTF-8', 'cp874', '0 วัน 0 ชั่วโมง 0 นาที'));
    //********************************  box D ************************************************************
    $this->pdf->Rect(150,157,54,14);
      $this->pdf->Rect(150,157,6,6);
      $this->pdf->text(152,161,iconv('UTF-8', 'cp874', 'D'));
      $this->pdf->text(164,162,iconv('UTF-8', 'cp874', 'รวมระยะเวลาที่ใช้'));
      $this->pdf->text(162,168,iconv('UTF-8', 'cp874', '0 วัน 0 ชั่วโมง 0 นาที'));
    //******************************* Box RepairBy Ane ReportBy ********************************************
    $this->pdf->Rect(148,199,52,35);
      $this->pdf->Rect(148,199,52,6);
      $this->pdf->text(152,161,iconv('UTF-8', 'cp874', 'D'));
      $this->pdf->text(164,162,iconv('UTF-8', 'cp874', 'รวมระยะเวลาที่ใช้'));
      $this->pdf->text(162,168,iconv('UTF-8', 'cp874', '0 วัน 0 ชั่วโมง 0 นาที'));
    //******************************** Round Two ****************************************

      //******************************** line 1 ********************************************
      $this->pdf->Cell(30,$height[2] ,iconv('UTF-8', 'cp874', 'หมายเลขเครื่องจักร :'),0,0,'R',0);
        $this->pdf->Text(40,$height[4] ,'__________________________________');
        $this->pdf->Cell(60,$height[2] ,$DATA_REPAIR_REQ->MACHINE_CODE,'',0,'C',0);
        $this->pdf->Cell(20,$height[2] ,iconv('UTF-8', 'cp874', 'ชื่อเครื่องจักร :'),'',0,'C',0);
        $this->pdf->Text(120,$height[4],'__________________________________');
        $this->pdf->Cell(60,$height[2] ,iconv('UTF-8', 'cp874', $DATA_REPAIR_REQ->MACHINE_NAME),'',0,'C',0);
        $this->pdf->Cell(24,$height[2] ,'','R',1,'C',0);
      //******************************** line 2 ********************************************
      $this->pdf->Cell(30,4 ,iconv('UTF-8', 'cp874', 'สถานที่ติดตั้ง :'),0,0,'R',0);
        $this->pdf->Cell(140,4,iconv('UTF-8', 'cp874', $DATA_REPAIR_REQ->MACHINE_LINE),'',1,'L',0);
        $this->pdf->Text(40,41 ,'________________________________________________________________________________');
     //******************************** line 3 ********************************************
      $this->pdf->Cell(30,8,iconv('UTF-8', 'cp874', 'อาการเสีย :'),'',0,'R',0);
        $this->pdf->MultiCell(140,8,iconv('UTF-8', 'cp874', $DATA_REPAIR_REQ->REPAIR_SUBSELECT_NAME.''),'','L',0);
        $this->pdf->Text(40,47,'________________________________________________________________________________');
        $this->pdf->Text(40,54,'________________________________________________________________________________');
        $this->pdf->SetY(60);
  //******************************** Round Two *******************************************************
    $this->pdf->SetFillColor(187,187,187);
    //******************************** date start *********************************************************
    $this->pdf->Cell(194,6,iconv('UTF-8', 'cp874', 'ตรวจสอบเบื้องต้น'),'1',1,'C',1);
      $this->pdf->Cell(30,$height[2] ,iconv('UTF-8', 'cp874', 'เริ่ม วันที่ :'),'',0,'R',0);
      $this->pdf->Cell(40,$height[2] ,date('d-m-Y',strtotime($DATA_REPAIR_REQ->INSPECTION_START_DATE)),'',0,'C',0);
      $this->pdf->Text(40,73,'_______________________');
      $this->pdf->Cell(20,$height[2] ,iconv('UTF-8', 'cp874', 'เวลา :'),'',0,'R',0);
      $this->pdf->Cell(40,$height[2] ,date('H:i',strtotime($DATA_REPAIR_REQ->INSPECTION_START_TIME)),'',1,'C',0);
      $this->pdf->Text(100,73,'_______________________');

    //******************************** date end *********************************************************
    $this->pdf->Cell(30,$height[7] ,iconv('UTF-8', 'cp874', 'เสร็จ วันที่ :'),'',0,'R',0);
      $this->pdf->Cell(40,$height[7] ,date('d-m-Y',strtotime($DATA_REPAIR_REQ->INSPECTION_END_DATE)),'',0,'C',0);
      $this->pdf->Text(40,80  ,'_______________________');
      $this->pdf->Cell(20,$height[7] ,iconv('UTF-8', 'cp874', 'เวลา :'),0,0,'R',0);
      $this->pdf->Cell(40,$height[7] ,date('H:i',strtotime($DATA_REPAIR_REQ->INSPECTION_END_TIME)),0,1,'C',0);
      $this->pdf->Text(100,80 ,'_______________________');
      $this->pdf->Cell(194,$height[8],'',0,1,'R',0);
  //******************************** Round Three *******************************************************
  $this->pdf->SetY(86);
    $this->pdf->Cell(194,6,iconv('UTF-8', 'cp874', 'การดำเนินการแก้ไข'),'1',1,'C',1);
    $this->pdf->Cell(50,$height[1],iconv('UTF-8', 'cp874', 'ดำเนินการซ่อมโดย : '),0,1,'C',0);
    $this->pdf->Cell(22,$height[1],$this->pdf->Image($UnCheck,$this->pdf->GetX()+15,$this->pdf->GetY(),6),0,0,'R',0);
    $this->pdf->Cell(40,$height[1],iconv('UTF-8', 'cp874', 'ช่างซ่อมภายนอก / จ้างซ่อม'),0,1,'L',0);
  //******************************* date start ************************************************************************
    $this->pdf->Cell(30,$height[1],iconv('UTF-8', 'cp874', 'เริ่ม วันที่ : '),0,0,'R',0);
      $this->pdf->Cell(30,$height[1],iconv('UTF-8', 'cp874',  date('d-m-Y',strtotime($DATA_REPAIR_REQ->WORKEROUT_START_DATE))),0,0,'R',0);
      $this->pdf->Text(40,109,'_______________________');
      $this->pdf->Cell(30,$height[1],iconv('UTF-8', 'cp874', 'เวลา : '),0,0,'R',0);
      $this->pdf->Cell(40,$height[1],iconv('UTF-8', 'cp874',  date('H:i',strtotime($DATA_REPAIR_REQ->WORKEROUT_START_TIME))),0,1,'C',0);
      $this->pdf->Text(100,109,'_______________________');
  //******************************* date end ************************************************************************
    $this->pdf->Cell(30,$height[1],iconv('UTF-8', 'cp874', 'เสร็จ วันที่ : '),0,0,'R',0);
      $this->pdf->Cell(30,$height[1],iconv('UTF-8', 'cp874',  date('d-m-Y',strtotime($DATA_REPAIR_REQ->WORKEROUT_END_DATE))),0,0,'R',0);
      $this->pdf->Text(40,115  ,'_______________________');
      $this->pdf->Cell(30,$height[1],iconv('UTF-8', 'cp874', 'เวลา : '),0,0,'R',0);
      $this->pdf->Cell(40,$height[1],iconv('UTF-8', 'cp874',  date('H:i',strtotime($DATA_REPAIR_REQ->WORKEROUT_END_TIME))),0,1,'C',0);
      $this->pdf->Text(100,115 ,'_______________________');

    $this->pdf->Cell(22,$height[1],$this->pdf->Image($UnCheck,$this->pdf->GetX()+15,$this->pdf->GetY(),6),0,0,'R',0);
      $this->pdf->Cell(40,$height[1],iconv('UTF-8', 'cp874', 'ช่างซ่อมภายในของบริษัท'),0,1,'L',0);
      $this->pdf->Cell(30,$height[1],iconv('UTF-8', 'cp874', $this->pdf->Image($UnCheck,$this->pdf->GetX()+22,$this->pdf->GetY(),6)),0,0,'R',0);
      $this->pdf->Cell(40,$height[1],iconv('UTF-8', 'cp874', 'ไม่เปลี่ยนอะไหล่'),0,1,'L',0);
      $this->pdf->Cell(30,$height[1],iconv('UTF-8', 'cp874', $this->pdf->Image($UnCheck,$this->pdf->GetX()+22,$this->pdf->GetY(),6)),0,0,'R',0);
      $this->pdf->Cell(40,$height[1],iconv('UTF-8', 'cp874', 'เปลี่ยนอะไหล่'),0,1,'L',0);
      $this->pdf->Cell(40,$height[1],iconv('UTF-8', 'cp874', $this->pdf->Image($UnCheck,$this->pdf->GetX()+32,$this->pdf->GetY(),6)),0,0,'R',0);
      $this->pdf->Cell(40,$height[1],iconv('UTF-8', 'cp874', 'อะไหล่ภายนอก'),0,1,'L',0);
      $this->pdf->Cell(40,$height[1],iconv('UTF-8', 'cp874', $this->pdf->Image($UnCheck,$this->pdf->GetX()+32,$this->pdf->GetY(),6)),0,0,'R',0);
      $this->pdf->Cell(24,$height[1],iconv('UTF-8', 'cp874', 'อะไหล่ภายใน'),0,0,'L',0);
    //******************************* date start ************************************************************************
    $this->pdf->Cell(18,$height[1],iconv('UTF-8', 'cp874', 'วันที่สั่งซื้อ '),0,0,'L',0);
      $this->pdf->Cell(20,$height[1],iconv('UTF-8', 'cp874',  date('d-m-Y',strtotime($DATA_REPAIR_REQ->SPAREPART_START_DATE))),'B',0,'C',0);
      $this->pdf->Cell(10,$height[1],iconv('UTF-8', 'cp874', 'เวลา : '),0,0,'L',0);
      $this->pdf->Cell(20,$height[1],iconv('UTF-8', 'cp874',  date('H:i',strtotime($DATA_REPAIR_REQ->SPAREPART_START_TIME))),'B',1,'C',0);
    //******************************* date end ************************************************************************
    $this->pdf->Cell(64,$height[1],iconv('UTF-8', 'cp874', ''),0,0,'R',0);
      $this->pdf->Cell(18,$height[1],iconv('UTF-8', 'cp874', 'วันที่รับเข้า '),0,0,'L',0);
      $this->pdf->Cell(20,$height[1],iconv('UTF-8', 'cp874',  date('d-m-Y',strtotime($DATA_REPAIR_REQ->SPAREPART_START_DATE))),'B',0,'C',0);
      $this->pdf->Cell(10,$height[1],iconv('UTF-8', 'cp874', 'เวลา : '),0,0,'L',0);
      $this->pdf->Cell(20,$height[1],iconv('UTF-8', 'cp874',  date('H:i',strtotime($DATA_REPAIR_REQ->SPAREPART_START_TIME))),'B',1,'C',0);
    $this->pdf->Cell(45,$height[1],iconv('UTF-8', 'cp874', 'ดำเนินการซ่อม : '),0,1,'R',0);
    //******************************* date start ************************************************************************
    $this->pdf->Cell(30,$height[1],iconv('UTF-8', 'cp874', 'เริ่ม วันที่ :'),0,0,'R',0);
      $this->pdf->Cell(40,$height[1],iconv('UTF-8', 'cp874',  date('d-m-Y',strtotime($DATA_REPAIR_REQ->WORKERIN_START_DATE))),0,0,'C',0);
      $this->pdf->Text(40,163,'_______________________');
      $this->pdf->Cell(20,$height[1],iconv('UTF-8', 'cp874', 'เวลา :'),0,0,'R',0);
      $this->pdf->Cell(40,$height[1],iconv('UTF-8', 'cp874',  date('H:i',strtotime($DATA_REPAIR_REQ->WORKERIN_START_TIME))),0,1,'C',0);
      $this->pdf->Text(100,163,'_______________________');
    //******************************* date end ************************************************************************
    $this->pdf->Cell(30,$height[1],iconv('UTF-8', 'cp874', 'เสร็จ วันที่ :'),0,0,'R',0);
      $this->pdf->Cell(40,$height[1],date('d-m-Y',strtotime($DATA_REPAIR_REQ->WORKERIN_END_TIME)),0,0,'C',0);
      $this->pdf->Text(40,169,'_______________________');
      $this->pdf->Cell(20,$height[1],iconv('UTF-8', 'cp874', 'เวลา :'),0,0,'R',0);
      $this->pdf->Cell(40,$height[1],date('H:i',strtotime($DATA_REPAIR_REQ->WORKERIN_END_TIME)),0,1,'C',0);
      $this->pdf->Text(100,169,'_______________________');
      $this->pdf->Cell(194,$height[8],'',0,1,'R',0);


    $this->pdf->Cell(194,3,iconv('UTF-8', 'cp874', ''),'T',1,'R',0);
    $this->pdf->SetX(15);
    $this->pdf->MultiCell(184,6,iconv('UTF-8', 'cp874', 'วิธีแก้ไข : ' .$DATA_REPAIR_REQ->REPAIR_SUBSELECT_NAME.''),0,'L',0);
    $this->pdf->Text(28,184,'_________________________________________________________________________________________________');
    $this->pdf->Text(15,190,'_________________________________________________________________________________________________________');
    $this->pdf->Text(15,196,'_________________________________________________________________________________________________________');
    $this->pdf->SetXY(12,199);
    $this->pdf->SetFont('THSarabunNew','',12 );
    $this->pdf->Cell(120,$height[0],iconv('UTF-8', 'cp874', 'รายการอะไหล่ที่ใช้ '),1,1,'C',1);

    $this->pdf->Cell(2,$height[0],iconv('UTF-8', 'cp874', ' '),0,0,'C',0);
    $this->pdf->Cell(10,$height[0],iconv('UTF-8', 'cp874', 'ลำดับ '),1,0,'C',1);
    $this->pdf->Cell(40,$height[0],iconv('UTF-8', 'cp874', 'ชื่ออะไหล่/อุปกรณ์ '),1,0,'C',1);
    $this->pdf->Cell(20,$height[0],iconv('UTF-8', 'cp874', 'หน่วย'),1,0,'C',1);
    $this->pdf->Cell(10,$height[0],iconv('UTF-8', 'cp874', 'จำนวน'),1,0,'C',1);
    $this->pdf->Cell(20,$height[0],iconv('UTF-8', 'cp874', 'ราคา'),1,0,'C',1);
    $this->pdf->Cell(20,$height[0],iconv('UTF-8', 'cp874', 'จำนวนเงิน'),1,1,'C',1);
    $this->pdf->Cell(2,$height[0],iconv('UTF-8', 'cp874', ' '),0,0,'C',0);
    for ($i=1; $i < 9 ; $i++) {
      $this->pdf->Cell(10,$height[0],iconv('UTF-8', 'cp874', $i),1,0,'C',0);
      $this->pdf->Cell(40,$height[0],iconv('UTF-8', 'cp874', ',สายพาน '),1,0,'C',0);
      $this->pdf->Cell(20,$height[0],iconv('UTF-8', 'cp874', 'เส้น'),1,0,'C',0);
      $this->pdf->Cell(10,$height[0],iconv('UTF-8', 'cp874', '1'),1,0,'C',0);
      $this->pdf->Cell(20,$height[0],iconv('UTF-8', 'cp874', '500'),1,0,'C',0);
      $this->pdf->Cell(20,$height[0],iconv('UTF-8', 'cp874', '500'),1,1,'C',0);
      $this->pdf->Cell(2,$height[0],iconv('UTF-8', 'cp874', ' '),0,0,'C',0);
    }


    $this->pdf->Cell(100,$height[0],iconv('UTF-8', 'cp874', 'รวมทั้งหมด '),1,0,'C',1);
    $this->pdf->Cell(20,$height[0],iconv('UTF-8', 'cp874', '500 '),1,1,'C',1);
    $this->pdf->SetY(260);
    $this->pdf->Cell(194,8,iconv('UTF-8', 'cp874', '*การรับประกันหลังซ่อม : .......................................................................................................................................................................................................................................'),0,1,'L',0);

    $this->pdf->Cell(80,$height[0],iconv('UTF-8', 'cp874', 'สรุประยะเวลาการซ่อมเครื่องจักร '),1,0,'C',0);
    $this->pdf->Cell(60,$height[0],iconv('UTF-8', 'cp874', 'ฝ่ายผลิตลงชื่อรับ '),1,0,'C',0);
    $this->pdf->Cell(54,$height[0],iconv('UTF-8', 'cp874', 'วันที่ตรวจสอบ '),1,1,'C',0);
    $this->pdf->Cell(80,15,iconv('UTF-8', 'cp874', 'สรุประยะเวลาการซ่อมเครื่องจักร '),1,0,'C',0);
    $this->pdf->Cell(60,15,iconv('UTF-8', 'cp874', 'ฝ่ายผลิตลงชื่อรับ '),1,0,'C',0);
    $this->pdf->Cell(54,15,iconv('UTF-8', 'cp874', 'วันที่ตรวจสอบ '),1,1,'C',0);
    $this->pdf->Text(10,292,iconv('UTF-8', 'cp874', 'อายุการจัดเก็บ 1 ปี'));
    $this->pdf->Text(170,292,iconv('UTF-8', 'cp874', 'FM-MA-08 REV.3 :15 Oct 09'));

    $this->pdf->Output();
  }


}
