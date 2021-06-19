
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
  /* @media all and (min-width: 900px) {
      .modal-body-step{
          height: 750px;
          overflow-y: auto;
      }
    } */
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
            <h4 class="modal-title badge my-1" id="step1">ตรวจสอบเบื้องต้น</h4>
            <i class="separator mx-2">
              <i class="fas fa-arrow-right"></i>
            </i>
            <h4 class="modal-title badge my-1" id="step2">เลือกช่าง</h4>
            <i class="separator mx-2">
              <i class="fas fa-arrow-right"></i>
            </i>
            <h4 class="modal-title badge my-1" id="step3">อะไหล่</h4>
            <i class="separator mx-2">
              <i class="fas fa-arrow-right"></i>
            </i>
            <h4 class="modal-title badge my-1" id="step4">การดำเนินงาน</h4>
            <i class="separator mx-2">
              <i class="fas fa-arrow-right"></i>
            </i>
            <h4 class="modal-title badge my-1" id="step5">สรุปผล</h4>
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
              <div class="col-6 col-sm-6 col-md-6 col-lg-4 ml-auto">
                <label>วันที่เริ่มตรวจสอบ</label>
                <input type="date" class="form-control form-control-sm " value="{{ date('Y-m-d') }}">
              </div>
              <div class="col-6 col-sm-6 col-md-6 col-lg-4 mr-auto">
                <label>เวลาตรวจสอบ</label>
                <input type="time" class="form-control form-control-sm " value="{{ date('H:m') }}">
              </div>
            </div>
            <div class="row has-error">
              <div class="col-6 col-sm-6 col-md-6 col-lg-4 ml-auto">
                <label>วันที่ตรวจสอบเสร็จ</label>
                <input type="date" class="form-control form-control-sm " value="{{ date('Y-m-d') }}">
              </div>
              <div class="col-6 col-sm-6 col-md-6 col-lg-4 mr-auto">
                <label>เวลาตรวจสอบเสร็จ</label>
                <input type="time" class="form-control form-control-sm " value="{{ date('H:m') }}">
              </div>
            </div>
            <div class="row has-error">
              <div class="col-12 col-sm-12 col-md-12 col-lg-8 ml-auto mr-auto">
                <lable>รายละเอียดการตรวจสอบ</lable>
                <textarea class="form-control" row="2"></textarea>
              </div>
            </div>
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
            <div class="form-group" id="work_in" hidden>
              <div class="row">
                <div class="col-4 col-md-3 col-lg-2 ml-auto text-right">
                  <label>เพิ่มพนักงาน :</label>
                </div>
                <div class="col-5 col-md-6 col-lg-4">
                  <select class="form-control form-control-sm col-9 REC_WORKER_NAME" id="WORKER_SELECT" name="WORKER_SELECT">
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
            <div class="form-group has-error"id="work_out" hidden>
              <div class="row" >
                <div class="col-6 col-sm-6 col-md-6 col-lg-4 ml-auto my-1">
                  <label>วันที่เริ่มซ่อม</label>
                  <input type="date" class="form-control form-control-sm " value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-4 mr-auto my-1">
                  <label>เวลาซ่อม</label>
                  <input type="time" class="form-control form-control-sm " value="{{ date('H:m') }}">
                </div>
              </div>
              <div class="row">
                <div class="col-6 col-sm-6 col-md-6 col-lg-4 ml-auto my-1">
                  <label>วันที่ซ่อมเสร็จ</label>
                  <input type="date" class="form-control form-control-sm "value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-4 mr-auto my-1">
                  <label>เวลาซ่อมเสร็จ</label>
                  <input type="time" class="form-control form-control-sm " value="{{ date('H:m') }}">
                </div>
              </div>
              <div class="row my-1">
                <div class="col-6 col-sm-6 col-md-6 col-lg-4 ml-auto">
                  <label> บริษัท/บุคคล </label>
                    <input type="text" class="form-control form-control-sm " autocomplete="">
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-4 mr-auto">
                  <label> ค่าบริการ </label>
                  <div class="input-group ">
                    <input type="number" class="form-control form-control-sm" min="0" value="0" step=".01">
                    <div class="input-group-append">
                      <span class="input-group-text">บาท</span>
                    </div>
                  </div>
                </div>
                <div class="col-8 col-sm-9 col-md-8 col-lg-6 ml-auto">
                  <label>วิธีการแก้ไข</label>
                  <textarea class="form-control mt--1 mb-1" row="2"></textarea>
                </div>
                <div class="col-4 col-sm-3 col-lg-4 mt-5">
                  <button type="button" class="btn btn-primary btn-sm mt-2 mx-2"><i class="fas fa-plus"> เพิ่ม</i></button>

                </div>
              </div>
              <div class="row">
                <div class="col-md-12 col-lg-8 ml-auto mr-auto">
                  <table class="table table-bordered table-head-bg-info table-bordered-bd-info mt-4">
										<thead>
											<tr>
												<th >#</th>
												<th >ชื่อ บริษัท/บุคคล</th>
												<th >ค่าบริการ</th>
											</tr>
										</thead>
										<tbody>
											<tr>
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
											</tr>
											<tr>
												<td>2</td>
												<td style="height: 50px;">Machinery Imporium (1995) co., ltd.
                          <div class="row">
                            <div class="col-md-12">
                              <label>วิธีการแก้ไข</label>
                              <textarea class="form-control mt--1 mb-1"></textarea>
                            </div>
                          </div>
                        </td>
												<td>2100 บาท</td>
											</tr>

										</tbody>
									</table>
                </div>
              </div>
            </div>
            <div class="row my-3">
              <div class="col-md-12 col-lg-10 modal-footer">
                <div class="col-9 col-sm-10 col-md-6 col-lg-3 ml-auto" >
                  <button type="button" class="btn btn-secondary btn-sm btn-link text-left" id="previous_worker" onclick="previous_step(1)">
                    <i class="fas fa-arrow-left fa-2x"></i>
                  </button>
                </div>
                <div class="col-3 col-sm-2 col-md-4 col-lg-2  ml-auto " >
                  <button type="button" class="btn btn-secondary btn-sm  btn-link text-right"
                  onclick="nextstep(3)">
                    <i class="fas fa-arrow-right fa-2x"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="WORK_STEP_3">
            <div class="form-group">
              <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-6 form-inline has-error">
                  <label>เพิ่มอะไหล่</label>
                  <div class="col-9 col-sm-10 col-md-10 col-lg-9">
                    <select class="form-control form-control-sm col-9 REC_WORKER_NAME" id="SPAREPART" name="SPAREPART">
                    </select>
                  </div>
                </div>
                <div class="col-4 col-sm-7 col-md-4 col-lg-3 my-2 form-inline has-error">
                  <label>จำนวนเบิก</label>
                  <input type="number" class="form-control form-control-sm col-md-5 mx-1" id="TOTAL_SPAREPART" min="0" value="1">
                </div>
                <div class="col-7 col-sm-5 col-md-4 col-lg-3 my-2  form-inline">
                  <button type="button" class="btn btn-secondary btn-sm mx-1" onclick="add_sparepart(1)">
                    ตัดสต็อก</button>
                  <button type="button" class="btn btn-secondary btn-sm mx-1" onclick="add_sparepart(2)">
                    ไม่ตัดสต็อก</button>
                </div>
              </div>
            </div>
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
                    <td colspan="7"></td>
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
                  <input type="date" class="form-control form-control-sm " value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-6 col-sm-6 col-md-4 mr-auto">
                  <label>เวลาสั่งซื้อ</label>
                  <input type="time" class="form-control form-control-sm " >
                </div>
              </div>
              <div class="row">
                <div class="col-6 col-sm-6 col-md-4 ml-auto">
                  <label>วันที่รับเข้า</label>
                  <input type="date" class="form-control form-control-sm ">
                </div>
                <div class="col-6 col-sm-6 col-md-4 mr-auto">
                  <label>เวลาสั่งซื้อ</label>
                  <input type="time" class="form-control form-control-sm " >
                </div>
              </div>
            </div>
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
            <div class="row">
              <div class="col-6 col-sm-6 col-md-4 ml-auto">
                <label>วันที่เริ่มซ่อม</label>
                <input type="date" class="form-control form-control-sm " value="{{ date('Y-m-d') }}">
              </div>
              <div class="col-6 col-sm-6 col-md-4 mr-auto">
                <label>เวลาซ่อม</label>
                <input type="time" class="form-control form-control-sm " >
              </div>
            </div>
            <div class="row">
              <div class="col-6 col-sm-6 col-md-4 ml-auto">
                <label>วันที่ซ่อมเสร็จ</label>
                <input type="date" class="form-control form-control-sm ">
              </div>
              <div class="col-6 col-sm-6 col-md-4 mr-auto">
                <label>เวลาซ่อมเสร็จ</label>
                <input type="time" class="form-control form-control-sm " >
              </div>
            </div>
            <div class="row">
              <div class="col-12 col-sm-12 col-md-8 ml-auto mr-auto">
                <label>วิธีการแก้ไข</label>
                <textarea class="form-control"></textarea>
              </div>
            </div>
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
            <div class="row">
              <div class="col-12 col-lg-10 ml-auto mr-auto" >
							<div class="page-divider"></div>
							<div class="row">
								<div class="col-md-12">
									<div class="card card-invoice" style="border: groove;">
                    <div class="card-header">
                      <div class="invoice-header form-inline">
                        <h3 class="invoice-title">
                          MC-001
                        </h3>
                        {{-- <div class="invoice-logo form-inline">
                          <img src="http://www.cmms.com/assets/img/logo13.jpg" alt="company logo" width="50px"><h4 class="mx-2">P Quality Machine Parts</h4>
                        </div> --}}
                      </div>
                      <div class="form-inline">
                        <div class="invoice-desc my-2">ผู้รับงาน : สุบรรณ์</div>
                        {{-- <div class="invoice-desc text-right my-2">188/8 หมู่ 1 ถ.เทพารักษ์ ต.บางเสาธง
                          <br>อ.บางเสาธง จ.สมุทรปราการ 10540</div> --}}
                      </div>
                    </div>
										<div class="card-body">
											<div class="row">
												<div class="col-6 col-sm-3 col-md-4 info-invoice">
													<h5 class="sub">วันที่แจ้ง</h5>
													<p>{{date('d-m-Y')}}</p>
												</div>
												<div class="col-6 col-sm-3 col-md-4 info-invoice">
													<h5 class="sub">วันที่ซ่อมเสร็จ</h5>
													<p>{{date('d-m-Y',strtotime('2021/06/20'))}}</p>
												</div>
												<div class="col-12 col-sm-6 col-md-4 info-invoice">
													<h5 class="sub">ระยะเวลา DownTime</h5>
														<p>{{date_diff(date_create(date('d-m-Y')),date_create(date('d-m-Y',strtotime('2021/06/20'))))->format('%d วัน %h ชั่วโมง %i นาที')}}</p>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class="invoice-detail">
														<div class="invoice-top">
															<h3 class="title"><strong>รายการอะไหล่</strong></h3>
														</div>
														<div class="invoice-item">
															<div class="table-responsive">
																<table class="table table-striped">
																	<thead>
																		<tr>
																			<td><strong>รหัส</strong></td>
																			<td class="text-left"><strong>ชื่อ</strong></td>
																			<td class="text-left"><strong>จำนวน</strong></td>
																			<td class="text-right"><strong>ราคา</strong></td>
																		</tr>
																	</thead>
																	<tbody>
																		<tr>
																			<td>BS-200</td>
																			<td class="text-left">สายพาน</td>
																			<td class="text-left">1</td>
																			<td class="text-right">200 ฿</td>
																		</tr>
																		<tr>
																			<td>BS-400</td>
																			<td class="text-left">belling</td>
																			<td class="text-left">3</td>
																			<td class="text-right">200 ฿</td>
																		</tr>
																		<tr>
																			<td>BS-1000</td>
																			<td class="text-left">-</td>
																			<td class="text-left">1</td>
																			<td class="text-right">200 ฿</td>
																		</tr>
																		<tr>
																			<td></td>
																			<td></td>
																			<td class="text-center"><strong>รวม</strong></td>
																			<td class="text-right">600 ฿</td>
																		</tr>
																	</tbody>
																</table>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
                    <style>
                    .card-invoice .transfer-total .price {
                          font-size: 28px;
                          color: #1572E8;
                          padding: 7px 0;
                          font-weight: 600;
                      }
                    </style>
										<div class="card-footer">
                      <div class="row">
												<div class="col-7 col-sm-7 col-md-5 mb-3 mb-md-0 transfer-to">
													<h5 class="sub">ค่าบริการช่างภายนอก</h5>
												</div>
												<div class="col-5 col-sm-5 col-md-7 transfer-total">
													<h5 class="sub">2,000 ฿</h5>
												</div>
											</div>
                      <div class="row">
                        <div class="col-6 col-sm-7 col-md-8 mb-3 mb-md-0 transfer-to">
												</div>
                        <div class="col-6 col-sm-5 col-md-4 transfer-total">
													<h5 class="sub">ค่าใช้จ่ายทั้งหมด</h5>
													<div class="price">600 ฿</div>
												</div>
                      </div>
											<div class="separator-solid"></div>
                      <h6 class="text-uppercase mt-4 mb-3 fw-bold">
												การแก้ไข
											</h6>
											<p class="text-muted mb-0">
												We really appreciate your business and if there's anything else we can do, please let us know! Also, should you need us to add VAT or anything else to this order, it's super easy since this is a template, so just ask!
											</p>
										</div>
									</div>
								</div>
							</div>
						</div>
            </div>
            <div class="row my-3">
              <div class="col-md-12 col-lg-10 modal-footer">
                <div class="col-6 col-sm-10 col-md-6 col-lg-3 ml-auto" >
                  <button type="button" class="btn btn-secondary btn-sm btn-link text-left" id="previous_worker" onclick="previous_step(4)">
                    <i class="fas fa-arrow-left fa-2x"></i>
                  </button>
                </div>
                <div class="col-6 col-sm-2 col-md-4 col-lg-2  ml-auto " >
                  <button type="button" class="btn btn-secondary btn-sm  btn-link text-right"
                  id="closeform">
                    <i class="fas fa-clipboard-check fa-2x">ปิดเอกสาร</i>
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
