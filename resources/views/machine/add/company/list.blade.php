@extends('masterlayout.masterlayout')
@section('tittle','homepage')
@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">

@endsection
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

						</div>
          </div>
				</div>
				<div class="py-12">
	        <div class="container mt-2">
						<div class="row">
							<div class="col-md-8">
								<div class="card ">
										<div class="card-header bg-primary ">
											<div class="row">
												<div class="col-8 col-lg-6">
													<h4 class="ml-3 my-2" style="color:white;" ><i class="fas fa-toolbox fa-lg mr-1"></i> รายการบริษัท </h4>
												</div>

											</div>
										 </div>
									<div id="result"class="card-body mt--3">
										<div class="table-responsive mt--4">
											<table class="table table-bordered table-head-bg-info table-bordered-bd-info mt-4">
												<thead>
													<tr>
														<th width="4%" class="text-center"> # </th>
														<th width="10%">รหัส</th>
														<th width="50%">ชื่อ</th>
														<th width="10%">สถานะ</th>
														<th width="10%">แก้ไข</th>
														<th width="10%">ลบ</th>
													</tr>
												</thead>
												<tbody>

													@foreach ($DATA_COMPANY as $index => $row)
														<tr>
															<td> {{ $index+1 }} </td>
															<td> {{ $row->COMPANY_CODE != '' ? $row->COMPANY_CODE : '-' }}</td>
															<td> {{ $row->COMPANY_NAME }}</td>
															<td>
																<label class="switch my-2">
																	<input type="checkbox" value="9" {{ $row->STATUS == '9' ? 'checked' : ''	}}
																	onclick="switchstatus(this)"
																	data-unid="{{ $row->UNID }}">
																	<span class="slider round"></span>
																</label>
															</td>
															<td><button type="button" class="btn btn-warning btn-sm btn-block my-1"
																onclick="editcompany(this)"
																data-unid="{{ $row->UNID}}"
																data-companycode="{{ $row->COMPANY_CODE}}"
																data-companyname="{{ $row->COMPANY_NAME}}"
																data-note="{{ $row->NOTE}}"
																data-status="{{ $row->STATUS}}">
																<i class="fas fa-edit mx-1"></i>Edit</button></td>
															<td><button type="button" class="btn btn-danger btn-sm btn-block my-1"
																onclick="deletecompany(this)"
																data-unid="{{ $row->UNID }}">
																<i class="fas fa-trash mx-1"></i>Delete</button></td>
														</tr>
													@endforeach
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<form action="{{ route('company.save') }}" id="FRM_SAVE" method="post" enctype="multipart/form-data">
									@csrf
									<input type="hidden" id="UNID" name="UNID">
									<div class="card ">
											<div class="card-header bg-primary ">
												<div class="row">
													<div class="col-8 col-lg-12">
														<h4 class="ml-3 my-2" style="color:white;" ><i class="fas fa-toolbox fa-lg mr-1"></i> เพิ่มรายการบริษัท </h4>
													</div>
												</div>
											 </div>
										<div id="result"class="card-body mt--3">
											<div class="row">
												<div class="col-md-12">
													<label>รหัส</label>
													<input type="text" class="form-control form-control-sm my-1" id="COMPANY_CODE" name="COMPANY_CODE" >
												</div>
												<div class="col-md-12">
													<label>ชื่อบริษัท</label>
													<input type="text" class="form-control form-control-sm my-1" id="COMPANY_NAME" name="COMPANY_NAME" required>
												</div>
												<div class="col-md-12">
													<label>คำอธิบายเพิ่มเติม/คำแนะนำ</label>
													<textarea class="form-control form-control-sm my-1" id="NOTE" name="NOTE"></textarea>
												</div>
											</div>
										</div>
										<div class="card-footer">
											<div class="row">
												<div class="col-md-5">
													<label for="comment" class="mr-2">Status</label>
													<!-- Rounded switch -->
													<label class="switch my-2">
														<input type="checkbox" id="STATUS" name="STATUS" value="9" checked>
														<span class="slider round"></span>
													</label>
												</div>
												<div class="col-md-7 text-right">
													<button type="button" class="btn btn-sm btn-danger my-2"
													id="BTN_CLOSE" hidden>Close</button>
													<button type="submit" class="btn btn-sm btn-primary my-2"
													id="BTN_SAVE">
													<i class="fas fa-plus"></i>เพิ่ม</button>
												</div>
											</div>
										</div>
									</div>
								</form>
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
	<script src={{ asset('assets/js/ajax/ajax-csrf.js') }}></script>
<script>
	function editcompany(thisdata){
		var unid 				= $(thisdata).data('unid');
		var companycode	= $(thisdata).data('companycode');
		var companyname	= $(thisdata).data('companyname');
		var note				= $(thisdata).data('note');
		var status			= $(thisdata).data('status');
		var checkstatus = status == '9' ? true : false ;
		var url 				= "{{ route('company.update') }}";
		$('#FRM_SAVE').attr('action',url);
		$('#UNID').val(unid);
		$('#COMPANY_CODE').val(companycode);
		$('#COMPANY_NAME').val(companyname);
		$('#NOTE').val(note);
		$('#STATUS').attr('checked',checkstatus);
		$('#BTN_SAVE').html('อัพเดท');
		$('#BTN_CLOSE').attr('hidden',false);
	}
	$('#BTN_CLOSE').on('click',function(){
		var url 				= "{{ route('company.save') }}";
		$('#FRM_SAVE').attr('action',url);
		$('#FRM_SAVE')[0].reset();
		$('#BTN_SAVE').html('<i class="fas fa-plus"></i>เพิ่ม');
		$('#BTN_CLOSE').attr('hidden',true);
	});
	function deletecompany(thisdata){
		var unid = $(thisdata).data('unid');
		var url = "{{ route('company.delete') }}?UNID="+unid;
		Swal.fire({
		  title: 'ต้องการลบรายการนี้มั้ย?',
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
			cancelButtonText: 'ยกเลิก',
		  confirmButtonText: 'ใช่!'
		}).then((result) => {
		  if (result.isConfirmed) {
		   window.location.href = url;
		  }
		})

	}
	function switchstatus(thisdata){
		var	unid = $(thisdata).data('unid');
		var url = "{{ route('company.switch') }}";
		$.ajax({
				type:'POST',
				url: url,
				datatype: 'json',
				data: {
					"_token": "{{ csrf_token() }}",
					UNID : unid
				},
				success:function(data){
					if (data.result == 'false') {

						Swal.fire({
						  title: 'เกิดข้อผิดพลาด?',
						  icon: 'error',
						  showCancelButton: false,
							showDenyButton: false,
							showConfirmButton: false,
							timer:'1500'
						})
					}
				}

			});
	}
</script>
@stop
{{-- ปิดส่วนjava --}}
