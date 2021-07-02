@extends('masterlayout.masterlayout')
@section('tittle','แจ้งซ่อม')
@section('meta')
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link href="{{asset('assets/css/select2.min.css')}}" rel="stylesheet" />
	<link href="{{asset('assets\css\cubeportfolio.css')}}" rel="stylesheet" type="text/css">

  	  <link href="{{asset('assets\css\portfolio.min.css')}}" rel="stylesheet" type="text/css">

 	 <link href="{{asset('assets\css\customize.css')}}" rel="stylesheet" type="text/css">

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
	@php
	$months=array(0 =>'ALL',1 => "มกราคม",2 => "กุมภาพันธ์",3 =>"มีนาคม",4 => "เมษายน",5 =>"พฤษภาคม",6 =>"มิถุนายน",
									 7 =>"กรกฎาคม",8 =>"สิงหาคม",9 =>"กันยายน",10 =>"ตุลาคม",11 => "พฤศจิกายน",12 =>"ธันวาคม");

	@endphp
	  <div class="content">
      <div class="page-inner">
				<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
          <div class="container">
						<div class="row">
							<div class="col-md-12 gx-4">
								@can('isUser')
									<a href="{{ url('/machine/user/homepage') }}">
										<button class="btn btn-warning  btn-xs ">
											<span class="fas fa-arrow-left fa-lg">Back </span>
										</button>
									</a>
	              @endcan
							</div>
						</div>
          </div>
				</div>
				<div class="py-12">
	        <div class="container mt-2">
						<div class="row">
							<div class="col-md-12">
								<div class="card ">
								  <div class="card-header bg-primary  ">
										<form action="{{ route('repair.list') }}" method="POST" id="FRM_SEARCH"enctype="multipart/form-data">
											@method('GET')
											@csrf
								        <div class="row ">
													<div class="col-md-12 col-lg-12 form-inline my-1">
															<label class="text-white mx-2">ปี : </label>
															<select class="form-control form-control-sm mt-1 mx-1" id="YEAR" name="YEAR" onchange="changesubmit()">
																<option value="0">ทั้งหมด</option>
																@for ($y=date('Y')-2; $y < date('Y')+1; $y++)
																	<option value="{{$y}}" {{ $YEAR == $y ?'selected' : ''}}>{{$y}}</option>
																@endfor
															</select>
															<label class="text-white mx-2">เดือน : </label>
															<select class="form-control form-control-sm mt-1 mx-1" id="MONTH" name="MONTH" onchange="changesubmit()">
																<option value="0">ทั้งหมด</option>
																@for ($m=1; $m < 13; $m++)
																	<option value="{{$m}}" {{ $MONTH == $m ?'selected' : ''}}>{{$months[$m]}}</option>
																@endfor
															</select>
															<label class="text-white mx-2">Line : </label>
															<select class="form-control form-control-sm mt-1 mx-1" id="LINE"name='LINE' onchange="changesubmit()">
																 <option value="">ทั้งหมด</option>
																@foreach ($LINE as $index => $row_line)
																	<option value="{{ $row_line->LINE_CODE }}"
																		{{ $MACHINE_LINE == $row_line->LINE_CODE ? 'selected' : '' }}>{{ $row_line->LINE_NAME }}</option>
																@endforeach
															</select>
															<label class="text-white mx-2">เอกสาร : </label>
															<select class="form-control form-control-sm mt-1 mx-1" id="DOC_STATUS" name="DOC_STATUS"onchange="changesubmit()">
																<option value="0">ทั้งหมด</option>
																<option value="9" {{ $DOC_STATUS == "9" ? 'selected' : "" }}>กำลังดำเนินการ</option>
																<option value="1" {{ $DOC_STATUS == "1" ? 'selected' : "" }}>ปิดเอกสาร</option>
															</select>
														<label class="text-white mx-1">ค้นหา : </label>
								              <div class="input-group mx-1">
								                <input  type="search" id="SEARCH"  name="SEARCH" class="form-control form-control-sm mt-1 col-lg-9" placeholder="ค้นหา........."
																value="{{ $SEARCH }}">
								                <div class="input-group-prepend">
								                  <button type="submit" class="btn btn-search pr-1 btn-xs	mt-1" id="BTN_SUBMIT">
								                    <i class="fa fa-search search-icon"></i>
								                  </button>
								                </div>
								              </div>
														{{-- </div> --}}
														{{-- <div class="col-md-12 col-lg-1 text-right"> --}}
														<div class="col-md-7 col-lg-1 text-right">
															<a href="{{ route('repair.repairsearch') }}"class="btn btn-warning  btn-xs mt-1 ">
																<span style="font-size: 13px;margin-bottom: 7px;">	แจ้งซ่อม</span>
															</a>
														</div>

													</div>
								        </div>
											</form>
								  </div>
								  <div class="card-body">
										<style>
										.text-size{
											font-size: 13px;
										}
										</style>
										<div class="row">
											<div class="col-6 col-sm-6 col-md-3 col-lg-2 ml-auto my-1">
												<div class="selectgroup w-100">
													<label class="selectgroup-item" >
														<input type="radio"  class="selectgroup-input" onchange="styletable(1)" {{ Cookie::get('table_style') == '1' ? 'checked' : ''}} name="styletable">
														<span class="selectgroup-button"><i class="fas fa-th-large"></i></span>
													</label>
													<label class="selectgroup-item"  >
														<input type="radio" class="selectgroup-input" onchange="styletable(2)" {{ Cookie::get('table_style') == '2' ? 'checked' : ''}} name="styletable">
														<span class="selectgroup-button"><i class="fas fa-list-ol"></i></span>
													</label>
												</div>
											</div>
										</div>

		                <div class="row" id="table_style" {{ Cookie::get('table_style') == '1' ? '' : 'hidden'}} >
											@php
												$array_EMP = array();
												foreach ($DATA_EMP as $index => $row_emp) {
													$array_EMP[$row_emp->EMP_CODE] = $row_emp->EMP_NAME_TH;
													$array_IMG[$row_emp->EMP_CODE] = $row_emp->EMP_ICON;
												}
											@endphp
		                  @foreach ($dataset as $key => $row)
												@php
													$BG_COLOR    = $row->PRIORITY == '9' ? 'bg-danger text-white' :  'bg-warning text-white';
										      if ($row->CLOSE_STATUS == '1') {
										        $BG_COLOR = 'bg-success text-white';
										      }
													$WORK_STATUS = isset($array_EMP[$row->INSPECTION_CODE]) ? $array_EMP[$row->INSPECTION_CODE] : 'รอรับงาน';
													$IMG         = isset($array_IMG[$row->INSPECTION_CODE]) ? asset('image/emp/'.$array_IMG[$row->INSPECTION_CODE]) : asset('../assets/img/noemp.png');
													$DATE_DIFF   = $row->REC_WORK_DATE != '1900-01-01 00:00:00.000'? 'รับเมื่อ:'.Carbon\Carbon::parse($row->REC_WORK_DATE)->diffForHumans() : 'แจ้งเมื่อ:'.Carbon\Carbon::parse($row->CREATE_TIME)->diffForHumans();
												@endphp
												<div class="col-lg-3">
													<div class="card card-round">
														<div class="card-body">
															<div class="card-title text-center fw-mediumbold {{ $BG_COLOR }}"id="BG_{{ $row->UNID }}">{{$row->MACHINE_CODE}}</div>
															<div class="card-list">
																<div class="item-list">
																	<div class="avatar">
																		<img src="{{$IMG}}"
																		id="IMG_{{ $row->UNID }}"alt="..." class="avatar-img rounded-circle">
																	</div>
																	<div class="info-user ml-3">
																		<div class="username" style=""id="WORK_STATUS_{{$row->UNID}}">{{ $WORK_STATUS }}</div>
																		<div class="status" >{{$row->REPAIR_SUBSELECT_NAME}}</div>
																		@if ($row->CLOSE_STATUS == '1')
																		<div class="status" id="DATE_DIFF_{{$row->UNID}}" > ดำเนินงานสำเร็จ</div>
																		@else
																		<div class="status" id="DATE_DIFF_{{$row->UNID}}">{{$DATE_DIFF}}</div>
																		@endif
																	</div>

																</div>
															</div>
															<div class="row ">
																<div class="col-md-12 text-center">
																	<button class="btn  btn-primary  btn-sm"
																	onclick="rec_work(this)"
																	data-unid="{{ $row->UNID }}"
																	data-docno="{{ $row->DOC_NO }}"
																	data-detail="{{ $row->REPAIR_SUBSELECT_NAME }}">
																		SELECT
																	</button>
																</div>

															</div>
														</div>
													</div>
												</div>
		                    @endforeach
		                </div>
								    <div class="table-responsive" id="list_table" {{ Cookie::get('table_style') == '2' ? '' : 'hidden'}} >
								      <table class="display table table-striped table-hover">
								        <thead class="thead-light">
								          <tr>
														<th>#</th>
														<th >วันที่เอกสาร</th>
								            <th >เลขที่เอกสาร </th>
														<th>Line</th>
								            <th>รหัสเครื่อง </th>
								            <th>ชื่อเครื่องจักร</th>
														<th>อาการ</th>
								            <th>สถานะงาน</th>
														<th >ผู้รับงาน</th>
														<th >วันที่รับงาน</th>
								          </tr>
								        </thead>
												<style>
													.btn-mute{
														background: #9e9e9e;
														color: white;
													}
												</style>
								        <tbody id="result">
								          @foreach ($dataset as $key => $sub_row)
														@php
															$REC_WORK_STATUS  = isset($array_EMP[$sub_row->INSPECTION_CODE]) ? $array_EMP[$sub_row->INSPECTION_CODE] : 'รอรับงาน';
															$BTN_COLOR_STATUS = $sub_row->INSPECTION_CODE == '' ? 'btn-mute' : ($sub_row->CLOSE_STATUS == '1' ? 'btn-success' : 'btn-info') ;
															$BTN_COLOR 			  = $sub_row->INSPECTION_CODE == '' ? 'btn-danger' : 'btn-secondary' ;
															$BTN_TEXT  			  = $sub_row->INSPECTION_CODE == '' ? 'รอรับงาน' : ($sub_row->CLOSE_STATUS == '1' ? 'เรียบร้อย' : 'กำลังดำเนินการ') ;
														@endphp
								            <tr >
															<td>{{ $key+1 }}</td>
															<td >{{ date('d-m-Y',strtotime($sub_row->DOC_DATE)) }}</td>
								              <td >{{ $sub_row->DOC_NO }}
								              </td>
															<td >  				{{ $sub_row->MACHINE_LINE }}	    </td>
								              <td >  				{{ $sub_row->MACHINE_CODE }}		     </td>
								              <td >  				{{ $sub_row->MACHINE_NAME }}		    </td>
															<td >  				{{ $sub_row->REPAIR_SUBSELECT_NAME }}		    </td>
								                  <td >
								                    <button type="button"class="btn {{$BTN_COLOR_STATUS}} btn-block btn-sm my-1 text-left"style="color:black;font-size:13px"
																		{{ $sub_row->CLOSE_STATUS == '1' ? 'onclick=pdfsaverepair("'.$sub_row->UNID.'")' : ''}}>
																			<i class="{{ $sub_row->CLOSE_STATUS == '1' ? 'fas fa-print' : '' }}"></i>
								                      <span class="btn-label " >
																				{{ $BTN_TEXT }}
								                      </span>
								                    </button>
								                  </td>
								                  <td >
																		@can('isAdminandManager')
																			<button onclick="rec_work(this)" type="button"
																			data-unid="{{ $sub_row->UNID }}"
																			data-docno="{{ $sub_row->DOC_NO }}"
																			data-detail="{{ $sub_row->REPAIR_SUBSELECT_NAME }}"
																			class="btn {{$BTN_COLOR}} btn-block btn-sm my-1 text-left">
																			 <span class="btn-label">
																				 <i class="fas fa-clipboard-check mx-1"></i>{{$REC_WORK_STATUS}}
																			 </span>
																		 </button>
																		@else
																		@endcan

																<td >{{ date('d-m-Y') }}</td>
								              </tr>
								            @endforeach
								        </tbody>
								    </table>
								  	</div>
									{{$dataset->appends(['MACHINE_LINE'=>$MACHINE_LINE,'MONTH' => $MONTH,'YEAR' => $YEAR,'DOC_STATUS' => $DOC_STATUS,'SEARCH',$SEARCH])
														->links('pagination.default')}}
								    </div>
								</div>
								</div>
              </div>

						</div>
					</div>
  			</div>
			</div>
		</div>
		@include('machine.repair.repairclosemodal')
		<div id="overlayinpage">
	    <div class="cv-spinner">
	      <span class="spinner"></span>
	    </div>
	  </div>
