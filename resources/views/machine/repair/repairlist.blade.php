@extends('masterlayout.masterlayout')
@section('tittle','แจ้งซ่อม')
@section('meta')
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link href="{{asset('assets/css/select2.min.css')}}" rel="stylesheet" />
	<link href="{{asset('assets/css/useinproject/stylerepair.css')}}" rel="stylesheet" />

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

	@php
	$months=array(0 =>'ALL',1 => "มกราคม",2 => "กุมภาพันธ์",3 =>"มีนาคม",4 => "เมษายน",5 =>"พฤษภาคม",6 =>"มิถุนายน",
									 7 =>"กรกฎาคม",8 =>"สิงหาคม",9 =>"กันยายน",10 =>"ตุลาคม",11 => "พฤศจิกายน",12 =>"ธันวาคม");
	$CHECK_URL = route('repair.list');

	@endphp
	<audio id="music" src="{{asset('assets/sound/mixkit-arabian-mystery-harp-notification-2489.wav')}}" ></audio>
	<button type="button" style="display:none;" id="startbtn"></button>
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
															<select class="form-control form-control-sm mt-1 mx-1 col-3 col-md-1" id="YEAR" name="YEAR" onchange="changesubmit()">
																<option value="0">ทั้งหมด</option>
																@for ($y=date('Y')-2; $y < date('Y')+1; $y++)
																	<option value="{{$y}}" {{ $YEAR == $y ?'selected' : ''}}>{{$y}}</option>
																@endfor
															</select>
															<label class="text-white mx-2">เดือน : </label>
															<select class="form-control form-control-sm mt-1 mx-1 col-4 col-md" id="MONTH" name="MONTH" onchange="changesubmit()">
																<option value="0">ทั้งหมด</option>
																@for ($m=1; $m < 13; $m++)
																	<option value="{{$m}}" {{ $MONTH == $m ?'selected' : ''}}>{{$months[$m]}}</option>
																@endfor
															</select>
															<label class="text-white mx-2">Line : </label>
															<select class="form-control form-control-sm mt-1 mx-1 col-3 col-md-1" id="LINE"name='LINE' onchange="changesubmit()">
																 <option value="0">ทั้งหมด</option>
																@foreach ($LINE as $index => $row_line)
																	<option value="{{ $row_line->LINE_CODE }}"
																		{{ $MACHINE_LINE == $row_line->LINE_CODE ? 'selected' : '' }}>{{ $row_line->LINE_NAME }}</option>
																@endforeach
															</select>
															<label class="text-white mx-2">เอกสาร : </label>
															<select class="form-control form-control-sm mt-1 mx-1 col-3 col-md" id="DOC_STATUS" name="DOC_STATUS"onchange="changesubmit()">
																<option value="0">ทั้งหมด</option>
																<option value="9" {{ $DOC_STATUS == "9" ? 'selected' : "" }}>กำลังดำเนินการ</option>
																<option value="1" {{ $DOC_STATUS == "1" ? 'selected' : "" }}>ดำเนินการสำเร็จ</option>
																<option value="PD_CLOSE" {{ $DOC_STATUS == "PD_CLOSE" ? 'selected' : "" }}>จัดเก็บเรียบร้อย</option>
															</select>
														<label class="text-white mx-1">ค้นหา : </label>
								              <div class="col-6 col-md-3 input-group mx-1">
								                <input  type="search" id="SEARCH_MACHINE"  name="SEARCH_MACHINE" class="form-control form-control-sm mt-1 " placeholder="ค้นหา........."
																value="{{ $SEARCH }}">
								                <div class="input-group-prepend">
								                  <button type="submit" class="btn btn-search pr-1 btn-xs	mt-1" id="BTN_SUBMIT">
								                    <i class="fa fa-search search-icon"></i>
								                  </button>
								                </div>
								              </div>
														<div class="col-md text-right">
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
														<input type="radio"  class="selectgroup-input"  onchange="styletable(1)" {{ Cookie::get('table_style') == '1' ? 'checked' : (Cookie::get('table_style') == '' ? 'checked' : '')}} name="styletable">
														<span class="selectgroup-button"><i class="fas fa-th-large"></i></span>
													</label>
													<label class="selectgroup-item"  >
														<input type="radio" class="selectgroup-input "  onchange="styletable(2)" {{ Cookie::get('table_style') == '2' ? 'checked' : ''}} name="styletable">
														<span class="selectgroup-button"><i class="fas fa-list-ol"></i></span>
													</label>
												</div>
											</div>
										</div>

		               <div class="row" id="table_style" {{ Cookie::get('table_style') == '1' ? '' : 'hidden'}} >
		                  @foreach ($dataset as $key => $row)
												@php
													$BG_COLOR    		=  $row->INSPECTION_CODE ? 'bg-warning text-white' : 'bg-danger text-white';
													$IMG_PRIORITY		=  $row->PRIORITY == '9' ? '<img src="'.asset('assets/css/flame.png').'" class="mt--2" width="20px" height="20px">' : '';

													$WORK_STATUS 		=  $row->INSPECTION_CODE != '' ? $array_EMP[$row->INSPECTION_CODE] : 'รอรับงาน';
													$TEXT_STATUS    =  $row->PD_CHECK_STATUS == '1' ? 'จัดเก็บเอกสารเรียบร้อย' : ($row->CLOSE_STATUS == '1' ? 'ดำเนินการสำเร็จ' : (isset($row->INSPECTION_CODE) ? 'กำลังดำเนินการ' : 'รอรับงาน' ));
													$IMG         	  = isset($array_IMG[$row->INSPECTION_CODE]) ? asset('image/emp/'.$array_IMG[$row->INSPECTION_CODE]) : asset('../assets/img/noemp.png');
													$DATE_DIFF   	  = $row->REC_WORK_DATE != '1900-01-01 00:00:00.000'? 'รับเมื่อ:'.Carbon\Carbon::parse($row->REC_WORK_DATE)->diffForHumans() : 'แจ้งเมื่อ:'.Carbon\Carbon::parse($row->CREATE_TIME)->diffForHumans();
													$HTML_STATUS    = '<div class="status" id="DATE_DIFF_'.$row->UNID.'">'.$DATE_DIFF.'</div>';
													$HTML_BTN       = '<button class="btn  btn-primary  btn-sm"
																						onclick="rec_work(this)"
																						data-unid="'.$row->UNID.'"
																						data-docno="'.$row->DOC_NO.'"
																						data-detail="'.$row->REPAIR_SUBSELECT_NAME.'">
																							SELECT
																						</button>';
													$HTML_AVATAR    = '<img src="'.$IMG.'"id="IMG_'.$row->UNID.'"alt="..." class="avatar-img rounded-circle">';
													if ($row->PD_CHECK_STATUS == '1') {
										      	$BG_COLOR  		= 'bg-success text-white';
														$HTML_STATUS  = '<div class="status" id="DATE_DIFF_'.$row->UNID.'" >ปิดเอกสารเรียบร้อย</div>';
														$HTML_BTN     =	'<button class="btn btn-primary  btn-sm"
																						onclick=pdfsaverepair("'.$row->UNID.'")>
																							<i class="fas fa-print mx-1"></i>
																								PRINT
																						</button>';
														 $HTML_AVATAR = '<div class="timeline-badge success rounded-circle text-center text-white" style="width: 100%;height: 100%;">
	 																					 <i class="fas fa-check my-2" style="font-size: 35px;"></i></div>' ;
										      }elseif ($row->CLOSE_STATUS == '1') {
										        $BG_COLOR  		= 'bg-primary text-white';
														$HTML_STATUS  = '<div class="status" id="DATE_DIFF_'.$row->UNID.'" >ดำเนินงานสำเร็จ</div>';
										      }
												@endphp
												<div class="col-lg-3">
													<div class="card card-round">
														<div class="card-body">
															<div class="card-title  fw-mediumbold {{ $BG_COLOR }}"id="BG_{{ $row->UNID }}">
																<div class="row text-center">
																	<div class="col-lg-12">
																		{!! $IMG_PRIORITY !!}
																		{{$row->MACHINE_CODE}}
																	</div>
																</div>
																<div class="row text-center ">
																	<div class="col-lg-12">
																		<h5>{{$TEXT_STATUS}}</h5>
																		</div>
																</div>
															</div>
															<div class="card-list">
																<div class="item-list">
																	<div class="avatar">
																		{!! $HTML_AVATAR !!}
																	</div>
																	<div class="info-user ml-3">
																		<div class="username" style=""id="WORK_STATUS_{{$row->UNID}}">{{ $WORK_STATUS }}</div>
																		<div class="status" >{{$row->REPAIR_SUBSELECT_NAME}}</div>
																		{!! $HTML_STATUS !!}
																	</div>

																</div>
															</div>
															<div class="row ">
																<div class="col-md-12 text-center">
																	{!! $HTML_BTN !!}
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
															$REC_WORK_STATUS  = $sub_row->INSPECTION_CODE != '' ? '<i class="fas fa-wrench fa-lg mx-1"></i>'.$array_EMP[$sub_row->INSPECTION_CODE] : 'รอรับงาน';
															$BTN_COLOR_STATUS = $sub_row->PD_CHECK_STATUS == '1' ? 'btn-success' : ($sub_row->CLOSE_STATUS == '1' ? 'btn-primary': (isset($sub_row->INSPECTION_CODE) ? 'btn-warning' : 'btn-danger text-center'));
															if ($sub_row->PD_CHECK_STATUS == '1') {
																$REC_WORK_STATUS = '<i class="fas fa-clipboard-check fa-lg mx-1"></i> จัดเก็บเอกสารเรียบร้อย';
															}elseif ($sub_row->CLOSE_STATUS == '1') {
																$REC_WORK_STATUS = '<i class="fas fa-clipboard fa-lg mx-1"></i> ดำเนินการสำเร็จ';
															}
														@endphp
								            <tr >
															<td>{{ $dataset->firstItem() + $key }}</td>
															<td width="10%">{{ date('d-m-Y',strtotime($sub_row->DOC_DATE)) }}</td>
								              <td width="11%">{{ $sub_row->DOC_NO }}</td>
															<td width="4%">{{ $sub_row->MACHINE_LINE }}</td>
								              <td width="8%">{{ $sub_row->MACHINE_CODE }}</td>
								              <td>{{ $sub_row->MACHINE_NAME_TH }}</td>
															<td>{{ $sub_row->REPAIR_SUBSELECT_NAME }}</td>
							                  <td width="15%">
																		<button onclick="rec_work(this)" type="button"
																		data-unid="{{ $sub_row->UNID }}"
																		data-docno="{{ $sub_row->DOC_NO }}"
																		data-detail="{{ $sub_row->REPAIR_SUBSELECT_NAME }}"
																		class="btn {{$BTN_COLOR_STATUS}} btn-block btn-sm my-1 text-left">
																		 <span class="btn-label">
																			 {!!$REC_WORK_STATUS!!}
																		 </span>
																	 </button>
																 </td>
																<td width="9%">{{ date('d-m-Y') }}</td>
								              </tr>
								            @endforeach
								        </tbody>
								    </table>
								  	</div>
										<input type="hidden" id="PAGE" name="PAGE" value="{{$dataset->currentPage()}}">
									{{$dataset->appends(['SEARCH_MACHINE'=>$SEARCH])->links('pagination.default')}}
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
<script src="{{ asset('assets/js/js.cookie.min.js') }}"></script>
{{-- script parameter --}}
<script>
	var array_emp_unid = [];
	var sparepart_total = {};
	var sparepart_type = {};
	var sparepart_cost = {};

	var arr_spare_total = [];
	var arr_spare_type = [];
	var arr_spare_cost = [];
	let number_count = 0;
