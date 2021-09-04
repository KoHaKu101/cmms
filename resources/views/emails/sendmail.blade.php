
<h4>แผน PM</h4>
<table>
  <theade></theade>
  <tbody>
    @foreach ($DATA_PM as $key => $row_pm)
      <tr>
        <td>{{ $key+1 }}.-></td>
        <td>LINE : {{ $row_pm->MACHINE_LINE }},</td>
        <td> {{ $row_pm->MACHINE_CODE }}</td>
        <td> {{ $row_pm->MACHINE_NAME_TH }}</td>
        <td>ตรวจเช็คระบบ : {{ $row_pm->PM_MASTER_NAME }},</td>
        <td>วันที่ตามแผน : {{ date('d-m-Y',strtotime($row_pm->PLAN_DATE)) }}</td>
      </tr>
    @endforeach

  </tbody>
</table>

<h4>แผน PDM</h4>
<table>
  <theade></theade>
  <tbody>
    @php
    $CHECK_MACHINE_CODE = '';
    $number       = 0;

    @endphp
    @foreach ($DATA_PDM as $key => $row_pdm)
      @php
      $number_show  = '';
      $MACHINE_CODE = '';
      $MACHINE_LINE = '';
      $MACHINE_NAME = '';
        if ($row_pdm->MACHINE_CODE != $CHECK_MACHINE_CODE) {
          $CHECK_MACHINE_CODE = $row_pdm->MACHINE_CODE;
          $MACHINE_NAME_TH    = $MACHINE_ARRAY[$row_pdm->MACHINE_CODE]->MACHINE_NAME_TH;
          $MACHINE_LINE       = 'LINE : '.$row_pdm->MACHINE_LINE.',';
          $MACHINE_CODE       = ' '.$row_pdm->MACHINE_CODE.',';
          $MACHINE_NAME       = ' '.$MACHINE_NAME_TH.',';
          $number             = $number+1;
          $number_show        = $number.'.->';
        }
      @endphp
      <tr>
        <td>{{ $number_show }}</td>
        <td>{{ $MACHINE_LINE }}</td>
        <td>{{ $MACHINE_CODE }}</td>
        <td>{{ $MACHINE_NAME }}</td>
        <td>ตรวจเช็คระบบ : {{ $row_pdm->SPAREPART_NAME }},</td>
        <td>วันที่ตามแผน : {{ date('d-m-Y',strtotime($row_pdm->PLAN_DATE)) }}</td>
      </tr>
    @endforeach

  </tbody>
</table>
