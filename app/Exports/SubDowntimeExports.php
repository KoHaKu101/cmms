<?php

namespace App\Exports;

use App\Models\Machine\MachineRepairREQ;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;



class SubDowntimeExports implements FromQuery,WithHeadings,WithTitle,ShouldAutoSize
{
  use Exportable;
  /**
    *@return \Illuminate\Support\Collection
    */
  private $year;
  private $month;
  public function __construct(int $year,int $month){
    $this->year = $year;
    $this->month = $month;
  }

  public function query(){
    $year = $this->year;
    $month  = $this->month;

    $DATA_REPAIR = MachineRepairREQ::query()
                                   ->selectraw('MACHINE_LINE,MACHINE_CODE
                                   ,dbo.decode_utf8(MACHINE_NAME) as MACHINE_NAME_TH
                                   ,REPAIR_SUBSELECT_NAME
                                   ,REPAIR_DETAIL
                                   ,EMP_CODE
                                   ,dbo.decode_utf8(EMP_NAME) as EMP_NAME_TH
                                   ,DOC_DATE,REPAIR_REQ_TIME
                                   ,INSPECTION_RESULT_TIME
                                   ,SPAREPART_RESULT_TIME
                                   ,WORKERIN_RESULT_TIME
                                   ,WORKEROUT_RESULT_TIME
                                   ,DOWNTIME
                                   ,dbo.decode_utf8(CLOSE_BY) as CLOSE_BY')
                                   ->where('DOC_YEAR','=',$year)
                                   ->where('DOC_MONTH','=',$month)->where('CLOSE_STATUS','=',1)->orderBy('DOWNTIME','DESC');


      return $DATA_REPAIR;
  }
  public function title(): string{
    $MONTH_TH = array(1 => "มกราคม",2 => "กุมภาพันธ์",3 =>"มีนาคม",4 => "เมษายน",5 =>"พฤษภาคม",6 =>"มิถุนายน",
                      7 =>"กรกฎาคม",8 =>"สิงหาคม",9 =>"กันยายน",10 =>"ตุลาคม",11 => "พฤศจิกายน",12 =>"ธันวาคม");
    return 'เดือน ' . $MONTH_TH[$this->month];
  }
  public function headings():array{
    return [
      'Line'
      ,'MC-CODE'
      ,'MC-Name'
      ,'รหัสผู้แจ้ง'
      ,'ชื่อผู้แจ้ง'
      ,'อาการเสีย'
      ,'อาการเสีย'
      ,'ระยะเวลาตรวจสอบ'
      ,'ระยะเวลาสั่งซื้ออะไหล่'
      ,'ระยะเวลาซ่อมของช่างภายใน'
      ,'ระยะเวลาซ่อมของช่างภายนอก'
      ,'ระยะเวลาเครื่องหยุด'
      ,'วันที่ทำการแจ้ง'
      ,'เวลาที่แจ้ง'
      ,'ปิดเอกสารโดย'
    ];
  }

}
