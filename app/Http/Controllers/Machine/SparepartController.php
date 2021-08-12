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
use App\Models\Machine\SparePartRec;
use App\Models\Machine\PmPlanSparepart;
use App\Models\Machine\EMPName;
use App\Models\Machine\SparePartPlan;

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


    public function StockList(Request $request){
      $SEARCH     = $request->SEARCH_SPAREPART;
      $STATUS     = $request->STATUS;
      $SORT_LIMIT = $request->SORT_LIMIT;

      $DATA_SPAREPART = SparePart::where(function($query) use ($SEARCH){
                                      if (isset($SEARCH)) {
                                        $query->where('SPAREPART_NAME','like','%'.$SEARCH.'%')
                                              ->orwhere('SPAREPART_CODE','like','%'.$SEARCH.'%');
                                      }
                                    })
                                  ->where(function($query) use ($STATUS){
                                      if ($STATUS == '1') {
                                        $query->whereRaw('LAST_STOCK > STOCK_MIN');
                                      }elseif ($STATUS == '2') {
                                        $query->whereRaw('LAST_STOCK <= STOCK_MIN');
                                      }
                                    })
                                  ->where('STATUS','=',9)->orderBy('SPAREPART_NAME')->paginate($SORT_LIMIT);
      return View('machine.sparepart.stock.index',compact('DATA_SPAREPART','SEARCH','STATUS','SORT_LIMIT'));
    }

    public function RecSparepartList(Request $request){
      $DATA_SPAREPART     = SparePart::where('STATUS','=',9)->orderBy('SPAREPART_NAME')->get();
      $DATA_SPAREPART_REC = SparepartRec::orderBy('DOC_DATE','DESC')->orderBy('CREATE_TIME','DESC')->get();
      $DATA_EMP           = EMPName::select('*')->selectraw('dbo.decode_utf8(EMP_NAME) as EMP_NAME_TH')->where('EMP_STATUS','=',9)->orderBy('EMP_NAME')->get();
      if($request->ajax()){
        if ($request->type == 'EMP') {
          $endcode          = EMPName::selectRaw("dbo.encode_utf8('$request->search') as SEARCH")->first();
          $DATA_EMP           = EMPName::select('*')->selectraw('dbo.decode_utf8(EMP_NAME) as EMP_NAME_TH')
                                        ->where('EMP_STATUS','=',9)->where(function($query) use ($endcode){
                                          $query->where('EMP_NAME','like','%'.$endcode->SEARCH.'%')
                                                ->orwhere('EMP_CODE','like','%'.$endcode->SEARCH.'%');
                                        })->orderBy('EMP_NAME')->get();
          return Response()->json($DATA_EMP);
        }elseif ($request->type == 'SPAREPART') {
          $SEARCH             = $request->search;
          $DATA_SPAREPART     = SparePart::where('STATUS','=',9)->where(function($query) use ($SEARCH){
                                            $query->where('SPAREPART_CODE','like','%'.$SEARCH.'%')
                                                  ->orwhere('SPAREPART_NAME','like','%'.$SEARCH.'%');
                                          })->orderBy('SPAREPART_NAME')->get();
          return Response()->json($DATA_SPAREPART);
        }
      }
      return View('machine.sparepart.stock.recindex',compact('DATA_SPAREPART','DATA_SPAREPART_REC','DATA_EMP'));
    }

    public function RecSparepartSave(Request $request){
      $SPAREPART_UNID         = $request->SPAREPART_UNID;
      $UNID_SPAREPARTREC      = $this->randUNID('PMCS_CMMS_SPAREPART_REC');
      $UNID_HISTORYSPAREPART  = $this->randUNID('PMCS_CMMS_HISTORY_SPAREPART');
      $SPAREPART              = Sparepart::where('UNID',$SPAREPART_UNID)->first();
      $IN_TOTAL               = $request->IN_TOTAL;
      $TOTAL                  = $SPAREPART->LAST_STOCK + $IN_TOTAL;
      $DOC_DATE               = $request->DOC_DATE;
      $DOC_NO                 = isset($request->DOC_NO) ? $request->DOC_NO : '';
      $EMP_NAME               = EMPName::where('EMP_CODE','=',$request->RECODE_BY)->first();
      $RECODE_BY              = $EMP_NAME->EMP_NAME;
      SparePartRec::insert([
        'UNID'              => $UNID_SPAREPARTREC
        ,'SPAREPART_UNID'   => $SPAREPART_UNID
        ,'SPAREPART_CODE'   => $SPAREPART->SPAREPART_CODE
        ,'SPAREPART_NAME'   => $SPAREPART->SPAREPART_NAME
        ,'SPAREPART_MODEL'  => $SPAREPART->SPAREPART_MODEL
        ,'SPAREPART_UNIT'   => $SPAREPART->UNIT
        ,'DOC_NO'           => $DOC_NO
        ,'DOC_DATE'         => date('Y-m-d',strtotime($DOC_DATE))
        ,'DOC_YEAR'         => date('Y',strtotime($DOC_DATE))
        ,'DOC_MONTH'        => date('m',strtotime($DOC_DATE))
        ,'TOTAL'            => $TOTAL
        ,'IN_TOTAL'         => $IN_TOTAL
        ,'RECODE_BY'        => $RECODE_BY
        ,'CREATE_BY'        => Auth::user()->name
        ,'CREATE_TIME'      => carbon::now()
        ,'MODIFY_BY'        => Auth::user()->name
        ,'MODIFY_TIME'      => carbon::now()
      ]);

      HistorySparepart::Insert([
        'UNID'           => $UNID_HISTORYSPAREPART
        ,'SPAREPART_UNID'=> $SPAREPART_UNID
        ,'MACHINE_UNID'  => ''
        ,'MACHINE_CODE'  => ''
        ,'DOC_NO'        => $DOC_NO
        ,'DOC_DATE'      => date('Y-m-d',strtotime($DOC_DATE))
        ,'DOC_YEAR'      => date('Y',strtotime($DOC_DATE))
        ,'DOC_MONTH'     => date('m',strtotime($DOC_DATE))
        ,'TOTAL'         => $TOTAL
        ,'IN_TOTAL'      => $IN_TOTAL
        ,'OUT_TOTAL'     => 0
        ,'UNID_REF'      => $UNID_SPAREPARTREC
        ,'TYPE'          => 'ADD_SPAREPART'
        ,'RECODE_BY'     => $RECODE_BY
        ,'REMARK'        => ''
        ,'CREATE_BY'     => Auth::user()->name
        ,'CREATE_TIME'   => carbon::now()
        ,'MODIFY_BY'     => Auth::user()->name
        ,'MODIFY_TIME'   => carbon::now()
      ]);
      SparePart::where('UNID','=',$SPAREPART_UNID)->update([
        'LAST_STOCK'        => $TOTAL
        ,'MODIFY_BY'        => Auth::user()->name
        ,'MODIFY_TIME'      => carbon::now()
      ]);
      alert()->success('บันทึกสำเร็จ')->autoClose(1500);
      return redirect()->back();
    }
    public function RecSparepartDelete(Request $request){

      $UNID               = $request->UNID;
      $SPAREPARTREC       = SparePartRec::where('UNID',$UNID);
      $SPAREPARTREC_FIRST = $SPAREPARTREC->first();
      $SPAREPART          = SparePart::select('LAST_STOCK')->where('UNID','=',$SPAREPARTREC_FIRST->SPAREPART_UNID)->first();
      $TOTAL              = $SPAREPART->LAST_STOCK - $SPAREPARTREC_FIRST->IN_TOTAL;
      SparePart::where('UNID','=',$SPAREPARTREC_FIRST->SPAREPART_UNID)->update([
        'LAST_STOCK' => $TOTAL
      ]);
      $SPAREPARTREC->delete();
      alert()->success('ลบรายการสำเร็จ')->autoclose(1500);
      return Redirect()->back();
    }
    public function AlertSparepartList(Request $request){
      // dd($request);
      $SEARCH         = $request->SEARCH_SPAREPART;
      $SORT_LIMIT     = $request->SORT_LIMIT != '' ? $request->SORT_LIMIT : 10;
      // dd($SORT_LIMIT);
      $DATA_SPAREPART = SparePart::where(function($query) use ($SEARCH){
                                      if (isset($SEARCH)) {
                                        $query->where('SPAREPART_NAME','like','%'.$SEARCH.'%')
                                              ->orwhere('SPAREPART_CODE','like','%'.$SEARCH.'%');
                                      }
                                    })->whereraw('STOCK_MIN >= LAST_STOCK')->where('STATUS','=',9)
                                      ->orderBy('SPAREPART_NAME')->paginate($SORT_LIMIT);
      return View('machine.sparepart.stock.alertindex',compact('DATA_SPAREPART','SEARCH','SORT_LIMIT'));
    }
    public function HistoryPDF(HistorySparepartHEAD $HistorySparepartHEAD,Request $request){
      $this->pdf = $HistorySparepartHEAD;
      $UNID = $request->UNID ;
      $SPAREPART = SparePart::where('UNID','=',$UNID)->first();
      $DATA_HISTORY = HistorySparepart::select('*')->selectraw("dbo.decode_utf8(RECODE_BY) as RECODE_BY_TH")
                                    ->Where('SPAREPART_UNID','=',$UNID)->get();
      $this->pdf->AddFont('THSarabunNew','','THSarabunNew.php');
      $this->pdf->AddFont('THSarabunNew','B','THSarabunNew_b.php');
      $this->pdf->SetFont('Arial','B',16);
      $this->pdf->setAutoPageBreak(true,10);
      //หน้ากระดาษ
      $this->pdf->AddPage('P','A4');
      $this->pdf->header($UNID);
      $this->pdf->Rect(10,5,186,279);
      $this->pdf->AliasNbPages();

      $this->pdf->SetFont('THSarabunNew','',14 );

      foreach ($DATA_HISTORY as $key => $row) {
        $IN_TOTAL     = $row->IN_TOTAL  > 0 ? $row->IN_TOTAL  : '-';
        $OUT_TOTAL    = $row->OUT_TOTAL > 0 ? $row->OUT_TOTAL : '-';
        $REMARK       = $row->REMARK != '' ? $row->REMARK.' '.$row->MACHINE_CODE : '-';
        $CENTER       = $REMARK == '-' ? 'C' : 'L';
        $this->pdf->Cell(21,6,$row->DOC_DATE,'BRL',0,'C');
        $this->pdf->Cell(20,6,$IN_TOTAL,'TBRL',0,'C');
        $this->pdf->Cell(16,6,iconv('UTF-8', 'cp874', $SPAREPART->UNIT),'TBRL',0,'C');
        $this->pdf->Cell(20,6,$OUT_TOTAL,'TBRL',0,'C');
        $this->pdf->Cell(16,6,iconv('UTF-8', 'cp874', $SPAREPART->UNIT),'TBRL',0,'C');
        $this->pdf->Cell(15,6,$row->TOTAL,'BRL',0,'C');
        $this->pdf->Cell(31,6,iconv('UTF-8', 'cp874', $row->RECODE_BY_TH),'BRL',0,'L');
        $this->pdf->Cell(47,6,iconv('UTF-8', 'cp874', $REMARK),'BRL',1,$CENTER);

        $Y= $this->pdf->getY();
        if ($Y > 279) {
          $this->pdf->AddPage('P','A4');
          $this->pdf->header($UNID);
          $this->pdf->Rect(10,5,186,279);
          $this->pdf->SetFont('THSarabunNew','',14);
        }
      }

      $this->pdf->Output('I','Plan.pdf');
      exit;
    }
    public function SaveHistory( $UNID_REF = NULL,$MACHINE_UNID = NULL,$DOC_NO = NULL
                                 ,$TYPE    = NULL,$RECODE_BY    = NULL){

      if ($TYPE == 'REPAIR') {
        $DATA_SPAREPART = RepairSparepart::Where('REPAIR_REQ_UNID','=',$UNID_REF)->get();
      }elseif ($TYPE == 'PLAN_PM') {
        $DATA_SPAREPART = PmPlanSparepart::where('PM_PLAN_UNID','=',$UNID_REF)->get();
      }elseif ($TYPE == 'PLAN_PDM') {
        $DATA_SPAREPART = SparePartPlan::where('UNID','=',$UNID_REF)->get();
      }
      $DOC_NO         = $DOC_NO != NULL ? $DOC_NO : '';
      $MACHINE        = Machine::select('UNID','MACHINE_CODE')->where('UNID','=',$MACHINE_UNID)->first();
      $ARRAY_REMARK   = array('REPAIR'=>'ซ่อมเครื่อง','PLAN_PM'=>'ตรวจเช็คเครื่อง','PLAN_PDM'=>'เปลี่ยนอะไหล่เครื่อง');
      foreach ($DATA_SPAREPART as $index => $row) {
        $SPAREPART      = Sparepart::select('LAST_STOCK')->where('UNID','=',$row->SPAREPART_UNID)->first();
        $UNID           = $this->randUNID('PMCS_CMMS_HISTORY_SPAREPART');
        $OUT_TOTAL      = $TYPE == 'REPAIR' ? $row->SPAREPART_TOTAL_OUT : ($TYPE == 'PLAN_PM' ? $row->TOTAL_PIC: ($TYPE == 'PLAN_PDM' ? $row->ACT_QTY : 0) );
        $DATE           = $TYPE == 'REPAIR' ? $row->CHANGE_DATE : ($TYPE == 'PLAN_PM' ? $row->CHANGE_DATE: ($TYPE == 'PLAN_PDM' ? $row->COMPLETE_DATE : 0) );
        $TOTAL_STOCK    = $SPAREPART->LAST_STOCK - $OUT_TOTAL;
        if ($row->SPAREPART_PAY_TYPE == 'CUT') {
          HistorySparepart::Insert([
            'UNID'           => $UNID
            ,'SPAREPART_UNID'=> $row->SPAREPART_UNID
            ,'MACHINE_UNID'  => $MACHINE->UNID
            ,'MACHINE_CODE'  => $MACHINE->MACHINE_CODE
            ,'DOC_NO'        => $DOC_NO
            ,'DOC_DATE'      => date('Y-m-d',strtotime($DATE))
            ,'DOC_YEAR'      => date('Y',strtotime($DATE))
            ,'DOC_MONTH'     => date('m',strtotime($DATE))
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
