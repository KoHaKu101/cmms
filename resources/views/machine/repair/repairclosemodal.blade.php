
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
       <form action="#" id="FRM_WORK_STEP_0" enctype="multipart/form-data">
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
        </form>
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
      height: 530px;
      overflow-y: auto;
  }
  .badge{
    font-size: 14px;
  }

  @media all and (max-width: 600px) {
      .modal-body-step{
          height: 500px;
          overflow-y: auto;
      }
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
</style>
<div class="modal fade" id="CloseForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLalavel" aria-hidden="true">
  <div id="overlay">
    <div class="cv-spinner">
      <span class="spinner"></span>
    </div>
  </div>
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
          <div class="col-12 col-md-12  my-1  ">
            <h4 class="modal-title badge my-1 WORK_STEP_1">ตรวจสอบเบื้องต้น</h4>
            <i class="separator mx-2">
              <i class="fas fa-arrow-right"></i>
            </i>
            <h4 class="modal-title badge my-1 WORK_STEP_2">เลือกช่าง</h4>
            <i class="separator mx-2">
              <i class="fas fa-arrow-right"></i>
            </i>
            <h4 class="modal-title badge my-1 WORK_STEP_3">อะไหล่</h4>
            <i class="separator mx-2">
              <i class="fas fa-arrow-right"></i>
            </i>
            <h4 class="modal-title badge my-1 WORK_STEP_4">การดำเนินงาน</h4>
            <i class="separator mx-2">
              <i class="fas fa-arrow-right"></i>
            </i>
            <h4 class="modal-title badge my-1 WORK_STEP_5">สรุปผล</h4>
          </div>
        </div>
        <div class="tab-content my-4  ">
          <div class="tab-pane active" id="WORK_STEP_1">
            <form action="#" id="FRM_WORK_STEP_1" enctype="multipart/form-data">
              <div class="row has-error">
                <div class="col-6 col-sm-6 col-md-6 col-lg-4 ml-auto">
                  <label>วันที่เริ่มตรวจสอบ</label>
                  <input type="date" class="form-control form-control-sm "
                    id="INSPECTION_START_DATE" name="INSPECTION_START_DATE" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-4 mr-auto">
                  <label>เวลาตรวจสอบ</label>
                  <input type="time" class="form-control form-control-sm "
                    id="INSPECTION_START_TIME" name="INSPECTION_START_TIME" value="{{ date('H:m') }}" required>
                </div>
              </div>
              <div class="row has-error">
                <div class="col-6 col-sm-6 col-md-6 col-lg-4 ml-auto">
                  <label>วันที่ตรวจสอบเสร็จ</label>
                  <input type="date" class="form-control form-control-sm"
                    id="INSPECTION_END_DATE" name="INSPECTION_END_DATE" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-4 mr-auto">
                  <label>เวลาตรวจสอบเสร็จ</label>
                  <input type="time" class="form-control form-control-sm"
                    id="INSPECTION_END_TIME" name="INSPECTION_END_TIME" value="{{ date('H:m') }}" required>
                </div>
              </div>
              <div class="row has-error">
                <div class="col-12 col-sm-12 col-md-12 col-lg-8 ml-auto mr-auto">
                  <lable>รายละเอียดการตรวจสอบ</lable>
                  <textarea class="form-control" id="INSPECTION_DETAIL" name="INSPECTION_DETAIL" row="2"></textarea>
                </div>
              </div>
            </form>
            <div class="row my-3">
              <div class="col-md-12 col-lg-10 modal-footer">
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
            <form action="#" class="form_work_in"enctype="multipart/form-data">
              <input type="hidden" id="WORKER_TYPE" name="WORKER_TYPE" value="IN">
              <div class="form-group" id="WORK_IN" hidden>
                <div class="row">
                  <div class="col-4 col-md-3 col-lg-2 ml-auto text-right">
                    <label>เพิ่มพนักงาน :</label>
                  </div>
                  <div class="col-5 col-md-6 col-lg-4">
                    <select class="form-control form-control-sm col-9 REC_WORKER_NAME" id="WORKER_SELECT" name="WORKER_SELECT">
                    </select>
                  </div>
                  <div class="col-3 col-md-2 col-lg-2 mr-auto">
                      <button type="button" class="btn btn-primary btn-sm mx-1" id="add_worker"><i class="fas fa-plus"></i>เพิ่มพนักงาน</button>
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
            </form>
            <form action="#" class="form_work_out" enctype="multipart/form-data">
              <input type="hidden" id="WORKER_TYPE" name="WORKER_TYPE" value="OUT">
              <div class="form-group has-error"id="WORK_OUT" hidden>
                  <div class="row">
                    <div class="col-lg-6 ml-auto">
                      <label> บริษัท/บุคคล </label>
                        <input type="text" class="form-control form-control-sm WORKEROUT_NAME" autocomplete="" >
                    </div>
                    <div class="col-lg-4 mr-auto">
                      <label> ค่าบริการ </label>
                      <div class="input-group ">
                        <input type="number" class="form-control form-control-sm WORKEROUT_COST" min="0" value="0" step=".01" >
                        <div class="input-group-append">
                          <span class="input-group-text">บาท</span>
                        </div>
                        <button type="button" class="btn btn-primary btn-sm  mx-2"id="add_workerout"><i class="fas fa-plus" > เพิ่ม</i></button>
                      </div>
                    </div>

                  </div>
                  <div class="row my-1">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-10 ml-auto mr-auto">
                      <label>วิธีการแก้ไข</label>
                      <textarea class="form-control mt--1 mb-1 WORKEROUT_DETAIL" row="2" ></textarea>
                    </div>
                  </div>
                <div class="row">
                  <div class="col-md-12 col-lg-10 ml-auto mr-auto">
                    <table class="table table-bordered table-head-bg-info table-bordered-bd-info mt-4" id="table_workerout">
  										<thead>
  											<tr>
  												<th >#</th>
  												<th >ชื่อ บริษัท/บุคคล</th>
  												<th >ค่าบริการ</th>
                          <th >action</th>
  											</tr>
  										</thead>
  										<tbody>
  											<tr hidden>
  												<td>1</td>
  												<td style="height: 50px;" >Machinery Imporium (1995) co., ltd.
                            <div class="row">
                              <div class="col-md-12">
                                <label>วิธีการแก้ไข</label>
                                <textarea class="form-control mt--1 mb-1"></textarea>
                              </div>
                            </div>
                          </td>
  												<td>1000 บาท</td>
                          <td></td>
  											</tr>
  										</tbody>
  									</table>
                  </div>
                </div>
              </div>
            </form>
            <div class="row my-3">
              <div class="col-md-12 col-lg-10 modal-footer">
                <div class="col-9 col-sm-10 col-md-6 col-lg-3 ml-auto mr-auto" >
                  <button type="button" class="btn btn-secondary btn-sm btn-link text-left" id="previous_worker" onclick="previous_step(1)">
                    <i class="fas fa-arrow-left fa-2x"></i>
                  </button>
                </div>
                <div class="col-3 col-sm-2 col-md-4 col-lg-2  ml-auto "  >
                  <button type="button" class="btn btn-secondary btn-sm  btn-link text-right" id="nextstep_3"hidden
                  >
                    <i class="fas fa-arrow-right fa-2x"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <style>
          .text-hidden{
            color: #ffffff00!important;
          }
          </style>
          <div class="tab-pane" id="WORK_STEP_3">
            <div class="form-group">
            <div class="row">
              <div class="col-9 col-lg-5 has-error">
                <label>เพิ่มอะไหล่</label>
                <div class="col-lg-11">
                  <select class="form-control form-control-sm " id="SPAREPART" name="SPAREPART"></select>
                </div>
              </div>
              <div class="col-3 col-lg-2 has-error">
                <label>จำนวน</label>
                <input type="number" class="form-control form-control-sm  mx-2" id="TOTAL_SPAREPART" min="0" value="1" step='1'>
              </div>
              <div class="col-5 col-lg-2 has-error">
                <label>ราคา</label>
                <input type="number" class="form-control form-control-sm  mx-2" id="SPAREPART_COST" name="SPAREPART_COST" min="0" value="1">
              </div>
              <div class="col-7 col-lg-3">
                <label class="text-hidden">จำนวน</label>
                <div>
                  <button type="button" class="btn btn-primary btn-sm mx-1 " onclick="add_sparepart(1)">
                    ตัดสต็อก</button>
                  <button type="button" class="btn btn-primary btn-sm " onclick="add_sparepart(2)">
                    ไม่ตัดสต็อก</button>
                </div>

              </div>
            </div>
            </div>
            <form action="#" id="FRM_WORK_STEP_3" enctype="multipart/form-data">
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
                        <th>Unit</th>
                        <th>เบิก</th>
                      </tr>
                    </thead>
                    <tbody id="table_sparepart">
                      <td colspan="8"></td>
                    </tbody>
                  </table>
                </div>
                <div class="row">
                  <div class="col-md-2">
                    <button type="button" class="btn btn-primary btn-sm" id="addbuy_sparepart"
                    value="1"
                    ><i class="fas fa-dollar-sign mr-1"></i>สั่งอะไหล่</button>
                  </div>
                </div>
              </div>
              <div id="buy_sparepart" hidden>
                  <div class="row has-error" >
                    <div class="col-6 col-sm-6 col-md-4 ml-auto">
                      <label>วันที่สั่งซื้อ</label>
                      <input type="date" class="form-control form-control-sm buy_sparepart" id="SPAREPART_START_DATE" name="SPAREPART_START_DATE" value="{{ date('Y-m-d') }}" disabled>
                    </div>
                    <div class="col-6 col-sm-6 col-md-4 mr-auto">
                      <label>เวลาสั่งซื้อ</label>
                      <input type="time" class="form-control form-control-sm buy_sparepart" id="SPAREPART_START_TIME" name="SPAREPART_START_TIME" value="{{ date('H:i') }}" disabled>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-6 col-sm-6 col-md-4 ml-auto">
                      <label>วันที่รับเข้า</label>
                      <input type="date" class="form-control form-control-sm buy_sparepart" id="SPAREPART_END_DATE" name="SPAREPART_END_DATE" value="{{ date('Y-m-d') }}" disabled>
                    </div>
                    <div class="col-6 col-sm-6 col-md-4 mr-auto">
                      <label>เวลาสั่งซื้อ</label>
                      <input type="time" class="form-control form-control-sm buy_sparepart" id="SPAREPART_END_TIME" name="SPAREPART_END_TIME" value="{{ date('H:i') }}" disabled>
                    </div>
                  </div>
             </div>
            </form>
            <div class="row my-3">
              <div class="col-md-12 col-lg-10 modal-footer">
                <div class="col-9 col-sm-10 col-md-6 col-lg-3 ml-auto" >
                  <button type="button" class="btn btn-secondary btn-sm btn-link text-left"
                    onclick="previous_step(2)">
                    <i class="fas fa-arrow-left fa-2x"></i>
                  </button>
                </div>
                <div class="col-3 col-sm-2 col-md-4 col-lg-2  ml-auto " >
                  <button type="button" class="btn btn-secondary btn-sm  btn-link text-right"
                  onclick="nextstep(4)">
                    <i class="fas fa-arrow-right fa-2x"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div class="tab-pane has-error" id="WORK_STEP_4">
            <form action="#" enctype="multipart/form-data" id="FRM_WORK_STEP_4">
              <div class="row">
                <div class="col-6 col-sm-6 col-md-4 ml-auto">
                  <label>วันที่เริ่มซ่อม</label>
                  <input type="date" class="form-control form-control-sm " id="WORKER_START_DATE" name="WORKER_START_DATE"
                  value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-6 col-sm-6 col-md-4 mr-auto">
                  <label>เวลาซ่อม</label>
                  <input type="time" class="form-control form-control-sm " id="WORKER_START_TIME" name="WORKER_START_TIME"
                  value="{{ date('H:i') }}" required>
                </div>
              </div>
              <div class="row">
                <div class="col-6 col-sm-6 col-md-4 ml-auto">
                  <label>วันที่ซ่อมเสร็จ</label>
                  <input type="date" class="form-control form-control-sm " id="WORKER_END_DATE" name="WORKER_END_DATE"
                  value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-6 col-sm-6 col-md-4 mr-auto">
                  <label>เวลาซ่อมเสร็จ</label>
                  <input type="time" class="form-control form-control-sm " id="WORKER_END_TIME" name="WORKER_END_TIME"
                  value="{{ date('H:i') }}" required>
                </div>
              </div>
              <div class="row">
                <div class="col-12 col-sm-12 col-md-8 ml-auto mr-auto">
                  <label>วิธีการแก้ไข</label>
                  <textarea class="form-control" id="REPAIR_DETAIL" name="REPAIR_DETAIL" required></textarea>
                </div>
              </div>
            </form>
            <div class="row my-3">
              <div class="col-md-12 col-lg-10 modal-footer">
                <div class="col-9 col-sm-10 col-md-6 col-lg-3 ml-auto" >
                  <button type="button" class="btn btn-secondary btn-sm btn-link text-left" id="previous_worker" onclick="previous_step(3)">
                    <i class="fas fa-arrow-left fa-2x"></i>
                  </button>
                </div>
                <div class="col-3 col-sm-2 col-md-4 col-lg-2  ml-auto " >
                  <button type="button" class="btn btn-secondary btn-sm  btn-link text-right"
                  onclick="nextstep(5)">
                    <i class="fas fa-arrow-right fa-2x"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="WORK_STEP_5">

            <div class="row" id="WORK_STEP_RESULT">
            </div>
            <div class="row">
              <div class="col-md-12 col-lg-10 modal-footer" id="stepsave">
                <div class="col-5 col-sm-7 col-md-7 col-lg-8 ml-auto" >
                  <button type="button" class="btn btn-secondary btn-sm btn-link text-left"  onclick="previous_step(4)">
                    <i class="fas fa-arrow-left fa-2x"></i>
                  </button>
                </div>
                <div class="col-7 col-sm-5 col-md-5 col-lg-2  ml-auto " >
                  <button type="button" class="btn btn-secondary btn-sm  text-right"
                  id="closeform" >
                    <i class="fas fa-clipboard-check fa-2x"> ปิดเอกสาร</i>
                  </button>
                </div>
              </div>
              <div class="col-md-12 col-lg-10 modal-footer stepclose" hidden>
                <div class="col-5 col-sm-7 col-md-7 col-lg-8 ml-auto" >

                </div>
                <div class="col-7 col-sm-5 col-md-5 col-lg-2  ml-auto " >
                  <button type="button" class="btn btn-secondary btn-sm  text-right"
                  data-dismiss='modal' >
                    <i class="fas fa-door-open fa-2x"> ออก</i>
                  </button>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
