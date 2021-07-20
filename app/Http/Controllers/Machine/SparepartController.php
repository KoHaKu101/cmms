<?php

namespace App\Http\Controllers\Machine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
//******************** model ***********************
use App\Models\Machine\SparePart;
use App\Models\Machine\HistorySparepart;
use App\Models\Machine\RepairSparepart;
use App\Models\Machine\Machine;

//***************** Controller ************************
use App\Http\Controllers\PDF\HeaderFooterPDF\HistorySparepart as HistorySparepartHEAD;

//************** Package form github ***************
use RealRashid\SweetAlert\Facades\Alert;



class SparepartController extends Controller
{
  protected $pdf;

  public function __construct(){
    $this->middleware('auth');
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


    public function StockList(){
      $DATA_SPAREPART = SparePart::where('STATUS','=',9)->orderBy('SPAREPART_NAME')->paginate(10);
      return View('machine.sparepart.stock.index',compact('DATA_SPAREPART'));
    }

    public function RecSparepartList(){

    }

    public function RecSparepart(){

    }

    public function HistoryPDF(HistorySparepartHEAD $HistorySparepartHEAD,Request $request){
      $this->pdf = $HistorySparepartHEAD;
      $UNID = $request->UNID ;
      $SPAREPART = SparePart::where('UNID','=',$UNID)->first();
      $DATA_HISTORY = HistorySparepart::select('*')->selectraw("dbo.decode_utf8(RECODE_BY) as RECODE_BY_TH")->Where('SPAREPART_UNID','=',$UNID)->get();
      $this->pdf->AddFont('THSarabunNew','','THSarabunNew.php');
      $this->pdf->AddFont('THSarabunNew','B','THSarabunNew_b.php');
      $this->pdf->SetFont('Arial','B',16);
      //หน้ากระดาษ
      $this->pdf->AddPage('P','A4');
      $this->pdf->header($UNID);

      $this->pdf->AliasNbPages();

      $this->pdf->SetFont('THSarabunNew','',14 );

      foreach ($DATA_HISTORY as $key => $row) {
        $this->pdf->Cell(21,6,iconv('UTF-8', 'cp874', $row->DOC_DATE),'BRL',0,'C');
        $this->pdf->Cell(20,6,iconv('UTF-8', 'cp874', $row->IN_TOTAL),'TBRL',0,'C');
        $this->pdf->Cell(16,6,iconv('UTF-8', 'cp874', $SPAREPART->UNIT),'TBRL',0,'C');
        $this->pdf->Cell(20,6,iconv('UTF-8', 'cp874', $row->OUT_TOTAL),'TBRL',0,'C');
        $this->pdf->Cell(16,6,iconv('UTF-8', 'cp874', $SPAREPART->UNIT),'TBRL',0,'C');
        $this->pdf->Cell(15,6,iconv('UTF-8', 'cp874', $row->TOTAL),'BRL',0,'C');
        $this->pdf->Cell(31,6,iconv('UTF-8', 'cp874', $row->RECODE_BY_TH),'BRL',0,'L');
        $this->pdf->Cell(47,6,iconv('UTF-8', 'cp874', $row->REMARK.' '.$row->MACHINE_CODE),'BRL',1,'L');
      }

      $this->pdf->Output('I','Plan.pdf');
      exit;
    }
    public function SaveHistory( $UNID_REF      = NULL,$MACHINE_UNID = NULL,$DOC_NO = NULL
                                 ,$TYPE         = NULL,$RECODE_BY = NULL){

      if ($TYPE == 'REPAIR') {
        $DATA_SPAREPART = RepairSparepart::Where('REPAIR_REQ_UNID','=',$UNID_REF)->get();
      }
      $DOC_NO         = $DOC_NO != NULL ? $DOC_NO : '';
      $MACHINE        = Machine::where('UNID','=',$MACHINE_UNID)->first();


      $ARRAY_REMARK   = array('REPAIR'=>'ซ่อมเครื่อง','PLAN_PM'=>'ตรวจเช็คเครื่อง','PLAN_PDM'=>'เปลี่ยนอะไหล่เครื่อง');

      foreach ($DATA_SPAREPART as $index => $row) {
        $SPAREPART      = Sparepart::where('UNID','=',$row->SPAREPART_UNID)->first();
        $UNID           = $this->randUNID('PMCS_CMMS_HISTORY_SPAREPART');
        $OUT_TOTAL      = $TYPE == 'REPAIR' ? $row->SPAREPART_TOTAL_OUT : ($TYPE == 'PLAN_PM' ? $row->TOTAL_PIC: ($TYPE == 'PLAN_PM' ? $row->ACT_QTY : 0) );

        $TOTAL_STOCK    = $SPAREPART->LAST_STOCK - $OUT_TOTAL;
        if ($row->SPAREPART_PAY_TYPE == 'CUT') {
          HistorySparepart::Insert([
            'UNID'           => $UNID
            ,'SPAREPART_UNID'=> $row->SPAREPART_UNID
            ,'MACHINE_UNID'  => $MACHINE->UNID
            ,'MACHINE_CODE'  => $MACHINE->MACHINE_CODE
            ,'DOC_NO'        => $DOC_NO
            ,'DOC_DATE'      => date('Y-m-d')
            ,'DOC_YEAR'      => date('Y')
            ,'DOC_MONTH'     => date('m')
            ,'TOTAL'         => $TOTAL_STOCK
            ,'IN_TOTAL'      => 0
            ,'OUT_TOTAL'     => $OUT_TOTAL
            ,'UNID_REF'      => $UNID_REF
            ,'TYPE'          => $TYPE
            ,'RECODE_BY'     => $RECODE_BY
            ,'REMARK'        => $ARRAY_REMARK[$TYPE]
            ,'CREATE_BY'     => Auth::user()->name
            ,'CREATE_TIME'   => carbon::now()
            ,'MODIFY_BY'     => Auth::user()->name
            ,'MODIFY_TIME'   => carbon::now()
          ]);
          Sparepart::where('UNID','=',$row->SPAREPART_UNID)->update([
            'LAST_STOCK' => $TOTAL_STOCK
          ]);
        }
      }

    }

  }
