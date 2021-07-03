<?php

namespace App\Http\Controllers\Machine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Middleware\VerifyCsrfToken;
use Carbon\Carbon;
use Auth;
use Cookie;
use Gate;
//******************** model ***********************
use App\Models\Machine\Machine;
use App\Models\Machine\SparePart;
use App\Models\Machine\RepairWorker;
use App\Models\Machine\RepairSparepart;
use App\Models\Machine\HistoryRepair;

use App\Http\Controllers\PDF\HeaderFooterPDF\HistoryHeaderFooter as HistoryHeaderFooter;
//************** Package form github ***************

class HistoryRepairController extends Controller
{
  public function __construct(HistoryHeaderFooter $HistoryHeaderFooter){
    $this->middleware('auth');
    $this->pdf = $HistoryHeaderFooter;
  }
  public function randUNID($table){
    $number = date("ymdhis", time());
    $length=7;
    do {
      for ($i=$length; $i--; $i>0) {
        $number .= mt_rand(0,9);
      }
    }
    while ( !empty(DB::table($table)
    ->where('UNID',$number)
    ->first(['UNID'])) );
    return $number;
   }


  public function RepairList(Request $request){
    $DATA_REPAIR_HEADER = HistoryRepair::select('MACHINE_UNID','MACHINE_CODE','MACHINE_NAME')
                                            ->groupBy('MACHINE_UNID','MACHINE_CODE','MACHINE_NAME')
                                            ->orderBy('MACHINE_CODE')->get();
    $DATA_REPAIR = HistoryRepair::select('*')->selectraw('dbo.decode_utf8(INSPECTION_BY) as INSPECTION_BY_TH')
                                   ->orderBy('MACHINE_CODE')->get();
    $DATA_SPAREPART = RepairSparepart::orderBy('SPAREPART_NAME')->get();
    return view('machine.history.list',compact('DATA_REPAIR','DATA_REPAIR_HEADER','DATA_SPAREPART'));
  }
  public function RepairPDF($MACHINE_UNID){
    $MACHINE_UNID =  0 ;
     $GROUP_HISTORY_REPAIR = HistoryRepair::select('MACHINE_CODE','MACHINE_UNID')
                                          ->where(function($query) use ($MACHINE_UNID){
                                            if ($MACHINE_UNID > 0) {
                                              $query->where('MACHINE_UNID','=',$MACHINE_UNID);
                                            }
                                          })->groupBy('MACHINE_UNID')->groupBy('MACHINE_CODE')
                                            ->orderBy('MACHINE_CODE')->get();

    $DATA_REPAIR_SPAREPART = RepairSparepart::orderBy('SPAREPART_NAME')->get();
    $this->pdf->AddFont('THSarabunNew','','THSarabunNew.php');
    $this->pdf->AddFont('THSarabunNew','B','THSarabunNew_b.php');
    $this->pdf->SetFont('THSarabunNew','',14 );


    foreach ($GROUP_HISTORY_REPAIR as $groupindex => $grouprow) {
      $this->pdf->AliasNbPages();
      $this->pdf->AddPage(['L','A4',]);
      $this->pdf->header($grouprow->MACHINE_UNID);
      $this->pdf->setXY(5,44);
      $this->pdf->setAutoPageBreak(false);
      $this->pdf->Rect(5,5,287,194);
      $this->pdf->SetFont('THSarabunNew','',10);

      $DATA_HISTORY_REPAIR = HistoryRepair::select('*')->selectraw(
                              'dbo.decode_utf8(INSPECTION_BY) as INSPECTION_BY_TH,
                               dbo.decode_utf8(REPAIR_BY)     as REPAIR_BY_TH,
                               dbo.decode_utf8(APPROVED_BY)   as APPROVED_BY_TH '
                              )->where('MACHINE_UNID','=',$grouprow->MACHINE_UNID)->orderBy('DOC_DATE')->get();
      foreach ($DATA_HISTORY_REPAIR as $index => $row) {
        $this->pdf->setX(5);
        $array_Text = array();
        foreach ($DATA_REPAIR_SPAREPART->where('REPAIR_REQ_UNID','=',$row->REPAIR_REQ_UNID) as $subindex => $subrow) {
          $array_Text[] = $subindex++.'. '.$subrow->SPAREPART_NAME;
          $array_n[] =  "\n";
        }
        $TEXT = implode("\n",$array_Text);
        $X= $this->pdf->getX();
        $this->pdf->SetWidths(array(10,16,22,40,40,16,16,35,20,24,24,24));
        $this->pdf->SetAligns(array('C','C','L','L','L','C','R','L','R','L','L','L'));
        $this->pdf->Row(array($index+1,date('d-m-Y',strtotime($row->DOC_DATE)),$row->DOC_NO,iconv('UTF-8','cp874', $row->REPAIR_REQ_DETAIL)
        ,iconv('UTF-8', 'cp874',$row->REPAIR_DETAIL),date('d-m-Y',strtotime($row->REPAIR_DATE)),iconv('UTF-8','cp874',number_format($row->DOWN_TIME).' นาที')
        ,iconv('UTF-8', 'cp874',$TEXT),iconv('UTF-8','cp874',number_format($row->TOTAL_COST).' บาท'),iconv('UTF-8','cp874',$row->INSPECTION_BY_TH)
        ,iconv('UTF-8','cp874',$row->REPAIR_BY_TH),iconv('UTF-8','cp874',$row->APPROVED_BY_TH)
        )) ;

        $Y= $this->pdf->getY();
        if ($Y > 190) {
          $this->pdf->AddPage(['L','A4',]);
          $this->pdf->header($grouprow->MACHINE_UNID);
          $this->pdf->setXY(5,44);
          $this->pdf->Rect(5,5,287,194);
          $this->pdf->SetFont('THSarabunNew','',10);
        }
      }
      // if ($GROUP_HISTORY_REPAIR->count() > 1) {
        // $this->pdf->AddPage(['L','A4',]);
        // $this->pdf->header($MACHINE_UNID);
        // $this->pdf->setXY(5,44);
        // $this->pdf->Rect(5,5,287,194);
        // $this->pdf->SetFont('THSarabunNew','',10);
      // }


    }

    $this->pdf->Output();
  }
}
