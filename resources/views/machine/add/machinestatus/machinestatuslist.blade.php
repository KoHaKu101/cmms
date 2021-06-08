@extends('masterlayout.masterlayout')
@section('tittle','homepage')
@section('css')
@endsection
{{-- ส่วนหัว --}}
@section('Logoandnavbar')

	@include('masterlayout.logomaster')
	@include('masterlayout.navbar.navbarmaster')

@stop
{{-- ปิดท้ายส่วนหัว --}}

{{-- ส่วนเมนู --}}
@section('sidebar')

	@include('masterlayout.sidebar.sidebarmaster0')

@stop
{{-- ปิดส่วนเมนู --}}

	{{-- ส่วนเนื้อหาและส่วนท้า --}}
@section('contentandfooter')
	<style>
		/* The switch - the box around the slider */
		.switch {
		  position: relative;
		  display: inline-block;
			width: 48px;
	    height: 22px;
		}

		/* Hide default HTML checkbox */
		.switch input {
		  opacity: 0;
		  width: 0;
		  height: 0;
		}

		/* The slider */
		.slider {
		  position: absolute;
		  cursor: pointer;
		  top: 0;
		  left: 0;
		  right: 0;
		  bottom: 0;
		  background-color: #ccc;
		  -webkit-transition: .4s;
		  transition: .4s;
		}

		.slider:before {
		    position: absolute;
		    content: "";
		    height: 15px;
		    width: 15px;
		    left: 4px;
		    bottom: 3px;
		    background-color: white;
		    -webkit-transition: .4s;
		    transition: .4s;
		}

		input:checked + .slider {
		  background-color: #2196F3;
		}

		input:focus + .slider {
		  box-shadow: 0 0 1px #2196F3;
		}

		input:checked + .slider:before {
		  -webkit-transform: translateX(26px);
		  -ms-transform: translateX(26px);
		  transform: translateX(26px);
		}

		/* Rounded sliders */
		.slider.round {
		  border-radius: 34px;
		}

		.slider.round:before {
		  border-radius: 50%;
		}
	</style>

	  <div class="content">
      <div class="page-inner">
				<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
          <div class="container">
						<div class="row">
							<div class="col-md-12 gx-4">

							</div>
						</div>
          </div>
				</div>
				<div class="py-12">
	        <div class="container mt-2">
						<div class="row">
							<div class="col-md-8">
								<div class="card ">
									<div class="card-header bg-primary">
										<h4 class="ml-3 mt-2" style="color:white;" ><i class="fas fa-toolbox fa-lg mr-1"></i> เพิ่มสถานะเครื่องจักร </h4>
									 </div>

									<div id="result"class="card-body">
										<div class="table-responsive mt--4">
											<table class="table table-bordered table-head-bg-info table-bordered-bd-info mt-4">
										<thead>
											<tr>
												<th scope="col">CODE</th>
												<th scope="col">สถานะ</th>
												<th scope="col">เปิด/ปิด</th>
												<th scope="col">action</th>
											</tr>
										</thead>
										<tbody>
											<style>
											.btn-mute {
												background: #a2a2a2!important;
												border-color: #a2a2a2!important;
											}
											</style>
											@foreach ($dataset as $key => $dataitem)

											<tr>
												<td>{{$dataitem->STATUS_CODE}}</td>
												<td style="width:200px">

														<button class="btn btn-primary btn-block btn-sm my-1 mx--2 "
														onclick="editstatus(this)"
														data-unid="{{ $dataitem->UNID}}"
														data-name="{{ $dataitem->STATUS_NAME}}"
														data-code="{{ $dataitem->STATUS_CODE}}"
														data-status="{{ $dataitem->STATUS}}">
															<span class="btn-label float-left">
																<i class="fa fa-eye mx-1 "></i>
																{{ $dataitem->STATUS_NAME }}
															</span>
														</button>
													</td>
												<td><button type="button" class="btn {{ $dataitem->STATUS == "9" ? 'btn-success' : 'btn-mute' }}
													btn-block btn-sm my-1"style="cursor:default">{{ $dataitem->STATUS == "9" ? 'เปิด' : 'ปิด' }}</button></td>
												<td>
													<button type="button" class="btn btn-danger btn-block btn-sm my-1" style="width:40px"
													onclick="deletestatus(this)"
													data-unid="{{ $dataitem->UNID }}">
														<i class="fas fa-trash fa-lg">	</i>
													</button>
												</td>
											</tr>
											@endforeach
										</tbody>
									</table>
									</div>
										</div>
								</div>
								</div>
								<div class="col-md-4">
									<div class="card">
										<div class="card-header bg-primary">
											<h4 class="ml-3 mt-2" style="color:white;" ><i class="fas fa-toolbox fa-lg mr-1"></i> เพิ่มสถานะเครื่องจักร </h4>
										 </div>
										<div class="card-body">
											<form action="{{ route('machinestatustable.store') }}" method="POST" id="FRM_MACHINE_STATUS" name="FRM_MACHINE_STATUS">
												@csrf

												<div class="form-group has-error">
													<label for="STATUS_CODE">CODE</label>
													<input type="text"  class="form-control" id="STATUS_CODE" name="STATUS_CODE" placeholder="CODE" required autofocus>
												</div>
												<div class="form-group has-error">
													<label for="STATUS_NAME">สถานะเครื่องจักร</label>
													<input type="text"  class="form-control" id="STATUS_NAME" name="STATUS_NAME" placeholder="สถานะ" required autofocus>

												</div>
												<div class="form-group has-error">

							              <label for="comment" class="mr-2">Status</label>
							              <!-- Rounded switch -->
							              <label class="switch">
							                <input type="checkbox" id="STATUS" name="STATUS" value="9" checked>
							                <span class="slider round"></span>
							              </label>
							            </div>
												<button tpye="submit" class="btn btn-primary">Save</button>
												<button tpye="button" class="btn btn-danger float-right" hidden="TRUE" id="BTN_CANCEL">cancel</button>
											</form>
										</div>
									</div>
								</div>
              </div>

						</div>
					</div>
  			</div>
			</div>
		</div>

@stop
{{-- ปิดส่วนเนื้อหาและส่วนท้า --}}

{{-- ส่วนjava --}}
@section('javascript')
	<script>
	function editstatus(thisdata){
		var unid = $(thisdata).data('unid');
		var name = $(thisdata).data('name');
		var code = $(thisdata).data('code');
		var status = $(thisdata).data('status');
		var check_status = status == '9' ? true : false ;
		var url = '/machine/machinestatustable/update/'+unid;

		$('#FRM_MACHINE_STATUS').attr('action',url);
		$('#STATUS_CODE').val(code);
		$('#STATUS_NAME').val(name);
		$('#STATUS').attr('checked',check_status);
		$('#BTN_CANCEL').attr('hidden',false);
	}
	$('#BTN_CANCEL').on('click',function(){
		var url = "{{ route('machinestatustable.store') }}";
		$('#FRM_MACHINE_STATUS').attr('action',url);
		$('#FRM_MACHINE_STATUS')[0].reset();
		$('#BTN_CANCEL').attr('hidden',true);
	})
	function deletestatus(thisdata){
		var unid = $(thisdata).data('unid');
		var url = '/machine/machinestatustable/delete/'+unid;
		Swal.fire({
				title: 'ต้องการลบ status นี้มั้ย?',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'ใช่!'
			}).then((result) => {
				if (result.isConfirmed) {
					window.location.href = url;
				}
			});
	}

</script>


@stop
{{-- ปิดส่วนjava --}}
