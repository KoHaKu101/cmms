@extends('masterlayout.masterlayout')
@section('tittle','homepage')
@section('css')
	<link href="{{asset('assets/css/select2.min.css')}}" rel="stylesheet" />

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
	#overlayinpage{
		position: fixed;
		top: 0;
		z-index: 100;
		width: 100%;
		height:100%;
		display: none;
		background: rgba(0,0,0,0.6);
	}
		#overlay{
		position: fixed;
		top: 0;
		z-index: 100;
		width: 100%;
		height:100%;
		display: none;
		background: rgba(0,0,0,0.6);
	}
	.cv-spinner {
		height: 100%;
		display: flex;
		justify-content: center;
		align-items: center;
	}
	.spinner {
		width: 40px;
		height: 40px;
		border: 4px #ddd solid;
		border-top: 4px #2e93e6 solid;
		border-radius: 50%;
		animation: sp-anime 0.8s infinite linear;
	}
	@keyframes sp-anime {
		100% {
			transform: rotate(360deg);
		}
	}
	.is-hide{
		display:none;
	}
	</style>
	  <div class="content">
      <div class="page-inner ">
				<div class="py-12">
	        <div class="container mt-2">
						<div class="card">
							<form action="{{ route('pr.itemout') }}" method="post" enctype="multipart/form-data">
								@method('GET')
								@csrf
							<div class="row">
								<div class="col-md-12">
									<div class="card-header bg-primary text-white">
										<div class="row">
											<div class="col-12 col-md-12 col-lg-6 form-inline">
												<h4 class="mt-1 ml-auto"> ปี :	</h4>
												<select class="form-control form-control-sm col-11 col-md-2 ml-auto"
												 onchange="submitform()" id="DOC_YEAR" name="DOC_YEAR" required>
													@for ($i=2021; $i < date('Y')+3 ; $i++)
														<option value="{{ $i }}" {{ $DOC_YEAR == $i ? 'selected' : '' }} >{{$i}}</option>
													@endfor
												</select>
												<h4 class="mt-1 ml-auto"> เดือน :	</h4>
												<select class="form-control form-control-sm col-10 col-md-3 ml-auto "
												onchange="submitform()" id="DOC_MONTH" name="DOC_MONTH" required>
												<?php
												$months=array(0 =>'ทั้งหมด',1 => "มกราคม",2 => "กุมภาพันธ์",3 =>"มีนาคม",4 => "เมษายน",5 =>"พฤษภาคม",6 =>"มิถุนายน",
																				 7 =>"กรกฎาคม",8 =>"สิงหาคม",9 =>"กันยายน",10 =>"ตุลาคม",11 => "พฤศจิกายน",12 =>"ธันวาคม");

												?>
												@foreach ($months as $month => $name)
													<option value="{{$month}}" {{$DOC_MONTH == $month ? 'selected' : '' }}>{{$name}}</option>
												@endforeach
												</select>
												<h4 class="mt-1 ml-auto "> สถานะ :	</h4>
												<select type="select" class="form-control form-control-sm col-10 col-md-3 ml-auto"
													id="STATUS" name="STATUS" onchange="submitform()">
													<option value="0">ทั้งหมด</option>
													<option value="9" {{$STATUS == '9' ? 'selected' : ''}}>กำลังดำเนินการ</option>
													<option value="1" {{$STATUS == '1' ? 'selected' : ''}}>ดำเนินการสำเร็จ</option>
												</select>
											</div>
											<div class="col-12 col-md-12 col-lg-5 ml-auto">
												<div class="row">
													<div class="col-12 col-md-9 form-inline ">
														<h4 class="mt-1">ค้นหา : </h4>
														<div class="input-group mx-1 col-10 col-md-9 ml-auto">
							                <input type="search" id="SEARCH" name="SEARCH" class="form-control form-control-sm " placeholder="ค้นหา........." value="">
							                <div class="input-group-prepend">
							                  <button type="submit" class="btn btn-search pr-1 btn-xs	" id="BTN_SUBMIT">
							                    <i class="fa fa-search search-icon"></i>
							                  </button>
							                </div>
							              </div>
													</div>
													<div class="col-3 col-md-3 form-inline ml-auto">
														<button type="button" class="btn btn-sm btn-warning " id="BTN_NEW_DOCUMENT">
															<i class="fas fa-plus mx-1"></i>สร้าง
														</button>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							</form>
							<div class="row">
								<div class="col-md-12">
									<divl class="table">
											<table class="table table-bordered table-head-bg-info table-bordered-bd-info ">
												<thead>
													<tr>
														<th width="3%" class="text-center">#</th>
														<th width="9%">วันที่นำออก</th>
														<th width="9%">เลขที่เอกสาร</th>
														<th width="6%">ประเภท</th>
														<th width="24%">บริษัทที่นำส่ง</th>
														<th width="10%">ผู้นำของออก</th>

														<th width="9%">สถานะ</th>
														<th width="26%">action</th>

													</tr>
												</thead>
												<tbody>
													@foreach ($DocItemOut as $key => $row)
														@php
															$DOC_TYPE 		 = $row->DOC_TYPE == '9' ? 'ส่งซ่อม' : ($row->DOC_TYPE == '1' ? 'ขาย' : '-');
															$STATUS 			 = $row->STATUS == '9' ? 'bg-danger text-white' : ($row->STATUS == '1' ? 'bg-success text-white' : '');
															$TEXT_STATUS 	 = $row->STATUS == '9' ? 'รับยังไม่ครบ' : ($row->STATUS == '1' ? 'รับครบทั้งหมด' : '');
														@endphp
														<tr>
															<td class="text-center">{{$key+1}}</td>
															<td>{{date('d-m-Y',strtotime($row->DOC_DATE))}}</td>
															<td>{{$row->DOC_NO}}</td>
															<td>{{$DOC_TYPE}}</td>
															<td>{{$row->COMPANY_NAME}}</td>
															<td>{{$row->EMP_NAME_TH}}</td>
															<td class="text-center {{ $STATUS }}">{{ $TEXT_STATUS }}</td>
															<td >
																<button type="button" class="btn btn-primary btn-sm mx-1 my-1"
																onclick="showdetail(this)" data-unid="{{ $row->UNID }}"
																><i class="fas fa-eye mx-1"></i>Detail</button>
																<button type="button" class="btn btn-secondary btn-sm mx-1 my-1"
																onclick="PrintDoc(this)" data-unid="{{ $row->UNID }}"
																><i class="fas fa-print mx-1"></i>Print</button>
																@if ($row->STATUS == '9')
																	<button type="button" class="btn btn-danger btn-sm mx-1 my-1"
																	onclick="CancelDoc(this)" data-unid="{{ $row->UNID }}"
																	><i class="fas fa-times mx-1"></i>ยกเลิก</button>
																@endif
															</td>
														</tr>
													@endforeach
												</tbody>
											</table>

									</divl>
								</div>
							</div>
						</div>
					</div>
				</div>
      </div>
			<div id="overlayinpage">
				<div class="cv-spinner">
					<span class="spinner"></span>
				</div>
			</div>

