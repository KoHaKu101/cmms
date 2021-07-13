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
										<form action="{{ route('pd.repairlist') }}" method="POST" id="FRM_SEARCH"enctype="multipart/form-data">
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
																<option value="1" {{ $DOC_STATUS == "1" ? 'selected' : "" }}>ดำเนินการเรียบร้อย</option>
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
										      if ($row->PD_CHECK_STATUS == '1') {
										        $BG_COLOR = 'bg-success text-white';
										      }elseif ($row->CLOSE_STATUS == '1') {
														$BG_COLOR = 'bg-info text-white';
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
																		@if ($row->PD_CHECK_STATUS == '1')
																			<div class="status" id="DATE_DIFF_{{$row->UNID}}" > ปิดเอกสารสำเร็จ</div>
																		@elseif ($row->CLOSE_STATUS == '1')
																			<div class="status" id="DATE_DIFF_{{$row->UNID}}" > ดำเนินงานสำเร็จ</div>
																		@else
																		<div class="status" id="DATE_DIFF_{{$row->UNID}}">{{$DATE_DIFF}}</div>
																		@endif
																	</div>

																</div>
															</div>
															@if ($row->CLOSE_STATUS == '1')
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
															@endif

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
															$BTN_COLOR_STATUS = $sub_row->INSPECTION_CODE == '' ? 'btn-mute' : ($sub_row->CLOSE_STATUS == '1' ? 'btn-info' : 'btn-warning') ;
															$BTN_COLOR_STATUS = $sub_row->PD_CHECK_STATUS != 9 ? 'btn-success' : $BTN_COLOR_STATUS;

															$BTN_COLOR 			  = $sub_row->INSPECTION_CODE == '' ? 'btn-danger' : 'btn-secondary' ;
															$BTN_TEXT  			  = $sub_row->INSPECTION_CODE == '' ? 'รอรับงาน' : ($sub_row->CLOSE_STATUS == '1' ? 'ดำเนินการสำเร็จ' : 'กำลังดำเนินการ') ;
															$BTN_TEXT					= $sub_row->PD_CHECK_STATUS != 9 ? 'ปิดเอกสารแล้ว' : $BTN_TEXT;
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
								                    <button type="button"class="btn {{$BTN_COLOR_STATUS}} btn-block btn-sm my-1 text-left"
																		{{ $sub_row->CLOSE_STATUS == '1' ? 'onclick=pdfsaverepair("'.$sub_row->UNID.'")' : ''}}>
																			<i class="{{ $sub_row->CLOSE_STATUS == '1' ? 'fas fa-print' : '' }}"style="color:black;font-size:13px"></i>
								                      <span class="btn-label " style="color:black;font-size:13px">
																				{{ $BTN_TEXT }}
								                      </span>
								                    </button>
								                  </td>
								                  <td >
																		@can('isAdminandManager')
																			<button  {{ $sub_row->CLOSE_STATUS == '1' ? 'onclick=rec_work(this)' : '' }} type="button"
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
		@include('machine.repair.repairclosemodalpd')
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
<script>
$(document).ready(function(){
		var url = "{{ route('pd.fetchdata') }}";
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

//******************************* End function ********************
	function rec_work(thisdata){
		$("#overlayinpage").fadeIn(300);　
		var repair_unid = $(thisdata).data('unid');
		var docno = $(thisdata).data('docno');
		var detail = $(thisdata).data('detail');
		var url = "{{ route('pd.result') }}";

		$.ajax({
				 type:'POST',
				 url: url,
				 data: {REPAIR_REQ_UNID : repair_unid},
				 datatype: 'json',
				 success:function(result){
					 $("#overlayinpage").fadeOut(300);
					 $("#WORK_STEP_RESULT").html(result.html);
					 $('#stepsave').html(result.footer);
					 $('#TITLE_DOCNO_SUB').html(docno);
					 $('#show-detail').html('อาการเสีย : '+detail);
					 $("#Result").modal('show');
					 $('#ConFirm').on('click',function(event){
				 		event.preventDefault();
						$("#overlay").fadeIn(300);
				 		var urlsub = "{{ route('pd.confirm') }}";
				 		var unid = $(this).attr('data-unid');
				 		var emp_code = $('#EMP_CODE').val();
				 		if (emp_code != "") {
				 			$.ajax({
				 					 type:'POST',
				 					 url: urlsub,
				 					 data: {REPAIR_REQ_UNID : unid,
				 			 		 				USER_PD_CODE    : emp_code},
				 					 datatype: 'json',
				 					 success:function(result){
				 						 $("#overlay").fadeOut(300);
										 if (result.pass) {
											 Swal.fire({
				 				 				  icon: 'success',
				 				 				  title: 'บันทึกสำเร็จ',
				 				 				  timer: 1500,
				 				 				}).then((Result)=>{
													$.ajax({
										 					 type:'POST',
										 					 url: url,
										 					 data: {REPAIR_REQ_UNID : repair_unid},
										 					 datatype: 'json',
										 					 success:function(result){
																 $("#WORK_STEP_RESULT").html(result.html);
																 $('#stepsave').html(result.footer);
															 }
														 });
												});
										 }else {
											 Swal.fire({
 								 				  icon: 'error',
 								 				  title: 'เกิดข้อผิดพลาด',
 								 				  timer: 1500,
 								 				});
										 }

				 					 }
				 				 });
				 		}else {
							$("#overlay").fadeOut(300);
				 			Swal.fire({
				 				  icon: 'error',
				 				  title: 'กรุณาใส่ชื่อผู้ตรวจสอบ',
				 				  timer: 1500,
				 				});
				 		}

				 	});
				 }
			 });
	}


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
