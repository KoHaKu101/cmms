<div class="tab-pane" id="planpdm-1" >
  <div class="row " >
    <div class="col-sm-12 ">
      <div class="jumbotron">
        <div class="col-md-8 col-lg-12">
          <div class="card">
            <div class="card-header bg-primary">
              <div class="row">
                <div class="col-md-8">
                  <h3 align="center" style="color:white;" class="mt-2">รายการอะไหล่</h3>
                </div>
                <div class="col-md-4">
                  <button  type="button" class="btn btn-warning float-right btn-sm btn-addsparepart"
                  ><span style="color:black;font-size:14px">เพิ่มรายการอะไหล่</span></button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="table table-responsive" >
                <table class="table table-sm" id="machinespartelist" >
                  <thead>
                    <tr>
                      <th>#</th>
                      <th width="150px">Code</th>
                      <th width="250px">Name</th>
                      <th width="100px">ครั้งล่าสุด</th>
                      <th width="100px">ครั้งถัดไป</th>
                      <th width="50px">วาระ(เดือน)</th>
                      <th width="50px">จำนวน</th>
                      <th width="50px">Unit</th>
                      <th width="100px">ราคา(Unit)</th>
                      <th width="130px">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($machinesparepart as $index => $row_machinesparepart)
                      @php
                        $NEXT_PLAN_DATE = $row_machinesparepart->NEXT_PLAN_DATE != '1900-01-01' ? date('d-m-Y',strtotime($row_machinesparepart->NEXT_PLAN_DATE)) : '';
                        $SPAREPART_CODE = $row_machinesparepart->SPAREPART_CODE;
                        $SPAREPART_NAME = $row_machinesparepart->SPAREPART_NAME;
                        $LAST_CHANGE    = $row_machinesparepart->LAST_CHANGE;
                        $PERIOD         = $row_machinesparepart->PERIOD;
                        $SPAREPART_QTY  = $row_machinesparepart->SPAREPART_QTY;
                        $STATUS         = $row_machinesparepart->STATUS;
                        $SPAREPART_UNID = $row_machinesparepart->SPAREPART_UNID;
                      @endphp
                      <tr>
                        <td>{{ $index+1 }}</td>
                        <td>{{$SPAREPART_CODE}}</td>
                        <td>{{$SPAREPART_NAME}}</td>
                        <td>{{date('d-m-Y',strtotime($LAST_CHANGE))}}</td>
                        <td>{{$NEXT_PLAN_DATE}}</td>
                        <td class='text-center'>{{$PERIOD}}</td>
                        <td class='text-center'>{{$SPAREPART_QTY}}</td>
                        <td class='text-center'>{{$row_machinesparepart->UNIT}}</td>
                        <td class='text-right'>{{number_format($row_machinesparepart->COST_STD)}}</td>
                        <td>
                          @if ($STATUS == 9)
                            <button type="button" class="btn btn-info btn-sm mx-1 my-1"
                              data-sparepartunid      = "{{$row_machinesparepart->UNID}}"
                              data-sparepartlastchange= "{{$LAST_CHANGE}}"
                              data-sparepartcode      = "{{$SPAREPART_CODE}}"
                              data-sparepartqty       = "{{$SPAREPART_QTY}}"
                              data-sparepartperiod    = "{{$PERIOD}}"
                              data-sparepartremark    = "{{$row_machinesparepart->REMARK}}"
                              data-sparepartstatus    = "{{$STATUS}}"
                              onclick="editsparepart(this)">
                              <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm mx-1 my-1"
                              data-machine_unid   = "{{$row_machinesparepart->MACHINE_UNID}}"
                              data-sparepart_unid = "{{$SPAREPART_UNID}}"
                              data-sparepart_name = "{{$SPAREPART_NAME}}"
                              onclick="deletesparepart(this)">
                              <i class="fas fa-trash"></i>
                            </button>
                          @else
                            <button type="button" class="btn btn-default btn-sm mx-1 my-1 btn-block"
                              data-sparepart_unid ="{{$SPAREPART_UNID}}"
                              onclick="openstatus(this)">
                                <i class="fas fa-power-off fa-lg mr-1"></i>อะไหล่ถูกระงับ
                            </button>
                          @endif
                        </td>
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
  </div>
</div>
