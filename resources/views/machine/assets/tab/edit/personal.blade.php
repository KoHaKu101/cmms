<div class="tab-pane" id="personal-1" >
  <div class="row">
    <div class="col-sm-12">
      <div class="jumbotron">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
          <div class="card-header bg-primary">
            <div class="row">
              <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <h3 align="center" style="color:white;" class="mt-2">พนักงานประจำเครื่อง</h3>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
          <div class="table table-responsive">
            <table class="table table-hover table-bordered"  >
              <thead>
                <tr>
                  <th class="text-center">ลำดับ</th>
                  <th>รหัสพนักงาน</th>
                  <th>ชื่อพนักงาน</th>
                  <th>นามสกุล</th>
                  <th>ประเทศ</th>
                  <th>กะพนักงาน</th>
                  <th>ประเภทพนักงาน</th>
                  </tr>
              </thead>
              <tbody>
                @foreach ($machineemp as $key => $rowmachineemp)
                  <tr>
                    <td class="text-center" > {{ $key+1 }}  </td>
                    <td> {{ $rowmachineemp->EMP_CODE }}     </td>
                    <td> {{ $rowmachineemp->EMP_NAME }}     </td>
                    <td> {{ $rowmachineemp->EMP_NAME_LAST }}</td>
                    <td> {{ $rowmachineemp->COUNTRY_CODE }} </td>
                    <td> {{ $rowmachineemp->EMP_KA }}       </td>
                    <td> {{ $rowmachineemp->EMP_TYPE = '2' ? 'พนักงานรอง' : 'พนักงานหลัก' }}</td>
                  </tr>
              @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
