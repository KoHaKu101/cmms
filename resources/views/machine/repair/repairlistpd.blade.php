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
	$CHECK_URL = route('pd.repairlist');

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
																<option value="9" {{ $DOC_STATUS == "9" ? 'selected' : "" }}>ยังไม่ปิดเอกสาร</option>
																<option value="1" {{ $DOC_STATUS == "1" ? 'selected' : "" }}>ปิดเอกสารแล้ว</option>
															</select>
														<label class="text-white mx-1">ค้นหา : </label>
								              <div class="input-group mx-1">
								                <input  type="search" id="SEARCH_MACHINE"  name="SEARCH_MACHINE" class="form-control form-control-sm mt-1 col-lg-9" placeholder="ค้นหา........."
																value="{{ $SEARCH }}">
								                <div class="input-group-prepend">
								                  <button type="submit" class="btn btn-search pr-1 btn-xs	mt-1" id="BTN_SUBMIT">
								                    <i class="fa fa-search search-icon"></i>
								                  </button>
								                </div>
								              </div>
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
														<input type="radio"  class="selectgroup-input" onchange="styletable(1)" {{ Cookie::get('table_style_pd') == '1' ? 'checked' : (Cookie::get('table_style_pd') == '' ? 'checked' : '')}} name="styletable">
														<span class="selectgroup-button"><i class="fas fa-th-large"></i></span>
													</label>
													<label class="selectgroup-item"  >
														<input type="radio" class="selectgroup-input" onchange="styletable(2)" {{ Cookie::get('table_style_pd') == '2' ? 'checked' : ''}} name="styletable">
														<span class="selectgroup-button"><i class="fas fa-list-ol"></i></span>
													</label>
												</div>
											</div>
										</div>

										<div class="row" id="table_style" {{ Cookie::get('table_style_pd') == '1' ? '' : 'hidden'}} >
 		                  @foreach ($dataset as $key => $row)
 												@php
 													$BG_COLOR    		= $row->INSPECTION_CODE != '' ? 'bg-warning text-white' : 'bg-danger text-white';
 													$IMG_PRIORITY		= $row->PRIORITY == '9' ? '<img src="'.asset('assets/css/flame.png').'" class="mt--2" width="20px" height="20px">' : '';
 													$WORK_STATUS 		= $row->INSPECTION_CODE != '' ? $array_EMP[$row->INSPECTION_CODE] : 'รอรับงาน';
 													$TEXT_STATUS    = $row->PD_CHECK_STATUS == '1' ? 'จัดเก็บเอกสารเรียบร้อย' : ($row->CLOSE_STATUS == '1' ? 'ดำเนินการสำเร็จ' : (isset($row->INSPECTION_CODE) ? 'กำลังดำเนินการ' : 'รอรับงาน' ));
 													$IMG         	  = isset($array_IMG[$row->INSPECTION_CODE]) ? asset('image/emp/'.$array_IMG[$row->INSPECTION_CODE]) : asset('../assets/img/noemp.png');
 													$DATE_DIFF   	  = $row->REC_WORK_DATE != '1900-01-01 00:00:00.000'? 'รับเมื่อ:'.Carbon\Carbon::parse($row->REC_WORK_DATE)->diffForHumans() : 'แจ้งเมื่อ:'.Carbon\Carbon::parse($row->CREATE_TIME)->diffForHumans();
 													$HTML_STATUS    = '<div class="status" id="DATE_DIFF_'.$row->UNID.'">'.$DATE_DIFF.'</div>';
 													$HTML_BTN       = '';
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
														$HTML_BTN     = '<button class="btn  btn-primary  btn-sm"
	 																						onclick="ConFirmForm(this)"
	 																						data-unid="'.$row->UNID.'"
	 																						data-docno="'.$row->DOC_NO.'"
	 																						data-detail="'.$row->REPAIR_SUBSELECT_NAME.'">
	 																							CLOSE FORM
	 																						</button>';
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
										<div class="table-responsive" id="list_table" {{ Cookie::get('table_style_pd') == '2' ? '' : 'hidden'}} >
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
													.btn-pink{
														background: #FFA5B5;
														color: white;
													}
												</style>
								        <tbody id="result">
								          @foreach ($dataset as $key => $sub_row)
														@php
															$BTN_CONFIRM			= $sub_row->CLOSE_STATUS		== '1' ? "onclick=ConFirmForm(this)" : '';
															$BTN              = '<button class="btn btn-danger btn-sm btn-block my-1"
																										style="cursor:default">รอรับงาน</button>';
															if ($sub_row->PD_CHECK_STATUS == '1') {
																$BTN_COLOR_WORKER = 'btn-secondary';
																$BTN				= '<button onclick=pdfsaverepair("'.$sub_row->UNID.'") type="button"
																	 							class="btn btn-primary btn-block btn-sm my-1 text-left">
																	 		 					<span class="btn-label">
																								<i class="fas fa-clipboard-check mx-1"></i>
																	 			 					จัดเก็บเอกสารสำเร็จ
																	 		 					</span>
																	 	 					</button>';
															}elseif ($sub_row->CLOSE_STATUS == '1') {
																$BTN_COLOR_WORKER = 'btn-pink';
																$BTN				= '<button onclick=ConFirmForm(this) type="button"
																	 							data-unid="'.$sub_row->UNID.'"
																	 							data-docno="'.$sub_row->DOC_NO.'"
																	 							data-detail="'.$sub_row->REPAIR_SUBSELECT_NAME.'"
																	 							class="btn btn-primary btn-block btn-sm my-1 text-left">
																	 		 					<span class="btn-label">
																								<i class="fas fa-clipboard mx-1"></i>
																	 			 					ดำเนินการสำเร็จ
																	 		 					</span>
																	 	 					</button>';
															}elseif ($sub_row->INSPECTION_CODE != '') {
																$BTN				= '<button class="btn btn-warning btn-sm btn-block my-1 text-left"
																								 style="cursor:default">
																								 <i class="fas fa-wrench fa-lg mx-1"></i>
																								 '.$sub_row->INSPECTION_NAME_TH.'
																							 </button>';
															}
														@endphp
								             <tr>
															<td>{{ $dataset->firstItem() + $key }}</td>
															<td width="9%">{{ date('d-m-Y',strtotime($sub_row->DOC_DATE)) }}</td>
								              <td width="11%">{{ $sub_row->DOC_NO }}</td>
															<td width="4%">{{ $sub_row->MACHINE_LINE }}</td>
								              <td width="8%">{{ $sub_row->MACHINE_CODE }}</td>
								              <td>{{ $sub_row->MACHINE_NAME_TH }}</td>
															<td>{{ $sub_row->REPAIR_SUBSELECT_NAME }}</td>
						                  <td width='15%'>{!! $BTN !!}</td>
															<td width="9%">{{ date('d-m-Y') }}</td>
								             </tr>
								            @endforeach
								        </tbody>
								    </table>
								  	</div>
										<input type="hidden" id="PAGE" name="PAGE" value="{{$dataset->currentPage()}}">
									{{$dataset->appends(['SEARCH_MACHINE' => $SEARCH])->links('pagination.default')}}
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
<script type="module" src="{{ asset('assets/js/js.cookie.min.js') }}"></script>
{{-- Cookie --}}
<script>
function styletable(table_style){
	if (table_style == '1') {
		$('#table_style').attr('hidden',false);
		$('#list_table').attr('hidden',true);
		 setcookie('table_style_pd','1');
	}else {
		$('#table_style').attr('hidden',true);
		$('#list_table').attr('hidden',false);
		 setcookie('table_style_pd','2');
	}
}
	var cookie_tablestyle = "{{Cookie::get('table_style_pd')}}";
	if (cookie_tablestyle == '') {
		$('#table_style').attr('hidden',false);
		$('#list_table').attr('hidden',true);
	}
	function setcookie(name,value){
		var urlcookie = "{{ route('cookie.set') }}";
		var data 			= {"_token": "{{ csrf_token() }}",NAME : name,VALUE : value}
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
{{-- function loop --}}
<script>
	var page = $('#PAGE').val();
	var url = "{{ route('pd.fetchdata') }}?page="+page;
	var data = $('#FRM_SEARCH').serialize();
	var url_confirmpd = "{{ route('repair.readnotify.pd')}}";
</script>
<script src="{{ asset('assets/js/useinproject/pdlooppage.js') }}"></script>
{{-- function common  --}}
<script>
	function ConFirmForm(thisdata){
		$("#overlayinpage").fadeIn(300);　
		var repair_unid = $(thisdata).data('unid');
		var docno 			= $(thisdata).data('docno');
		var detail 			= $(thisdata).data('detail');
		var url 				= "{{ route('pd.result') }}";
		$.ajax({
				 type:'POST',
				 url: url,
				 data: {REPAIR_REQ_UNID : repair_unid},
				 datatype: 'json',
				 success:function(result){
					 $("#overlayinpage").fadeOut(300);
					 $("#WORK_STEP_RESULT").html(result.html);
					 $('#stepsave').html(result.footer);
					 $('#TITLE_DOCNO').html(docno);
					 $('#EMP_CODE').select2({
						 width: '60%',
					 });
					 $.fn.modal.Constructor.prototype._enforceFocus = function() {};
					 $('#show-detail').html('อาการเสีย : '+detail);
					 $('#Result').modal({backdrop: 'static', keyboard: false});

					 $("#Result").modal('show');
					 $('#ConFirm').on('click',function(){
						 var unid 				= $(this).attr('data-unid');
						 SaveFrom(repair_unid,unid);
				 		});
				 },
				 error: function (request, status, error) {
					 Swal.fire({
							icon: 'error',
							title: 'เกิดข้อผิดพลาดกรุณาลองใหม่',
							timer: 1500,
						});
						$("#overlayinpage").fadeOut(300);
    			}
			 });
	}
	function Renew(thisdata){
		var unid = $(thisdata).data('unid');

		Swal.fire({
					title: 'กรุณาใส่หมายเหตุ',
					input: 'text',
					inputAttributes: {
						autocapitalize: 'off'
					},
					showDenyButton: true,
					showCancelButton: false,
					confirmButtonText: `ยืนยัน`,
					denyButtonText: `ยกเลิก`,
					preConfirm: (text) => {
						return fetch(`/machine/repair/renewconfirm?note=${text}&unid=`+unid)
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
									return data.pass
								}
							})
							.catch(error => {
								Swal.showValidationMessage(
									'กรุณาใส่ข้อหมายเหตุ'
								)
							})
						},
						allowOutsideClick: () => !Swal.isLoading()
				}).then((result) => {
					if (result.isConfirmed) {
						var url = "{{ route('pd.renew') }}";
						$("#overlay").fadeIn(300);
						$.ajax({
							type:'POST',
							url:url,
							data:{REPAIR_UNID:unid},
							success:function(result){
								$("#overlay").fadeIn(300);
								Swal.fire({
									 icon: 'success',
									 title: 'บันทึกสำเร็จ',
									 timer: 1500,
								 }).then((Result)=>{
									 $('#Result').modal('hide');
									 window.location.reload();
								 });
							}
						})
					}
				})

	}
	function SaveFrom(repair_unid,unid){
		var repair_unid = repair_unid;
		 $("#overlay").fadeIn(300);
		 var url = "{{ route('pd.confirm') }}";
		 var unid = unid;
		 var emp_code = $('#EMP_CODE').val();
		 if (emp_code != "") {
			 $.ajax({
						type:'POST',
						url: url,
						data: {REPAIR_REQ_UNID : unid,
									 USER_PD_CODE    : emp_code},
						datatype: 'json',
						success:function(result){
							$("#overlay").fadeOut(300);
							if (result.pass) {
								loopdata_table();
								Swal.fire({
									 icon: 'success',
									 title: 'บันทึกสำเร็จ',
									 timer: 1500,
								 }).then((Result)=>{
									 var url 	= "{{ route('pd.result') }}";
									 $.ajax({
												type:'POST',
												url: url,
												data: {REPAIR_REQ_UNID : repair_unid},
												datatype: 'json',
												success:function(result){
													$("#WORK_STEP_RESULT").html(result.html);
													$('#stepsave').html(result.footer);
												},
												error: function (request, status, error) {
								 					 Swal.fire({
								 							icon: 'error',
								 							title: 'เกิดข้อผิดพลาดกรุณาลองใหม่',
								 							timer: 1500,
								 						});
								 						$("#overlayinpage").fadeOut(300);
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
					 title: 'กรุณาเลือกผู้ตรวจสอบ',
					 timer: 1500,
				 });
		 }
	}
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
<script>
	function DELETEREPARI(thisdata){
		var unid = $(thisdata).data('unid');
		var url  = "/machine/repair/delete?UNID="+unid;
		window.location.href = url;
	}
</script>
@stop
{{-- ปิดส่วนjava --}}
