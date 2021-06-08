<div class="tab-pane" id="history-1">
  <div class="row">
      <div class="col-md-12">
        <div class="jumbotron">
          <div class="col-md-12">
            <div class="card-header bg-primary">
              <div class="row">
                <div class="col-md-9 col-lg-10">
                  <h3 align="center" style="color:white;" class="mt-2">ประวัติเครื่อง </h3>
                </div>
                <div class="col-md-3 col-lg-2">
                  <button type="button" class="btn btn-secondary btn-sm mt-1"
                  onclick="printhistory( '{{$dataset->UNID}}' )" id="button" >
                    <span class="float-left">
                      <i  style="font-size:17px"class="icon-printer mx-1"></i>
                      Print ประวัติ
                    </span>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="table table-responsive">
              <table class="table table-hover table-bordered">
                <thead>
                  <tr>
                    <th >NO.</th>
                    <th>Docno</th>
                    <th>  Docdate</th>
                    <th>  User Name</th>
                    <th>  เวลา</th>
                    <th>  อาการเสีย</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($machinerepair as $key => $rowrepair)
                    <tr>
                      <td class="text-center"> {{ $key+1 }} </td>
                      <td > {{ $rowrepair->MACHINE_DOCNO }} </td>
                      <td > {{ $rowrepair->MACHINE_DOCDATE }} </td>
                      <td > {{ $rowrepair->EMP_NAME }} </td>
                      <td > {{ $rowrepair->CREATE_TIME }} </td>
                      <td > {{ $rowrepair->MACHINE_CAUSE }} </td>
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


  {{-- <div class="card-body">
    <div class="row">
      <div class="col-sm-12">
        <div class="jumbotron">
          <div class="col-md-8 col-lg-12">
            <div class="table">
            <table class="table table-bordered" >
              <thead>
                <tr>
                  <th class="bg-primary" colspan="6" >
                    <h3 align="center" style="color:white;" class="mt-2">ประวัติการแจ้งซ่อม
                    <button type="button" class="btn btn-secondary btn-sm  float-right mt--1"
                    onclick="printhistory( '{{$dataset->UNID}}' )" id="button" style="width:120px">
                      <span class="float-left">

                        <i  style="font-size:17px"class="icon-printer mx-1 mt-1"></i>
                        Print ประวัติ
                      </span>
                      </h3>
                    </button>
                  </th>



                </tr>
                <tr>
                  <th style="width:60px">
                    NO.
                  </th>
                  <th style="width:130px">
                    Docno
                  </th>
                  <th style="width:100px" >
                    Docdate
                  </th>
                  <th style="width:100px">
                    User Name
                  </th>
                  <th style="width:100px">
                    เวลา
                  </th>
                  <th>
                    อาการเสีย
                  </th style="width:100px">
                </tr>

              </thead>
              <tbody>
                @foreach ($machinerepair as $key => $rowrepair)
                  <tr>
                    <td style="width:60px"> {{ $key+1 }} </td>
                    <td style="width:150px"> {{ $rowrepair->MACHINE_DOCNO }} </td>
                    <td style="width:100px"> {{ $rowrepair->MACHINE_DOCDATE }} </td>
                    <td style="width:100px"> {{ $rowrepair->EMP_NAME }} </td>
                    <td style="width:100px"> {{ $rowrepair->CREATE_TIME }} </td>
                    <td style="width:150px"> {{ $rowrepair->MACHINE_CAUSE }} </td>
                  </tr>
                @endforeach

              </tbody>
            </table>
          </div>
          </div>
        </div>
      </div>
    </div>
  </div> --}}
