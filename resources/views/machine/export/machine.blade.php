<table >
  <thead>
    <tr><th colspan="8" style="text-align:center">รายละเอียด เครื่องจักร DownTime</th></tr>
    <tr>
      <th >Line</th>
      <th >ชื่อเครื่อง</th>
      <th >รหัสเครื่อง</th>
      <th >สถานะ</th>
      <th >เริ่มใช้งาน</th>
      <th >ซื้อจากบริษัท</th>
      <th >ประเภทเครื่องจักร</th>
    </tr>
  </thead>
  <tbody>

    @foreach ($data_set as $row)

      <tr>

        <td >  {{ $row->MACHINE_LINE }}  </td>
        <td >  {{ $row->MACHINE_NAME }}  </td>
        <td >  {{ $row->MACHINE_CODE }}   </td>
        <td style="white-space:nowrap">  	{{ $row->MACHINE_CHECK == '1' ? 'หยุดทำงาน'
                                          :( $row->MACHINE_CHECK == '2' ? 'เครื่องทำงาน'
                                          :( $row->MACHINE_CHECK == '3' ? 'เครื่องกำลังซ่อม'
                                          :( $row->MACHINE_CHECK == '4' ? 'เครื่องจำหน่าย' : 'สถานะไม่แน่ชัด' ))) }}		</td>
        <td >  {{ $row->MACHINE_RVE_DATE }}     </td>
        <td >  {{ $row->PURCHASE_FORM }}     </td>
        <td >  {{ $row->MACHINE_TYPE }}     </td>
        </tr>
    @endforeach
  </tbody>
</table>