</script>
{{-- common script--}}
<script>
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
</script>
<script>
	var page = $('#PAGE').val();
	var url = "{{ route('repair.fetchdata') }}?page="+page;
	var url_readenotify = "{{ route('repair.readnotify')}}";
	var data = $('#FRM_SEARCH').serialize();
</script>
<script src="{{ asset('assets/js/useinproject/repairlooppage.js') }}"></script>
<script>
	// 1. loop table worker in step close repair
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
	// 2. Loop table sparepart in step close repair
	function loop_tabel_sparepart(unid,total,type,cost){
	 	//********************* input array ***********************
	 	arr_spare_total.push({unid:unid,total:total});
	 	arr_spare_type.push({unid:unid,type:type});
	 	arr_spare_cost.push({unid:unid,cost:cost});
	 	//********************** loop add array *************************
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
	// 3. Loop removeclass active
	function loop_removeclass(){
		for (var i = 1; i < 6; i++) {
			$('.WORK_STEP_'+i).removeClass('badge-primary badge-success fw-bold');
			$('#WORK_STEP_'+i).removeClass('active show');
		}
	}
	// 4. Loop add class step active
	function loop_addclass(step_number){
		for (var i = 1; i < step_number ; i++) {
			$('.WORK_STEP_'+i).addClass('badge-success fw-bold');
		 }
	}
</script>
{{-- Cookie --}}
<script>
	// Cookie Style List
	var cookie_tablestyle = "{{Cookie::get('table_style')}}";
	if (cookie_tablestyle == '') {
		$('#table_style').attr('hidden',false);
		$('#list_table').attr('hidden',true);
	}
	// create Cookie
	function setcookie(name,value){
		var urlcookie = "{{ route('cookie.set') }}";
		var data = {"_token": "{{ csrf_token() }}",NAME : name,VALUE : value}

		$.ajax({
			type:'POST',
			url: urlcookie,
			datatype: 'json',
			data: data ,
			success:function(res){
					}
				});
	}
</script>
{{-- Function --}}
<script>

//********************** function open step ที่ ค้างเอาไว้ ***********************

function modalstep3(repair_sparepart,repair_count){
	$("#overlay").fadeIn(300);
	$.each(repair_sparepart, function(key, val) {
		var unid 		= val.SPAREPART_UNID;
		var total 	= val.SPAREPART_TOTAL_OUT;
		var typeadd = val.SPAREPART_PAY_TYPE;
		var cost 		= val.SPAREPART_COST;
			loop_tabel_sparepart(unid,total,typeadd,cost);
	});
	if (repair_count > 0) {
		$('#addbuy_sparepart').attr('disabled',true);
		buysparepart('1');
	}else {
		$('#addbuy_sparepart').attr('disabled',false);
		buysparepart('2');
	}
	$("#overlay").fadeOut(300);
	}
function modalstep5(repair_unid){
	var url = "{{ route('repair.result') }}";
	$.fn.modal.Constructor.prototype._enforceFocus = function() {};
	$('#CloseForm').modal({backdrop: 'static', keyboard: false});
	$('#CloseForm').modal('show');
	$("#overlay").fadeIn(300);　
	$.ajax({
	 type:'POST',
	 url: url,
	 datatype: 'json',
	 data: {UNID_REPAIR:repair_unid} ,
	 success:function(res){
				 $('#closeform').attr('data-total_sparepart',res.total_sparepart);
				 $('#closeform').attr('data-total_worker',res.total_worker);
				 $('#closeform').attr('data-total_all',res.total_all);
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
			}
function rec_work(thisdata){
	$("#overlayinpage").fadeIn(300);　
	var repair_unid = $(thisdata).data('unid');
	var docno = $(thisdata).data('docno');
	var url = "{{ route('repair.empcallajax') }}";
	$.ajax({
			 type:'GET',
			 url: url,
			 data: {REPAIR_REQ_UNID : repair_unid},
			 datatype: 'json',
			 success:function(data){
				 $("#overlayinpage").fadeOut(300);
				 $('#INSPECTION_START_DATE').val(data.date);
				 $('#INSPECTION_START_TIME').val(data.time);
				 $('#datetime-doc').html('วันที่แจ้ง : '+data.date+' เวลา : '+data.time)
				 $('#TITLE_DOCNO').html('เลขที่เอกสาร : '+docno);
		 //************************ set select 2 **************************
				 $('#show_detail')											.html(data.html_detail);
				 $('#SPAREPART')												.html(data.html_sparepart);
				 $('#select_recworker,#WORKER_SELECT')	.html(data.html_select);
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
					$.fn.modal.Constructor.prototype._enforceFocus = function() {};

		//***************************** step ที่ค้างไว้ *********************************
				 if (data.step) {
					 var step_number = data.step.replace("WORK_STEP_", "");
					 var detail 		 = $('#DETAIL_REPAIR option:selected').attr('data-name');
					 $('#TITLE_DOCNO_SUB').html(docno);
					 $("#show-detail").html('อาการเสีย : '+detail);
					 	 loop_removeclass();
						 loop_addclass(step_number);

						if (step_number == '3') {
							//***************************** step 3 *********************************
							modalstep3(data.repair_sparepart,data.repair_count);
						}else if (step_number == '5') {
							//***************************** step 5 *********************************
							modalstep5(repair_unid)
						}else if (step_number == '1' || step_number == '2' || step_number == '4') {
							//***************************** step 1 & 2 & 4 *********************************

							$('#stepsave').attr('hidden',false);
							$('.stepclose').attr('hidden',true);
						}
						//***************************** open modal CloseForm *********************************
						$('.WORK_STEP_'+step_number).addClass('badge-primary fw-bold');
						$('#WORK_STEP_'+step_number).addClass('active show');
						$('#CloseForm').modal({backdrop: 'static', keyboard: false});
						$('#CloseForm').modal('show');
				 }else {
					 //***************************** open modal Recform *********************************
					 $('#Recform').modal({backdrop: 'static', keyboard: false,focus:false});
					 $("#Recform").modal("show");
					 $('#stepsave').attr('hidden',false);
					 $('.stepclose').attr('hidden',true);
				 }
			 }
		 });
}

//********************** function save ****************************
function savestep(work_step_current,work_step_next){
	var work_step_current = work_step_current;
	var work_step_next 		= work_step_next;
	var unid 							= $("#UNID_REPAIR_REQ").val();
	var url 							= "{{ route('repair.savestep') }}?UNID_REPAIR_REQ="+unid+"&WORK_STEP="+work_step_current+'&WORK_STEP_NEXT='+work_step_next;
	var data 							= $('#FRM_'+work_step_current).serialize();
	$("#overlay").fadeIn(300);
	$.ajax({
		type:'POST',
		url: url,
		datatype: 'json',
		data: data ,
		success:function(res){
			$("#overlay").fadeOut(300);
				 //page EMP_NAME
					if (res.EMP_NAME) {
						$('#IMG_'+unid).attr('src',res.IMG);
						$('#WORK_STATUS_'+unid).html(res.EMP_NAME);
						$('#DATE_DIFF_'+unid).html('แจ้งเมื่อ:'+res.DATE);
					}
					// Sparepart
					if (res.pass == 'false') {
						var text 		= '';
						var title   = 'รายการอะไหล่หมด';
						if (res.text) {
							var title = res.text;
						}
						let number 	= 0 ;
						buysparepart('1');
						$('#addbuy_sparepart').attr('disabled',true);
						$.each(res.sparepart, function(key, val) {
							  number++
								text+= ' '+number+'. '+key+' ';
				    });
						Swal.fire({
						  icon: 'error',
						  title: title,
						  text: text,
						});
					}else if (res.pass == 'true') {
						$('.'+work_step_next).addClass('badge-primary fw-bold');
						$('.'+work_step_current).removeClass('badge-primary fw-bold');
						$('.'+work_step_current).addClass('badge-success fw-bold');
						$('#'+work_step_current).removeClass('active show');
						$('#'+work_step_next).addClass('active show');
					}
				}
			});
}
function result(work_step_next,work_step_current){
	$("#overlay").fadeIn(300);　
	var url 				= "{{ route('repair.result') }}";
	var unid_repair = $("#UNID_REPAIR_REQ").val();
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
					$('.'+work_step_next).addClass('badge-primary fw-bold');
					$('.'+work_step_current).removeClass('badge-primary fw-bold');
					$('.'+work_step_current).addClass('badge-success fw-bold');
					$('#'+work_step_current).removeClass('active show');
					$('#'+work_step_next).addClass('active show');
					if (res.html) {
						$('#WORK_STEP_5').addClass('active');
					}
				}
			});
}
//******************************* End function ********************
//******************************* function Next and previous********************
function previous_step(step_number){
		var step_number_up 			= Number(step_number) + 1;
		var work_step_previous 	= 'WORK_STEP_'+step_number;
		var work_step_current   = 'WORK_STEP_'+step_number_up;
		$('.'+work_step_previous).removeClass('badge-success fw-bold');
		$('.'+work_step_previous).addClass('badge-primary fw-bold');
		$('.'+work_step_current).removeClass('badge-primary fw-bold');
		$('#'+work_step_current).removeClass('active show');
		$('#'+work_step_previous).addClass('active show');
	};
function nextstep(step_number){
	var step_number_down 		= Number(step_number) - 1;
	var work_step_next 			= 'WORK_STEP_'+step_number;
	var work_step_current  	= 'WORK_STEP_'+step_number_down;
	if ( $('#FRM_'+work_step_current).valid() ){
		 if (work_step_current == 'WORK_STEP_4') {
			 savestep(work_step_current,work_step_next);
			result(work_step_next,work_step_current);
		}else {
			savestep(work_step_current,work_step_next);
		}
	}else {
		sweetalertnoinput();
	}

};
//******************************* End function Next and previous********************

//******************************* function worker********************
function previous_worker(){
	$('#WORK_IN,#WORK_OUT,#nextstep_3').attr('hidden',true);
	$('.form_work_in,.form_work_out').attr('id','');
	$('#select_typeworker').attr('hidden',false);
	$("#previous_worker").attr('onclick','previous_step(1)');
	$('#nextstep_3').data('type','');
}
function type_worker(type_worker){
	var check_type = type_worker;
	if (check_type == '1') {
		 $('#WORK_IN').attr('hidden',false);
		 $('.form_work_in').attr('id','FRM_WORK_STEP_2');
		 $('#nextstep_3').data('type','IN');
	}else {
		$('#WORK_OUT').attr('hidden',false);
		$('.form_work_out').attr('id','FRM_WORK_STEP_2');
		$('#nextstep_3').data('type','OUT');
	}
	$("#previous_worker").attr('onclick','previous_worker()');
	$('#nextstep_3').attr('hidden',false);
	$('#select_typeworker').attr('hidden',true);
}
function deleteworker(thisdata){
 	var unid 		= $(thisdata).data('empunid');
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
//******************************* End function worker********************

//******************************* function worker********************
function add_sparepart(typeadd){
 $(document).off('focusin.modal');
 var unid 	= $('#SPAREPART').val();
 var total 	= $('#TOTAL_SPAREPART').val();
 var cost 	= $('#SPAREPART_COST').val();
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
//******************************* End function worker********************

 $('#closestep_1').on('click',function(){
	 var docno 				 = $('#TITLE_DOCNO').html();
	 var detail 			 = $('#DETAIL_REPAIR option:selected').attr('data-name');
	 var check_select  = $('#REC_WORKER').val();
	 if (check_select != '' && check_select != null) {
		 $.fn.modal.Constructor.prototype._enforceFocus = function() {};
		 $('#TITLE_DOCNO_SUB').html(docno);
		 $("#show-detail").html('อาการเสีย : '+detail);
		 loop_removeclass();
		 $('.WORK_STEP_1').addClass('badge-primary fw-bold');
		 $('#WORK_STEP_1').addClass('active show');
		 $('#Recform').modal('hide');
		 $('#CloseForm').modal({backdrop: 'static', keyboard: false});
		 $('#CloseForm').modal('show');
		 savestep('WORK_STEP_0','WORK_STEP_1');
	 }
 });
 $('#nextstep_3').on('click',function(){
	 var type_worker = $('#nextstep_3').data('type');
	 if (type_worker == 'IN') {
		 if (array_emp_unid != '') {
			 nextstep('3');
		 }
	 }else if(type_worker == 'OUT'){
		var check = $(".tablecolumn").length;
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
function buysparepart(check){
	var check = check;
			$('#buy_sparepart .buy_sparepart').attr('disabled',false);
			$('#addbuy_sparepart').attr("onclick","buysparepart('2')");
			$('#buy_sparepart').attr('hidden',false);
			if (check == '2') {
				$('#buy_sparepart .buy_sparepart').attr('disabled',true);
				$('#addbuy_sparepart').attr("onclick","buysparepart('1')");
				$('#buy_sparepart').attr('hidden',true);
			}
		}
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
		 number_count++;
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

	$('#add_workerout').html('<i class="fas fa-plus" > เพิ่ม</i>');
	$('.WORKEROUT_NAME').val('');
	$('.WORKEROUT_COST').val('');
	$('.WORKEROUT_DETAIL').val('');
 }

});
function editworkout(thisdata){
 var name 		= $(thisdata).data('name');
 var cost 		= $('#WORKOUT_COST\\['+name+'\\]').val();
 var detail 	= $('#WORKOUT_DETAIL\\['+name+'\\]').val();
 var table_id = $(thisdata).data('table');
 var i = $('#table_workerout .tablecolumn').length  ;
 $('#add_workerout').html('<i class="fas fa-plus" > แก้ไข</i>');
 $('.WORKEROUT_NAME').val(name);
 $('.WORKEROUT_COST').val(cost);
 $('.WORKEROUT_DETAIL').val(detail);
 $('table#table_workerout tr#tablerow'+table_id).remove();
	resetIndexes();
 if( i == 0 ) {
	 number_count = 0 ;
 }
}
function deleteworkout(thisdata){
	 var table_id = $(thisdata).data('table');
	 var i = $('#table_workerout .tablecolumn').length;
	 $('table#table_workerout tr#tablerow'+table_id).remove();
	 resetIndexes();
	if( i == 0 ) {
		number_count = 0 ;
	}
}
function resetIndexes(){
    var count = 0;
    $('.tablerow').each(function(){
			count++;
        if( count > 0 ){
        $(this).attr('id', 'tablerow' + count);
				 $('#tablerow'+count+' .tablecolumn').attr('id', 'tablecolumn' + count);
            $('#tablerow'+count+' .tablecolumn').html(count);
						$('#tablerow'+count+' .editworkout').attr('data-table',  count);
						$('#tablerow'+count+' .deleteworkout').attr('data-table',  count);
        }
				number_count = count;
    });
	}
$('#closeform').on('click',function(){
	var total_sparepart = $(this).attr('data-total_sparepart');
	var total_worker	  = $(this).attr('data-total_worker');
	var total_all 			= $(this).attr('data-total_all');
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
 })
 $('#CloseForm').on('hidden.bs.modal', function (e) {
	 for (var i = 1; i < 5; i++) {
		 var number = $('#FRM_WORK_STEP_'+i).length;
		 if (number == 1 ) {
			 $("#FRM_WORK_STEP_"+i)[0].reset();
		 }
	 }
	 array_emp_unid 	= []	;
	 sparepart_total 	= {}	;
	 sparepart_type 	= {}	;
	 sparepart_cost 	= {}	;
	 arr_spare_total 	= []	;
	 arr_spare_type 	= []	;
	 arr_spare_cost 	= []	;
	 number_count 		= '' 	;
	 loop_tabel_worker(array_emp_unid);
	 loop_removeclass();
	 loaddata_table();
	 $('#WORK_IN').attr('hidden',true);
	 $('#WORK_OUT').attr('hidden',true);
	 $('#select_typeworker').attr('hidden',false);
	 var max = $('#table_workerout .tablerow').length;
	 for (var i = 1; i < max+1; i++) {
		 $('#tablerow'+i).remove();
	 }
	 $('#addbuy_sparepart').attr('disabled',false);
	 $('table#table_workerout tr#tablerow').remove();
	 resetIndexes();
	 loop_tabel_sparepart('','','','');
	 for (var i = 1; i < 5; i++) {
		$('#FRM_WORK_STEP_'+i).trigger("reset");
	 }
 })
</script>
<script type="text/javascript">
	function changesubmit(){
		$('#BTN_SUBMIT').click();
	}
	function pdfsaverepair(unid){
		var unid = unid;
		window.open('/machine/repair/savepdf/'+unid,'RepairSaveprint','width=1000,height=1000,resizable=yes,top=100,left=100,menubar=yes,toolbar=yes,scroll=yes');

	}

</script>
@stop
{{-- ปิดส่วนjava --}}