@stop
{{-- ปิดส่วนเนื้อหาและส่วนท้า --}}

{{-- ส่วนjava --}}
@section('javascript')
<script src="{{ asset('assets/js/ajax/ajax-csrf.js') }}"></script>
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
{{-- <script src="{{ asset('assets/js/useinproject/jquery-1.11.0.min.js') }}"></script> --}}
<script>
$(document).ready(function(){
		var url = "{{ route('repair.fetchdata') }}";
		var data = $('#FRM_SEARCH').serialize();
		var loaddata_table_all = function (){
			$.ajax({
						 type:'GET',
						 url: url,
						 data: data,
						 datatype: 'json',
						 success:function(data){
							 $('#result').html(data.html);
							 $('#table_style').html(data.html_style);
						 }
					 });
				 }
	setInterval(loaddata_table_all,10000);

});
//************************* array *********************************
	var array_emp_unid = [];
	var sparepart_total = {};
	var sparepart_type = {};
	var sparepart_cost = {};

	var arr_spare_total = [];
	var arr_spare_type = [];
	var arr_spare_cost = [];
	let number_count = 1;


//******************************* function ************************

//********************** function loop array **********************

function loop_tabel_worker(array_emp_unid){
	var url = "{{ route('repair.addtableworker') }}";
	$.ajax({
			 type:'POST',
			 url: url,
			 datatype: 'json',
			 data: {
				 "_token": "{{ csrf_token() }}",
				 "UNID" : array_emp_unid,
			 } ,
			 success:function(data){
				 $('#table_worker').html(data.html);
			 }
		 });
};
function loop_tabel_sparepart(unid,total,type,cost){
	//********************* input array ***********************
	arr_spare_total.push({unid:unid,total:total});
	arr_spare_type.push({unid:unid,type:type});
	arr_spare_cost.push({unid:unid,cost:cost});
	//********************** loop arry *************************
	$.each(arr_spare_total,function(key, value){
		sparepart_total[value.unid] = value.total;
	});
	$.each(arr_spare_type,function(key, value){
		sparepart_type[value.unid]  = value.type;
	});
	$.each(arr_spare_cost,function(key, value){
		sparepart_cost[value.unid]  = value.cost;
	});

	var url = "{{ route('repair.addsparepart') }}";
	$.ajax({
			 type:'POST',
			 url: url,
			 datatype: 'json',
			 data: {TOTAL_SPAREPART : sparepart_total,
			 				TYPE_SPAREPART : sparepart_type	,
							SPAREPART_COST : sparepart_cost},
			 success:function(data){
				 $('#table_sparepart').html(data.html);
			 }
		 });
	 };
