@extends('masterlayout.masterlayout')
@section('tittle','แจ้งซ่อม')
@section('meta')
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link href="{{asset('assets/css/select2.min.css')}}" rel="stylesheet" />
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
										<form action="{{ route('repair.list') }}" method="POST" enctype="multipart/form-data">
											@method('GET')
											@csrf
								        <div class="row ">
													<div class="col-md-12 col-lg-10 form-inline my-1">

															<label class="text-white mx-2">ปี : </label>
															<select class="form-control form-control-sm mt-1 mx-1" id="YEAR" name="YEAR" >
																<option value="0">ทั้งหมด</option>
																@for ($y=date('Y')-2; $y < date('Y')+1; $y++)
																	<option value="{{$y}}" {{ $YEAR == $y ?'selected' : ''}}>{{$y}}</option>
																@endfor
															</select>
															<label class="text-white mx-2">เดือน : </label>
															<select class="form-control form-control-sm mt-1 mx-1" id="MONTH" name="MONTH" >
																<option value="0">ทั้งหมด</option>
																@for ($m=1; $m < 13; $m++)
																	<option value="{{$m}}" {{ $MONTH == $m ?'selected' : ''}}>{{$months[$m]}}</option>
																@endfor
															</select>
															<label class="text-white mx-2">Line : </label>
															<select class="form-control form-control-sm mt-1 mx-1" id="LINE"name='LINE' >
																 <option value="">ทั้งหมด</option>
																@foreach ($LINE as $index => $row_line)
																	<option value="{{ $row_line->LINE_CODE }}"
																		{{ $MACHINE_LINE == $row_line->LINE_CODE ? 'selected' : '' }}>{{ $row_line->LINE_NAME }}</option>
																@endforeach
															</select>
															<label class="text-white mx-2">เอกสาร : </label>
															<select class="form-control form-control-sm mt-1 mx-1" id="CLOSE_STATUS" name="CLOSE_STATUS">
																<option value="0">ทั้งหมด</option>
																<option value="9" {{ $DOC_STATUS == "9" ? 'selected' : "" }}>กำลังดำเนินการ</option>
																<option value="1" {{ $DOC_STATUS == "1" ? 'selected' : "" }}>ปิดเอกสาร</option>
															</select>
														<label class="text-white mx-1">ค้นหา : </label>
								              <div class="input-group mx-1">
								                <input  type="search" id="SEARCH"  name="SEARCH" class="form-control form-control-sm mt-1" placeholder="ค้นหา........."
																value="{{ $SEARCH }}">
								                <div class="input-group-prepend">
								                  <button type="submit" class="btn btn-search pr-1 btn-xs	mt-1" id="BTN_SUBMIT">
								                    <i class="fa fa-search search-icon"></i>
								                  </button>
								                </div>
								              </div>
														</div>
														<div class="col-md-12 col-lg-2 text-right">
														<a href="{{ route('repair.repairsearch') }}"class="btn btn-warning  btn-xs mt-2">
															<span class="fas fa-file fa-lg">	แจ้งซ่อม	</span>
														</a>
													</div>
								        </div>
											</form>
								  </div>
								  <div id="result"class="card-body">
								    <div class="table-responsive" id="dynamic_content">
								      <table class="display table table-striped table-hover">
								        <thead class="thead-light">
								          <tr>
														<th >วันที่เอกสาร</th>
								            <th >เลขที่เอกสาร </th>
														<th>Line</th>
								            <th>รหัสเครื่อง </th>
								            <th>ชื่อเครื่องจักร</th>
														<th>อาการ</th>
								            <th>สถานะเครื่องจักร</th>
								            <th >สถานะงาน</th>
														<th >ผู้รับงาน</th>
														<th >วันที่รับงาน</th>
								          </tr>
								        </thead>

								        <tbody>
								          @foreach ($dataset as $key => $row)
								            <tr>
															<td >{{ date('d-m-Y',strtotime($row->DOC_DATE)).' '.date('H:i',strtotime($row->REPAIR_REQ_TIME)) }}</td>
								              <td >{{ $row->DOC_NO }}
								              </td>
															<td >  				{{ $row->MACHINE_LINE }}	    </td>
								              <td >  				{{ $row->MACHINE_CODE }}		     </td>
								              <td >  				{{ $row->MACHINE_NAME }}		    </td>
															<td >  				{{ $row->REPAIR_SUBSELECT_NAME }}		    </td>
								              <td >  				{{ $row->MACHINE_STATUS == '1' ? 'หยุดทำงาน' : 'ทำงาน'}}	    </td>
								                @if ($row->CLOSE_STATUS ===  '9')
								                  <td >
								                    <button type="button"class="btn btn-success btn-block btn-sm my-1 ">
								                      <span class="btn-label text-center" style="color:black">
								                        รอรับงาน
								                      </span>
								                    </button>
								                  </td>
								                  <td >
																		@can('isAdminandManager')
																			<button onclick="rec_work(this)" type="button"
																			data-unid="{{ $row->UNID }}"
																			data-docno="{{ $row->DOC_NO }}"
																			data-detail="{{ $row->REPAIR_SUBSELECT_NAME }}"
																			href="#"
																			class="btn btn-danger btn-block btn-sm my-1"
																		 >
																			 <span class="btn-label">
																				 <i class="fas fa-clipboard-check mx-1"></i>สุบรรณ
																			 </span>
																		 </button>
																		@else
																		@endcan
								                @endif

																<td >{{ date('d-m-Y H:i') }}</td>
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

