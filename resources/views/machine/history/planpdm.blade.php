<div class="card-body">
  <div class="row">
    <div class="col-md-12  ">
      <table class="table table-sm table-striped 	table-bordered table-head-bg-info table-bordered-bd-info ">
        <style>
        .table>tbody>tr>td{
          font-size: 0.75rem;
          vertical-align: baseline;
          word-break: break-all;
        }
        .table td.text-aliginup{
          vertical-align: baseline !important;
        }
        </style>
        @foreach ($DATA_REPAIR_HEADER as $key => $row)
            <tr>
              <th class="bg-info text-white " colspan="8" style="font-size:18px">MC-CODE : {{ $row->MACHINE_CODE }} </th>
              <th class="bg-info text-white text-right" >
                <button type="button" class="btn btn-sm btn-warning  my-1"
                onclick="window.open('/machine/history/repairpdf/{{$row->MACHINE_UNID}}','RepairSaveprint','width=1000,height=1000,resizable=yes,top=100,left=100,menubar=yes,toolbar=yes,scroll=yes')">
                  <i class="fas fa-print" style="font-size:15px"></i>
                </button>
              </th>
            </tr>
            <tr class="bg-secondary text-white">
              <td class="text-center">#</td>
              <td >ระยะรอบ(เดือน)</td>
              <td >วันที่ตามแผน</td>
              <td >วันที่ตรวจเช็ค</td>
              <td >เวลาหยุดเครื่อง</td>
              <td >รหัสอะไหล่</td>
              <td >รายการอะไหล่</td>
              <td class="text-center">ค่าใช้จ่าย</td>
              <td >ผู้ทำการเปลี่ยน</td>
            </tr>
            @php
              $no = 1 ;
              $sub_no = 1 ;

              $DATA_PLAN_PDM = App\Models\Machine\History::select('SPAREPART_PLAN_UNID','DOC_DATE','REPAIR_DATE','DOWN_TIME','TOTAL_COST')
                                              ->where('DOC_YEAR','=',date('Y'))
                                              ->where('DOC_TYPE','=','PLAN_PDM')->where('MACHINE_UNID','=',$row->MACHINE_UNID)
                                              ->orderBy('MACHINE_CODE')->get();
            @endphp
            @foreach ($DATA_PLAN_PDM as $key => $row_sparepart)
              @php
              $DATA_SPAREPART_PLAN = $DATA_SPAREPART_PLAN->where('UNID','=',$row_sparepart->SPAREPART_PLAN_UNID)->first();
              @endphp
              <tr>
                <td style="width:2%" class="text-center text-aliginup">1</td>
                <td style="width:6%" class="text-aliginup">{{$DATA_SPAREPART_PLAN->PERIOD}} เดือน</td>
                <td style="width:6%" class="text-aliginup">{{date('d-m-Y',strtotime($row_sparepart->DOC_DATE))}}</td>
                <td style="width:6%" class="text-aliginup">{{date('d-m-Y',strtotime($row_sparepart->REPAIR_DATE))}}</td>
                <td style="width:6%" class="text-aliginup text-right">{{number_format($row_sparepart->DOWN_TIME).' นาที'}}</td>
                <td style="width:10%" class="text-aliginup">{{ $DATA_SPAREPART_PLAN->SPAREPART_CODE }}</td>
                <td style="width:16%" class="text-aliginup">{{ $DATA_SPAREPART_PLAN->SPAREPART_NAME	 }}
                </td>
                <td style="width:6%" class="text-aliginup text-right">{{ number_format($row_sparepart->TOTAL_COST).' บาท' }}</td>
                <td style="width:8%" class="text-aliginup">สุบรรณ์</td>
              </tr>
            @endforeach

        @endforeach
      </table>
      {{ $DATA_REPAIR_HEADER->links('pagination.default') }}
    </div>
  </div>
</div>
