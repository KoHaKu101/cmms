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
                  onclick="window.open('/machine/history/repairpdf/{{$dataset->UNID}}','RepairSaveprint','width=1000,height=1000,resizable=yes,top=100,left=100,menubar=yes,toolbar=yes,scroll=yes')" >
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
              <table class="table table-hover table-bordered" id="table_history">
                <thead>
                  <tr>
                    <th width="4%" class="text-center">NO.</th>
                    <th width="12%">Docno</th>
                    <th width="10%">Docdate</th>
                    <th>อาการเสีย</th>
                    <th width="9%">DownTime</th>
                    <th width="16%">ผู้รายงาน</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($machinerepair as $key => $rowrepair)

                    <tr>
                      <td class="text-center"> {{ $key+1 }} </td>
                      <td > {{ $rowrepair->DOC_NO != '' ? $rowrepair->DOC_NO : '-' }} </td>
                      <td > {{ date('d-m-Y',strtotime($rowrepair->DOC_DATE)) }} </td>
                      <td > {{ $rowrepair->REPAIR_REQ_DETAIL }} </td>
                      <td class="text-right"> {{ number_format($rowrepair->DOWN_TIME).' นาที' }} </td>
                      <td > {{ $rowrepair->REPORT_BY_TH }} </td>
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
