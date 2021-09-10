<div class="card-body">
  <div class="row">
    <div class="col-md-12 table-responsive">
      <table class="table table-sm table-bordered table-head-bg-info table-bordered-bd-info">
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
        <tbody>
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
                <td scope="col">#</td>
                <td scope="col">วันที่แจ้ง</td>
                <td scope="col">เอกสาร</td>
                <td scope="col">อาการเสีย</td>
                <td scope="col">วันที่ซ่อม</td>
                <td scope="col">วิธีการแก้ไข</td>
                <td scope="col">อะไหล่</td>
                <td scope="col">ราคา</td>
                <td scope="col">DownTime</td>
                <td scope="col">ผู้รายงาน</td>
              </tr>
              @php
                $i = 1 ;
                $i_sub = 1 ;
                $DATA_REPAIR = $DATA_REPAIR->where('MACHINE_UNID','=',$row->MACHINE_UNID);
              @endphp
            @foreach ($DATA_REPAIR as $index => $sub_row)
              <tr>
                <td class="text-aliginup">{{$i++}}</td>
                <td class="text-aliginup">{{date('d-m-Y',strtotime($sub_row->DOC_DATE))}}</td>
                <td class="text-aliginup">{{$sub_row->DOC_NO}}</td>
                <td class="text-aliginup"style="width:20%">{{ $sub_row->REPAIR_REQ_DETAIL }}</td>
                <td class="text-aliginup">{{ date('d-m-Y',strtotime($sub_row->REPAIR_DATE)) }}</td>
                <td class="text-aliginup"style="width:20%">{{ $sub_row->REPAIR_DETAIL }}</td>

                <td class="text-aliginup"style="width:16%">
                  @if ($DATA_SPAREPART->where('REPAIR_REQ_UNID','=',$sub_row->REPAIR_REQ_UNID)->count() == 0)
                    -
                  @endif
                  @foreach ($DATA_SPAREPART->where('REPAIR_REQ_UNID','=',$sub_row->REPAIR_REQ_UNID) as $key => $subsub_row)
                    {{ $i_sub++.'.'.$subsub_row->SPAREPART_NAME }} <br>
                  @endforeach
                </td>
                <td class="text-right text-aliginup">{{ $sub_row->TOTAL_COST != 0 ? number_format($sub_row->TOTAL_COST).' บาท' : '-'}} </td>
                <td class="text-right text-aliginup" >{{ $sub_row->DOWN_TIME != 0 ? number_format($sub_row->DOWN_TIME).' นาที' : '-'}} </td>
                <td class="text-aliginup">{{ $sub_row->INSPECTION_BY_TH}} </td>
              </tr>
            @endforeach
          @endforeach

        </tbody>
      </table>
      {{ $DATA_REPAIR_HEADER->links('pagination.default') }}
    </div>
  </div>
</div>
