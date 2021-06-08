<!-- แผนการปฎิบัติการ -->
<div class="tab-pane" id="plan-1" >
  <div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12">
      <div class="jumbotron">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
          <div class="card-header bg-primary">
            <div class="row">
              <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <h3 align="center" style="color:white;" class="mt-2">ชิ้นงานที่ผลิต</h3>
              </div>
            </div>
          </div>
      </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
          <div class="table table-responsive">
            <table class="table table-striped table-hover table-bordered" >
              <thead>
                <tr>
                  <th>##</th>
                  <th>BOM REV</th>
                  <th>Product Name</th>
                  <th>Process</th>
                  <th>CT (วินาที)</th>
                  <th>ชิ้นต่อวัน</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($DATA_PRODUCT as $key => $row)
                  <tr>
                    <td>{{ $key+1 }}</td>
                      <td class="text-center">{{ $row->FORMULA_CODE }}</td>
                    <td>{{ $row->PRODUCT_NAME_TH }}</td>
                    <td>{{ $row->PROCESS_NAME }}</td>
                    <td class="text-center">{{ $row->ON_CT }} </td>
                    <td class="text-center">{{ $row->ON_CT_DAY }}</td>
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
