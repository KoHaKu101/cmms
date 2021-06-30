<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Machine\Machine;
use App\Models\Machine\MachineRepairREQ;
use App\Models\Machine\RepairWorker;
use App\Models\Machine\RepairSparepart;
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
    $DATA_REPAIR_REQ = MachineRepairREQ::select('*')->selectraw("dbo.decode_utf8(INSPECTION_NAME)as INSPECTION_NAME")->where('UNID','=',$UNID)->first();
    $DATA_WORKER = RepairWorker::select('WORKER_TYPE')->selectraw('dbo.decode_utf8(WORKER_NAME) as WORKER_NAME_TH')->where('REPAIR_REQ_UNID','=',$UNID)->get();
    $DATA_SPAREPART = RepairSparepart::where('REPAIR_REQ_UNID','=',$UNID)->get();
    $TYPE_WORKER = $DATA_WORKER[0]->WORKER_TYPE;
    $TYPE_SPARTPART = isset($DATA_SPAREPART[0]) ? $DATA_SPAREPART[0]->SPAREPART_TYPE_OUT : '';

    $WORKER_START_DATE = $TYPE_WORKER == 'IN' ? $DATA_REPAIR_REQ->WORKERIN_START_DATE : $DATA_REPAIR_REQ->WORKEROUT_START_DATE;
    $WORKER_START_TIME = $TYPE_WORKER == 'IN' ? $DATA_REPAIR_REQ->WORKERIN_START_TIME : $DATA_REPAIR_REQ->WORKEROUT_START_TIME;
    $WORKER_END_DATE   = $TYPE_WORKER == 'IN' ? $DATA_REPAIR_REQ->WORKERIN_END_DATE   : $DATA_REPAIR_REQ->WORKEROUT_END_DATE;
    $WORKER_END_TIME   = $TYPE_WORKER == 'IN' ? $DATA_REPAIR_REQ->WORKERIN_END_TIME   : $DATA_REPAIR_REQ->WORKEROUT_END_TIME;

    //*********** down time ********************************************
    $DOWNTIME = $DATA_REPAIR_REQ->DOWNTIME;
    $day = floor ($DOWNTIME / 1440);
    $hour = floor ($DOWNTIME / 60);
    $minutes = $DOWNTIME - ($hour * 60);
    //*********** INSPECTION ********************************************
    $INSPECTION_START = date_create($DATA_REPAIR_REQ->INSPECTION_START_DATE.$DATA_REPAIR_REQ->INSPECTION_START_TIME);
    $INSPECTION_END   = date_create($DATA_REPAIR_REQ->INSPECTION_END_DATE.$DATA_REPAIR_REQ->INSPECTION_END_TIME);
    $INSPECTION_DIFF  = date_diff($INSPECTION_START,$INSPECTION_END)->format('%d วันที่ %h ชั่วโมง %i นาที');
    //*********** WORKER OUT ********************************************
    $WORKEROUT_START = date_create($DATA_REPAIR_REQ->WORKEROUT_START_DATE.$DATA_REPAIR_REQ->WORKEROUT_START_TIME);
    $WORKEROUT_END   = date_create($DATA_REPAIR_REQ->WORKEROUT_END_DATE.$DATA_REPAIR_REQ->WORKEROUT_END_TIME);
    $WORKEROUT_DIFF  = date_diff($WORKEROUT_START,$WORKEROUT_END)->format('%d วันที่ %h ชั่วโมง %i นาที');
    // //*********** WORKER IN ********************************************
    $WORKERIN_START = date_create($DATA_REPAIR_REQ->WORKERIN_START_DATE.$DATA_REPAIR_REQ->WORKERIN_START_TIME);
    $WORKERIN_END   = date_create($DATA_REPAIR_REQ->WORKERIN_END_DATE.$DATA_REPAIR_REQ->WORKERIN_END_TIME);
    $WORKERIN_DIFF  = date_diff($WORKERIN_START,$WORKERIN_END)->format('%d วันที่ %h ชั่วโมง %i นาที');
    // //*********** WORKER IN ********************************************
    $SPAREPART_START = date_create($DATA_REPAIR_REQ->SPAREPART_START_DATE.$DATA_REPAIR_REQ->SPAREPART_START_TIME);
    $SPAREPART_END   = date_create($DATA_REPAIR_REQ->SPAREPART_END_DATE.$DATA_REPAIR_REQ->SPAREPART_END_TIME);
    $SPAREPART_DIFF  = date_diff($SPAREPART_START,$SPAREPART_END)->format('%d วันที่ %h ชั่วโมง %i นาที');


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
    $this->pdf->header($UNID);
    $this->pdf->SetFont('THSarabunNew','',14 );
    $this->pdf->Rect(10,5,194,283);
    $this->pdf->SetAutoPageBreak(true, 5);
    $height = array(5,8,10,17,34,49,59,4,6);
    //********************************  box A ************************************************************
    $this->pdf->Rect(150,69,54,14);
      $this->pdf->Rect(150,69,6,6);
      $this->pdf->text(152,73,iconv('UTF-8', 'cp874', 'A'));
      $this->pdf->text(164,74,iconv('UTF-8', 'cp874', 'รวมระยะเวลาที่ใช้'));
      $this->pdf->text(162,80,iconv('UTF-8', 'cp874', $INSPECTION_DIFF));
    //********************************  box B ************************************************************
    $this->pdf->Rect(150,118,54,14);
      $this->pdf->Rect(150,118,6,6);
      $this->pdf->text(152,122,iconv('UTF-8', 'cp874', 'B'));
      $this->pdf->text(164,123,iconv('UTF-8', 'cp874', 'รวมระยะเวลาที่ใช้'));
      $this->pdf->text(162,130,iconv('UTF-8', 'cp874',  ($TYPE_WORKER == 'IN' ? $WORKERIN_DIFF : $WORKEROUT_DIFF) ));
    //********************************  box C ************************************************************
    $this->pdf->Rect(150,152,54,14);
      $this->pdf->Rect(150,152,6,6);
      $this->pdf->text(152,156,iconv('UTF-8', 'cp874', 'C'));
      $this->pdf->text(164,157,iconv('UTF-8', 'cp874', 'รวมระยะเวลาที่ใช้'));
      $this->pdf->text(162,163,iconv('UTF-8', 'cp874', $SPAREPART_DIFF));
    //********************************  box D ************************************************************
    // $this->pdf->Rect(150,157,54,14);
    //   $this->pdf->Rect(150,157,6,6);
    //   $this->pdf->text(152,161,iconv('UTF-8', 'cp874', 'D'));
    //   $this->pdf->text(164,162,iconv('UTF-8', 'cp874', 'รวมระยะเวลาที่ใช้'));
    //   $this->pdf->text(162,168,iconv('UTF-8', 'cp874', $WORKERIN_DIFF));
    //******************************* Box RepairBy Ane ReportBy ********************************************
    $this->pdf->Rect(148,199,52,35);
      $WORK_1 = isset($DATA_WORKER[0]->WORKER_NAME_TH) ? $DATA_WORKER[0]->WORKER_NAME_TH : '';
      $WORK_2 = isset($DATA_WORKER[1]->WORKER_NAME_TH) ? $DATA_WORKER[1]->WORKER_NAME_TH : '';
      $WORK_3 = isset($DATA_WORKER[2]->WORKER_NAME_TH) ? $DATA_WORKER[2]->WORKER_NAME_TH : '';
      $this->pdf->Rect(148,199,52,7);
        $this->pdf->text(161,204,iconv('UTF-8', 'cp874', 'ช่างผู้ดำเนินการซ่อม'));
      $this->pdf->text(154,212,iconv('UTF-8', 'cp874', '1  '.($TYPE_WORKER == 'IN' ? $WORK_1 : '')));
      $this->pdf->text(154,220,iconv('UTF-8', 'cp874', '2  '.($TYPE_WORKER == 'IN' ? $WORK_2 : '')));
      $this->pdf->text(154,228,iconv('UTF-8', 'cp874', '3  '.($TYPE_WORKER == 'IN' ? $WORK_3 : '')));
      $this->pdf->text(158,212,iconv('UTF-8', 'cp874', '_____________________'));
      $this->pdf->text(158,220,iconv('UTF-8', 'cp874', '_____________________'));
      $this->pdf->text(158,228,iconv('UTF-8', 'cp874', '_____________________'));
      $this->pdf->Rect(148,234,52,25);
      $this->pdf->text(150,240,iconv('UTF-8', 'cp874', 'ผู้รายงาน : '.$DATA_REPAIR_REQ->INSPECTION_NAME ));
      $this->pdf->text(150,248,iconv('UTF-8', 'cp874', 'วันที่รายงาน : '.($TYPE_WORKER == 'IN' ? date('d-m-Y',strtotime($DATA_REPAIR_REQ->WORKERIN_END_DATE)) : date('d-m-Y',strtotime($DATA_REPAIR_REQ->WORKEROUT_END_DATE)))));
      $this->pdf->text(150,256,iconv('UTF-8', 'cp874', 'วันที่ส่งคืนผลิต : '.($TYPE_WORKER == 'IN' ? date('d-m-Y',strtotime($DATA_REPAIR_REQ->WORKERIN_END_DATE)) : date('d-m-Y',strtotime($DATA_REPAIR_REQ->WORKEROUT_END_DATE)))));
      $this->pdf->text(165,240,iconv('UTF-8', 'cp874', '___________________'));
      $this->pdf->text(169,248,iconv('UTF-8', 'cp874', '_________________'));
      $this->pdf->text(171,256,iconv('UTF-8', 'cp874', '________________'));
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
    $this->pdf->SetFillColor(200,200,200);
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
    $this->pdf->Cell(30,$height[1],iconv('UTF-8', 'cp874', 'ดำเนินการซ่อมโดย '),0,1,'R',0);
    $this->pdf->Cell(22,$height[1],$this->pdf->Image(($TYPE_WORKER == 'OUT' ? $Check : $UnCheck),$this->pdf->GetX()+15,$this->pdf->GetY(),6),0,0,'R',0);
      $this->pdf->Cell(40,$height[1],iconv('UTF-8', 'cp874', 'ช่างซ่อมภายนอก / จ้างซ่อม'),0,0,'L',0);
    $this->pdf->Cell(22,$height[1],$this->pdf->Image(($TYPE_WORKER == 'IN' ? $Check : $UnCheck),$this->pdf->GetX()+15,$this->pdf->GetY(),6),0,0,'R',0);
      $this->pdf->Cell(40,$height[1],iconv('UTF-8', 'cp874', 'ช่างซ่อมภายในของบริษัท'),0,1,'L',0);

    $this->pdf->Cell(30,$height[1],iconv('UTF-8', 'cp874', 'ดำเนินการซ่อม '),0,1,'R',0);

  //******************************* date start ************************************************************************
    $this->pdf->Cell(30,$height[1],iconv('UTF-8', 'cp874', 'เริ่ม วันที่ :'),0,0,'R',0);
      $this->pdf->Cell(30,$height[1],(  date('d-m-Y',strtotime($WORKER_START_DATE)) ),0,0,'R',0);
      $this->pdf->Text(40,122,'_______________________');
      $this->pdf->Cell(30,$height[1],iconv('UTF-8', 'cp874', 'เวลา :'),0,0,'R',0);
      $this->pdf->Cell(40,$height[1],(  date('H:i',strtotime($WORKER_START_TIME)) ),0,1,'C',0);
      $this->pdf->Text(100,122,'_______________________');
  //******************************* date end ************************************************************************
    $this->pdf->Cell(30,$height[1],iconv('UTF-8', 'cp874', 'เสร็จ วันที่ :'),0,0,'R',0);
      $this->pdf->Cell(30,$height[1],(  date('d-m-Y',strtotime($WORKER_END_DATE)) ),0,0,'R',0);
      $this->pdf->Text(40,130  ,'_______________________');
      $this->pdf->Cell(30,$height[1],iconv('UTF-8', 'cp874', 'เวลา :'),0,0,'R',0);
      $this->pdf->Cell(40,$height[1],(  date('H:i',strtotime($WORKER_END_TIME)) ),0,1,'C',0);
      $this->pdf->Text(100,130 ,'_______________________');
    $this->pdf->setY(135);
    $this->pdf->Cell(22,$height[1],iconv('UTF-8', 'cp874', $this->pdf->Image(($DATA_SPAREPART->count() == 0 ? $Check : $UnCheck),$this->pdf->GetX()+15,$this->pdf->GetY(),6)),0,0,'R',0);
      $this->pdf->Cell(40,$height[1],iconv('UTF-8', 'cp874', 'ไม่เปลี่ยนอะไหล่'),0,0,'L',0);
      $this->pdf->Cell(22,$height[1],iconv('UTF-8', 'cp874', $this->pdf->Image(($DATA_REPAIR_REQ->SPAREPART_START_DATE == NULL ? $Check : $UnCheck),$this->pdf->GetX()+15,$this->pdf->GetY(),6)),0,0,'R',0);
      $this->pdf->Cell(40,$height[1],iconv('UTF-8', 'cp874', 'อะไหล่ภายใน'),0,1,'L',0);
      $this->pdf->Cell(22,$height[1],iconv('UTF-8', 'cp874', $this->pdf->Image(($DATA_SPAREPART->count() > 0 ? $Check : $UnCheck),$this->pdf->GetX()+15,$this->pdf->GetY(),6)),0,0,'R',0);
      $this->pdf->Cell(40,$height[1],iconv('UTF-8', 'cp874', 'เปลี่ยนอะไหล่'),0,0,'L',0);
      $this->pdf->Cell(22,$height[1],iconv('UTF-8', 'cp874', $this->pdf->Image(($DATA_REPAIR_REQ->SPAREPART_START_DATE != NULL ? $Check : $UnCheck),$this->pdf->GetX()+15,$this->pdf->GetY(),6)),0,0,'R',0);
      $this->pdf->Cell(24,$height[1],iconv('UTF-8', 'cp874', 'อะไหล่ภายนอก'),0,1,'L',0);

    //******************************* date start ************************************************************************
    $this->pdf->Cell(30,$height[1],iconv('UTF-8', 'cp874', 'วันที่สั่งซื้อ :'),0,0,'R',0);
      $this->pdf->Cell(30,$height[1],(isset($DATA_REPAIR_REQ->SPAREPART_START_DATE) ? date('d-m-Y',strtotime($DATA_REPAIR_REQ->SPAREPART_START_DATE)) : ''),0,0,'R',0);
      $this->pdf->Text(40,157 ,'_______________________');
      $this->pdf->Cell(30,$height[1],iconv('UTF-8', 'cp874', 'เวลา :'),0,0,'R',0);
      $this->pdf->Cell(40,$height[1],(isset($DATA_REPAIR_REQ->SPAREPART_START_DATE) ? date('H:i',strtotime($DATA_REPAIR_REQ->SPAREPART_START_TIME)) : ''),0,1,'C',0);
      $this->pdf->Text(100,157 ,'_______________________');
    //******************************* date end ************************************************************************
      $this->pdf->Cell(30,$height[1],iconv('UTF-8', 'cp874', 'วันที่รับเข้า:'),0,0,'R',0);
      $this->pdf->Cell(30,$height[1],(isset($DATA_REPAIR_REQ->SPAREPART_END_DATE) ? date('d-m-Y',strtotime($DATA_REPAIR_REQ->SPAREPART_END_DATE)) : ''),0,0,'R',0);
      $this->pdf->Text(40,165 ,'_______________________');
      $this->pdf->Cell(30,$height[1],iconv('UTF-8', 'cp874', 'เวลา :'),0,0,'R',0);
      $this->pdf->Cell(40,$height[1],(isset($DATA_REPAIR_REQ->SPAREPART_END_DATE) ? date('H:i',strtotime($DATA_REPAIR_REQ->SPAREPART_END_TIME)) : ''),0,1,'C',0);
      $this->pdf->Text(100,165 ,'_______________________');
    //******************************* date start ************************************************************************

    $this->pdf->SetY(170);

    $this->pdf->Cell(194,3,iconv('UTF-8', 'cp874', ''),'T',1,'R',0);
    $this->pdf->SetX(15);
    $this->pdf->MultiCell(184,6,iconv('UTF-8', 'cp874', 'วิธีแก้ไข : เปลี่ยนสายลมเปลี่ยนTOOL จาก 8ทท เป็น 12 mm และติดตั้งถังพักลม SET UP ALINEMENT TOOLING'),0,'L',0);
    $this->pdf->Text(28,178,'_________________________________________________________________________________________________');
    $this->pdf->Text(15,184,'_________________________________________________________________________________________________________');
    $this->pdf->Text(15,190,'_________________________________________________________________________________________________________');
    $this->pdf->SetXY(12,199);
    $this->pdf->SetFont('THSarabunNew','',12 );
    $this->pdf->Cell(130,$height[0],iconv('UTF-8', 'cp874', 'รายการอะไหล่ที่ใช้ '),1,1,'C',1);

    $this->pdf->Cell(2,$height[0],iconv('UTF-8', 'cp874', ' '),0,0,'C',0);
    $this->pdf->Cell(10,$height[0],iconv('UTF-8', 'cp874', 'ลำดับ '),1,0,'C',1);
    $this->pdf->Cell(50,$height[0],iconv('UTF-8', 'cp874', 'ชื่ออะไหล่/อุปกรณ์ '),1,0,'C',1);
    $this->pdf->Cell(15,$height[0],iconv('UTF-8', 'cp874', 'หน่วย'),1,0,'C',1);
    $this->pdf->Cell(15,$height[0],iconv('UTF-8', 'cp874', 'จำนวน'),1,0,'C',1);
    $this->pdf->Cell(20,$height[0],iconv('UTF-8', 'cp874', 'ราคา'),1,0,'C',1);
    $this->pdf->Cell(20,$height[0],iconv('UTF-8', 'cp874', 'จำนวนเงิน'),1,1,'C',1);
    $this->pdf->Cell(2,$height[0],iconv('UTF-8', 'cp874', ' '),0,0,'C',0);
    if ($DATA_SPAREPART->count() < 1) {
      $this->pdf->Cell(10,$height[0],iconv('UTF-8', 'cp874', '-' ),1,0,'C',0);
      $this->pdf->Cell(50,$height[0],iconv('UTF-8', 'cp874', '-'),1,0,'C',0);
      $this->pdf->Cell(20,$height[0],iconv('UTF-8', 'cp874', '-'),1,0,'C',0);
      $this->pdf->Cell(10,$height[0],iconv('UTF-8', 'cp874', '-'),1,0,'C',0);
      $this->pdf->Cell(20,$height[0],iconv('UTF-8', 'cp874', '-'),1,0,'C',0);
      $this->pdf->Cell(20,$height[0],iconv('UTF-8', 'cp874', '-'),1,1,'C',0);
      $this->pdf->Cell(2,$height[0],iconv('UTF-8', 'cp874', ''),0,0,'C',0);
    }
    foreach ($DATA_SPAREPART as $key => $row){
      $this->pdf->Cell(10,$height[0],iconv('UTF-8', 'cp874', $key+1 ),1,0,'C',0);
      $this->pdf->Cell(50,$height[0],iconv('UTF-8', 'cp874', $row->SPAREPART_NAME),1,0,'L',0);
      $this->pdf->Cell(15,$height[0],iconv('UTF-8', 'cp874', $row->SPAREPART_UNIT),1,0,'C',0);
      $this->pdf->Cell(15,$height[0],iconv('UTF-8', 'cp874', $row->SPAREPART_TOTAL_OUT),1,0,'C',0);
      $this->pdf->Cell(20,$height[0],iconv('UTF-8', 'cp874', number_format($row->SPAREPART_COST)),1,0,'C',0);
      $this->pdf->Cell(20,$height[0],iconv('UTF-8', 'cp874', number_format($row->SPAREPART_TOTAL_COST)),1,1,'C',0);
      $this->pdf->Cell(2,$height[0],iconv('UTF-8', 'cp874', ''),0,0,'C',0);
    }


    $this->pdf->Cell(110,$height[0],iconv('UTF-8', 'cp874', 'รวมทั้งหมด '),1,0,'C',1);
    $this->pdf->Cell(20,$height[0],iconv('UTF-8', 'cp874', number_format($DATA_REPAIR_REQ->TOTAL_COST_SPAREPART)),1,1,'C',1);
    $this->pdf->SetY(260);
    $this->pdf->Cell(194,8,iconv('UTF-8', 'cp874', '*การรับประกันหลังซ่อม : .......................................................................................................................................................................................................................................'),0,1,'L',0);

    $this->pdf->Cell(80,$height[0],iconv('UTF-8', 'cp874', 'สรุประยะเวลาการซ่อมเครื่องจักร (A+B+C)'),1,0,'C',0);
    $this->pdf->Cell(60,$height[0],iconv('UTF-8', 'cp874', 'ฝ่ายผลิตลงชื่อรับ '),1,0,'C',0);
    $this->pdf->Cell(54,$height[0],iconv('UTF-8', 'cp874', 'วันที่ตรวจสอบ '),1,1,'C',0);
    $this->pdf->Cell(80,15,iconv('UTF-8', 'cp874', ''),1,0,'C',0);
    $this->pdf->Cell(60,15,iconv('UTF-8', 'cp874', ''),1,0,'C',0);
    $this->pdf->Cell(54,15,iconv('UTF-8', 'cp874', ''),1,1,'C',0);
    $this->pdf->Text(10,292,iconv('UTF-8', 'cp874', 'อายุการจัดเก็บ 1 ปี'));
    $this->pdf->Text(170,292,iconv('UTF-8', 'cp874', 'FM-MA-08 REV.3 :15 Oct 09'));

    $this->pdf->Text(22,282,iconv('UTF-8', 'cp874',$hour));
    $this->pdf->Text(46,282,iconv('UTF-8', 'cp874',$minutes));
    $this->pdf->Text(73,282,iconv('UTF-8', 'cp874',$DATA_REPAIR_REQ->DOWNTIME));

    $this->pdf->Text(15,283,iconv('UTF-8', 'cp874','...................ชม.'));
    $this->pdf->Text(40,283,iconv('UTF-8', 'cp874','...................นาที'));
    $this->pdf->Text(65,283,iconv('UTF-8', 'cp874','รวม...................นาที'));

    $this->pdf->Output();
  }


}
