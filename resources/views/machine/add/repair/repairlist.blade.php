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
							<div class="col-md-5">
								<div class="card ">
										<div class="card-header bg-primary ">
											<div class="row">
												<div class="col-8 col-lg-6">
													<h4 class="ml-3 my-2" style="color:white;" ><i class="fas fa-toolbox fa-lg mr-1"></i> ประเภทการแจ้งซ่อม </h4>
												</div>
												<div class="col col-lg-6">
													<button type="button" class="btn btn-warning btn-sm mx-1 my-1 float-right" id="BTN_NEW" name="BTN_NEW"><i class="fas fa-plus fa-lg"> ประเภท</i></button>
												</div>
											</div>
										 </div>
									<div id="result"class="card-body mt--3">
										<div class="table-responsive mt--4">
											<table class="table table-bordered table-head-bg-info table-bordered-bd-info mt-4">
												<thead>
													<tr>
														<th> # </th>
														<th width="150px">รายการ</th>
														<th >สถานะ</th>
														<th >action</th>
													</tr>
												</thead>
												<tbody>
													@php
														$STATUS = array('1' => 'ปิด', '9' => 'เปิด');
														$MACHINE_STATUS = array();
														foreach ($MACHINESTATUS as $key => $row_set) {
															$MACHINE_STATUS[$row_set->STATUS_CODE] = $row_set->STATUS_NAME;
														}
													@endphp
													@foreach ($DATA_SELECTMAINREPAIR as $index => $datarow)
														<tr>
															<td>{{$index+1}}</td>
															<td>{{$datarow->REPAIR_MAINSELECT_NAME}}</td>
															<td>{{ $STATUS[$datarow->STATUS] }}</td>
															<td>
																<button type="button" class="btn btn-secondary btn-sm mx-1 my-1"
																	onclick="BTN_EDIT(this)"
																	data-repair_name="{{ $datarow->REPAIR_MAINSELECT_NAME }}"
																	data-remark="{{ $datarow->REMARK }}"
																	data-unid="{{ $datarow->UNID }}"
																	data-status="{{ $datarow->STATUS }}">
																	<i class="fas fa-edit fa-lg"></i></button>
																<button type="button" class="btn btn-danger btn-sm mx-1 my-1"
																onclick="BTN_DELETE(this)"
																data-unid="{{ $datarow->UNID }}"
																data-repair_name="{{ $datarow->REPAIR_MAINSELECT_NAME }}"
																>
																	<i class="fas fa-trash fa-lg"></i></button>
																<a href="{{ route('repairtemplate.list',$datarow->UNID) }}" class="btn btn-info btn-sm mx-1 my-1">
																	<i class="fas fa-eye fa-lg"></i></a>
															</td>
														</tr>
													@endforeach
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
							@if ($OPEN == 1)
								<div class="col-md-7">
									<div class="card ">
											<div class="card-header bg-primary ">
												<div class="row">
													<div class="col-8 col-lg-8">
														<h4 class="ml-3 my-2" style="color:white;" ><i class="fas fa-toolbox fa-lg mr-1"></i> รายละเอียดแจ้งซ่อม : {{ $SELECTMAINREPAIR_FIRST->REPAIR_MAINSELECT_NAME }} </h4>
													</div>
													<div class="col col-lg-4">
														<button type="button" class="btn btn-warning btn-sm mx-1 my-1 float-right"
														 data-mainunid="{{$SELECTMAINREPAIR_FIRST->UNID }}"
														 id="BTN_SUBNEW" name="BTN_SUBNEW"><i class="fas fa-plus fa-lg"> รายละเอียดแจ้งซ่อม</i></button>
													</div>
												</div>
											 </div>
										<div id="result"class="card-body mt--3">
											<div class="table-responsive mt--4">
												<table class="table table-bordered table-head-bg-info table-bordered-bd-info mt-4">
													<thead>
														<tr>
															<th> # </th>
															<th width="250px">รายการ</th>
															<th >สถานะเครื่องจักร</th>
															<th >สถานะ</th>
															<th >action</th>
														</tr>
													</thead>
													<tbody>

														@foreach ($DATA_SELECTSUBREPAIR as $subindex => $datasubrow)
															<tr>
																<td>{{$subindex+1}}</td>
																<td>{{$datasubrow->REPAIR_SUBSELECT_NAME}}</td>
																<td>{{ $MACHINE_STATUS[$datasubrow->STATUS_MACHINE] }}</td>
																<td>{{ $STATUS[$datasubrow->STATUS] }}</td>
																<td>
																	<button type="button" class="btn btn-secondary btn-sm mx-1 my-1"
																		onclick="BTN_SUBEDIT(this)"
																		data-repair_subname="{{ $datasubrow->REPAIR_SUBSELECT_NAME }}"
																		data-unid="{{ $datasubrow->UNID }}"
																		data-status="{{ $datasubrow->STATUS }}"
																		data-machinestatus="{{ $datasubrow->STATUS_MACHINE }}">
																		<i class="fas fa-edit fa-lg"></i></button>
																	<button type="button" class="btn btn-danger btn-sm mx-1 my-1"
																	onclick="BTN_SUBDELETE(this)"
																	data-unid="{{ $datasubrow->UNID }}"
																	data-repair_subname="{{ $datasubrow->REPAIR_SUBSELECT_NAME }}"
																	>
																		<i class="fas fa-trash fa-lg"></i></button>
																</td>
															</tr>
														@endforeach
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							@endif

            </div>

						</div>
					</div>
  			</div>
			</div>
			<div class="modal fade" id="NEW_MAINREPAIR" tabindex="-1" role="dialog" aria-labelledby="exampleModalLalavel" aria-hidden="true">
			  <div class="modal-dialog " role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <h5 class="modal-title" id="Title_MAINREPAIR">เพิ่มรายการ</h5>

			      </div>
			      <form action="{{ route('repairtemplate.save') }}" id="FRM_SAVEMAIN" name="FRM_SAVEMAIN" method="post" enctype="multipart/form-data">
			        @csrf
							<input type="hidden" id="REPAIR_MAINSELECT_UNID" name="REPAIR_MAINSELECT_UNID">
			        <div class="modal-body">
			          <div class="card-body ml-2">
			            <div class="row ">
			              <div class="col-md-6 col-lg-12"> รายการแจ้งซ่อม </div>
			              <div class="col-md-6 col-lg-12 mt-2 has-error">
			                <input type="text" class="form-control" id="REPAIR_MAINSELECT_NAME" name="REPAIR_MAINSELECT_NAME" required>
			              </div>
			            </div>
									<div class="row my-2">
										<div class="col-md-12">
													<label for="comment">คำอธิบาย</label>
													<textarea class="form-control" id="REMARK" name="REMARK"rows="3"></textarea>
										</div>

									</div>

									<div class="row mt-3">
				            <div class="col-md-12 ml-2">
				              <label for="comment" class="mr-2">Status</label>
				              <!-- Rounded switch -->
				              <label class="switch">
				                <input type="checkbox" id="STATUS" name="STATUS" value="9" checked>
				                <span class="slider round"></span>
				              </label>
				            </div>
				          </div>
			          </div>
			        </div>
			        <div class="modal-footer">
			          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			          <button type="submit" class="btn btn-primary" id="SAVE_MAIN">Save</button>
			        </div>
			      </form>
			    </div>
			  </div>
			</div>
			<div class="modal fade" id="NEW_SUBREPAIR" tabindex="-1" role="dialog" aria-labelledby="exampleModalLalavel" aria-hidden="true">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <h5 class="modal-title" id="Title_SUBREPAIR">เพิ่มรายการ</h5>

			      </div>
			      <form action="{{ route('repairtemplate.subsave') }}" id="FRM_SAVESUB" name="FRM_SAVESUB" method="post" enctype="multipart/form-data">
			        @csrf
							<input type="hidden" id="REPAIR_MAINSELECT_UNIDREF" name="REPAIR_MAINSELECT_UNIDREF">
							<input type="hidden" id="REPAIR_SUBSELECT_UNIDREF" name="REPAIR_SUBSELECT_UNIDREF">
			        <div class="modal-body">
			          <div class="card-body ml-2">
			            <div class="row ">
			              <div class="col-md-6 col-lg-12">  รายละเอียดอาการ  </div>
										<textarea class="form-control" id="REPAIR_SUBSELECT_NAME" name="REPAIR_SUBSELECT_NAME"rows="2" required></textarea>
			            </div>
									<div class="row">
										<div class="form-group ml-2 CHECKSTATUS">
											<label> สถานะเครื่องจักร </label>
											<select class="form-control from-control-sm" id="STATUS_MACHINE" name="STATUS_MACHINE">
												@foreach ($MACHINESTATUS as $row_machinestatus)
													<option value="{{ $row_machinestatus->STATUS_CODE }}"> {{ $row_machinestatus->STATUS_NAME }}</option>
												@endforeach
											</select>

											</div>
									</div>
									<div class="row mt-3">
				            <div class="col-md-12 ml-2">
				              <label for="comment" class="mr-2">Status</label>
				              <!-- Rounded switch -->
				              <label class="switch">
				                <input type="checkbox" class="STATUS" id="STATUS" name="STATUS" value="9" checked>
				                <span class="slider round"></span>
				              </label>
				            </div>
				          </div>
			          </div>
			        </div>
			        <div class="modal-footer">
			          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			          <button type="submit" class="btn btn-primary"  id="SAVE_SUB">Save</button>
			        </div>
			      </form>
			    </div>
			  </div>
			</div>
