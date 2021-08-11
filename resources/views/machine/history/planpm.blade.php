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
              <th class="bg-info text-white " colspan="9" style="font-size:18px">MC-CODE : {{ $row->MACHINE_CODE }} </th>
              <th class="bg-info text-white text-right" >
                <button type="button" class="btn btn-sm btn-warning  my-1"
                onclick="window.open('/machine/history/repairpdf/{{$row->MACHINE_UNID}}','RepairSaveprint','width=1000,height=1000,resizable=yes,top=100,left=100,menubar=yes,toolbar=yes,scroll=yes')">
                  <i class="fas fa-print" style="font-size:15px"></i>
                </button>
              </th>
            </tr>
            <tr class="bg-secondary text-white">
              <td class="text-center">#</td>
              <td class="text-center">Rank</td>
              <td >ระยะรอบ(เดือน)</td>
              <td >วันที่ตามแผน</td>
              <td >วันที่ตรวจเช็ค</td>
              <td >เวลาหยุดเครื่อง</td>
              <td >ประเภทเครื่องจักร</td>
              <td >รายการตรวจเช็ค</td>
              <td >หมายเหตุ/รายละเอียดเพิ่มเติม</td>
              <td >ผู้ตรวจเช็ค</td>
            </tr>
            @php
              $no = 1 ;
              $sub_no = 1 ;
            @endphp

            @foreach ($DATA_PLANPM as $key => $row_plan)
              @php
              $DATA_MACHINE_PLAN = $DATA_MACHINE_PLAN->where('UNID','=',$row_plan->PM_PLAN_UNID)->first();
              @endphp

              <tr>
                <td style="width:2%" class="text-center text-aliginup">1</td>
                <td style="width:4%" class="text-center text-aliginup">{{$DATA_MACHINE_PLAN->PLAN_RANK}}</td>
                <td style="width:8%" class="text-aliginup">{{$DATA_MACHINE_PLAN->PLAN_PERIOD}} เดือน</td>
                <td style="width:8%" class="text-aliginup">{{date('d-m-Y',strtotime($row_plan->DOC_DATE))}}</td>
                <td style="width:8%" class="text-aliginup">{{date('d-m-Y',strtotime($row_plan->REPAIR_DATE))}}</td>
                <td style="width:8%" class="text-aliginup">{{$row_plan->DOWN_TIME}}</td>
                <td style="width:10%" class="text-aliginup">{{ $DATA_MACHINE_PLAN->PM_MASTER_NAME }}</td>
                <td style="width:16%" class="text-aliginup">
                  @foreach ($DATA_MASTERTEMPLAT->where('PM_PLAN_UNID','=',$row_plan->PM_PLAN_UNID) as $key => $subrow_plan)
                    {{ $sub_no++.'. '.$subrow_plan->PM_MASTER_LIST_NAME."\n" }}<br>
                  @endforeach
                </td>
                <td style="width:26%" class="text-aliginup">-</td>
                <td style="width:8%" class="text-aliginup">สุบรรณ์</td>
              </tr>
            @endforeach

        @endforeach
      </table>
      {{ $DATA_REPAIR_HEADER->appends(['SEARCH_MACHINE' => $SEARCH])->links('pagination.default') }}
    </div>
  </div>
</div>
