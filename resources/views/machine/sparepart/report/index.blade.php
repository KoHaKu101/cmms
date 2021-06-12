@extends('masterlayout.masterlayout')
@section('tittle','homepage')
@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('assets/css/useinproject/_magnific-popup.scss')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-datepicker.css') }}">
<style>

	.bg-bluelight{
		    background-color: #b5daff;
	}
	.datepicker td, .datepicker th {
    width: 2.5rem;
    height: 2.5rem;
    font-size: 0.85rem;
}
.deleteimg {
	color: #fff;
	position: absolute;
	padding: 10px;
	top: 0px;
	left: 0px;

}
.close-img {
	position: absolute;
	padding: 10px;
	top: 0px;
	left: 1290px;

}
button.mfp-close{
	display: none;
}
.mfp-close{
	display: none;
}
.mfp-container {
	color: #fff;
	position: absolute;
	top: 10px;
	left: 10px;

}
.mfp-content {
	text-align: center;
}
.bg-muted{
	background-color:#696969;
	color:#696969;
}


</style>

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
					<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
	          <div class="container">
							<div class="row">

							</div>
	          </div>
					</div>
					<div class="py-12">
	        	<div class="container mt-2">
							<div class="row">
								<div class="col-md-12">
									<div class="card ">
										<form action="{{ route('SparPart.Report.Index') }}" method="GET" id="FRM_REPORT" name="FRM_REPORT">
											@csrf
											<div class="card-header bg-primary sortsparepart">
												<div class="row">
													<div class="col-12 col-md-12 col-lg-9 form-inline">
													 	<div class="form-group">
															<h4 class="ml-1 mt-2 " style="color:white;" ><i class="fas fa-clipboard-list fa-lg mr-1"></i> ปี</h4>
														 	<select class="form-control form-control-sm input-group filled text-info mx-1"
															 onchange="subminform()" id="DOC_YEAR" name="DOC_YEAR" required>
															 	@for ($i=2021; $i < date('Y')+3 ; $i++)
																	<option value="{{ $i }}" {{ $DOC_YEAR == $i ? 'selected' : '' }} >{{$i}}</option>
																@endfor
															</select>
														</div>
														<div class="form-group">
															<h4 class="ml-1 mt-2 " style="color:white;" >เดือน</h4>
															<select class="form-control form-control-sm input-group filled text-info mx-1"
															onchange="subminform()" id="DOC_MONTH" name="DOC_MONTH" required>
															<?php
															// $months = array(0 => 'All',1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => '.', 5 => 'May', 6 => 'Jun.', 7 => 'Jul.', 8 => 'Aug.', 9 => 'Sep.', 10 => 'Oct.', 11 => 'Nov.', 12 => 'Dec.');
															$months=array(0 =>'ALL',1 => "มกราคม",2 => "กุมภาพันธ์",3 =>"มีนาคม",4 => "เมษายน",5 =>"พฤษภาคม",6 =>"มิถุนายน",
																							 7 =>"กรกฎาคม",8 =>"สิงหาคม",9 =>"กันยายน",10 =>"ตุลาคม",11 => "พฤศจิกายน",12 =>"ธันวาคม");
															$transposed = array_slice($months, date('n'), 12, true) + array_slice($months, 0, date('n'), true);
															$last8 = array_reverse(array_slice($transposed, -8, 12, true), true);
															if (!isset($DOC_MONTH)) {
																$DOC_MONTH = date('n');
															}
															foreach ($months as $num => $name) {
																if ($DOC_MONTH == $num ) {
																	echo '<option value="'.$num.'" selected >'.$name.'</option>';
																}else {
																	echo '<option value="'.$num.'" >'.$name.'</option>';
																}
															}
	    												?>

															</select>
														</div>
														<div class="form-group">
															<h4 class="ml-1 mt-2 " style="color:white;" ><i class="fas fa-clipboard-list fa-lg mr-1"></i> สถานะ</h4>
														 	<select class="form-control form-control-sm input-group filled text-info mx-1"
															 onchange="subminform()" id="STATUS" name="STATUS">
																	<option value="" 					{{ $STATUS == "" ? 'selected' : '' }} >All</option>
																	<option value="NEW" 			{{ $STATUS == 'NEW' ? 'selected' : '' }} >UNCHECK</option>
																	<option value="COMPLETE"  {{ $STATUS == 'COMPLETE' ? 'selected' : '' }} >CHECKED</option>
															</select>
														</div>
														<div class="form-group">
															<h4 class="ml-1 mt-2 " style="color:white;" ><i class="fas fa-clipboard-list fa-lg mr-1"></i> LINE</h4>
														 	<select class="form-control form-control-sm input-group filled text-info mx-1"
															 onchange="subminform()" id="MACHINE_LINE" name="MACHINE_LINE">
															 <option value=""> All </option>
															 	@for ($i=1; $i < 7; $i++)
																	<option value="{{ 'L'.$i }}"  {{ $MACHINE_LINE == "L".$i."" ? 'selected' : '' }} >{{ 'L'.$i }}</option>
																@endfor
															</select>
														</div>
													</div>

													<div class="col-lg-3">
														<div class="input-group">
															<input type="text" id="SEARCH" name="SEARCH" class="form-control form-control-sm mt-3"
															 placeholder="ค้นหา รหัสเครื่องจักร"value="{{ isset($SEARCH) ? $SEARCH : '' }}">
															<div class="input-group-append">
																<button type="submit" class="btn btn-search pr-1 btn-xs	mt-3">
																	<i class="fa fa-search search-icon"></i>
																</button>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="card-body">

												@if (count($DATA_SPAREPLAN) > 0)
													<div class="row">
														<div class="col-md-12">
															<button type="button" class="btn btn-primary btn-sm my-1 float-right"
															 onclick="positionedPopup('{{ route('SparPart.Report.planmonthprint').'?DOC_YEAR='.$DOC_YEAR.'&DOC_MONTH='.$DOC_MONTH.'&SEARCH='.$SEARCH}}','myWindow');return false"
															><i class="fas fa-print fa-lg mr-1"></i>รายงานประจำเดือน</button>
														</div>
													</div>
												@endif

												<div class="row">
													<div class="col-md-12 table-responsive">
														<table class="table  table-bordered table-head-bg-info table-bordered-bd-info table-striped table-hover">
															<thead>
																<tr>
																	<th scope="col">#</th>
																	<th scope="col">Plan Date</th>
																	<th scope="col">LINE</th>
																	<th scope="col">Machine</th>
																	<th scope="col">SparPartName</th>
																	<th scope="col">Plan</th>
																	<th scope="col">Actual</th>
																	<th scope="col">Unit</th>
																	<th scope="col">Plan Cost</th>
																	<th scope="col">Actual Cost</th>
																	<th scope="col">Complete Date</th>
																	<th scope="col">Action</th>


																</tr>
															</thead>
															<tbody>
																@foreach ($DATA_SPAREPLAN as $index => $row)
																	<tr>
																		<td>{{ $index+1 }}</td>
																		<td>{{ date("d-m-Y", strtotime($row->PLAN_DATE))}}</td>
																		<td>{{ $row->MACHINE_LINE}}</td>
																		<td>{{ $row->MACHINE_CODE}}</td>
																		<td>{{ $row->SPAREPART_NAME}}</td>

																		<td class="text-center">{{ $row->PLAN_QTY}} </td>
																		<td class="text-center">{{ $row->ACT_QTY}} </td>
																		<td>{{ $row->UNIT}}</td>
																		<td class="text-right">{{ number_format($row->TOTAL_COST,0)}} </td>
																		<td class="text-right">{{ number_format($row->COST_ACT,0)}} </td>
																		<td>{{ $row->STATUS == 'COMPLETE' ? date("d-m-Y", strtotime($row->COMPLETE_DATE)) : ''}}</td>
																		<td>
																			<button type="button" class="btn btn-primary btn-sm mx-1 my-1 "
																			data-planunid="{{ $row->UNID }}"
																			data-machine_code = "{{$row->MACHINE_CODE}}"
																			data-planusercheck = "{{$row->USER_CHECK}}"
																			data-btn_status="VIEW"
																			onclick="viewform(this)">
																				<i class="fas fa-eye fa-lg mr-1"></i>View</button>
																		@if ($row->classtext == 'TRUE')
																			@if ($row->STATUS == 'COMPLETE')
																				<button type="button" class="btn btn-danger btn-sm mx-1 my-1"
																				data-planunid="{{ $row->UNID }}"
																				data-machine_code = "{{$row->MACHINE_CODE}}"
																				data-planusercheck = "{{$row->USER_CHECK}}"
																				data-btn_status="VOID"
																				onclick="voidform(this)">
																					<i class="fas fa-retweet fa-lg mr-1"></i>Void</button>
																			@else
																				<button type="button" class="btn btn-secondary btn-sm mx-1 my-1"
																				data-planunid="{{ $row->UNID }}"
																				data-machine_code = "{{$row->MACHINE_CODE}}"
																				data-planusercheck = "{{$row->USER_CHECK}}"
																				data-btn_status="CHANGE"
																				onclick="checkplansparepart(this)">
																					<i class="fas fa-edit fa-lg mr-1"></i>Change</button>
																			@endif

																		@endif
																		</td>

																	</tr>
																@endforeach
															</tbody>
														</table>
														{{ $DATA_SPAREPLAN->appends(['DOC_YEAR'=>$DOC_YEAR,'DOC_MONTH'=>$DOC_MONTH,'SEARCH'=>$SEARCH,'STATUS' => $STATUS,'MACHINE_LINE'=>$MACHINE_LINE])->links('pagination.default') }}
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
			</div>
{{-- modal plancheck --}}
			<div class="modal fade" id="modal-plansparepartcheck" tabindex="-1" role="dialog" aria-labelledby="exampleModalLalavel" aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-content">
							<form action="{{ route('SparPart.Report.Save')}}" method="POST" id="FRM_CHECKSPAREPART" name="FRM_CHECKSPAREPART">
								@csrf
							<div class="modal-header bg-primary">
								<h5 class="modal-title" id="Title_plansparepartcheck">Machine Code :</h5>
							</div>
							<div class="modal-body modal-planform">

							 </div>

							 <div class="modal-body">
								 <div class="row">
 									<div class="col-md-6 my-2 form-inline" id="BTN_CONFIRM">
										<div class="input-group">
								   <div class="input-group-prepend">
								   	<span class="input-group-text bg-info text-white" id="basic-addon3">เลื่อนแผน</span>
								  	</div>
								  	<input type="text" class="col-md-8 form-control form-control-sm bg-bluelight text-black" id="PLAN_CHANGE" name="PLAN_CHANGE">
								  </div>
								 <button type="button" class="btn btn-warning btn-sm btn-confirm">Confirm</button>
 									</div>


 									<div class="col-md-6 my-2">
 										<div class="input-group has-error">
 											<div class="input-group-prepend">
 												<span class="input-group-text bg-info text-white" id="basic-addon3">ผู้ทำการเปลี่ยน</span>
 											</div>
 											<input type="text" class="form-control form-control-sm bg-bluelight text-black"
 											 id="USER_CHECK" name="USER_CHECK" required>
 										</div>
 									</div>
 								</div>
							 </div>
							<div class="modal-footer" id="FOOTER" name="FOOTER">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						      <button type="button" class="btn btn-primary btn-saveform" id="BTN_SAVEFORM" name="BTN_SAVEFORM">Save</button>
							</div>
						</form>
						</div>
					</div>
				</div>
			</div>

	{{-- modal img --}}
	<div class="modal fade" id="modal-plansparepartcheck-img" tabindex="-1" role="dialog" aria-labelledby="exampleModalLalavel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-content">
					<form action="{{ route('SparPart.Report.SaveImg') }}" id="FRM_SPAREPART_UPLOAD" name="FRM_SPAREPART_UPLOAD" method="POST" enctype="multipart/form-data">
						@csrf
						<div class="modal-header">
								<h5 class="modal-title" id="Title_IMG">Machine Code :</h5>
								<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
						</div>
						<div class="modal-body">
							<div class="col col-lg-12 form-inline">
								<div class="form-group mx-1">
									<label for="exampleFormControlFile1">แนบรูปภาพปฏิบัติงาน</label>
									<input type="file" class="form-control-file my-1" id="IMG_SPAREPART_FILE_NAME"
									name="IMG_SPAREPART_FILE_NAME" accept="image/*" required>
									<input type="hidden" id="IMG_SPAREPART_UNID"name="IMG_SPAREPART_UNID"value="">
								</div>
							</div>
						</div>

						<div class="modal-footer">
							<div class="col col-lg-12">
								<button class="btn btn-primary btn-block" id="BTN_UPLOAD" name="BTN_UPLOAD"
								type="submit">Upload</button>
							</div>
						</div>
						<div class="card-body" id="IMG_SHOW" name="IMG_SHOW">

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
	<script src="{{ asset('assets/js/ajax/ajax-csrf.js') }}"></script>
	<script src="{{ asset('assets/js/ajax/appcommon.js') }}"></script>
	<script src="{{ asset('assets/js/bootstrap-datepicker.js') }}"></script>
	<script src="{{ asset('assets/js/useinproject/jquery.magnific-popup.min.js') }}"></script>

	<script src="{{ asset('assets/js/useinproject/sparepart/sparepartplanmonth.js') }}"></script>
	<script>
	$(document).ready(function(){
		$.magnificPopup.defaults.closeOnBgClick = false;
	});


	function image_gallery(thisdata){

		var imgunid = $(thisdata).data("imgunid");
		var file = $('#IMGLOCATION'+imgunid).attr("src");
			$.magnificPopup.open({
			items: {
				src: $('<img src="' + file + '" class="col col-lg-3"/>'+
				'<button type="button" class="deleteimg btn btn-danger" onclick="deleteimg(this)" data-imgunid="'+imgunid+'" ><i class="fas fa-trash"></i></button>'+
		  	'<button type="button" class="close-img btn btn-info" onclick="closeimg()"><i class="fas fa-times"></i></button>'),
				type: 'inline'
			},
			});
				$('#modal-plansparepartcheck-img').modal('hide');
	}
	function image_gallery_view(thisdata){

		var imgunid = $(thisdata).data("imgunid");
		var file = $('#IMGLOCATION'+imgunid).attr("src");
			$.magnificPopup.open({
			items: {
				src: $('<img src="' + file + '" class="col col-lg-3"/>'+
		  	'<button type="button" class="close-img btn btn-info" onclick="closeimg_view(this)" data-btn_status="VIEW"><i class="fas fa-times"></i></button>'),
				type: 'inline'
			},
			});
				$('#modal-plansparepartcheck-img').modal('hide');
	}
	function closeimg(){
			var plan_sparepartunid = $('#IMG_SPAREPART_UNID').val();
			var url = '/machine/spart/report/planmonth/formimg'
			var data = { SPAREPART_PLAN_UNID:plan_sparepartunid}
			$.ajax({
				type: "GET",
				url: url,
				data: data,
				success: function (data) {
					$.magnificPopup.close();
					$('#IMG_SPAREPART_UNID').val(plan_sparepartunid);
					$('#IMG_SHOW').html(data.html);
					$('#modal-plansparepartcheck-img').modal('show');
				}
			});
	}
	function closeimg_view(thisdata){
		  var btn_status = $(thisdata).data('btn_status');
			var plan_sparepartunid = $('#IMG_SPAREPART_UNID').val();
			var url = '/machine/spart/report/planmonth/formimg'
			var data = { SPAREPART_PLAN_UNID:plan_sparepartunid,
									BTN_STATUS : btn_status}
			$.ajax({
				type: "GET",
				url: url,
				data: data,
				success: function (data) {
					$.magnificPopup.close();
					$('#BTN_UPLOAD').hide();
					$('#IMG_SPAREPART_UNID').val(plan_sparepartunid);
					$('#IMG_SHOW').html(data.html);
					$('#modal-plansparepartcheck-img').modal('show');
				}
			});
	}

	</script>
@stop
{{-- ปิดส่วนjava --}}