@stop
{{-- ปิดส่วนเนื้อหาและส่วนท้า --}}

{{-- ส่วนjava --}}
@section('javascript')
<script src="{{ asset('assets/js/ajax/ajax-csrf.js') }}"></script>
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script>

//************************* array ****************************
	var array_emp_code = [];
	var sparepart_total = {};
	var arr = [];
//************************* array ****************************
//******************************* function ********************
function loop_tabel_worker(array_emp_code){
	var url = "{{ route('repair.addtableworker') }}";
	$.ajax({
			 type:'POST',
			 url: url,
			 datatype: 'json',
			 data: {
				 "_token": "{{ csrf_token() }}",
				 "EMP_CODE" : array_emp_code,
			 } ,
			 success:function(data){
				 $('#table_worker').html(data.html);
			 }
		 });
};
function loop_tabel_sparepart(unid,total){
	arr.push({unid:unid,total:total});
	$.each(arr,function(key, value){
		sparepart_total[value.unid] = value.total;
	});
	var url = "{{ route('repair.addsparepart') }}";
	$.ajax({
			 type:'POST',
			 url: url,
			 datatype: 'json',
			 data: {TOTAL_SPAREPART : sparepart_total},
			 success:function(data){
				 $('#table_sparepart').html(data.html);
			 }
		 });
};
function input_totals_parepart(unid){
	Swal.fire({
			title: 'ใส่จำนวนเบิก',
			input: 'number',
		}).then((result) => {
			if (result.value != "" && result.value != null) {
				var total = result.value;
				loop_tabel_sparepart(unid,total);
			}
		});
}

