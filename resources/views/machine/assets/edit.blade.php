@extends('masterlayout.masterlayout')
@section('tittle','homepage')
@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
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

	.hide { display: none; }
</style>
		<div class="content">
			<div class="page-inner">
				<!--ส่วนปุ่มด้านบน-->
				<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
					<div class="container">
						<div class="row">
							<div class="form-group gx-4">
								<a href="{{ url('machine/assets/machinelist') }}">
									<button class="btn btn-warning  btn-xs ">
										<span class="fas fa-arrow-left fa-lg">Back </span>
									</button>
								</a>
							</div>
							<div class="form-group gx-4">
								<form action="{{ url('machine/assets/update/'.$dataset->UNID) }}" method="POST" enctype="multipart/form-data">
									@csrf
									<button class="btn btn-primary btn-xs" type="submit">
										<span class="fas fa-save fa-lg">	save	</span>
									</button>
							</div>
						</div>
					</div>
				</div>
				<!--ส่วนกรอกข้อมูล-->
				<div class="py-12">
	        <div class="container mt-2">
						<div class="card">
								<div class="card-header bg-primary">
									<h4 class="ml-3 mt-2" style="color:white;" >ลงทะเบียนเครื่องจักร </h4>

								</div>
								<div class="card-body">
									<div class="row">
										<!-- ช่อง1-->
										<div class="col-md-6 col-lg-3">
											<div class="form-group mt-4 text-center">
												<img
												<?php
												$noimg = asset("assets/img/nobody.jpg");
												$hasimg = asset('image/machine/'.$dataset->MACHINE_LINE.'/'.$dataset->MACHINE_ICON);
												echo ($dataset->MACHINE_ICON == "") ? 'src= '.$noimg.' ' : 'src= '.$hasimg.' ' ;
												?>
												 width="200" height="200px" class="mt-4">
												  <input type="hidden" id="MACHINE_UPDATE" name="MACHINE_UPDATE" value="{{$dataset->MACHINE_ICON}}">
													<input type="file" class="form-control form-control-sm mt-4" id="MACHINE_ICON" name="MACHINE_ICON"  accept="image/*">

											</div>
										</div>
										<!-- ช่อง2-->
										<div class="col-md-6 col-lg-4">
											<div class="form-group has-error">
												<label for="MACHINE_CODE">รหัสเครื่องจักร</label>
													<input type="text" class="form-control form-control-sm " id="MACHINE_CODE" name="MACHINE_CODE" value="{{ $dataset->MACHINE_CODE }}">
													<input type="hidden"  id="MACHINE_UNID" name="MACHINE_UNID"  value="{{ $dataset->UNID }}">

													{{-- <input type="hidden"  wire:model="dataset"  value="{{ $dataset->MACHINE_CODE }}"> --}}
											</div>

											<div class="form-group">
												<label for="MACHINE_STARTDATE">วันที่เริ่มใช้งาน	</label>
												<input type="date" class="form-control form-control-sm" id="MACHINE_STARTDATE" name="MACHINE_STARTDATE" value="{{ $dataset->MACHINE_STARTDATE }}">
											</div>
											<div class="form-group" >
												<label for="PM_LAST_DATE">ตรวจเช็คระบบ ล่าสุด	</label>
												<input type="date" class="form-control form-control-sm changedateedit" id="PM_LAST_DATE" name="PM_LAST_DATE" value="{{ $dataset->PLAN_LAST_DATE == '1900-01-01' ? "" : $dataset->PLAN_LAST_DATE }}" readonly>
											</div>
											<div class="form-group has-error col-lg-12 from-inline">
												<div class="row">
													<div class="col-6 col-sm-6 col-lg-6">
														<label >สถานะการใช้งาน</label>
														<select class=" form-control form-control-sm " id="MACHINE_CHECK" name="MACHINE_CHECK" required >
															<option value>-แสดงทั้งหมด-</option>
															@foreach ($machinestatus as $key => $srow)
																<option value="{{ $srow->STATUS_CODE }}"
																	{{ $dataset->MACHINE_CHECK == $srow->STATUS_CODE ? 'selected' : ''}}>{{$srow->STATUS_NAME}}</option>
															@endforeach
														</select>
													</div>
													<div class="col-6 col-sm-6 col-lg-6">
														<label>ตำแหน่งเครื่อง</label>
														<select class=" form-control form-control-sm " id="MACHINE_LINE" name="MACHINE_LINE" required >
															<option value> -แสดงทั้งหมด- </option>
															@foreach($machineline as $dataline)
															<option value="{{ $dataline->LINE_CODE  }}"
																{{ $dataset->MACHINE_LINE == $dataline->LINE_CODE ? 'selected' : ''}}> {{$dataline->LINE_NAME}} </option>
															@endforeach
														</select>
													</div>
												</div>
											</div>

											<div class="form-group has-error col-lg-12 from-inline">
												<div class="row">
													<div class="col-6 col-sm-6 col-lg-6">
															<label for="MACHINE_TYPE">ชนิดเครื่องจักร</label>
															<select class="form-control form-control-sm form-control form-control-sm-sm" id="MACHINE_TYPE" name="MACHINE_TYPE">
																<option value>--แสดงทั้งหมด--</option>
																@foreach($machinetype as $datatype)
																	<option value="{{ $datatype->TYPE_CODE}}"
																	{{ $dataset->MACHINE_TYPE == $datatype->TYPE_CODE ? 'selected' : ''}} > {{$datatype->TYPE_CODE}} </option>
																		@endforeach
															</select>
													</div>
													<div class="col-6 col-sm-6 col-lg-6">
															<label class="text-white">สถานะการผลิต</label>
															<select class="form-control form-control-sm form-control form-control-sm-sm" id="MACHINE_TYPE_STATUS" name="MACHINE_TYPE_STATUS" >
																<option value="9" {{ $dataset->MACHINE_TYPE_STATUS == "9" ? 'selected' : "" }}>Machine</option>
																<option value="1" {{ $dataset->MACHINE_TYPE_STATUS == "1" ? 'selected' : "" }}>โต๊ะ Support</option>
															</select>
													</div>
												</div>
											</div>
										</div>
										<!-- ช่อง3-->
										<div class="col-md-12 col-lg-4">
											<div class="form-group has-error">
												<label for="MACHINE_NAME">ชื่อเครื่องจักร</label>
												<input type="text" class="form-control form-control-sm" id="MACHINE_NAME" name="MACHINE_NAME"  value="{{ $dataset->MACHINE_NAME }}">
											</div>
											<div class="form-group has-error">
												<label for="MACHINE_RVE_DATE">วันที่ ซ่อมแซม 	</label>
												<input type="date" class="form-control form-control-sm" id="" name=""  value="{{ $dataset->REPAIR_LAST_DATE == '1900-01-01' ? "" : $dataset->REPAIR_LAST_DATE }}" readonly>
											</div>
											<div class="form-group">
												<label for="MACHINE_RVE_DATE">วันที่ เปลี่ยนอะไหล่ 	</label>
												<input type="date" class="form-control form-control-sm" id="" name=""  value="{{ $dataset->SPAR_PART_DATE == '1900-01-01' ? "" : $dataset->SPAR_PART_DATE }}" readonly>
											</div>
											<div class="form-group has-error">
												<label for="PURCHASE_FORM">ซื้อจากบริษัท	</label>
												<input type="text" class="form-control form-control-sm" id="PURCHASE_FORM" name="PURCHASE_FORM"  value="{{ $dataset->PURCHASE_FORM }}">
											</div>
												<div class="form-group has-error col-lg-12 from-inline">
													<div class="row">
														<div class="col-6 col-sm-6 col-lg-6">
															<label for="PURCHASE_FORM">Rank	</label>
															<select class="form-control form-control-sm" id="MACHINE_RANK_MONTH" name="MACHINE_RANK_MONTH" required>
																<option value>กรุณาเลือก Rank</option>
																@foreach ($machinerank as $key => $datamachinerank)
																	<option value="{{$datamachinerank->MACHINE_RANK_MONTH}}" {{ $dataset->MACHINE_RANK_MONTH == $datamachinerank->MACHINE_RANK_MONTH ? 'selected' : ''}} >{{$datamachinerank->MACHINE_RANK_CODE}}</option>
																@endforeach

															</select>
														</div>
														<div class="col-6 col-sm-6 col-lg-6">
															<label for="PURCHASE_FORM">สถานะ	</label>
															<select class="form-control form-control-sm" id="MACHINE_STATUS" name="MACHINE_STATUS" required>
																<option value="9" {{ $dataset->MACHINE_STATUS == '9' ? 'selected' : ''}}>แสดง</option>
																<option value="1" {{ $dataset->MACHINE_STATUS == '1' ? 'selected' : ''}}>ซ่อน</option>
															</select>
														</div>
													</div>
												</div>

										</div>
									</div>
									<div class="row">
										<div class="col-md-12 mt-2">
											<div class="card-body" id="tabLink">
												@include('machine.assets.tab.styletab')
												<ul class="nav nav-pills justify-content-center mt--4" >
	  											<li>
	    											<a id="home" data-toggle="tab" href="#home" class="tabselect active" >ข้อมูลทั่วไป</a>
	  											</li>
	  											<li>
	    											<a id="history" data-toggle="tab" href="#history" class="tabselect"  >ประวัติเครื่อง</a>
	  											</li>
	  											<li>
	    											<a id="plan" data-toggle="tab" href="#plan"  class="tabselect" >ชิ้นงานที่ผลิต</a>
								  				</li>
								  				<li>
	    											<a id="personal" data-toggle="tab" href="#personal" class="tabselect" >พนักงานประจำเครื่อง</a>
	  											</li>
													<li>
	    											<a id="planpm" data-toggle="tab" href="#planpm" class="tabselect" >รายการตรวจเช็ค</a>
	  											</li>
													<li>
	    											<a id="planpdm" data-toggle="tab" href="#planpdm" class="tabselect">อะไหล่</a>
	  											</li>
													<li>
	    											<a id="uploadmanue" data-toggle="tab" href="#uploadmanue" class="tabselect" >Upload</a>
	  											</li>
	  										</ul>
	  										<div class="tab-content clearfix">
														<!-- ข้อมูลทั่วไป -->
	  												@include('machine.assets.tab.edit.homeedit')
														</form>
														<!-- ประวัติการแจ้งซ่อม -->
														@include('machine.assets.tab.edit.history')

														@include('machine.assets.tab.edit.product')

														@include('machine.assets.tab.edit.personal')
														<!-- ตรวจสอบระบบ -->
														@include('machine.assets.tab.edit.planpm')
														<!-- อะไหล่ที่ต้องเปลี่ยน -->
														@include('machine.assets.tab.edit.planpdm')
														<!-- upload -->
														@include('machine.assets.tab.edit.uploadmanue')
												</div>
											</div>
										</div>
									</div>
									<div class="card-footer">
										<div class="row">
												<small><b>สร้างโดย : </b></small>&nbsp;
												<small> {{ $dataset->CREATE_BY }}</small>&emsp;
												<small><b>วันที่สร้าง : </b></small>&nbsp;
												<small> {{ $dataset->CREATE_TIME }}</small>&emsp;
												<small><b>แก้ไขโดย : </b></small>&nbsp;
												<small> {{ $dataset->MODIFY_BY }}</small>&emsp;
												<small><b>วันที่แก้ไข : </b></small>&nbsp;
												<small> {{ $dataset->MODIFY_TIME }}</small>&emsp;

										</div>
									</div>
								</div>
						</div>
					</div>
				</div>
			</div>
		</div>


