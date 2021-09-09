<table >
  <thead>
    <tr><th colspan="8" style="text-align:center">Plan Predictive Maintenance (PDM)</th></tr>
    <tr>
      <th>Line</th>
      <th>ชื่อเครื่อง</th>
      <th>รหัสเครื่อง</th>
      <th>รายการอะไหล่</th>
      <th>กำหนดตรวจเช็ค</th>
      <th>สถานะตรวจเช็ค</th>
      <th>วันที่ตรวจเช็ค</th>
      <th>จำนวนที่ต้องเปลี่ยน</th>
      <th>จำนวนที่เปลี่ยน</th>
      <th>ผู้ตรวจเช็ค</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($DATA_PDM as $row)
      @php
        $DATA_MACHINE = $Machine->where('UNID','=',$row->MACHINE_UNID)->first();
      @endphp
      <tr>
        <td>  {{ $row->MACHINE_LINE }}  </td>
        <td>  {{ $DATA_MACHINE->MACHINE_NAME_TH }}  </td>
        <td>  {{ $row->MACHINE_CODE }}   </td>
        <td>  {{ $row->SPAREPART_NAME}} </td>
        <td>  {{ date('d-m-Y',strtotime($row->PLAN_DATE)) }}</td>
        <td>  {{ $row->PLAN_STATUS == 'COMPLETE' ? 'ดำเนินการสำเร็จ' : 'ยังไม่ได้ดำเนินการ' }}</td>
        <td>  {{ $row->PLAN_STATUS == 'COMPLETE' ? date('d-m-Y',strtotime($row->COMPLETE_DATE)) : '-'}}</td>
        <td>  {{ $row->PLAN_QTY > 0 ? $row->PLAN_QTY : '-'}}  </td>
        <td>  {{ $row->ACT_QTY  > 0 ? $row->ACT_QTY  : '-'}}  </td>
        <td>  {{ $row->PLAN_STATUS == 'COMPLETE' ? $row->PM_USER_CHECK : '-' }}</td>
        </tr>
    @endforeach
  </tbody>
</table>