//********************** function common **********************
function setcookie(name,value){
	var urlcookie = "{{ route('cookie.set') }}";
	var data = {"_token": "{{ csrf_token() }}",NAME : name,VALUE : value}
	$.ajax({
		type:'GET',
		url: urlcookie,
		datatype: 'json',
		data: data ,
		success:function(res){
				}
			});
}
function styletable(table_style){
	if (table_style == '1') {
		$('#table_style').attr('hidden',false);
		$('#list_table').attr('hidden',true);
		 setcookie('table_style','1');
	}else {
		$('#table_style').attr('hidden',true);
		$('#list_table').attr('hidden',false);
		 setcookie('table_style','2');
	}
}
function sweetalertnoinput(){
	Swal.fire({
	  icon: 'error',
	  title: 'ไม่สามารถไปขั้นตอนถัดไปได้',
	  text: 'กรุณากรอกข้อมูลให้ครบถ้วน!',
		timer: 1500
	});
}
//********************** function class tab ***********************
function loop_removeclass(){
	for (var i = 1; i < 6; i++) {
		$('.WORK_STEP_'+i).removeClass('badge-primary badge-success fw-bold');
		$('#WORK_STEP_'+i).removeClass('active show');
	}
}
function modalstep0(docno,detail){
	$('.WORK_STEP_1').addClass('badge-primary fw-bold');
	$('#WORK_STEP_1').addClass('active show');
	$('#RepairForm').modal('hide');
	$('#CloseForm').modal({backdrop: 'static', keyboard: false});
	$('#CloseForm').modal('show');
}