@include('machine.assets.modal.uploadmanue')
@include('machine.assets.modal.machinesparepart')
@include('machine.assets.modal.addsparepart')
@include('machine.assets.modal.modalpm')
@include('machine.assets.modal.modalpmremove')


@stop
{{-- ปิดส่วนเนื้อหาและส่วนท้า --}}

{{-- ส่วนjava --}}
@section('javascript')
	<script src="{{ asset('assets/fullcalendar/moment.js') }}"></script>
	<script src="{{ asset('assets/js/plugin/datatables/datatables.min.js')}}">
	</script>
	<script src="{{ asset('assets/js/useinproject/machine/editmachine.js') }}"></script>
	 <script src="{{ asset('assets/js/ajax/ajax-csrf.js') }}"></script>
	 <script>
	 $(document).ready(function(){
			$('#machinespartelist').DataTable({
				 "pageLength": 10,
				 "bLengthChange": false,
				 "bFilter": true,
				 "bInfo": false,
				 "bAutoWidth": false,
				 'bSort': false,

			 });
			 $('#addpmmachine').DataTable({
					"pageLength": 10,
					"bLengthChange": false,
					"bFilter": true,
					"bInfo": false,
					"bAutoWidth": false,
					'bSort': false,

				});
				$('#removepmmachine').DataTable({
					 "pageLength": 10,
					 "bLengthChange": false,
					 "bFilter": true,
					 "bInfo": false,
					 "bAutoWidth": false,
					 'bSort': false,

				 });
				 $('#table_history').DataTable({
					 "searching": false,
 					 "pageLength": 10,
 					 "bLengthChange": false,
 					 "bFilter": true,
 					 "bInfo": false,
 					 "bAutoWidth": false,
 					 'bSort': false,

 				 });
	 });
	 function edituploadfile(thisdata){
		 var uploadtopicname = $(thisdata).data('uploadtopicname');
		 var uploadunid = $(thisdata).data('uploadunid');
		 var url = '/machine/upload/update';
		 $('#FRM_UPLOAD_MANUAL').attr('action',url);
		 $('#FILE_UPLOAD').attr('required',false);
		 $('#TOPIC_NAME').val(uploadtopicname);
		 $('#UPLOAD_MANUAL_UNID').val(uploadunid);
		 if (uploadunid != '') {
			  $('#UPLOAD_MANUAL').modal('show');
		 }
	 }
	 $('#UPLOAD_MANUAL').on('hidden.bs.modal', function (e) {
		 var url = "{{ route('machine.storeupload') }}";
		 $('#FRM_UPLOAD_MANUAL').attr('action',url);
		 $('#FILE_UPLOAD').attr('required',true);
		 $('#TOPIC_NAME').val('');
		 $('#UPLOAD_MANUAL_UNID').val('');
	 });
	 function	deleteupload(thisdata){
		 var uploadunid = $(thisdata).data('uploadunid');
		  var url = "/machine/upload/delete/"+uploadunid;
			Swal.fire({
			  title: 'คุณต้องการลบคู่มือนี้ ?',
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

		 $('#FRM_SAVE_PM').submit(function(){
			 $("#BTN_SUBMIT_PM", this)
				 .html("Please Wait...")
				 .attr('disabled', 'disabled');
			 return true;
		});


	 $('#FRM_UPLOAD_MANUAL').submit(function(){
	 		$("#BTN_SUBMIT_MANUAL", this)
	 			.html("Please Wait...")
	 			.attr('disabled', 'disabled');
	 		return true;
	 });
 </script>
@stop
{{-- ปิดส่วนjava --}}
