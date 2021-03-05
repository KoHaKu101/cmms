<?php

namespace App\Http\Controllers\Machine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Middleware\VerifyCsrfToken;
use Carbon\Carbon;
use Auth;
//******************** model ***********************
use App\Models\MachineAddTable\MachineRepairTable;
use App\Models\Machine\Machine;
use App\Models\Machine\MachineEMP;
use App\Models\Machine\MachineRepair;
//************** Package form github ***************
use App\Exports\MachineExport;
use Maatwebsite\Excel\Facades\Excel;

class MachineRepairController extends Controller
{
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
  public function Index(){

    $dataset = MachineRepair::paginate(10);
    //dd($data_set);
    return View('machine/repair/repairlist',compact('dataset'));
  }
  public function Indexserach(Request $request){

    if($request->ajax())
    {

         $querydata = $request->get('query');
         $query = str_replace(" ", "%", $querydata);
         $dataset = DB::table('PMCS_REPAIR_MACHINE')
                 ->where('MACHINE_CODE', 'like', '%'.$query.'%')
                 ->orWhere('MACHINE_LOCATION', 'like', '%'.$query.'%')
                 ->orWhere('MACHINE_NAME', 'like', '%'.$query.'%')
                 // ->orderBy($sort_by, $sort_type)
                 ->paginate(10);
   return view('machine/repair/searchrepair', compact('dataset'))->render();
    }
    }
  public function PrepareSearch(){

    return View('machine/repair/search');
  }
  public function Search(Request $request){
      if($request->has('machine_code'))
        {
          $MACHINE_CODE=$request->machine_code;
          $data = Machine::where('MACHINE_CODE','like','%'.$MACHINE_CODE.'%')->get();
          return response()->json(['dataset'=>$data]);
        }else {
        return View('machine/repair/search');
        }
      }
  public function Create($MACHINE_CODE){

      $dataset = MachineRepairTable::where('REPAIR_STATUS','=','9')->get();
      $datamachine = Machine::where('MACHINE_CODE','=',$MACHINE_CODE)->first();
      $dataemp = MachineEMP::where('MACHINE_CODE','=',$MACHINE_CODE)->get();

    return View('machine/repair/form',compact('dataset','datamachine','dataemp'));
  }
  public function Emp($EMP_NAME){
    $dataemp = MachineEMP::where('EMP_NAME','=',$EMP_NAME)->first();
    $data = MachineEMP::where('EMP_CODE','=',$dataemp->EMP_CODE)->first();
    return response()->json($data);
  }
  public function Store(Request $request){
    if(!empty($request->MACHINE_NOTE)){
      // $arraymachinerepair = array($request->MACHINE_REPAIR);
      $machinerepair = implode(",",$request->MACHINE_NOTE);
      $array = array($machinerepair,$request->machinerepair);
      $machinecause = implode(",",$array);
    }
    MachineRepair::insert([
        'MACHINE_DOCNO'         => $request->MACHINE_DOCNO,
        'MACHINE_DOCDATE'       => $request->MACHINE_DOCDATE,
        'MACHINE_TIME'          => $request->MACHINE_TIME,
        'MACHINE_NUMBER'        => $request->MACHINE_NUMBER,
        'MACHINE_CODE'          => $request->MACHINE_CODE,
        'MACHINE_NAME'          => $request->MACHINE_NAME,
        'MACHINE_LOCATION'      => $request->MACHINE_LOCATION,
        'MACHINE_CAUSE'         => $machinecause,
        'MACHINE_CAUSE_DT'      => $request->MACHINE_CAUSE_DT,
        'MACHINE_BY_REPAIR'     => $request->MACHINE_BY_REPAIR,
        'REPAIR_DOCDATE'        => $request->REPAIR_DOCDATE,
        'REPAIR_TIME'           => $request->REPAIR_TIME,
        'MACHINE_INSPECTION'    => $request->MACHINE_INSPECTION,
        'MACHINE_BECAUSE'       => $request->MACHINE_BECAUSE,
        'MACHINE_NOTE'          => $machinerepair,
        'STATUS'                => $request->STATUS,
        'POSTED'                => $request->POSTED,
        'TODAY_DOC'             => $request->TODAY_DOC,
        'TODAY_YY'              => $request->TODAY_YY,
        'TODAY_MM'              => $request->TODAY_MM,
        'TODAY_MAX'             => $request->TODAY_MAX,
        'EMP_CODE'              => $request->EMP_CODE,
        'EMP_NAME'              => $request->EMP_NAME,
        'SECTION_CODE'          => $request->SECTION_CODE,
        'MACHINE_TYPE'          => $request->MACHINE_TYPE,
        'BU_JOB_NAME'           => $request->BU_JOB_NAME,
        'BU_TYPE'               => $request->BU_TYPE,
        'BU_DESCRIPTION'        => $request->BU_DESCRIPTION,
        'BU_DUEDATE'            => $request->BU_DUEDATE,
        'RP_CODE'               => $request->RP_CODE,
        'EG_DESC'               => $request->EG_DESC,
        'EG_TYYPE'              => $request->EG_TYYPE,
        'EX_DESC1'              => $request->EX_DESC1,
        'RECORD_STATUS'         => $request->RECORD_STATUS,
        'TIMESTAMP'             => $request->TIMESTAMP,
        'CLOSE_STATUS'          => $request->CLOSE_STATUS,
        'CM_STARTDATE'          => $request->CM_STARTDATE,
        'CM_ENDDATE'            => $request->CM_ENDDATE,
        'CLOSE_BY'              => $request->CLOSE_BY,
        'CLOSE_TIME'            => $request->CLOSE_TIME,
        'CREATE_BY'             => $request->EMP_NAME,
        'CREATE_TIME'           => Carbon::now(),
        'UNID'                  => $this->randUNID('PMCS_REPAIR_MACHINE'),

    ]);
    return redirect()->route('repair.list');
  }
  public function Edit($UNID) {
      $dataset = MachineRepair::where('UNID','=',$UNID)->first();
      $datarepair = MachineRepairTable::where('REPAIR_STATUS','=','9')->get();
      $datamachine = Machine::where('MACHINE_CODE','=',$dataset->MACHINE_CODE)->first();
      $dataemp = MachineEMP::where('MACHINE_CODE','=',$dataset->MACHINE_CODE)->get();


    return view('machine/repair/edit',compact('dataset','datarepair','datamachine','dataemp',));

  }
  public function Update(Request $request,$UNID){
    $data_set = MachineRepair::where('UNID','=',$UNID)->update([
          'MACHINE_DOCNO'         => $request->MACHINE_DOCNO,
          'MACHINE_DOCDATE'       => $request->MACHINE_DOCDATE,
          'MACHINE_TIME'          => $request->MACHINE_TIME,
          'MACHINE_NUMBER'        => $request->MACHINE_NUMBER,
          'MACHINE_CODE'          => $request->MACHINE_CODE,
          'MACHINE_NAME'          => $request->MACHINE_NAME,
          'MACHINE_LOCATION'      => $request->MACHINE_LOCATION,
          'MACHINE_CAUSE'         => $request->MACHINE_CAUSE,
          'MACHINE_CAUSE_DT'      => $request->MACHINE_CAUSE_DT,
          'MACHINE_BY_REPAIR'     => $request->MACHINE_BY_REPAIR,
          'REPAIR_DOCDATE'        => $request->REPAIR_DOCDATE,
          'REPAIR_TIME'           => $request->REPAIR_TIME,
          'MACHINE_INSPECTION'    => $request->MACHINE_INSPECTION,
          'MACHINE_BECAUSE'       => $request->MACHINE_BECAUSE,
          'MACHINE_NOTE'          => $request->MACHINE_NOTE,
          'STATUS'                => $request->STATUS,
          'POSTED'                => $request->POSTED,
          'TODAY_DOC'             => $request->TODAY_DOC,
          'TODAY_YY'              => $request->TODAY_YY,
          'TODAY_MM'              => $request->TODAY_MM,
          'TODAY_MAX'             => $request->TODAY_MAX,
          'EMP_CODE'              => $request->EMP_CODE,
          'EMP_NAME'              => $request->EMP_NAME,
          'SECTION_CODE'          => $request->SECTION_CODE,
          'MACHINE_TYPE'          => $request->MACHINE_TYPE,
          'BU_JOB_NAME'           => $request->BU_JOB_NAME,
          'BU_TYPE'               => $request->BU_TYPE,
          'BU_DESCRIPTION'        => $request->BU_DESCRIPTION,
          'BU_DUEDATE'            => $request->BU_DUEDATE,
          'RP_CODE'               => $request->RP_CODE,
          'EG_DESC'               => $request->EG_DESC,
          'EG_TYYPE'              => $request->EG_TYYPE,
          'EX_DESC1'              => $request->EX_DESC1,
          'RECORD_STATUS'         => $request->RECORD_STATUS,
          'TIMESTAMP'             => $request->TIMESTAMP,
          'CLOSE_STATUS'          => $request->CLOSE_STATUS,
          'CM_STARTDATE'          => $request->CM_STARTDATE,
          'CM_ENDDATE'            => $request->CM_ENDDATE,
          'CLOSE_BY'              => $request->CLOSE_BY,
          'CLOSE_TIME'            => $request->CLOSE_TIME,
          'MODIFY_BY'             => Auth::user()->name,
          'MODIFY_TIME'           => Carbon::now(),
      ]);
            return Redirect()->route('repair.list')->with('success','อัพเดทรายการ สำเร็จ');
          }
          public function Delete($UNID){
            $STATUS = '1';
              $data_set = Machine::where('UNID',$UNID)->update([
                      'STATUS'          => $STATUS,

                'MODIFY_BY'            => Auth::user()->name,
                'MODIFY_TIME'          => Carbon::now(),
                ]);
              return Redirect()->back()-> with('success','ซ่อนเอกสารเสำเร็จ ');
          }
  public function Notification(Request $request){

    if ($request->has('option')) {
      $NOTIFICATION = '1';
      $data_set = MachineRepair::where('NOTIFICATION_STATUS','=','')->update([
        'NOTIFICATION_STATUS' => $NOTIFICATION,
      ]);
    }
    // MachineRepair::orderBy('UNID','DESC')->get()->count();
    $query = DB::table('PMCS_REPAIR_MACHINE')->orderBy('UNID','desc')->take(3)->count();
    $result = $query;
    $output = "";
    if ($query > 0) {
      while($row = $query){
        $output .='
        <a href="#">
          <div class="notif-icon notif-danger"> <i class="">J</i> </div>
          <div class="notif-content">
            <span class="block">  '.$row['MACHINE_CODE'].' LINE:'.$row['MACHINE_LOCATION'].'  </span>
            <span class="block">  '.$row['MACHINE_CAUSE'].'  </span>
            <span class="time">'.$row['MACHINE_TIME'].'</span>
          </div>
        </a>';

      }
    }else {
      $output .='
      <a href="#">
        <div class="notif-content">
          <span class="block"> รายการแจ้งซ่อม 0 รายการ </span>
        </div>
      </a>';
    }
    $status_query = MachineRepair::where('NOTIFICATION_STATUS','=','0')->get();
    $result_query = MachineRepair::where('NOTIFICATION_STATUS','=',$status_query)->get();
    $count = mysqli_num_rows($result_query);
    $data = array(
      'notification' => $output,
      'unreadNotification' => $count,
    );
    echo json_encode($data);
  }
}