//********************** function save ****************************
function savestep(idform,steppoint){
	var unid = $("#UNID_REPAIR_REQ").val();
	var steppoint = steppoint;
	var url = "{{ route('repair.savestep') }}?UNID_REPAIR_REQ="+unid+"&WORK_STEP="+idform+'&WORK_STEP_NEXT='+steppoint;
	var idform = '#FRM_'+idform;

	var data = $(idform).serialize();
	$.ajax({
		type:'POST',
		url: url,
		datatype: 'json',
		data: data ,
		success:function(res){
					if (res.name) {
						$('#IMG_'+unid).attr('src',res.img);
						$('#WORK_STATUS_'+unid).html(res.name);
						$('#DATE_DIFF_'+unid).html('แจ้งเมื่อ:'+res.date);
					}
				}
			});
}
//******************************* End function ********************
	function rec_work(thisdata){
		$("#overlayinpage").fadeIn(300);　
		var repair_unid = $(thisdata).data('unid');
		var docno = $(thisdata).data('docno');
		var detail = $(thisdata).data('detail');
		var url = "{{ route('repair.empcallajax') }}";

		$.ajax({
				 type:'GET',
				 url: url,
				 data: {REPAIR_REQ_UNID : repair_unid},
				 datatype: 'json',
				 success:function(data){
					 $("#overlayinpage").fadeOut(300)
			 //************************ set html **************************
					 $('#show_detail').html(data.html_detail);
					 $('#select_recworker').html(data.html_select);
					 $('#WORKER_SELECT').html(data.html_select);
					 $('#SPAREPART').html(data.html_sparepart);
					 $('#SPAREPART').select2({
						 placeholder: "กรุณาเลือก",
						 width:'116%',
					 });
					 $('#WORKER_SELECT').select2({
						 placeholder: "กรุณาเลือก",
						 width:'100%',
					 });
					 $('.select-repairdetail').select2({
						   placeholder: "กรุณาเลือก",
					 	   width:'100%',
					  	 selectionCssClass:'my-1 ',
					  });
					 $('.REC_WORKER').select2({
						 placeholder: "กรุณาเลือก",
						 width:'100%',
						 selectionCssClass:'required',
						});
					 $('#TITLE_DOCNO').html('เลขที่เอกสาร : '+docno);
					 $.fn.modal.Constructor.prototype._enforceFocus = function() {};
			//***************************** step ที่ค้างไว้ *********************************
					 if (data.step) {
						 var step_number = data.step.replace("WORK_STEP_", "");
						 var detail = $('#DETAIL_REPAIR option:selected').attr('data-name');
						 $('#TITLE_DOCNO_SUB').html(docno);
						 $("#show-detail").html('อาการเสีย : '+detail);
						 	 loop_removeclass();
						 if (step_number == '1') {
						//***************************** step แรกสุด *********************************
							 modalstep0(docno,detail);
						 }else {
								for (var i = 1; i < step_number ; i++) {

 							 	$('.WORK_STEP_'+i).addClass('badge-success fw-bold');
						//***************************** step สุดท้าย *********************************
								if (i == 4) {
									var url = "{{ route('repair.result') }}";
									$('#CloseForm').modal({backdrop: 'static', keyboard: false});
									$('#CloseForm').modal('show');
									$('#WORK_STEP_5').addClass('active');
									$("#overlay").fadeIn(300);　
									$.ajax({
									 type:'POST',
									 url: url,
									 datatype: 'json',
									 data: {UNID_REPAIR:repair_unid} ,
									 success:function(res){
												 $('#closeform').attr('data-total_sparepart',res.total_sparepart);
												 $('#closeform').attr('data-total_worker',res.total_worker);
												 $('#closeform').attr('data-total_all',res.total_all);+
												 $('#stepsave').attr('hidden',false);
											 }
										 }).done(function(res) {
										      setTimeout(function(){
										        $("#overlay").fadeOut(300);
														$('#WORK_STEP_RESULT').html(res.html);
														$('#stepsave').attr('hidden',false);
														$('.stepclose').attr('hidden',true);
	 												 	if (res.status == '1') {
	 													 $('#stepsave').attr('hidden',true);
	 													 $('.stepclose').attr('hidden',false);
	 												 	}
										      },500);

										    });
								}else {
									$('#CloseForm').modal({backdrop: 'static', keyboard: false});
									$('#CloseForm').modal('show');
									$('.WORK_STEP_'+step_number).addClass('badge-primary fw-bold');
									$('#WORK_STEP_'+step_number).addClass('active show');
									$('#stepsave').attr('hidden',false);
									$('.stepclose').attr('hidden',true);
								}
							}
					//***************************** step ต่างๆ *********************************
						 }
					 }else {
						 $('#RepairForm').modal({backdrop: 'static', keyboard: false,focus:false});
						 $("#RepairForm").modal("show");
						 $('#stepsave').attr('hidden',false);
						 $('.stepclose').attr('hidden',true);
					 }

				 }
			 });
	}
	function previous_step(step_number){
			var step_number_up = Number(step_number) + 1;
			var work_step_previous = 'WORK_STEP_'+step_number;
			var work_step_simple   = 'WORK_STEP_'+step_number_up;
			$('.'+work_step_previous).removeClass('badge-success fw-bold');
			$('.'+work_step_previous).addClass('badge-primary fw-bold');
			$('.'+work_step_simple).removeClass('badge-primary fw-bold');
			$('#'+work_step_simple).removeClass('active show');
			$('#'+work_step_previous).addClass('active show');
		};
	function nextstep(step_number){
		var step_number_down = Number(step_number) - 1;
		var work_step_next = 'WORK_STEP_'+step_number;
		var work_step_simple   = 'WORK_STEP_'+step_number_down;
		if ($('#FRM_'+work_step_simple).valid()) {
			savestep(work_step_simple,work_step_next);
			if (work_step_next == 'WORK_STEP_5') {
				$("#overlay").fadeIn(300);　
				var url = "{{ route('repair.result') }}";
				var unid_repair = 			 $("#UNID_REPAIR_REQ").val();
				$.ajax({
					type:'POST',
					url: url,
					datatype: 'json',
					data: {UNID_REPAIR:unid_repair} ,
					success:function(res){
								$("#overlay").fadeOut(300);
								$('#closeform').attr('data-total_sparepart',res.total_sparepart);
								$('#closeform').attr('data-total_worker',res.total_worker);
								$('#closeform').attr('data-total_all',res.total_all);
								$('#WORK_STEP_RESULT').html(res.html);
								if (res.html) {
									$('#WORK_STEP_5').addClass('active');
								}
							}
						});
			}
			$('.'+work_step_next).addClass('badge-primary fw-bold');
			$('.'+work_step_simple).removeClass('badge-primary fw-bold');
			$('.'+work_step_simple).addClass('badge-success fw-bold');
			$('#'+work_step_simple).removeClass('active show');
			$('#'+work_step_next).addClass('active show');
		}else {
			sweetalertnoinput();
		}

	};
	function previous_worker(){
		$('#WORK_IN').attr('hidden',true);
		$('#WORK_OUT').attr('hidden',true);
		$('.form_work_in').attr('id','');
	 	$('.form_work_out').attr('id','');
		$('#nextstep_3').attr('hidden',true);
		$('#select_typeworker').attr('hidden',false);
		$("#previous_worker").attr('onclick','previous_step(1)');
		$('#nextstep_3').data('type','');
	}
	function type_worker(type_worker){
		var check_type = type_worker;
		var url = "{{ route('repair.empcallajax') }}";
		if (check_type == '1') {
			 $('#WORK_IN').attr('hidden',false);
			 $('.form_work_in').attr('id','FRM_WORK_STEP_2');
			 $("#previous_worker").attr('onclick','previous_worker()');
			 $('#nextstep_3').data('type','IN');
			 $('#nextstep_3').attr('hidden',false);

		}else {
			$('#WORK_OUT').attr('hidden',false);
			$('.form_work_out').attr('id','FRM_WORK_STEP_2');
			$("#previous_worker").attr('onclick','previous_worker()');
			$('#nextstep_3').data('type','OUT');
			$('#nextstep_3').attr('hidden',false);
		}
		 $('#select_typeworker').attr('hidden',true);
	}
 function deleteworker(thisdata){
	 	var unid = $(thisdata).data('empunid');
		var empcode = $(thisdata).data('empcode');
		var empname = $(thisdata).data('empname');
		var data = {
			id: unid,
			text: empcode+' '+empname
	 };
		 for( var i = 0; i < array_emp_unid.length; i++){
				 if ( array_emp_unid[i] == unid) {
						 array_emp_unid.splice(i, 1);
				 }
		 }
		var newOption = new Option(data.text, data.id, false, false);
		$('#WORKER_SELECT').append(newOption).trigger('change');
		loop_tabel_worker(array_emp_unid);
	 }
 function add_sparepart(typeadd){
	 //1 ตัดสต็อก  2ไม่ตัดสต็อก
	 $(document).off('focusin.modal');
	 var unid = $('#SPAREPART').val();
	 var total = $('#TOTAL_SPAREPART').val();
	 var cost = $('#SPAREPART_COST').val();
   loop_tabel_sparepart(unid,total,typeadd,cost);
 };
 function edittotal(thisdata){
	 var unid = $(thisdata).data('unid');
	 $('#SPAREPART').val(unid);
   $('#SPAREPART').select2({
		 width:'116%',
	 }).trigger('change');
	 var total = sparepart_total[unid];
	 $('#TOTAL_SPAREPART').val(total);
 }
 function removesparepart(thisdata){
	 var unid = $(thisdata).data('unid');
	 for( var i = 0; i < arr_spare_total.length; i++){
			 if ( arr_spare_total[i].unid == unid) {
					 arr_spare_total.splice(i, 1);
					 arr_spare_type.splice(i,1);
					 arr_spare_cost.splice(i,1);
			 }
	 }
	 loop_tabel_sparepart(unid);
 }

 $('#closestep_1').on('click',function(){
	 var docno 				= $('#TITLE_DOCNO').html();
	 var detail 			 	= $('#DETAIL_REPAIR option:selected').attr('data-name');
	 var check_select  = $('#REC_WORKER').val();
	 if (check_select != '' && check_select != null) {
		 $('#TITLE_DOCNO_SUB').html(docno);
		 $("#show-detail").html('อาการเสีย : '+detail);
		 loop_removeclass();
		 modalstep0(docno,detail);
		 savestep('WORK_STEP_0','WORK_STEP_1');
	 }

 });
 $('#nextstep_3').on('click',function(){
	 var type_worker = $('#nextstep_3').data('type');
	 console.log(type_worker);
	 if (type_worker == 'IN') {
		 if (array_emp_unid != '') {
			 nextstep('3');
		 }
	 }else if(type_worker == 'OUT'){
		var check = $(".tablecolumn").length;
		console.log(check);
		if (check > 0) {
			nextstep('3');
		}
	 }
 });
 $('#add_worker').on('click',function(event){
			event.preventDefault();
			var emp_code = $('#WORKER_SELECT').val();
			$('#WORKER_SELECT option[value="'+emp_code+'"]').detach();
			if (emp_code != "" && emp_code != null) {
				array_emp_unid.push(emp_code);
				loop_tabel_worker(array_emp_unid);
			}
		});
 $('#addbuy_sparepart').on('click',function(){
	  var check = $('#addbuy_sparepart').val();
		$('#buy_sparepart .buy_sparepart').attr('disabled',false);
		$('#addbuy_sparepart').val('2');
		$('#buy_sparepart').attr('hidden',false);
		if (check == '2') {
			$('#buy_sparepart .buy_sparepart').attr('disabled',true);
			$('#addbuy_sparepart').val('1');
			$('#buy_sparepart').attr('hidden',true);
		}
 });
 $('#SPAREPART').on('change',function(){
		var unid = $('#SPAREPART').val();
		var sparepartcost  = $('#'+unid).data('sparepartcost');
		$('#SPAREPART_COST').val(sparepartcost);
	 });

 $('#add_workerout').on('click',function(){
	 var name = $('.WORKEROUT_NAME').val();
	 var cost = $('.WORKEROUT_COST').val();
	 var detail = $('.WORKEROUT_DETAIL').val();
	 var number_check = 0;
	 if (name != '') {
		 $("#table_workerout").each(function () {
			var tds = '<tr id="tablerow'+number_count+'" class="tablerow">';
			jQuery.each($('tr:last td', this), function () {
					number_check++;
					if (number_check == '1') {
						tds += '<td class="tablecolumn">' + (number_count) + '</td>';
					}else if(number_check == '2'){
						tds += '<td>' +name+
						'<div class="row">'+
							'<div class="col-md-12">'+
								'<label>วิธีการแก้ไข</label>'+
								'<input type="hidden" value="'+name+'" id="WORKOUT_NAME['+name+']" name="WORKOUT_NAME['+name+']">'+
								'<input type="hidden" value="'+cost+'" id="WORKOUT_COST['+name+']" name="WORKOUT_COST['+name+']">'+
								'<textarea class="form-control mt--1 mb-1" id="WORKOUT_DETAIL['+name+']" name="WORKOUT_DETAIL['+name+']">'+detail+'</textarea>'+
							'</div>'+
						'</div>' + '</td>';
					}else if(number_check == '3'){
						tds += '<td>' + cost + '</td>';
					}else {
						tds += '<td><button type="button" class="btn btn-warning btn-block btn-sm editworkout"'+
												'onclick="editworkout(this)" data-name="'+name+'" data-table="'+number_count+'">แก้ไข</button>'+
											 '<button type="button" class="btn btn-danger btn-block btn-sm deleteworkout"'+
											 	'onclick="deleteworkout(this)" data-table="'+number_count+'">ลบรายการ</button></td>';
					}
			});
			tds += '</tr>';
					$('tbody', this).append(tds);
		});
		number_count++;
		$('#add_workerout').html('<i class="fas fa-plus" > เพิ่ม</i>');
		$('.WORKEROUT_NAME').val('');
		$('.WORKEROUT_COST').val('');
		$('.WORKEROUT_DETAIL').val('');
	 }

 });
 function editworkout(thisdata){
	 var name = $(thisdata).data('name');
	 var cost = $('#WORKOUT_COST\\['+name+'\\]').val();
   var detail = $('#WORKOUT_DETAIL\\['+name+'\\]').val();
	 var table_id = $(thisdata).data('table');
	 var i = $('#table_workerout .tablecolumn').length  ;
	 $('#add_workerout').html('<i class="fas fa-plus" > แก้ไข</i>');
	 $('.WORKEROUT_NAME').val(name);
	 $('.WORKEROUT_COST').val(cost);
	 $('.WORKEROUT_DETAIL').val(detail);
	 $('table#table_workerout tr#tablerow'+table_id).remove();
		resetIndexes();
	 if( i == 1 ) {
		 number_count = 1 ;
	 }
 }
 function deleteworkout(thisdata){
	 var table_id = $(thisdata).data('table');
	 var i = $('#table_workerout .tablecolumn').length;
	 $('table#table_workerout tr#tablerow'+table_id).remove();
	 resetIndexes();
	if( i == 1 ) {
		number_count = 1 ;
	}
 }
