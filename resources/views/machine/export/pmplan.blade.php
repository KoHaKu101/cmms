<table >
  <thead>
    <tr><th colspan="8" style="text-align:center">Plan Preventive Maintenance (PM)</th></tr>
    <tr>
      <th>Line</th>
      <th>ชื่อเครื่อง</th>
      <th>รหัสเครื่อง</th>
      <th>รายการตรวจเช็ค</th>
      <th>กำหนดตรวจเช็ค</th>
      <th>สถานะตรวจเช็ค</th>
      <th>วันที่ตรวจเช็ค</th>
      <th>ผู้ตรวจเช็ค</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($DATA_PM as $row)
      @php
        $DATA_USER_CHECK = $USER_CHECK->where('PM_PLAN_UNID','=',$row->UNID)->first();
      @endphp
      <tr>
        <td>  {{ $row->MACHINE_LINE }}  </td>
        <td>  {{ $row->MACHINE_NAME_TH }}  </td>
        <td>  {{ $row->MACHINE_CODE }}   </td>
        <td>  {{$row->PM_MASTER_NAME}}</td>
        <td>  {{ date('d-m-Y',strtotime($row->PLAN_DATE)) }}</td>
        <td>  {{ $row->PLAN_STATUS == 'COMPLETE' ? 'ดำเนินการสำเร็จ' : 'ยังไม่ได้ดำเนินการ' }}</td>
        <td>  {{ isset($row->COMPLETE_DATE) ? date('d-m-Y',strtotime($row->COMPLETE_DATE)) : '-'}}</td>
        <td>  {{ isset($DATA_USER_CHECK->PM_USER_CHECK) ? $DATA_USER_CHECK->PM_USER_CHECK : '-' }}</td>
        </tr>
    @endforeach
  </tbody>
</table>
