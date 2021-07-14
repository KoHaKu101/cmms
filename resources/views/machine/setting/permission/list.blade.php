@extends('masterlayout.masterlayout')
@section('tittle','homepage')
@section('css')

@endsection
{{-- ส่วนหัว --}}
@section('Logoandnavbar')

		{{-- @include('masterlayout.logomaster') --}}
		{{--  @include('masterlayout.navbar.navbarmaster')  --}}

@stop
{{-- ปิดท้ายส่วนหัว --}}

{{-- ส่วนเมนู --}}
@section('sidebar')

		{{--   @include('masterlayout.sidebar.sidebarmaster')  --}}

@stop
{{-- ปิดส่วนเมนู --}}

	{{-- ส่วนเนื้อหาและส่วนท้า --}}
@section('contentandfooter')

		<div class="content">
			<div class="page-inner">
				<!--ส่วนปุ่มด้านบน-->
				<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
					<div class="container">
						<div class="row">
              <div class="col-md-8">
                <div class="card">
                  <div class="card-header bg-primary">
                    <div class="row">
                      <div class="col-md-6 text-white mt-1">
                          <h4>รายชื่อผู้ใช้งาน</h4>
                      </div>
                      <div class="col-md-6 text-right">
                        <button type="button" class="btn btn-warning btn-sm" id="BTN_ADD"
                        ><i class="fas fa-plus"></i>เพิ่มผู้ใช้งาน</button>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="table">
                      <table class="table table-bordered table-head-bg-info table-bordered-bd-info ">
    										<thead>
    											<tr>
    												<th>#</th>
    												<th>ชื่อผู้ใช้งาน</th>
                            <th width="120px">สิทธ์การใช้งาน</th>
    												<th width="150px">ใช้งานล่าสุด</th>
    												<th width="190px">action</th>
    											</tr>
    										</thead>
    										<tbody>
                          @php
                            $role = array('user' => 'User','manager_ma' => 'Manager_MA','manager_pd' => 'Manager_PD','admin' => 'Admin');
                          @endphp
                          @foreach ($DATA_USER as $index => $row)
    											<tr>
    												<td>{{$index+1}}</td>
    												<td>{{$row->name}}</td>
    												<td>{{$role[$row->role_v2]}}</td>
    												<td>12/06/2021 08:07</td>
                            <td>
                              <button type="button" class="btn btn-warning btn-sm mx-1 my-1"
                              onclick="EditUser(this)"
                              data-id="{{$row->id}}"
                              data-name="{{$row->name}}"
                              data-password = "{{ $row->password }}"
                              data-email="{{$row->email}}"
                              data-role="{{$row->role_v2}}">
                                <i class="fas fa-edit"></i>แก้ไข</button>
                              @can ('isAdmin')
                                <button type="button" class="btn btn-danger btn-sm mx-1 my-1"
                                onclick="deleteuser(this)"
                                data-id="{{$row->id}}"
                                data-name="{{$row->name}}">
                                  <i class="fas fa-trash"></i>ลบผู้ใช้งาน</button>
                              @endcan
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
				<!--ส่วนกรอกข้อมูล-->


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
	                            {{-- <option value="{{$key}}">{{$value}}</option> --}}
	                            {{-- <option value="{{$key}}">{{$value}}</option> --}}
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

@stop
{{-- ปิดส่วนเนื้อหาและส่วนท้า --}}

{{-- ส่วนjava --}}
@section('javascript')
  <script>
  $('#BTN_ADD').on('click',function(){
    var url = "{{ route('permission.store')}}";
    $('#FRM_USER').trigger('reset');
    $('#FRM_USER').attr('action',url);
    $('#password').attr('disabled',false);
    $('#EDIT_PASSWORD').attr('hidden',true);
    $('#EDIT_CANCEL').attr('hidden',true);
    $('#modal-adduser').modal('show');
  });
  function EditUser(thisdata){
    var id = $(thisdata).data('id');
    var name = $(thisdata).data('name');
    var email = $(thisdata).data('email');
    var role = $(thisdata).data('role');
    var password = $(thisdata).data('password')
    var url = "{{ route('permission.update') }}?id="+id;
    $('#name').val(name);
    $('#email').val(email);
    $('#password').val(password);
    $('#password').attr('disabled',true);
    $('#EDIT_PASSWORD').attr('hidden',false);
    $('#EDIT_CANCEL').attr('hidden',true);
    $("div.role select").val(role);
    $('#FRM_USER').attr('action',url);
    if (id != '') {
        $('#modal-adduser').modal('show');
    }
    $('#EDIT_PASSWORD').on('click',function(){
      $('#password').val('');
      $('#password').attr('disabled',false);
      $('#EDIT_PASSWORD').attr('hidden',true);
      $('#EDIT_CANCEL').attr('hidden',false);
    });
    $('#EDIT_CANCEL').on('click',function(){
      $('#password').val(password);
      $('#password').attr('disabled',true);
      $('#EDIT_PASSWORD').attr('hidden',false);
      $('#EDIT_CANCEL').attr('hidden',true);
    });
  }
  function deleteuser(thisdata){
    var id = $(thisdata).data('id');
    var name = $(thisdata).data('name');
    var url = "{{ route('permission.delete') }}?id="+id;
    Swal.fire({
      title: 'ต้องการลบ User?',
      text: name,
      showDenyButton: true,
      showCancelButton: false,
      confirmButtonText: `ใช่`,
      denyButtonText: `ยกเลิก`,
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
              title: 'ใส่รหัสเพื่อยืนยัน',
              input: 'password',
              inputAttributes: {
                autocapitalize: 'off'
              },
              showDenyButton: true,
              showCancelButton: false,
              confirmButtonText: `ยืนยัน`,
              denyButtonText: `ยกเลิก`,
              preConfirm: (login) => {
                return fetch(`/machine/config/permission/confirm?password=${login}`)
                  .then(response => {
                        if (!response.ok) {
                          throw new Error(response)
                        }
                        return response.json()
                      })
                  .then(data => {
                    if (!data.pass) {
                      throw new Error(data)
                    }else {
                      return data.pass
                    }
                  })
                  .catch(error => {
                    Swal.showValidationMessage(
                      'รหัสผ่านไม่ถูกต้อง'
                    )
                  })
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
              if (result.isConfirmed) {
                window.location.href = url;
              }
            })

      }
    })
  }
  </script>

@stop
{{-- ปิดส่วนjava --}}
