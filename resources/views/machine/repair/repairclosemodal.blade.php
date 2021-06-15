
<style>
.modal-sm {
    max-width: 30% !important;
}
.modal-ms {
    max-width: 50% !important;
}
.text-col{
  top: 15px;
}
</style>

{{-- ปิดเอกสาร --}}
<div class="modal fade" id="RepairForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLalavel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">การดำเนินงาน</h5>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6 ml-auto mr-auto">
              <table class="table table-bordered table-bordered-bd-info">
                <tbody>
                  <tr>
                    <td width="80px" style="background:#aab7c1;color:black;"><h5 class="my-1"> MC-NO </h5></td>
                    <td> MC-001 LINE:L1</td>
                  </tr>
                  <tr>
                    <td style="background:#aab7c1;color:black;"><h5 class="my-1">พนักงาน</h5>  </td>
                    <td id="summaryemp"> 590020 เป้ </td>
                  </tr>
                  <tr>
                    <td style="background:#aab7c1;color:black;"><h5 class="my-1">อาการ</h5>  </td>
                    <td id="summaryrepair"> เครื่อง Alarm </td>
                  </tr>
                  <tr>
                    <td style="background:#aab7c1;color:black;"><h5 class="my-1">ระดับ</h5>  </td>
                    <td id="summarypriority">เร่งด่วน </td>
                  </tr>
                </tbody>
              </table>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 ml-auto mr-auto has-error">
            <div class="form-inline">
              <label> ผู้รับแจ้ง </label>
              <select class="form-control form-control-sm col-md-10 col-10 ml-2">
                <option> นาย สุบรรณ</option>
                <option> นาย อุทัย</option>
              </select>
            </div>
          </div>
        </div>
      </div>
        <button type="button" class="btn btn-primary btn-sm" id="closestep_1"> Save </button>
    </div>
  </div>