@include('machine.pr.modalcreatedoc')
@include('machine.pr.modaldetail')




@stop
{{-- ปิดส่วนเนื้อหาและส่วนท้า --}}

{{-- ส่วนjava --}}
@section('javascript')
	<script src={{ asset('assets/js/ajax/ajax-csrf.js') }}></script>
	<script src="{{ asset('assets/js/ajax/appcommon.js') }}"></script>
	<script src="{{ asset('assets/js/select2.min.js') }}"></script>

  <script>
			$('#BTN_NEW_DOCUMENT').on('click',function(e){
				e.preventDefault();
				$("#overlayinpage").fadeIn(300);
				var url = " {{route('pr.openmodal') }}"
				$.ajax({
				 type:'GET',
				 url: url,
				 datatype: 'json',
				 success:function(res){
					 $('#EMP_UNID').html(res.html_emp);
					 $('#COMPANY_UNID').html(res.html_company);
						$('#NewPr').modal({backdrop: 'static', keyboard: false});
			 			$.fn.modal.Constructor.prototype._enforceFocus = function() {};
			 			$('#NewPr').modal('show');
						$("#overlayinpage").fadeOut(300);
						 }
					 });

			});
			function nextstep2(thisdata){
				$("#overlay").fadeIn(300);
				var url    	  = "{{ route('pr.typeselect') }}";
				var unid      = $('#DOC_ITEM_UNID').val();
				$.ajax({
				 type:'GET',
				 url: url,
				 datatype: 'json',
				 data: {DOC_ITEM_UNID:unid},
				 success:function(res){
					 		$('#FRM_STEP2').html(res.html);
					 		$('.select2').select2({
								width:'100%',
							});
							$('#BTN_PREVIOUS').attr('data-previous',1);
							$('#BTN_NEXT').attr('data-next',3);
					 		$('#step2').addClass('active');
							$('#step1').removeClass('active');
							$('#header_step1').removeClass('badge-primary');
							$('#header_step1').addClass('badge-success');
							$('#header_step2').addClass('badge-primary');
							$("#overlay").fadeOut(300);
							SaveStep2();
						 }
					 });
			}
			$('#BTN_PREVIOUS').on('click',function(){
				var step 		= $(this).attr('data-previous');
				var count 	= step - 1;
				var countup = count + 2;
				if (count < '1') {
					$('#BTN_PREVIOUS').attr('hidden',true);
					$('#BTN_NEXT').attr('hidden',false);
				}
				if (step == '2') {
					$('#BTN_PREVIOUS').attr('hidden',false);
					$('#BTN_NEXT').html('<i class="fas fa-arrow-right"></i>');
					$('#BTN_NEXT').attr('hidden',false);
				}
				$('#step'+countup).removeClass('active');
				$('#step'+step).addClass('active');
				$('#header_step'+countup).removeClass('badge-primary');
				$('#header_step'+countup).removeClass('fw-bold badge-success');
				$('#header_step'+step).removeClass('fw-bold badge-success');
				$('#header_step'+step).addClass('badge-primary');
				$('#BTN_PREVIOUS').attr('data-previous',count);
				$('#BTN_NEXT').attr('data-next',countup);
			});
			$('#BTN_NEXT').on('click',function(){
				var step 		= $(this).attr('data-next');
				var count 	= step - 1;
				var countup = count + 2 ;
				var count_table = $('#result_detail tr').length;

				if (step == '2') {
					SaveStep1();
					nextstep2();
					nextstep(step,count,countup);
					$('#BTN_PREVIOUS').attr('hidden',false);
					$('#BTN_NEXT').attr('hidden',false);
				}
				if (step == '3' && count_table > '0') {
					ShowResult();
					nextstep(step,count,countup);
					$('#BTN_PREVIOUS').attr('hidden',false);
					$('#BTN_NEXT').html('<i class="fas fa-save mx-2"></i> บันทึก');
					$('#BTN_NEXT').attr('hidden',false);
				}
				if (step == '4') {
					SaveResult();
				}

			});
			function deletedetail(thisdata){
				$("#overlay").fadeIn(300);
				var unid 		 = $(thisdata).data('unid');
				var itemunid = $(thisdata).data('itemunid');
				var url 		 = "{{ route('pr.deletedetail') }}";
				$.ajax({
				 type:'GET',
				 url: url,
				 datatype: 'json',
				 data: {UNID:unid,
				 				DOC_ITEMOUT_UNID:itemunid} ,
				 success:function(res){
							$('#result_detail').html(res.html);
							$('#SPAREPART_UNID').html(res.select);
							$("#overlay").fadeOut(300);
						 }
					 });
			};
			function nextstep(step,count,countup){
				$('#step'+count).removeClass('active');
				$('#step'+step).addClass('active');
				$('#header_step'+count).removeClass('badge-primary');
				$('#header_step'+count).addClass('fw-bold badge-success');
				$('#header_step'+step).addClass('badge-primary');
				$('#BTN_PREVIOUS').attr('data-previous',count);
				$('#BTN_NEXT').attr('data-next',countup);
			}
	</script>
	{{-- Step Save By Step --}}
	<script>
		function SaveStep1(){
			var data = $('#FRM_SAVE_STEP1').serialize();
			var unid = $('#DOC_ITEM_UNID').val();
			var url  = "{{ route('pr.savestep1') }}?UNID="+unid;
			$("#overlay").fadeIn(300);
			$.ajax({
			 type:'GET',
			 url: url,
			 datatype: 'json',
			 data: data ,
			 success:function(res){
			 $('#DOC_ITEM_UNID').val(res.UNID);
			 $("#overlay").fadeOut(300);
					 }
				 });
		}
		function SaveStep2(){
			$('#BTN_SAVE').on('click',function(event){
				event.preventDefault();
				$("#overlay").fadeIn(300);
				var note = $('#NOTE').val();
				if (note != '') {
					var url = "{{ route('pr.savestep2') }}";
					var data = $('#FRM_SAVE_STEP2').serialize();
					$.ajax({
					 type:'GET',
					 url: url,
					 datatype: 'json',
					 data: data ,
					 success:function(res){
								 $("#overlay").fadeOut(300);
						 		if (res.pass) {
									$('#SPAREPART_UNID').html(res.select);
									$('#result_detail').html(res.html)
									$('#DANGER_FORM').removeClass('has-error');
									$('#NOTE').val('');
									$('#alerttext').attr('hidden',true);
						 		}else {
									Swal.fire({
			 							title: 'กรุณาเลือกเครื่องจักรหรืออะไหล่',
			 							icon: 'warning',
			 							showDenyButton: false,
			 							showCancelButton: false,
			 							showConfirmButton: false,
			 							timer:'1500',
			 						})
						 		}

							 }
						 });
				}else {
					$("#overlay").fadeOut(300);
					$('#DANGER_FORM').addClass('has-error');
					$('#NOTE').focus();
					$('#alerttext').attr('hidden',false);
				}
			});
		}
		function ShowResult(){
			var unid	= $('#DOC_ITEM_UNID').val();
			var url	  = "{{ route('pr.showresult') }}";
			$("#overlay").fadeIn(300);
			$.ajax({
			 type:'GET',
			 url: url,
			 datatype: 'json',
			 data: {UNID:unid},
			 success:function(res){
						$('#step3').html(res.html);
						$("#overlay").fadeOut(300);
					 }
				 });
		}
		function SaveResult(){
			var unid	= $('#DOC_ITEM_UNID').val();
			var url	  = "{{ route('pr.saveresult') }}";
			$("#overlay").fadeIn(300);
			$.ajax({
			 type:'GET',
			 url: url,
			 datatype: 'json',
			 data: {UNID:unid} ,
			 success:function(res){
				 $("#overlay").fadeOut(300);
				 if (res.pass == true) {
					 Swal.fire({
							title: 'บันทึกรายการสำเร็จ?',
							icon: 'success',
							showDenyButton: false,
							showCancelButton: false,
							showconfirmButton: false,
							timer:'1500',
						}).then(function(){
							$("#NewPr").modal('hide');
						})
				 }else {
					 Swal.fire({
							title: 'เกิดข้อผิดพลาด',
							icon: 'error',
							showDenyButton: false,
							showCancelButton: false,
							showConfirmButton: false,
							timer:'1500',
						})
				 }
				}
			});
		}
	</script>
	{{-- Webpage --}}
	<script>
		$("#NewPr").on('hidden.bs.modal',function(){
			window.location.reload();
		});

		function showdetail(thisdata){
			var unid = $(thisdata).data('unid');
			var url = "{{ route('pr.detail') }}";
			$('#overlayinpage').fadeIn(300);
			$.ajax({
			 type:'GET',
			 url: url,
			 datatype: 'json',
			 data:{UNID:unid},
			 success:function(res){
				 $("#overlayinpage").fadeOut(300);
				 $('#SHOW_RESULT').html(res.html);
				 $('#DOC_ITEMOUT_UNID').val(res.DOC_ITEMOUT_UNID);
				 $('#PRINT_PR').attr('data-unid',res.DOC_ITEMOUT_UNID);
				 $('#BTN_SAVE_REC').attr('data-status',res.STATUS);
				 $('#BTN_SAVE_REC').html(res.TEXT_BTN);
					$('#Result').modal({backdrop: 'static', keyboard: false});
					$.fn.modal.Constructor.prototype._enforceFocus = function() {};
					$('#Result').modal('show');

					 }
				 });
		}
		function CancelDoc(thisdata){
			var unid = $(thisdata).data('unid');
	        Swal.fire({
	              title: 'กรุณาใส่สาเหตุยกเลิก',
	              input: 'text',
	              inputAttributes: {
	                autocapitalize: 'off'
	              },
	              showDenyButton: true,
	              showCancelButton: false,
	              confirmButtonText: `ยืนยัน`,
	              denyButtonText: `ยกเลิก`,
	              preConfirm: (note) => {
	                return fetch(`/machine/pr/canceldoc?unid=`+unid+`&note=${note}`)
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
												Swal.fire({
												  icon: 'success',
												  title: 'ยกเลิกรายการสำเร็จ',
													timer: '1500',
												}).then(function() {
						                window.location.reload();
						            })
	                    }
	                  })
	                  .catch(error => {
	                    Swal.showValidationMessage(
	                      'กรุณากรอกสาเหตุ'
	                    )
	                  })
	                },
	            })
		}
		function submitform(){
			$('#BTN_SUBMIT').click();
		}
		function PrintDoc(thisdata){
			var url = "{{ route('pr.printdoc') }}";
			var unid = $(thisdata).data('unid');
			window.open(url+'?UNID='+unid,'Repairprint','width=1000,height=1000,resizable=yes,top=100,left=100,menubar=yes,toolbar=yes,scroll=yes');
		}
		function Save_Rec(thisdata){
			var form_status = $('#BTN_SAVE_REC').attr('data-status');
			if (form_status == 'SAVE') {
				var data = $('#FRM_SAVE_REC').serialize();
				var url  = '{{ route("pr.saverec") }}';
				$.ajax({
				 type:'GET',
				 url: url,
				 datatype: 'json',
				 data: data ,
				 success:function(res){

							 if (res.pass) {
								 Swal.fire({
								  title: 'บันทึกสำเร็จ',
								  icon: 'success',
								  showCancelButton: false,
									showConfirmButton: false,
									showDenyButton: false,
									timer:'1500',
								}).then(function(){
									$('#Result').modal('hide');
									window.location.reload();
								})
							 }else {
								 Swal.fire({
								  title: 'กรุณากรอกข้อมูล',
								  icon: 'error',
									showCancelButton: false,
									showConfirmButton: false,
									showDenyButton: false,
									timer:'1500',
								})
							 }
						 }
					 });
			}else if (form_status == 'EDIT') {
				$('#BTN_SAVE_REC').attr('data-status','SAVE');
				$('#BTN_SAVE_REC').html('<i class="fas fa-save mx-2"></i>Save');
				$('.frm_rec').removeClass('form-control-plaintext');
				$('.frm_rec').addClass('form-control');
				$('.frm_rec').attr('readonly',false);
			}
		}
	</script>


@stop
{{-- ปิดส่วนjava --}}
