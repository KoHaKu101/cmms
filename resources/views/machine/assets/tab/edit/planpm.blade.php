<meta name="_token" content="{{ csrf_token() }}" />
<div class="tab-pane" id="planpm-1" >
  <div class="row">
    <div class="col-sm-12">
      <div class="jumbotron">
        <div class="col-md-12 col-lg-12">
          <div class="card-header bg-warning">
            <div class="row">
              <div class="col-6 col-sm-5 col-md-7 col-lg-9">
                <h3 align="center" style="color:white;" class="mt-2">รายการตรวจเช็ค</h3>
              </div>
              <div class="col-6 col-sm-7 col-md-5 col-lg-3">
                  <button id="add" type="button" class="btn btn-primary btn-sm mt-2 mx-2"
                    data-toggle="modal" data-target="#PMMachine">
                    <span style="color:black;font-size:13px">เพิ่มระบบ</span>
                  </button>
                  <button id="remove" type="button" class="btn btn-danger btn-sm mt-2 ml-2"
                    data-toggle="modal" data-target="#PMMachineRemove">
                    <span style="color:black;font-size:13px">ลบระบบ</span>
                  </button>
              </div>
            </div>
          </div>
        </div>
          <div class="col-12 col-sm-12 col-md-12 col-lg-12">
            <div class="accordion accordion-primary"  id="accordionExample" >
              @foreach ($masterimps as $key => $datamasterimps)
                @php
                  $PM_TEMPLATE_UNID_REF = $datamasterimps->PM_TEMPLATE_UNID_REF;

                  $PM_LAST_DATE = $datamasterimps->PM_LAST_DATE;
                  $MACHINE_RANK_MONTH = $dataset->MACHINE_RANK_MONTH;
                  $pmlistcount =  1;
                  $DATA_MASTERIMPS_GROUP = $masterimpsgroup->where('PM_TEMPLATE_UNID_REF',$PM_TEMPLATE_UNID_REF);
                @endphp
                <div class="card">
                  <div class="card-header bg-primary text-white" id="headingOne" >
                    <div class="col-md-12" id="datadate">
                      <div class="row">
                        <div class="col-md-12 col-lg-3" id="clickshowpmlist" data-toggle="collapse" data-target="#{{$PM_TEMPLATE_UNID_REF}}"
                          aria-expanded="false" aria-controls="collapseOne" role="button" >
                          <div class="fas fa-bookmark my-3" > Type: {{$datamasterimps->PM_TEMPLATE_NAME}}</div>
                        </div>
                        <div class="col-md-6 col-lg-4 form-inline" >
                          ตรวจเช็คล่าสุด
                            <input type="date" style="width:150px" class="form-control form-control-sm ml-1 changedate"
                              data-dataunidpmlist="{{$PM_TEMPLATE_UNID_REF}}"
                              id="PM_LAST_DATE_{{$PM_TEMPLATE_UNID_REF}}" name="PM_LAST_DATE"
                              value="{{ $MACHINE_RANK_MONTH != NULL? $PM_LAST_DATE: '' }}" >
                            <button type="button" class="btn btn-success btn-sm mx-1" id="savedate" data-dataunidpmlist="{{$PM_TEMPLATE_UNID_REF}}" >
                              <i class="fas fa-save fa-lg" style="color:white" ></i></button>
                        </div>
                        <div class="col-md-5 col-lg-3 form-inline" >
                           ครั้งถัดไป
                            <input type="text" class="form-control form-control-sm ml-1"
                            id="PM_NEXT_DATE_{{$PM_TEMPLATE_UNID_REF}}" name="PM_NEXT_DATE" style="width:150px"
                            value="{{ $MACHINE_RANK_MONTH != NULL
                             ? \Carbon\Carbon::parse($PM_LAST_DATE)->addMonth($MACHINE_RANK_MONTH)->format('d/m/Y') : '' }}" readonly>
                        </div>
                        <div class="col-md-12 col-lg-2 mt-2 text-center" id="clickshowpmlist"
                          data-toggle="collapse" data-target="#{{$PM_TEMPLATE_UNID_REF}}"
                          aria-expanded="false" aria-controls="collapseOne" role="button">
                          <i class="fas fa-caret-down "></i>
                          <input type="hidden" id="dataunidpmlist" name="dataunidpmlist" value="{{$PM_TEMPLATE_UNID_REF}}">
                        </div>
                      </div>
                    </div>
                  </div>
                  <div id="{{$PM_TEMPLATE_UNID_REF}}" data-parent="#accordionExample" class="collapse {{$key+1 == 1 ? 'show' : ''}}" aria-labelledby="headingOne" wire:ignore.self>
                    <div class="card-body">
                      <div class="col-md-12 col-lg-12">
                        <div class="row">
                          <div class="table col-md-6 col-lg-6">
                            <table class="table table table-sm table-bordered">
                              <thead>
                                <tr>
                                  <th style="width:5px">ลำดับ</th>
                                  <th colspan="2">รายการ PM</th>
                                </tr>
                              </thead>
                              <tbody class="{{$PM_TEMPLATE_UNID_REF}}">
                                @foreach ($DATA_MASTERIMPS_GROUP as $key => $datamasterimpsgroup)
                                  <tr>
                                   <td><center>{{$pmlistcount++}}</center></td>
                                   <td>{{$datamasterimpsgroup->PM_TEMPLATELIST_NAME}}</td>
                                   <td style="width:40px">
                                     <button type="button" class="btn btn-primary btn-block btn-sm my-1 detail" id="{{ $datamasterimpsgroup->PM_TEMPLATELIST_UNID_REF }}" >
                                     รายละเอียด<i class="fas fa-caret-right mx-2"></i></button>
                                   </td>
                                 </tr>
                                @endforeach
                              </tbody>
                            </table>
                          </div>
                          <div class="table col-md-6 col-lg-6" >
                            @foreach ($DATA_MASTERIMPS_GROUP as $datamasterimpsgroupsub)
                            <table class="table table-sm table-bordered pmlistdetail" id="{{ $datamasterimpsgroupsub->PM_TEMPLATELIST_UNID_REF}}-1">
                                <thead>
                                  <tr>
                                    <th colspan="2">รายละเอียด{{ $datamasterimpsgroupsub->PM_TEMPLATELIST_NAME }}</th>
                                  </tr>
                                </thead>
                                <tbody >
                                  @php
                                    $pmlistdetailcount = 1;
                                  @endphp
                                  @foreach ($pmlistdetail->where('PM_TEMPLATELIST_UNID_REF','=',$datamasterimpsgroupsub->PM_TEMPLATELIST_UNID_REF) as $key => $datapmlistdetail)
                                    <tr>
                                      <td style="width:25px" class="text-center">{{ $pmlistdetailcount++ }}</td>
                                      <td style="width:330px">{{$datapmlistdetail->PM_DETAIL_NAME}}</td>
                                      <td>{{ $datapmlistdetail->PM_DETAIL_STD }}</td>
                                    </tr>
                                  @endforeach
                                </tbody>

                            </table>
                              @endforeach
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