@stop
{{-- ปิดส่วนเนื้อหาและส่วนท้า --}}

{{-- ส่วนjava --}}
@section('javascript')
	<script src="{{ asset('assets/js/ajax/ajax-csrf.js') }}"></script>
	<script>
			$('#BTN_NEW').on('click',function(){
			 $('#NEW_MAINREPAIR').modal('show');
			});
			$('#BTN_SUBNEW').on('click',function(){

			 var mainunid = $(this).data('mainunid');
			 $('#NEW_SUBREPAIR').modal('show');
			 $('#REPAIR_MAINSELECT_UNIDREF').val(mainunid);
			});
			$('#NEW_SUBREPAIR').on('hidden.bs.modal', function (e) {
				var url = "{{ route('repairtemplate.subsave') }}";
				$('#Title_SUBREPAIR').html('เพิ่มรายการ');
				$('#FRM_SAVESUB').attr('action', url);
				$('.STATUS').attr('checked',true);
				$('#FRM_SAVESUB')[0].reset();
			});
			$('#NEW_MAINREPAIR').on('hidden.bs.modal', function (e) {
				var url = "{{ route('repairtemplate.save') }}";
				$('#Title_MAINREPAIR').html('เพิ่มรายการ');
				$('#FRM_SAVEMAIN').attr('action', url);
				$('#STATUS').attr('checked',true);
			  $('#FRM_SAVEMAIN')[0].reset();
			});
			function BTN_EDIT(thisdata){
				var repair_name = $(thisdata).data('repair_name');
				var remark = $(thisdata).data('remark');
				var unid = $(thisdata).data('unid');
				var status = $(thisdata).data('status');
				var checkstatus = status == '9' ? true : false ;
				var url = "{{ route('repairtemplate.update') }}";
				$('#REPAIR_MAINSELECT_NAME').val(repair_name);
				$('#REMARK').val(remark);
				$('#REPAIR_MAINSELECT_UNID').val(unid);
				$('#STATUS').attr('checked',checkstatus);
				$('#NEW_MAINREPAIR').modal('show');
				$('#FRM_SAVEMAIN').attr('action', url);
				$('#Title_MAINREPAIR').html('รายการ :'+repair_name);
			}
			function BTN_DELETE(thisdata){
				var unid = $(thisdata).data('unid');
				var repair_name = $(thisdata).data('repair_name');
				var url = "{{ route('repairtemplate.delete') }}";
				Swal.fire({
				  title: 'คุณต้องการลบรายการนี้',
					text: ''+repair_name+'มั้ย?',
				  showCancelButton: true,
				  confirmButtonText: `Yes`,
				}).then((result) => {
				  if (result.isConfirmed) {
						$.ajax({
							type:'POST',
							url: url,
							datatype: 'json',
							data: {
								"_token": "{{ csrf_token() }}",
								"UNID" : unid,
							} ,
							success:function(data){
								Swal.fire({
									title: data.title,
									icon: data.icon,
									showCancelButton: false,
									confirmButtonText: `OK`,
									timer: 2000
								}).then(() => {
									if (data.res) {
										location.href = "{{ route('repairtemplate.list') }}";
									}
								});

							}
						});
				  }
				});
			}

			function BTN_SUBEDIT(thisdata){
				var repair_subname 				= $(thisdata).data('repair_subname');
				var unid 								= $(thisdata).data('unid');
				var status 							= $(thisdata).data('status');
				var machinestatus 			= $(thisdata).data('machinestatus');
				var checkstatus 				= status == '9' ? true : false ;
				var checkmachinestatus 	= machinestatus == '9' ? true : false ;
				var url = "{{ route('repairtemplate.subupdate') }}";

				$('#REPAIR_SUBSELECT_NAME').val(repair_subname);
				$('#REPAIR_SUBSELECT_UNIDREF').val(unid);
				$('.STATUS').attr('checked',checkstatus);
				$("div.CHECKSTATUS select").val(machinestatus);

				$('#NEW_SUBREPAIR').modal('show');
				$('#FRM_SAVESUB').attr('action', url);
				$('#Title_SUBREPAIR').html('รายการ :'+repair_subname);
			}
			function BTN_SUBDELETE(thisdata){
				var unid = $(thisdata).data('unid');
				var repair_subname = $(thisdata).data('repair_subname');
				var url = "{{ route('repairtemplate.subdelete') }}";
				Swal.fire({
					title: 'คุณต้องการลบรายการ',
					text: ''+repair_subname+'นี้ มั้ย?',
					showCancelButton: true,
					confirmButtonText: `Yes`,
				}).then((result) => {
					if (result.isConfirmed) {
						$.ajax({
							type:'POST',
							url: url,
							datatype: 'json',
							data: {
								"_token": "{{ csrf_token() }}",
								"UNID" : unid,
							} ,
							success:function(data){
								Swal.fire({
									title: data.title,
									icon: data.icon,
									showCancelButton: false,
									confirmButtonText: `OK`,
									timer: 2000
								}).then(() => {
									if (data.res) {
										location.reload();
									}
								});

							}
						});
					}
				});
			}
			$('#SAVE_MAIN').on('click',function(){
				$('#SAVE_MAIN').attr('disabled',true);
				var check = $('#REPAIR_MAINSELECT_NAME').val();
				if (check == '') {
					$('#SAVE_MAIN').attr('disabled',false);
				}else {
				 $('#FRM_SAVEMAIN').submit();
				}


			});
			$('#SAVE_SUB').on('click',function(){

			 $('#SAVE_SUB').attr('disabled',true);
			 var check = $('#REPAIR_SUBSELECT_NAME').val();
			 if (check == '') {
				 $('#SAVE_SUB').attr('disabled',false);
			 }else {
			 	$('#FRM_SAVESUB').submit();
			 }

			});

	</script>
@stop
{{-- ปิดส่วนjava --}}