function resetIndexes(){
    var count = 1;
    $('.tablerow').each(function(){
        if( count > 0){
        $(this).attr('id', 'tablerow' + count);
				 $('#tablerow'+count+' .tablecolumn').attr('id', 'tablecolumn' + count);
            $('#tablerow'+count+' .tablecolumn').html(count);
						$('#tablerow'+count+' .editworkout').attr('data-table',  count);
						$('#tablerow'+count+' .deleteworkout').attr('data-table',  count);
        }
        count++;
				number_count = count;
    });
	}
$('#closeform').on('click',function(){
	var total_sparepart = $(this).data('total_sparepart');
	var total_worker	  = $(this).data('total_worker');
	var total_all 			= $(this).data('total_all');
	var url 						= "{{ route('repair.closeform') }}";
	var repair_unid		  = $("#UNID_REPAIR_REQ").val();
	$.ajax({
	 type:'POST',
	 url: url,
	 datatype: 'json',
	 data: {UNID_REPAIR:repair_unid,
		 			TOTAL_SPAREPART :total_sparepart,
					TOTAL_WORKER :total_worker,
					TOTAL_ALL :total_all} ,
	 success:function(res){
				 if (res.pass == 'true') {
					 Swal.fire({
							  icon: 'success',
							  title: 'ปิดเอกสารเรียบร้อย',
							  timer: 1500,
							}).then((result) => {
								  $('#BG_'+repair_unid).removeClass('bg-danger');
									$('#BG_'+repair_unid).removeClass('bg-warning');
									$('#BG_'+repair_unid).addClass('bg-success');
									// $('#CloseForm').modal('hide');
									$('#stepsave').attr('hidden',true);
									$('.stepclose').attr('hidden',false);
							});
				 }else {
					 Swal.fire({
							 icon: 'error',
							 title: 'เกิดข้อผิดพลาด',
							 text: 'กรุณากรอกข้อมูลให้ครบถ้วน',
							 timer: 1500,
						 });
				 }
			 }
		 });
 // $('#CloseForm').modal('hide');
 })
 $('#CloseForm').on('hidden.bs.modal', function (e) {
	 for (var i = 1; i < 5; i++) {
		document.getElementById("FRM_WORK_STEP_"+i).reset();
	 }
	 array_emp_unid = []; ;
	 sparepart_total = {}; ;
	 sparepart_type = {}; ;
	 sparepart_cost = {}; ;
	 arr_spare_total = []; ;
	 arr_spare_type = []; ;
	 arr_spare_cost = []; ;
	 number_count = '' ;
	 loop_tabel_worker(array_emp_unid);
	 // var table_id = $(thisdata).data('table');
	 var max = $('#table_workerout .tablerow').length;
	 for (var i = 1; i < max+1; i++) {
		 $('#tablerow'+i).remove();
	 }
	 resetIndexes();
	 loop_tabel_sparepart('','','','');
	 for (var i = 1; i < 5; i++) {
		document.getElementById("FRM_WORK_STEP_"+i).reset();
	 }
 })
</script>
<script type="text/javascript">
	function changesubmit(){
		$('#BTN_SUBMIT').click();
	}
	function pdfrepair(m){
		var unid = (m);
		window.open('/machine/repair/pdf/'+unid,'Repairprint','width=1000,height=1000,resizable=yes,top=100,left=100,menubar=yes,toolbar=yes,scroll=yes');
	}
	function pdfsaverepair(unid){
		var unid = unid;
		window.open('/machine/repair/savepdf/'+unid,'RepairSaveprint','width=1000,height=1000,resizable=yes,top=100,left=100,menubar=yes,toolbar=yes,scroll=yes');

	}

</script>
@stop
{{-- ปิดส่วนjava --}}
