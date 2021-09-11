<div class="modal fade" id="modal-adduser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLalavel" aria-hidden="true">
  <div class="modal-dialog " role="document">
    <div class="modal-content">
      <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title">เพิ่มผู้ใช้งาน</h5>
            <button type="close" class="btn btn-warning btn-sm" data-dismiss="modal"><i class="fas fa-window-close fa-lg mr-1"></i>Close</button>
          </div>
          <form action="{{ route('permission.store') }}" method="post" id="FRM_USER" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12 ml-auto mr-auto">
                    <div class="form-group form-show-validation row has-error">
                      <label for="email" class="col-lg-4 col-md-3 col-sm-4 mt-sm-2 text-right mr-2">ชื่อผู้ใช้</label>
                      <div class="col-lg-6 col-md-9 col-sm-8">
                        <input type="text" class="form-control form-control-sm" id="name" name="name"  required autocomplete="off">
                      </div>
                    </div>
                    <div class="form-group form-show-validation row has-error">
                      <label for="email" class="col-lg-4 col-md-3 col-sm-4 mt-sm-2 text-right mr-2">อีเมล์</label>
                      <div class="col-lg-6 col-md-9 col-sm-8">
                        <input type="text" class="form-control form-control-sm" id="email" name="email"  required autocomplete="off">
                      </div>
                    </div>
                    <div class="form-group form-show-validation row has-error">
                      <label for="email" class="col-lg-4 col-md-3 col-sm-4 mt-sm-2 text-right mr-2">รหัสผ่าน</label>
                      <div class="col-lg-6 col-md-9 col-sm-8 input-group">
                        <input type="password" class="form-control form-control-sm" id="password" name="password"  required autocomplete="off">
                        <div class="input-group-prepend">
                          <button class="btn btn-secondary btn-sm" type="button" id="EDIT_PASSWORD" hidden>แก้ไข</button>
                          <button class="btn btn-danger btn-sm" type="button" id="EDIT_CANCEL" hidden>ยกเลิก</button>
                        </div>
                      </div>
                    </div>
                    <div class="form-group form-show-validation row has-error">
                      <label for="email" class="col-lg-4 col-md-3 col-sm-4 mt-sm-2 text-right mr-2">สิทธิ์การใช้งาน</label>
                      <div class="col-lg-6 col-md-9 col-sm-8 role">
                        <select class="form-control form-control-sm" id="role" name="role" required>
                          <option value>กรุณาเลือก</option>
                          @foreach ($role as $key => $value)
                            <option value="{{$key}}">{{$value}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger btn-sm my-1" data-dismiss="modal"><i class="fas fa-window-close"></i>Cancel</button>
              <button type="submit" class="btn btn-primary btn-sm my-1"><i class="fas fa-save"></i>Save</button>
            </div>
          </form>
      </div>
    </div>
  </div>
</div>
