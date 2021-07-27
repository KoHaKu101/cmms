<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Machine\MachinePlanPm;
use App\Models\Machine\SparePartPlan;
use App\Models\Machine\SparePart;
use App\Models\SettingMenu\MailSetup;




class CalendarController extends Controller
{
  public function __construct(){
    $this->middleware('auth');
  }

  public function Index(){
    $DATA_MACHINEPLANPM = MachinePlanPm::all();
    $DATA_PMPLANSPAREPART = SparePartPlan::select('PLAN_DATE','MACHINE_CODE','MACHINE_UNID')
                                          ->groupBy('PLAN_DATE')->groupBy('MACHINE_CODE')
                                          ->groupBy('MACHINE_UNID')->get();

    return View('machine/celendar/celendar',compact('DATA_MACHINEPLANPM','DATA_PMPLANSPAREPART'));
  }
  public function ShowModal(Request $request){

    $PLAN_TYPE  = $request->PLAN_TYPE;
    $html = '';
    if ($PLAN_TYPE == 'PLAN_PM') {

    }elseif($PLAN_TYPE == 'PLAN_PDM') {
      $SPAREPART_PLAN  = SparepartPlan::select('*')
                                      ->where('MACHINE_CODE','=',$request->MACHINE_CODE)
                                      ->where('PLAN_DATE','=',$request->PLAN_DATE)->get();
      $SPAREPART = SparePart::select('UNID','SPAREPART_MODEL','SPAREPART_SIZE')->get();
      $ARRAY_SPAREPART_MODEL = array();
      $ARRAY_SPAREPART_SIZE = array();
      foreach ($SPAREPART as $key => $spareparts) {
        $ARRAY_SPAREPART_MODEL[$spareparts->UNID] = $spareparts->SPAREPART_MODEL;
        $ARRAY_SPAREPART_SIZE[$spareparts->UNID] = $spareparts->SPAREPART_SIZE;
      }
      $html.='<table class="table table-bordered table-head-bg-info table-bordered-bd-info mt-4">
								<thead>
									<tr>
										<th>#</th>
										<th>รหัส</th>
										<th>ชื่อ</th>
										<th>รุ่น</th>
                    <th>ขนาด</th>
                    <th>จำนวน</th>
									</tr>
								</thead>
								<tbody>';
              foreach ($SPAREPART_PLAN as $index => $row) {
                $SPAREPART_MODEL = $ARRAY_SPAREPART_MODEL[$row->SPAREPART_UNID] != '' ? $ARRAY_SPAREPART_MODEL[$row->SPAREPART_UNID] : '-' ;
                $SPAREPART_SIZE = $ARRAY_SPAREPART_SIZE[$row->SPAREPART_UNID] != '' ? $ARRAY_SPAREPART_SIZE[$row->SPAREPART_UNID] : '-' ;
                $html.='<tr>
                    <td>'.$index+1 .'</td>
                    <td>'.$row->SPAREPART_CODE.'</td>
                    <td>'.$row->SPAREPART_NAME.'</td>
                    <td>'.$SPAREPART_MODEL.'</td>
                    <td>'.$SPAREPART_SIZE.'</td>
                    <td>'.$row->PLAN_QTY.'</td>
									</tr>';
              }
					$html.='</tbody>
							</table>';
      return Response()->JSON(['html' => $html]);
    }
  }

}
