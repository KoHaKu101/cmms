
<style>
.modal-sm {
    max-width: 30% !important;
}
.modal-ms {
    max-width: 50% !important;
}
body.modal-open {
    overflow: visible;
}
</style>

{{-- ปิดเอกสาร --}}
<div class="modal fade" id="RepairForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLalavel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="TITLE_DOCNO">การดำเนินงาน</h5>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12 ml-auto mr-auto" id="show_detail">
          </div>
        </div>
        <div class="row">
            <div class="col-3 col-md-2">
              <label> ผู้รับงาน </label>
            </div>
            <div class="col-9 col-md-10" id="select_recworker">
            </div>
        </div>
      </div>
      <div class="card-footer text-right">
        <button type="button" class="btn btn-sm btn-danger "data-dismiss="modal" >Cancel</i></button>
        <button type="button" class="btn btn-primary btn-sm" id="closestep_1"> Save </button>
      </div>
    </div>
  </div>
</div>
<style>
.card-stats .card-body {
  padding: 0px!important;
}
.modal-body-step{

    overflow-y: auto;
}
.sparepart-table .sparepart-action{
  width: 110px;
}
.separator-solid{
  border-top: 1px solid #c3c3c3;
  margin: 6px;
  margin-left: -1px;
}
.modal-body-step{
    height: 500px;
    overflow-y: auto;
}
  @media all and (max-width: 600px) {
      .modal-body-step{
          height: 500px;
          overflow-y: auto;
      }
  @media all and (min-width: 900px) {
      .modal-body-step{
          height: 750px;
          overflow-y: auto;
      }
  @media all and (max-height: 400px){
    .modal-body-step{
        height: 300px;
        overflow-y: auto;
    }

    .text-col{
      top: 0px;
    }
  }
}
</style>
<div class="modal fade" id="CloseForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLalavel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-primary">
        <h5 class="modal-title" id="TITLE_DOCNO_SUB"></h5>
        <button type="button" class="btn btn-sm btn-danger "data-dismiss="modal" ><i class="fas fa-times"></i></button>
      </div>
      <div class="modal-body modal-body-step">
        <div class="row">
          <div class="col-12 col-md-12">
            <div class="row">
              <div class="col-12 col-md-12">
                  <h4 class="modal-title" id="show-detail">อาการเสีย : {{ Cookie::get('DETAIL')}}</h4>
              </div>
            </div>
          </div>
        </div>
        <div class="separator-solid" ></div>
        <div class="row">
          <div class="col-12 col-md-12 form-inline my-1  ">
            <h4 class="modal-title text-primary" id="step1">ตรวจสอบเบื้องต้น</h4>
            <i class="separator mx-2">
              <i class="fas fa-arrow-right"></i>
            </i>
            <h4 class="modal-title" id="step2">เลือกช่าง</h4>
            <i class="separator mx-2">
              <i class="fas fa-arrow-right"></i>
            </i>
            <h4 class="modal-title" id="step3">อะไหล่</h4>
            <i class="separator mx-2">
              <i class="fas fa-arrow-right"></i>
            </i>
            <h4 class="modal-title" id="step4">การดำเนินงาน</h4>
            <i class="separator mx-2">
              <i class="fas fa-arrow-right"></i>
            </i>
            <h4 class="modal-title" id="step5">สรุปผล</h4>
          </div>
        </div>
        {{-- <div class="separator-solid"></div> --}}
        {{-- <div class="row">
          <div class="col-12 col-md-12 tabactive">
            <div class="col-8 col-md-5 col-lg-3 ml-auto mr-auto">
              <ul class=" nav nav-pills nav-primary">
                <li class="step">
                  <a class="nav-link active WORK_STEP_1"  href="#WORK_STEP_1"
                  aria-expanded="true" id="step" style="width: 191px;font-size: 16px;" data-toggle="tab">
                    <i class="fa fa-user mr-2"></i>ตรวจสอบเบื้องต้น</a>
                </li>
              </ul>
            </div>
              <li class="step">
                <a class="nav-link WORK_STEP2_IN" href="#WORK_STEP2_IN"  data-toggle="tab" id="step2" hidden> </a>
              </li>
              <li class="step">
                <a class="nav-link WORK_STEP3" href="#WORK_STEP3" id="step3"  data-toggle="tab" hidden> </a>
              </li>
              <li class="step">
                <a class="nav-link WORK_STEP3_WAITPART" href="#WORK_STEP3_WAITPART"
                id="step3_waitpart"  data-toggle="tab" hidden> </a>
              </li>
              <li class="step">
                <a class="nav-link WORK_FINAL" href="#WORK_FINAL" id="step4"  data-toggle="tab" hidden></a>
              </li>
              <li class="step">
                <a class="nav-link WORK_RESULT" href="#WORK_RESULT" id="step5"  data-toggle="tab" hidden></a>
              </li>
          </div>
        </div> --}}
        <div class="tab-content my-4  ">
          <div class="tab-pane active" id="WORK_STEP_1">
            <div class="row has-error">
              <div class="col-6 col-sm-10 col-md-4 ml-auto">
                <label>วันที่เริ่มตรวจสอบ</label>
                <input type="date" class="form-control form-control-sm " value="{{ date('Y-m-d') }}">
              </div>
              <div class="col-6 col-sm-10 col-md-4 mr-auto">
                <label>เวลาตรวจสอบ</label>
                <input type="time" class="form-control form-control-sm " value="{{ date('H:m') }}">
              </div>
            </div>
            <div class="row has-error">
              <div class="col-6 col-sm-10 col-md-4 ml-auto">
                <label>วันที่ตรวจสอบเสร็จ</label>
                <input type="date" class="form-control form-control-sm " value="{{ date('Y-m-d') }}">
              </div>
              <div class="col-6 col-sm-10 col-md-4 mr-auto">
                <label>เวลาตรวจสอบเสร็จ</label>
                <input type="time" class="form-control form-control-sm " value="{{ date('H:m') }}">
              </div>
            </div>
            <div class="row my-3">
              <div class="col-md-10 col-lg-10 modal-footer">
                {{-- <div class="col-9 col-sm-3 col-lg-3 mr-auto ml-auto" >
    							<button type="button" class="btn btn-secondary btn-sm btn-link text-left">
                    <i class="fas fa-times fa-2x"></i>
                  </button>
    						</div> --}}
                <div class="text-right" >
    							<button type="button" class="btn btn-secondary btn-sm  btn-link text-right"
                  onclick="nextstep(2)">
                    <i class="fas fa-arrow-right fa-2x"></i>
                  </button>
    						</div>
              </div>
            </div>

          </div>
          <div class="tab-pane " id="WORK_STEP_2">
            <div class="form-group" id="select_typeworker">
              <div class="row ">
                <div class="col-6 col-md-5 col-lg-3 ml-auto">
                  <div class="card card-stats card-primary card-round ">
                    <div class="card-body" style="cursor: pointer;" onclick="type_worker(1)">
                      <div class="row">
                        <div class="col-5">
                          <div class="icon-big ml-3">
                            <i class="fas fa-users-cog"></i>
                          </div>
                        </div>
                        <div class="col-7 col-stats">
                          <div class="numbers">
                            <h4 class="card-title text-center">ภายใน</h4>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-6 col-md-5 col-lg-3  mr-auto">
                  <div class="card card-stats card-primary card-round ">
                    <div class="card-body" style="cursor: pointer;" onclick="type_worker(2)">
                      <div class="row">
                        <div class="col-5">
                          <div class="icon-big ml-3">
                            <i class="fas fa-users"></i>
                          </div>
                        </div>
                        <div class="col-7 col-stats">
                          <div class="numbers">
                            <h4 class="card-title text-center">ภายนอก</h4>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group" id="work_in" hidden>
              <div class="row">
                <div class="col-4 col-md-3 col-lg-2 ml-auto text-right">
                  <label>เพิ่มพนักงาน :</label>
                </div>
                <div class="col-5 col-md-6 col-lg-4">
                  <select class="form-control form-control-sm col-9 REC_WORKER_NAME" id="WORKER_SELECT" name="WORKER_SELECT">
                    <option value> กรุณาเลือก </option>
                    {{-- @foreach ($DATA_EMPNAME as $index => $row)
                        <option value="{{ $row->EMP_CODE }}">{{ $row->EMP_CODE. ' ' .$row->EMP_NAME_TH }}</option>
                    @endforeach --}}
                  </select>
                </div>
                <div class="col-3 col-md-2 col-lg-2 mr-auto">
                    <button type="button" class="btn btn-secondary btn-sm mx-1" id="add_worker"><i class="fas fa-plus"></i>เพิ่มพนักงาน</button>
                </div>
              </div>
              <div class="row">
                <div class="col-12 col-md-12 col-lg-8 ml-auto mr-auto">
                  <table class="table table-bordered table-head-bg-info table-bordered-bd-info mt-4">
                    <thead>
                      <tr>
                        <th width="40px" class="text-center">#</th>
                        <th>พนักงาน</th>
                        <th width="60px">action</th>
                      </tr>
                    </thead>
                    <tbody id="table_worker">
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
              <div class="row" id="work_out" hidden>
                <div class="col-6 col-sm-10 col-md-4 ml-auto my-1">
                  <label>วันที่เริ่มซ่อม</label>
                  <input type="date" class="form-control form-control-sm " value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-6 col-sm-10 col-md-4 mr-auto my-1">
                  <label>เวลาซ่อม</label>
                  <input type="time" class="form-control form-control-sm " value="{{ date('H:m') }}">
                </div>
              </div>
              <div class="row">
                <div class="col-6 col-sm-10 col-md-4 ml-auto my-1">
                  <label>วันที่ซ่อมเสร็จ</label>
                  <input type="date" class="form-control form-control-sm "value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-6 col-sm-10 col-md-4 mr-auto my-1">
                  <label>เวลาซ่อมเสร็จ</label>
                  <input type="time" class="form-control form-control-sm " value="{{ date('H:m') }}">
                </div>
              </div>
              <div class="row my-1">
                <div class="col-6 col-sm-5 col-md-4 ml-auto">
                  <label> บริษัท/บุคคล </label>
                  <input type="text" class="form-control form-control-sm" autocomplete>
                </div>
                <div class="col-6 col-sm-5 col-md-4 mr-auto">

                  <label> ค่าใช้จ่าย </label>
                    <div class="input-group">
                      <input type="number" class="form-control form-control-sm"
                      min="0" value="0" step=".01">
                      <div class="input-group-append">
                        <span class="input-group-text">บาท</span>
                      </div>
                    </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12 col-sm-10 col-md-8 ml-auto mr-auto">
                  <label>วิธีการแก้ไข</label>
                  <textarea class="form-control"></textarea>
                </div>
              </div>
            <div class="row my-3">
              <div class="col-5 col-sm-4 col-lg-3 ml-auto" >
  							<div class="card text-white">
  								<div class="d-flex align-items-center bg-danger" style="cursor:pointer" id="previous_worker" onclick="previous_step(1)">
  									<span class="stamp stamp-md bg-danger">
  										<i class="fas fa-arrow-alt-circle-left"></i>
  									</span>
  									<div>
  										<h5 class="mb-1 my-1"><b> ย้อนกลับ </b></h5>
  									</div>
  								</div>
  							</div>
  						</div>
              <div class="col-5 col-sm-4 col-lg-3 mr-auto" >
  							<div class="card text-white" >
  								<div class="d-flex flex-row-reverse align-items-center  bg-primary" style="cursor:pointer"  onclick="nextstep(3)">
  									<span class="stamp stamp-md  bg-primary ">
  										<i class="fas fa-arrow-alt-circle-right"></i>
  									</span>
  									<div>
  										<h5 class="mb-1 my-1"><b> ไปต่อ </b></h5>
  									</div>
  								</div>
  							</div>
  						</div>
            </div>
          </div>
          <div class="tab-pane" id="WORK_STEP_3">

              <div class="form-group">
                <div class="row">
                <div class="col-12 col-md-12 col-lg-8 form-inline">
                  <label>เพิ่มอะไหล่</label>
                  <div class="col-9 col-md-10 col-lg-8">
                    <select class="form-control form-control-sm col-9 REC_WORKER_NAME" id="SPAREPART" name="SPAREPART">
                      <option value> กรุณาเลือก </option>
                      {{-- @foreach ($DATA_SPAREPART as $index => $row)
                          <option value="{{ $row->UNID }}" id="{{ $row->UNID }}"
                            data-sparepartcode="{{$row->SPAREPART_CODE}}"
                            data-sparepartname="{{$row->SPAREPART_NAME}}"
                            data-sparepartsize="{{$row->SPAREPART_SIZE}}"
                            data-sparepartmodel="{{$row->SPAREPART_MODEL}}"
                            >{{ $row->SPAREPART_CODE. ' : '. $row->SPAREPART_NAME }}</option>
                      @endforeach --}}
                    </select>
                  </div>
                </div>
                <div class="col-4 col-md-4 col-lg-2 my-2 ml-auto mr-auto">
                  <button type="button" class="btn btn-secondary btn-sm btn-block" onclick="add_sparepart(1)">
                    <i class="fas fa-plus mr-2"></i>ตัดสต็อก</button>
                </div>
                <div class="col-5 col-md-4 col-lg-2 my-2 ml-auto mr-auto">
                  <button type="button" class="btn btn-secondary btn-sm btn-block" onclick="add_sparepart(2)">
                    <i class="fas fa-plus mr-2"></i>เปลี่ยนอะไหล่</button>
                </div>
              </div>

            </div>
            {{-- <div class="row">
              <div class="col-6 col-md-6 col-lg-3 my-2">
                  <input type="text" class="form-control-sm form-control-plaintext bg-info text-white " id="SPAREPART_CODE" readonly value="รหัส :">
              </div>
              <div class="col-6 col-md-6 col-lg-3 my-2">
                <input type="text" class="form-control-sm form-control-plaintext bg-info text-white " id="SPAREPART_NAME" readonly value="ชื่อ :">
              </div>
              <div class="col-4 col-md-4 col-lg-2 my-2">
                <input type="text" class="form-control-sm form-control-plaintext bg-info text-white " id="SPAREPART_SIZE" readonly value="เบอร์ :">
              </div>
              <div class="col-4 col-md-4 col-lg-2 my-2">
                <input type="text" class="form-control-sm form-control-plaintext bg-info text-white " id="SPAREPARTM_ODEL" readonly value="ขนาด :">
              </div>


            </div> --}}
            <div class="form-group mt--4">
              <div class="row sparepart-table-responsive">
                <table class="table sparepart-table table-bordered table-head-bg-info table-bordered-bd-info mt-2">
                  <thead>
                    <tr>
                      <th class="sparepart-action">action</th>
                      <th>รหัส</th>
                      <th>ชื่อ</th>
                      <th>เบอร์</th>
                      <th>ขนาด</th>
                      <th>ราคา</th>
                      <th>เบิก</th>
                    </tr>
                  </thead>
                  <tbody id="table_sparepart">

                  </tbody>
                </table>
              </div>
            </div>
            <div class="row my-3">
              <div class="col-5 col-sm-4 col-lg-3 ml-auto" >
  							<div class="card text-white">
  								<div class="d-flex align-items-center bg-danger" style="cursor:pointer"  onclick="previous_step(2)">
  									<span class="stamp stamp-md bg-danger">
  										<i class="fas fa-arrow-alt-circle-left"></i>
  									</span>
  									<div>
  										<h5 class="mb-1 my-1"><b> ย้อนกลับ </b></h5>
  									</div>
  								</div>
  							</div>
  						</div>
              <div class="col-5 col-sm-4 col-lg-3 mr-auto" >
  							<div class="card text-white" >
  								<div class="d-flex flex-row-reverse align-items-center  bg-primary" style="cursor:pointer"  onclick="nextstep(4)">
  									<span class="stamp stamp-md  bg-primary ">
  										<i class="fas fa-arrow-alt-circle-right"></i>
  									</span>
  									<div>
  										<h5 class="mb-1 my-1"><b> ไปต่อ </b></h5>
  									</div>
  								</div>
  							</div>
  						</div>
            </div>
            {{-- <div class="form-group">
              <div class="row">
                <div class="col-12 col-md-6 col-lg-4  ml-auto " id="buypart" hidden>
                  <div class="card card-stats card-warning card-round">
                    <div class="card-body" style="cursor: pointer;" onclick="buypart()">
                      <div class="row">
                        <div class="col-5">
                          <div class="icon-big text-center">
                            <i class="fas fa-clipboard-check"></i>
                          </div>
                        </div>
                        <div class="col-7 col-stats">
                          <div class="numbers">
                            <h4 class="card-title text-center">สั่งซื้อ</h4>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4 ml-auto mr-auto">
                  <div class="card card-stats card-primary card-round">
                    <div class="card-body" style="cursor: pointer;" onclick="step_final()">
                      <div class="row">
                        <div class="col-5">
                          <div class="icon-big text-center">
                            <i class="fas fa-clipboard-check"></i>
                          </div>
                        </div>
                        <div class="col-7 col-stats">
                          <div class="numbers">
                            <h4 class="card-title text-center">ปิดเอกสาร</h4>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div> --}}
          </div>
          <div class="tab-pane" id="WORK_STEP3_WAITPART">
            <div class="form-group">
              <div class="row">
                <div class="col-6 col-sm-10 col-md-4 ml-auto">
                  <label>วันที่สั่งซื้อ</label>
                  <input type="date" class="form-control form-control-sm " value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-6 col-sm-10 col-md-4 mr-auto">
                  <label>เวลาสั่งซื้อ</label>
                  <input type="time" class="form-control form-control-sm " >
                </div>
              </div>
              <div class="row">
                <div class="col-6 col-sm-10 col-md-4 ml-auto">
                  <label>วันที่รับเข้า</label>
                  <input type="date" class="form-control form-control-sm ">
                </div>
                <div class="col-6 col-sm-10 col-md-4 mr-auto">
                  <label>เวลาสั่งซื้อ</label>
                  <input type="time" class="form-control form-control-sm " >
                </div>
              </div>
              <div class="row">
                <table class="table table-bordered table-head-bg-info table-bordered-bd-info mt-4">
                  <thead>
                    <tr>
                      <th scope="col">action</th>
                      <th scope="col">อะไหล่</th>
                      <th scope="col">เบอร์</th>
                      <th scope="col">สต็อก</th>
                      <th scope="col">เบิก</th>
                      <th scope="col">คงเหลือ</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <button type="button" class="btn btn-secondary btn-sm btn-block my-1" onclick="withdraw(this)"
                        data-unid="1">เบิก</button>
                      </td>
                      <td>สายพาน</td>
                      <td>8</td>
                      <td>10</td>
                      <td>1</td>
                      <td>9</td>
                    </tr>
                    <tr class="bg-danger text-white">
                      <td>
                        <button type="button" class="btn btn-secondary btn-sm btn-block my-1" onclick="withdraw(this)"
                        data-unid="2">เบิก</button>
                      </td>
                      <td>น้ำมันคูแล่น</td>
                      <td>8</td>
                      <td>0</td>
                      <td>1</td>
                      <td>0</td>
                    </tr>
                    <tr>
                      <td>
                        <button type="button" class="btn btn-secondary btn-sm btn-block my-1" onclick="withdraw(this)"
                        data-unid="3">เบิก</button>
                      </td>
                      <td>มอนิเตอร์</td>
                      <td></td>
                      <td>2</td>
                      <td>1</td>
                      <td>1</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="form-group">
              <div class="row ">
                <div class="col-12 col-md-5 col-lg-4  ml-auto ">
                  <div class="card card-stats card-primary card-round">
                    <div class="card-body" style="cursor: pointer;" data-dismiss="modal" >
                      <div class="row">
                        <div class="col-5">
                          <div class="icon-big text-center">
                            <i class="fas fa-clipboard-check"></i>
                          </div>
                        </div>
                        <div class="col-7 col-stats">
                          <div class="numbers">
                            <h4 class="card-title text-center">รอดำเนินการ</h4>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4  mr-auto">
                  <div class="card card-stats card-primary card-round">
                    <div class="card-body" style="cursor: pointer;" onclick="step_final()">
                      <div class="row">
                        <div class="col-5">
                          <div class="icon-big text-center">
                            <i class="fas fa-clipboard-check"></i>
                          </div>
                        </div>
                        <div class="col-7 col-stats">
                          <div class="numbers">
                            <h4 class="card-title text-center">ปิดเอกสาร</h4>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="WORK_FINAL">
            <div class="row">
              <div class="col-6 col-sm-10 col-md-4 ml-auto">
                <label>วันที่เริ่มซ่อม</label>
                <input type="date" class="form-control form-control-sm " value="{{ date('Y-m-d') }}">
              </div>
              <div class="col-6 col-sm-10 col-md-4 mr-auto">
                <label>เวลาซ่อม</label>
                <input type="time" class="form-control form-control-sm " >
              </div>
            </div>
            <div class="row">
              <div class="col-6 col-sm-10 col-md-4 ml-auto">
                <label>วันที่ซ่อมเสร็จ</label>
                <input type="date" class="form-control form-control-sm ">
              </div>
              <div class="col-6 col-sm-10 col-md-4 mr-auto">
                <label>เวลาซ่อมเสร็จ</label>
                <input type="time" class="form-control form-control-sm " >
              </div>
            </div>
            <div class="row">
              <div class="col-12 col-sm-10 col-md-8 ml-auto mr-auto">
                <label>วิธีการแก้ไข</label>
                <textarea class="form-control"></textarea>
              </div>

            </div>
            <div class="form-group my-4">
              <div class="row ">
                <div class="col-8 col-md-5 col-lg-4  ml-auto mr-auto">
                  <div class="card card-stats card-primary card-round">
                    <div class="card-body" style="cursor: pointer;" onclick="step_result()">
                      <div class="row">
                        <div class="col-5">
                          <div class="icon-big text-center">
                            <i class="fas fa-clipboard-check"></i>
                          </div>
                        </div>
                        <div class="col-7 col-stats">
                          <div class="numbers">
                            <h4 class="card-title text-center">สรุปผล</h4>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="WORK_RESULT">
            <div class="table-responsive">
              <table class="table-step table table-bordered table-head-bg-info table-bordered-bd-info mt-4">
  							<thead>
  								<tr>
  									<th>รายการ</th>
  									<th>เริ่มวันที่</th>
  									<th>เสร็จวันที่</th>
                    <th>เริ่มเวลา</th>
  									<th>เสร็จเวลา</th>
  									<th>สรุประยะเวลา</th>
  								</tr>
  							</thead>
  							<tbody>
  								<tr>
  									<td>ตรวจสอบเบื้องต้น</td>
  									<td>{{date('d-m-',strtotime('08-06-2021')).date('y')+43}}</td>
  									<td>{{date('d-m-',strtotime('08-06-2021')).date('y')+43}}</td>
                    <td>08:30</td>
  									<td>09:00</td>
  									<td>0 วัน 0 ชั่วโมง 30 นาที</td>
  								</tr>
  								<tr>
  									<td>ช่างซ่อมภายนอก</td>
                    <td>-</td>
  									<td>-</td>
                    <td>-</td>
  									<td>-</td>
  									<td>0 วัน 0 ชั่วโมง 0 นาที</td>
  								</tr>
  								<tr>
  									<td>สั่งอะไหล่</td>
                    <td>-</td>
  									<td>-</td>
                    <td>-</td>
  									<td>-</td>
  									<td>0 วัน 0 ชั่วโมง 0 นาที</td>
  								</tr>
                  <tr>
  									<td>ดำเนินการซ่อม</td>
                    <td>{{date('d-m-',strtotime('08-06-2021')).date('y')+43}}</td>
  									<td>{{date('d-m-',strtotime('08-06-2021')).date('y')+43}}</td>
                    <td>9:00</td>
  									<td>10:00</td>
  									<td>0 วัน 1 ชั่วโมง 0 นาที</td>
  								</tr>
                  <tr>
  									<td colspan="5" class="bg-primary text-white text-center">ผลรวม</td>
                    <td>0 วัน 1 ชั่วโมง 30 นาท</td>
  								</tr>
  							</tbody>
  						</table>
            </div>
            <div class="row my-4">
              <div class="col-12 col-sm-12 col-md-12 ml-auto mr-auto">
                <label>วิธีการแก้ไข</label>
                <textarea class="form-control">เช็คระบบน้ำมันหล่อลื่นแกน x</textarea>
              </div>
            </div>
            <button type="button" class="btn btn-primary btn-block" data-dismiss="modal">Save</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
