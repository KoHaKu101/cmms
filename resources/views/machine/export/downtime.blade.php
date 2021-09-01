<table >
  <thead>
    <tr><th colspan="8" style="text-align:center">ข้อมูลเครื่องจักรทั้งหมด</th></tr>
    <tr><th colspan="2">จำนวนเครื่องจักร</th></tr>
    <tr><td colspan="2">Total : {{ $DATA_REPAIR->count() }} เครื่อง</td></tr>
    <tr></tr>
    <tr>
      <th >No.</th>
      <th >MC-CODE</th>
      <th >MC-NAME</th>
      <th >สาเหตุ / อาการที่เสีย</th>
      <th >วิธีแก้ไข</th>
      <th >ตรวจสอบ (นาที)</th>
      <th >ซื้ออะไหล่ (นาที)</th>
      <th >ซ่อม (นาที)</th>
      <th >รวม (นาที)</th>
      <th >ผู้ดำเนินการ</th>
    </tr>
  </thead>
  <tbody>

    @foreach ($DATA_REPAIR as $index => $row)
      @php
        $index = $index + 1;
        $INSPECTION_RESULT_TIME = $row->INSPECTION_RESULT_TIME > 0 ? number_format($row->INSPECTION_RESULT_TIME) : '-';
        $SPAREPART_RESULT_TIME  = $row->SPAREPART_RESULT_TIME  > 0 ? number_format($row->SPAREPART_RESULT_TIME)  : '-';
        $WORK_RESULT_TIME 			= $row->WORKERIN_RESULT_TIME 	 > 0 ? number_format($row->WORKERIN_RESULT_TIME)   : number_format($row->WORKEROUT_RESULT_TIME);
        $WORK_RESULT_TIME 			= $WORK_RESULT_TIME != 0           ? $WORK_RESULT_TIME : '-';
        $CLOSE_BY               = isset($row->CLOSE_BY)            ? $row->CLOSE_BY    : '-';
        $MACHINE_NAME           = isset($row->MACHINE_NAME_TH)     ? $row->MACHINE_NAME_TH : '-';
        $REPAIR_SUBSELECT_NAME  = isset($row->REPAIR_SUBSELECT_NAME) ? $row->REPAIR_SUBSELECT_NAME : '-';
        $REPAIR_DETAIL          = isset($row->REPAIR_DETAIL)       ? $row->REPAIR_DETAIL : '-';
      @endphp

      <tr>
        <td >  {{ $index }}                    </td>
        <td >  {{ $row->MACHINE_CODE }}        </td>
        <td >  {{ $MACHINE_NAME }}             </td>
        <td >  {{ $REPAIR_SUBSELECT_NAME }}    </td>
        <td >  {{ $REPAIR_DETAIL }}            </td>
        <td >  {{ $INSPECTION_RESULT_TIME }}   </td>
        <td >  {{ $SPAREPART_RESULT_TIME }}    </td>
        <td >  {{ $WORK_RESULT_TIME }}         </td>
        <td >  {{ $row->DOWNTIME }}            </td>
        <td >  {{ $CLOSE_BY }}                 </td>
        </tr>
    @endforeach
  </tbody>
</table>
