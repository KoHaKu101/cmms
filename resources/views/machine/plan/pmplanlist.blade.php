@extends('masterlayout.masterlayout')
@section('tittle','homepage')
@section('css')
		<script src="{{asset('assets/js/useinproject/jsQR.js')}}"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
.selectgroup-input:checked+.selectgroup-button {
	border-color: #8898ad;
	z-index: 1;
	color: #ffffff;
	background: orange;
	}
	.selectgroup-button {
		border: 1px solid rgb(21 114 232);
		background: white;
		color: rgb(72 171 247);
	}
	.icon-mute {
    background: #9e9e9e;
		color: white;
		cursor: no-drop;
	}
	.no-mouse{
		cursor: default;
	}
	h1 {
	margin: 10px 0;
	font-size: 40px;
}
.wrap-qrcode-scanner{
			max-width: 640px;
			margin: 0 auto;
			position: relative;
}
#loadingMessage {
	text-align: center;
	padding: 40px;
	background-color: #eee;
}
#canvas {
	width: 100%;
}
#output {
	margin-top: 20px;
	background: #eee;
	padding: 10px;
	padding-bottom: 0;
}
#output div {
	padding-bottom: 10px;
	word-wrap: break-word;
}
#beepsound{width: 0px;height: 1px;}
</style>

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
										<form action="{{ url('machine/pm/planlist') }}" method="post" id="FRM_PLANLIST" name="FRM_PLANLSIT"	>
											{{-- @method('post') --}}
											@csrf
											<div class="card-header bg-primary">
												<div class="row">
													<div class="col-12 col-md-12 col-lg-5 form-inline">

													 	<div class="form-group">
															<h4 class="ml-1 mt-2 " style="color:white;" ><i class="fas fa-clipboard-list fa-lg mr-1"></i> ปี</h4>
														 	<select class="form-control form-control-sm input-group filled text-info mx-1" id="PLAN_YEAR" name="PLAN_YEAR" required>
															 	@for ($i=2021; $i < date('Y')+3 ; $i++)
																	<option value="{{ $i }}"{{ $PLAN_YEAR == $i ? 'selected' : "" }} >{{$i}}</option>
																@endfor
															</select>
															<select class=" form-control form-control-sm input-group filled text-info mx-1" id="PLAN_STATUS" name="PLAN_STATUS" required>
																<option value="%" {{ $PLAN_STATUS == "%" ? 'selected' : "" }} >ALL</option>
																<option value="NEW" {{ $PLAN_STATUS == "NEW" ? 'selected' : "" }}>UN CHECK</option>
																<option value="EDIT" {{ $PLAN_STATUS == "EDIT" ? 'selected' : "" }} >CHECKING</option>
																<option value="COMPLETE" {{ $PLAN_STATUS == "COMPLETE" ? 'selected' : "" }} >CHECKED</option>


															</select>
															<select class="col-md-3 form-control form-control-sm input-group fukked text-info" id="PLAN_MONTH" name="PLAN_MONTH" style="width:170px">
    												<?php
														// $months = array(0 => 'All',1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => '.', 5 => 'May', 6 => 'Jun.', 7 => 'Jul.', 8 => 'Aug.', 9 => 'Sep.', 10 => 'Oct.', 11 => 'Nov.', 12 => 'Dec.');
														$months=array(0 =>'ALL',1 => "มกราคม",2 => "กุมภาพันธ์",3 =>"มีนาคม",4 => "เมษายน",5 =>"พฤษภาคม",6 =>"มิถุนายน",
																						 7 =>"กรกฎาคม",8 =>"สิงหาคม",9 =>"กันยายน",10 =>"ตุลาคม",11 => "พฤศจิกายน",12 =>"ธันวาคม");
														$transposed = array_slice($months, date('n'), 12, true) + array_slice($months, 0, date('n'), true);
														$last8 = array_reverse(array_slice($transposed, -8, 12, true), true);
														if (!isset($PLAN_MONTH)) {
															$PLAN_MONTH = date('n');
														}
														foreach ($months as $num => $name) {
															if ($PLAN_MONTH == $num ) {
																echo '<option value="'.$num.'" selected >'.$name.'</option>';
															}else {
																echo '<option value="'.$num.'" >'.$name.'</option>';
															}
														}
    												?>
														</select>
														</div>

													</div>

													<div class="col-12 col-md-12 col-lg-4">
														<div class="form-group">
															<div class="selectgroup w-100 ">
																<label class="selectgroup-item colorinput  mt-1">
																	<input type="radio" id="MACHINE_LINE" name="MACHINE_LINE" value="" class="selectgroup-input" {{ $MACHINE_LINE != '' ? '' : "checked" }} >
																	<span class="selectgroup-button selectgroup-button-icon " >All</span>
																</label>
																@foreach ($machineline as $index => $dataline)
																	<label class="selectgroup-item  mt-1">
																		<input type="radio" id="MACHINE_LINE" name="MACHINE_LINE" value="{{$dataline->LINE_CODE}}"  class="selectgroup-input" {{ $MACHINE_LINE == $dataline->LINE_CODE ? 'checked' : "" }}>
																		<span class="selectgroup-button" >{{$dataline->LINE_CODE}}</span>
																	</label>
																@endforeach
															</div>
														</div>
													</div>
													<div class="col-lg-3">
														<div class="input-group">
															<input type="text" id="MACHINE_CODE" name="MACHINE_CODE" class="form-control form-control-sm mt-3" value="{{ isset($MACHINE_CODE) ? $MACHINE_CODE : "" }}">
															<div class="input-group-append">
																<button type="submit" class="btn btn-search pr-1 btn-xs	mt-3">
																	<i class="fa fa-search search-icon"></i>
																</button>
															</div>
														</div>
													</div>


												</div>
											</div>
										</form>
										<div id="result"class="card-body " >
											<div class="row">
												@foreach ($machinepmplan as $number => $dataset)
													<div class="col-sm-6 col-md-3 " >
																<div class="card card-stats card-round ">
																	<a href="{{ $dataset->classtext == 'icon-mute' ? '#' : url('machine/pm/plancheck/'.$dataset->UNID) }}" style="text-decoration:none">
																		<div class="card-body {{ $dataset->classtext == 'icon-mute' ? 'no-mouse' : ''}} ">
																			<div class="card-title bg-primary text-white text-center">PM : {{ $dataset->PM_MASTER_NAME }}</div>
																			<div class="row align-items-center my-1">
																				<div class="col-icon">
																					<div class="icon-big text-center {{$dataset->classtext}} bubble-shadow-small" >
																						@php
																						 $ICON_STATUS  = array( 'NEW' => 'flaticon-stopwatch' , 'EDIT' => 'fas fa-user-cog', 'COMPLETE' => 'fas fa-check-circle');
																						@endphp
																						<i class="{{ $ICON_STATUS[$dataset->PLAN_STATUS] }}"></i>
																					</div>
																				</div>
																				<div class="col col-stats ml-3 ml-sm-0">
																					<div class="numbers">
																						<p class="card-title">{{$dataset->MACHINE_CODE}}</p>
																						<h4 class="card-category">LINE : {{$dataset->MACHINE_LINE}}</h4>
																						<h4 class="card-category">{{ $dataset->PLAN_DATE }}</h4>
																					</div>
																				</div>
																			</div>
																		</div>
																	</a>
																</div>
															</div>
												@endforeach

												</div>
											{{ $machinepmplan->appends(['MACHINE_CODE'=>$MACHINE_CODE,'MACHINE_LINE'=>$MACHINE_LINE,'PLAN_YEAR'=>$PLAN_YEAR,'PLAN_STATUS'=>$PLAN_STATUS,'PLAN_MONTH'=>$PLAN_MONTH])->links('pagination.default') }}
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
	<script src="{{ asset('assets/js/ajax/ajax-csrf.js') }}"></script>
		<script src="{{asset('assets/js/useinproject/html5-qrcode.min.js')}}">

		</script>
<script>
	$( document ).ready(function() {

	 $("input[type='radio']").click(function(e){
		 event.preventDefault();
		 $("button[type='submit']").trigger("click");
		 // $("#FRM_PLANLSIT").submit(); // Submit the form
 });
 $("#PLAN_MONTH").on('change',function(e){
	 event.preventDefault();
	 $("button[type='submit']").trigger("click");
	 // $("#FRM_PLANLSIT").submit(); // Submit the form
});
$("#PLAN_YEAR").on('change',function(e){
	event.preventDefault();
	$("button[type='submit']").trigger("click");
	// $("#FRM_PLANLSIT").submit(); // Submit the form
});
$("#PLAN_STATUS").on('change',function(e){
	event.preventDefault();
	$("button[type='submit']").trigger("click");
	// $("#FRM_PLANLSIT").submit(); // Submit the form
});
});
</script>
@stop
{{-- ปิดส่วนjava --}}
