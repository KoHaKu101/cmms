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
    												<th width="150px">สร้างล่าสุด</th>
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
    												<td>{{ date('d-m-Y',strtotime($row->created_at)) }}</td>
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
							<div class="col-md-8">
								<div class="card">
									<div class="card-body">
										<table class="table table-bordered table-head-bg-info table-bordered-bd-info">
											<theade>
												<tr>
													<th>MACHINE_REPORT_NO</th>
													<th>DOC_NO</th>
													<th>CREATE_TIME</th>
													<th>CLOSE_DATE</th>
												</tr>
											</theade>
											<tbody>
													@foreach ($MACHINEREPAIRREQ as $key => $row)
														<tr>
															<td>{{ $row->MACHINE_REPORT_NO }}</td>
															<td>{{ $row->DOC_NO }}</td>
															<td>{{ $row->CREATE_TIME }}</td>
															<td>{{ $row->CLOSE_DATE.':'.$row->CLOSE_TIME }}</td>
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
				<!--ส่วนกรอกข้อมูล-->
@include('machine.setting.permission.modaladduser')

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