</div>
<style>
.modal-body-step{
    height: 500px;
    overflow-y: auto;
}
  @media all and (max-width: 1000px) {
      .modal-body-step{
          height: 250px;
          overflow-y: auto;
      }
      .table-step{
       width: 160%;
       overflow-x: auto;
      }
      .text-col{
        top: 0px;
      }
}
</style>
<div class="modal fade" id="CloseForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLalavel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">การดำเนินงาน</h5>
        <button type="button" class="btn btn-sm btn-danger "data-dismiss="modal" ><i class="fas fa-times"></i></button>
      </div>
      <div class="modal-body modal-body-step">
        <div class="row">
          <div class="col-12 col-md-10 " id="tabactive">
            <ul class=" nav nav-pills nav-primary">
              <li class="step">
                <a class="nav-link active WORK_STEP1"  href="#WORK_STEP1"  aria-expanded="true" id="step1" data-toggle="tab"><i class="fa fa-user mr-0"></i> ตรวจสอบเบื้องต้น</a>
              </li>
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
            </ul>
          </div>
        </div>
        <div class="tab-content">
          <div class="tab-pane active" id="WORK_STEP1">
            <div class="row">
              <div class="col-6 col-sm-10 col-md-4 ml-auto">
                <label>วันที่เริ่มตรวจสอบ</label>
                <input type="date" class="form-control form-control-sm " value="{{ date('Y-m-d') }}">
              </div>
              <div class="col-6 col-sm-10 col-md-4 mr-auto">
                <label>เวลาตรวจสอบ</label>
                <input type="time" class="form-control form-control-sm " >
              </div>
            </div>
            <div class="row">
              <div class="col-6 col-sm-10 col-md-4 ml-auto">
                <label>วันที่ตรวจสอบเสร็จ</label>
                <input type="date" class="form-control form-control-sm ">
              </div>
              <div class="col-6 col-sm-10 col-md-4 mr-auto">
                <label>เวลาตรวจสอบเสร็จ</label>
                <input type="time" class="form-control form-control-sm " >
              </div>
            </div>
            <div class="row my-5">
              <div class="col-10 col-sm-6 col-md-5 col-lg-4 ml-auto mr-auto">
  							<div class="card card-stats card-primary card-round">
  								<div class="card-body" style="cursor: pointer;" id="step2_in">
  									<div class="row">
  										<div class="col-12 col-md-6 col-lg-5 ml-auto mr-auto">
  											<div class="icon-big text-center">
  												<i class="fas fa-user-cog"></i>
  											</div>
  										</div>
  										<div class="col-6 col-md-12 col-lg-7 ml-auto mr-auto text-col">
  											<div class="numbers text-center">
  												<h4 class="card-title">ช่างภายใน</h4>
  											</div>
  										</div>
  									</div>
  								</div>
  							</div>
    					</div>
              <div class="col-10 col-sm-6 col-md-5 col-lg-4 ml-auto mr-auto">
  							<div class="card card-stats card-primary card-round">
  								<div class="card-body" style="cursor: pointer;" id="step2_sup">
  									<div class="row">
  										<div class="col-12 col-md-6 col-lg-5 ml-auto mr-auto">
  											<div class="icon-big text-center">
  												<i class="fas fa-users"></i>
  											</div>
  										</div>
  										<div class="col-6 col-md-12 col-lg-7 ml-auto mr-auto text-col">
  											<div class="numbers text-center">
  												<h4 class="card-title">ช่างภายนอก</h4>
  											</div>
  										</div>
  									</div>
  								</div>
  							</div>
    					</div>
              <div class="col-10 col-sm-6 col-md-5 col-lg-4 ml-auto mr-auto">
  							<div class="card card-stats card-primary card-round">
  								<div class="card-body" style="cursor: pointer;" id="step3_finish" onclick="step_final()">
  									<div class="row">
  										<div class="col-12 col-md-6 col-lg-5 ml-auto mr-auto">
  											<div class="icon-big text-center">
  												<i class="fas fa-clipboard-check"></i>
  											</div>
  										</div>
  										<div class="col-6 col-md-12 col-lg-7 ml-auto mr-auto text-col">
  											<div class="numbers text-center">
  												<h4 class="card-title">ปิดเอกสาร</h4>
  											</div>
  										</div>
  									</div>
  								</div>
  							</div>
  						</div>
              <div class="col-10 col-sm-6 col-md-5 col-lg-4 ml-auto mr-auto">
  							<div class="card card-stats card-danger card-round">
  								<div class="card-body" style="cursor: pointer;" id="step3_finish" onclick="cancelform()">
  									<div class="row">
  										<div class="col-12 col-md-6 col-lg-5 ml-auto mr-auto">
  											<div class="icon-big text-center">
  												<i class="fas fa-clipboard-check"></i>
  											</div>
  										</div>
  										<div class="col-6 col-md-12 col-lg-7 ml-auto mr-auto">
  											<div class="numbers text-center">
  												<h4 class="card-title">ยกเลิกเอกสาร</h4>
  											</div>
  										</div>
  									</div>
  								</div>
  							</div>
  						</div>
            </div>
          </div>
          <div class="tab-pane " id="WORK_STEP2_IN">
            <div class="form-group">
              <div class="row">
                <div class="col-sm-6 col-md-4">
                  <label>ช่างซ่อม 1</label>
                    <select class="form-control form-control-sm my-2">
                      <option>อนุศักดิ์ ผิวดำ</option>
                      <option>อนุลักษณ์ รัตนประเสริฐ</option>
                    </select>
                </div>
                <div class="col-sm-6 col-md-4">
                  <label>ช่างซ่อม 2</label>
                    <select class="form-control form-control-sm my-2">
                      <option>อนุศักดิ์ ผิวดำ</option>
                      <option>อนุลักษณ์ รัตนประเสริฐ</option>
                    </select>
                </div>
                <div class="col-sm-6 col-md-4">
                  <label>ช่างซ่อม 3</label>
                    <select class="form-control form-control-sm my-2">
                      <option>อนุศักดิ์ ผิวดำ</option>
                      <option>อนุลักษณ์ รัตนประเสริฐ</option>
                    </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row ">
                <div class="col-6 col-md-5 col-lg-4 ml-auto">
                  <div class="card card-stats card-primary card-round">
                    <div class="card-body" style="cursor: pointer;" id="step3_partchange">
                      <div class="row">
                        <div class="col-5">
                          <div class="icon-big text-center">
                            <i class="fas fa-user-cog"></i>
                          </div>
                        </div>
                        <div class="col-7 col-stats">
                          <div class="numbers">
                            <h4 class="card-title text-center">เปลี่ยนอะไหล่</h4>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-6 col-md-5 col-lg-4  mr-auto">
                  <div class="card card-stats card-primary card-round">
                    <div class="card-body" style="cursor: pointer;" id="step3_finish" onclick="step_final()">
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
          <div class="tab-pane " id="WORK_STEP2_SUP">
              <div class="row">
                <div class="col-6 col-sm-10 col-md-4 ml-auto my-1">
                  <label>วันที่เริ่มซ่อม</label>
                  <input type="date" class="form-control form-control-sm " value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-6 col-sm-10 col-md-4 mr-auto my-1">
                  <label>เวลาซ่อม</label>
                  <input type="time" class="form-control form-control-sm " >
                </div>
              </div>
              <div class="row">
                <div class="col-6 col-sm-10 col-md-4 ml-auto my-1">
                  <label>วันที่ซ่อมเสร็จ</label>
                  <input type="date" class="form-control form-control-sm ">
                </div>
                <div class="col-6 col-sm-10 col-md-4 mr-auto my-1">
                  <label>เวลาซ่อมเสร็จ</label>
                  <input type="time" class="form-control form-control-sm " >
                </div>
              </div>
              <div class="row my-1">
                <div class="col-12 col-sm-10 col-md-8 ml-auto  mr-auto">
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
              <div class="row my-4">
                <div class="col-12 col-md-5 col-lg-4  ml-auto ">
                  <div class="card card-stats card-primary card-round">
                    <div class="card-body" style="cursor: pointer;" data-dismiss="modal">
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
                <div class="col-12 col-md-5 col-lg-4   mr-auto">
                  <div class="card card-stats card-primary card-round">
                    <div class="card-body" style="cursor: pointer;" id="step3_finish" onclick="step_result()">
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
          <div class="tab-pane" id="WORK_STEP3">
            <div class="form-group">
              <div class="row">
                <table class="table table-bordered table-head-bg-info table-bordered-bd-info mt-4">
                  <thead>
                    <tr>
                      <th scope="col">action</th>
                      <th scope="col">อะไหล่</th>
                      <th scope="col">เบอร์</th>
                      <th scope="col">ขนาด</th>
                      <th scope="col">ราคา</th>
                      <th scope="col">เบิก</th>
                      <th scope="col">หน่อย</th>
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
                      <td>1นิ้ว</td>
                      <td>200</td>
                      <td>9</td>
                      <td>เส้น</td>
                    </tr>
                    <tr>
                      <td>
                        <button type="button" class="btn btn-secondary btn-sm btn-block my-1" onclick="withdraw(this)"
                        data-unid="2">เบิก</button>
                      </td>
                      <td>น้ำมันคูแล่น</td>
                      <td>-</td>
                      <td>ถัง</td>
                      <td>200</td>
                      <td>1</td>
                      <td>ถัง</td>
                    </tr>
                    <tr>
                      <td>
                        <button type="button" class="btn btn-secondary btn-sm btn-block my-1" onclick="withdraw(this)"
                        data-unid="3">เบิก</button>
                      </td>
                      <td>มอนิเตอร์</td>
                      <td>-</td>
                      <td>-</td>
                      <td>300</td>
                      <td>1</td>
                      <td>เครื่อง</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="form-group">
              <div class="row" >
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
            </div>
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