//******************************* End function ********************
	function rec_work(thisdata){
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
					 $('#show_detail').html(data.html_detail);
					 $('#select_recworker').html(data.html_select);
					 $('.REC_WORKER_NAME').select2({
						 placeholder: "กรุณาเลือก",
						 width:'100%',
						});
						$('#TITLE_DOCNO').html('เลขที่เอกสาร : '+docno);
						$('#RepairForm').modal({backdrop: 'static', keyboard: false});
						$.fn.modal.Constructor.prototype._enforceFocus = function() {};
						$("#RepairForm").modal("show");
				 }
			 });
	}
	$('#closestep_1').on('click',function(){
		var docno = $('#TITLE_DOCNO').html();
		console.log();
		var detail = $('#DETAIL_REPAIR').val();
		$('#TITLE_DOCNO_SUB').html(docno);
		$("#show-detail").html('อาการเสีย : '+detail);
		$('#CloseForm').modal({backdrop: 'static', keyboard: false});
		$('#RepairForm').modal('hide');
		$('#CloseForm').modal('show');
	});
	function previous_step(step_number){
		var step_number_up = Number(step_number) + 1;
		var work_step_previous = 'WORK_STEP_'+step_number;
		var work_step_simple   = 'WORK_STEP_'+step_number_up;
		$('#step'+step_number).addClass('text-primary fw-bold');
		$('#step'+step_number_up).removeClass('text-primary fw-bold');
		$('#'+work_step_simple).removeClass('active show');
		$('#'+work_step_previous).addClass('active show');
	};
	function nextstep(step_number){
		var step_number_down = Number(step_number) - 1;
		var work_step_next = 'WORK_STEP_'+step_number;
		var work_step_simple   = 'WORK_STEP_'+step_number_up;
		$('#step'+step_number).addClass('text-primary fw-bold');
		$('#step'+step_number_down).removeClass('text-primary fw-bold');
		$('#'+work_step_simple).removeClass('active show');
		$('#'+work_step_next).addClass('active show');
	};
	function previous_worker(){
		$('#work_in').attr('hidden',true);
		$('#work_out').attr('hidden',true);
		$('#select_typeworker').attr('hidden',false);
		$("#previous_worker").attr('onclick','previous_step(1)');
	}
	 function type_worker(type_worker){
			var check_type = type_worker;
			if (check_type == '1') {
				$('#work_in').attr('hidden',false);
				$("#previous_worker").attr('onclick','previous_worker()');
			}else {
				$('#work_out').attr('hidden',false);
				$("#previous_worker").attr('onclick','previous_worker()');
			}
			$('#select_typeworker').attr('hidden',true);
		}
	 $('#step_cancel').on('click',function(){
			jQuery(document).off('focusin.modal');
			Swal.fire({
				title: 'สาเหตุการยกเลิก',
				input: 'text',
			}).then((result) => {
					 $('#CloseForm').modal('hide');
			});
		});
	 $('#add_worker').on('click',function(event){
			 event.preventDefault();
			 var emp_code = $('#WORKER_SELECT').val();
			 $('#WORKER_SELECT option[value="'+emp_code+'"]').detach();
			 if (emp_code != "" && emp_code != null) {
				 array_emp_code.push(emp_code);
				 loop_tabel_worker(array_emp_code);
			 }
		 });
	 function deleteworker(thisdata){
			var empcode = $(thisdata).data('empcode');
			var empname = $(thisdata).data('empname');
			var data = {
				id: empcode,
				text: empcode+' '+empname
		 };
			 for( var i = 0; i < array_emp_code.length; i++){
					 if ( array_emp_code[i] == empcode) {
						 console.log(i);
							 array_emp_code.splice(i, 1);
					 }
			 }
			var newOption = new Option(data.text, data.id, false, false);
			$('#WORKER_SELECT').append(newOption).trigger('change');
			loop_tabel_worker(array_emp_code);
		 }
	 $('#SPAREPART').on('change',function(){
			var unid = $('#SPAREPART').val();
			var sparepartcode = $('#'+unid).data('sparepartcode');
			var sparepartname = $('#'+unid).data('sparepartname');
			var sparepartsize = $('#'+unid).data('sparepartsize');
			var sparepartmodel = $('#'+unid).data('sparepartmodel');
			$('#SPAREPART_CODE').val("รหัส : "+sparepartcode);
			$('#SPAREPART_NAME').val("ชื่อ : "+sparepartname);
			$('#SPAREPART_SIZE').val("เบอร์ : "+sparepartsize);
			$('#SPAREPARTM_ODEL').val("ขนาด : "+sparepartmodel);
		 });
	 function add_sparepart(typeadd){
		 //1 ตัดสต็อก  2ไม่ตัดสต็อก
		 $(document).off('focusin.modal');
		 var unid = $('#SPAREPART').val();
	   input_totals_parepart(unid);
	 };
	 function edittotal(thisdata){
		 var unid = $(thisdata).data('unid');
		 input_totals_parepart(unid);
	 }
	 function removesparepart(thisdata){
		 var unid = $(thisdata).data('unid');
		 for( var i = 0; i < arr.length; i++){
				 if ( arr[i].unid == unid) {
						 arr.splice(i, 1);
				 }
		 }
		 loop_tabel_sparepart(unid);
	 }


 function btn_closeform(){
	 $('#CloseForm').modal({backdrop: 'static', keyboard: false});
	 $('#CloseForm').modal('show');
 }

</script>

<script type="text/javascript">
	function changesubmit(){
		$('#BTN_SUBMIT').click();
	}
	function pdfrepair(m){
		console.log(m);
		var unid = (m);
		window.open('/machine/repair/pdf/'+unid,'Repairprint','width=1000,height=1000,resizable=yes,top=100,left=100,menubar=yes,toolbar=yes,scroll=yes');
	}


</script>
@stop
{{-- ปิดส่วนjava --}}
