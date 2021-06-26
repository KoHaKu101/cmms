@extends('masterlayout.masterlayout')
@section('tittle','homepage')
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
		.table-responsive-sf{
			display: block;
			max-width: 900px;
			overflow-y: auto;
		}
	</style>

	  <div class="content">
      <div class="page-inner">
				{{-- <div class="py-12"> --}}
	        <div class="container mt--4">
						<div class="row">
							<div class="col-md-12">
								<div class="card "></div>
								<div class="row">
									<div class="col-md-6 col-lg-9 ">
										<div class="card">
											<div class="card-header bg-primary">
												<h4 class="ml-3 mt-2" style="color:white;" ><i class="fas fa-cubes fa-lg mr-1"></i>	รายการอะไหล่
													<button type="button" class="btn btn-warning float-right btn-sm btn-new">
														<i class="fas fa-file" style="color:white;font-size:14px"> New</i>
													</button>
												</h4>
											</div>
											<div class="card-body">

													<div class="table-responsive-sf" >
														<table class="table  table-bordered table-head-bg-info table-bordered-bd-info">
															<thead>
																<tr>
																	<th >#</th>
																	<th >Code</th>
																	<th >Name</th>
																	<th >Model</th>
																	<th >Size</th>
																	<th >Safety Stock</th>
																	<th >Price</th>
																	<th >Action</th>
																	<th >Machine</th>
																</tr>
															</thead>
															<tbody>
																<style>
																.bg-statusoff{
																	    background-color: #ff7d84!important;
																}
																</style>
																@foreach ($DATA_SPAREPART as $key => $row)
																	@php
																		$BG = $row->STATUS == '9' ? '' : 'bg-statusoff text-white';
																	@endphp
																	<tr class="{{ $BG }}">
																		<td class="text-nowrap">{{ $key+1 }}</td>
																		<td class="text-nowrap"><strong>{{$row->SPAREPART_CODE}}</strong></td>
																		<td class="text-nowrap">{{$row->SPAREPART_NAME}}</td>
																		<td class="text-nowrap">{{$row->SPAREPART_MODEL}}</td>
																		<td class="text-nowrap">{{$row->SPAREPART_SIZE}}</td>
																		<td class="text-nowrap">{{$row->STOCK_MIN}}</td>
																		<td class="text-nowrap">{{number_format($row->SPAREPART_COST)}}</td>
																		<td class="text-nowrap">
																			<button type="button" class="btn btn-primary btn-sm mx-1 my-1 btn-edit-spare"
																			data-spcode="{{$row->SPAREPART_CODE}}"
																			data-spname="{{$row->SPAREPART_NAME}}"
																			data-spmodel="{{$row->SPAREPART_MODEL}}"
																			data-spsize="{{$row->SPAREPART_SIZE}}"
																			data-spstock="{{$row->STOCK_MIN}}"
																			data-spstatus="{{$row->STATUS}}"
																			data-spremark="{{ $row->SPAREPART_REMARK }}"
																			data-spcost="{{ $row->SPAREPART_COST }}"
																			data-spunit="{{ $row->UNIT }}"
																			>
																			<i class="fas fa-edit fa-lg"></i></button>
																			<button type="button" class="btn btn-danger btn-sm mx-1 my-1 btn-delete-spare"
																			data-spcode="{{$row->SPAREPART_CODE}}"
																			data-spunid="{{$row->UNID}}"
																			>
																			<i class="fas fa-trash fa-lg"></i></button>
																		</td>
																		<td class="text-nowrap">
																			<button type="button" class="btn btn-primary btn-sm mx-1 my-1 btn-machine"
																			data-spcode="{{$row->SPAREPART_CODE}}"
																			data-spunid="{{$row->UNID}}"
																			>
																			<i class="fas fa-plus fa-lg"></i></button>
																				<a href="{{ route('SparPart.List',$row->UNID) }}" class="btn {{ isset($DATA_MACHINESPAREPART_FIRST->UNID) ? $DATA_MACHINESPAREPART_FIRST->UNID == $row->UNID ? 'btn-secondary' : 'btn-info' : 'btn-info' }} btn-sm mx-1 my-1">
																				<i class="fas fa-eye fa-lg"></i></a>
																		</td>
																	</tr>
																@endforeach

															</tbody>

														</table>
														{{ $DATA_SPAREPART->appends(['machinepage' => $DATA_MACHINESPAREPART->currentPage()])->links('pagination.default',['paginator' => $DATA_SPAREPART,
																			 'link_limit' => $DATA_SPAREPART->perPage()]) }}
													</div>
											</div>
										</div>
									</div>
									<div class="col-md-6 col-lg-3">
										<div class="card">
											<div class="card-header bg-primary">
												<h4 class="ml-3 mt-2" style="color:white;" ><i class="fas fa-cubes fa-lg mr-1"></i>	Machine

												</h4>
											</div>

											<div class="card-body">
												<div class="row">
													<div class="col-md-12">
														<table class="table table-bordered table-head-bg-info table-bordered-bd-info">
															<thead>
																<tr>
																	<th scope="col">#</th>
																	<th colspan="1" width="130px">{{ isset($DATA_MACHINESPAREPART_FIRST->SPAREPART_CODE) ? $DATA_MACHINESPAREPART_FIRST->SPAREPART_CODE : ''}}</th>
																	<th ></th>
															</thead>
															<tbody>
																@foreach ($DATA_MACHINESPAREPART as $index => $subrow)
																<tr>
																	<td>{{ $index+1 }}</td>
																	<td>{{$subrow->MACHINE_CODE}}</td>
																	<td>
																		<button
																			type="button" class="btn btn-danger btn-sm btn-block my-1"
																		  onclick="deletemachine(this)"
																			data-machine_unid="{{ $subrow->MACHINE_UNID}}"
																			data-machine_code="{{ $subrow->MACHINE_CODE}}"
																			data-sparepart_unid = "{{ $subrow->SPAREPART_UNID}}">
																			<i class="fas fa-trash fa-lg" ></i>
																		</button>
																	</td>
																</tr>
																@endforeach
															</tbody>

														</table>
														{{ $DATA_MACHINESPAREPART->appends( ['sparepartpage' => $DATA_SPAREPART->currentPage()])
																										 ->links() }}
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				{{-- </div> --}}
      </div>
		</div>

		@include('machine.sparepart.modaladdsparepart')
		@include('machine.sparepart.modaladdmachinesparepart')
		{{-- เพิ่มเครื่องจักร --}}





@stop
{{-- ปิดส่วนเนื้อหาและส่วนท้า --}}

{{-- ส่วนjava --}}
@section('javascript')
	<script src={{ asset('assets/js/ajax/ajax-csrf.js') }}></script>
	<script src="{{ asset('../../assets/js/plugin/datatables/datatables.min.js')}}"></script>
<script>

function savemachine(machine_unid,spartpart_unid,spartpart_code,period,datestart,sparepart_qty){
	var url = "{{ route('SparPart.SaveMachine') }}";
	var data = {MACHINE_UNID : machine_unid,
							PERIOD : period,
							DATESTART : datestart,
							SPARTPART_UNID : spartpart_unid,
							SPARTPART_CODE : spartpart_code,
							SPAREPART_QTY : sparepart_qty,};

	$.ajax({
		type: "GET",
		url: url,
		data: data,
		dataType: 'JSON',
		success: function (data) {

			if (data.res == false) {
			Swal.fire({
			  title: 'กรุณาระบุระยะเวลา แผน',
			  text: "ใน ตั้งค่า -> CMMS",
			  icon: 'error',
			  showCancelButton: false,
			  confirmButtonColor: '#3085d6',
			  confirmButtonText: 'OK!'
			}).then((result) => {
			  if (result.isConfirmed) {
					location.reload();
						}
			  });
			}
		}
	});
};
$(document).ready(function() {
	$('.btn-new').on('click',function(){
		$("#FRM_SPAREPART").attr("action", "/machine/spart/save");
		$('#modal-sparepart').modal('show');
	});
	$('.btn-edit-spare').on('click',function(){
		var	spcode		= $(this).data('spcode');
		var spname		= $(this).data('spname');
		var spmodel		= $(this).data('spmodel');
		var spsize		= $(this).data('spsize');
		var spstock		= $(this).data('spstock');
		var spremark		= $(this).data('spremark');
		var spstatus		= $(this).data('spstatus');
		var spcost		= $(this).data('spcost');
		var spunit		= $(this).data('spunit');
		var checkstatus =  spstatus == '9' ? true : false ;
		$('#SPAREPART_CODE').val(spcode);
		$('#STOCK_MIN').val(spstock);
		$('#SPAREPART_NAME').val(spname);
		$('#SPAREPART_MODEL').val(spmodel);
		$('#SPAREPART_SIZE').val(spsize);
		$('#SPAREPART_REMARK').val(spremark);
		$('#SPAREPART_COST').val(spcost);
		$('#UNIT').val(spunit);
		$('#STATUS').prop('checked',checkstatus);
		$("#FRM_SPAREPART").attr("action", "/machine/spart/update");
		if (spcode != '') {
			$('#modal-sparepart').modal('show');
		}

	});
	$('.btn-delete-spare').on('click',function(){
			var	spcode		= $(this).data('spcode');
			var	spunid		= $(this).data('spunid');
		Swal.fire({
				title: 'คุณต้องการลบอะไหล่ ',
				text: 'Code : '+spcode+' นี้ มั้ย?',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'ใช่',
				cancelButtonText: 'ไม่'
		}).then(function(result) {
			if (result.isConfirmed) {
				$.ajax({
					type: "GET",
					url: '{{route('SparPart.Delete')}}',
					data: { SPAREPART_UNID : spunid} ,
					dataType: 'JSON',
					success: function (data) {
						if (data.res) {
							Swal.fire({
									title: 'ลบสำเร็จ',
									icon: 'success',
									showCancelButton: false,
									showConfirmButton: false,
									timer: 1000
							}).then(() => {
								location.reload();
								});
						}else{
							Swal.fire({
									title: 'รายการนี้กำลังใช้งาน',
									text: 'ไม่สามารถลบได้!',
									icon: 'error',
									confirmButtonColor: '#3085d6',
									confirmButtonText: 'OK'
							});
						}
					}
				});
			};
		});
	});
	$('#modal-sparepart').on('hidden.bs.modal', function(){
		$('#FRM_SPAREPART')[0].reset();
	});
	$('#modal-machine').on('hidden.bs.modal', function(){
		$('.frm-reset').trigger('reset');
		location.reload();
	});

 });

 $('.btn-machine').on('click',function(){
	 var	spcode		= $(this).data('spcode');
	 var spunid		= $(this).data('spunid');
	 $('#SPARPART_CODE').val(spcode);
	 $('#SPARPART_UNID').val(spunid);
	 var url = "{{ route('SparPart.GetMachineList').'/' }}"+spunid;
	 $.ajax({
		 type: "GET",
		 url: url,
		 success: function (data) {
			 $('.data-machine').html(data.res);
			 var table =	$('#machine_list').DataTable({
					 "pageLength": 10,
					 "bLengthChange": false,
					 "bFilter": true,
					 "bInfo": false,
					 "bAutoWidth": false,
					 columnDefs: [
					 { orderable: false, targets:[0,1,2,3,4] }
				 ]
				 });

				 $("#machine_list").on("click", "input[type='checkbox']", function(){
    	 	var tr = $(this)[0].closest("tr");

				var checkbox_val = $('#MACHINE_UNID_'+tr.id)[0].checked;
				var machine_unid = tr.id;
				var period = $("#PERIOD_"+tr.id).val();
				var datestart = $("#DATESTART_"+tr.id).val();
				var sparepart_qty = $("#SPAREPART_QTY_"+tr.id).val();
				var spartpart_unid = $("#SPARPART_UNID").val();
				var spartpart_code = $("#SPARPART_CODE").val();
				$('#MACHINE_UNID_'+machine_unid).attr('disabled',true);
				if (checkbox_val) {

					if (sparepart_qty == 0 || sparepart_qty < 0 ) {
						$("#SPAREPART_QTY_"+machine_unid).val(1);
						sparepart_qty = 1 ;
					}
					savemachine(machine_unid,spartpart_unid,spartpart_code,period,datestart,sparepart_qty);
				}
					});
		 }
	 });

	 if (spcode != '') {
		 $('#modal-machine').modal('show');
		 $('#MODAL_TITLE').html('SparPart Code : '+spcode);
	 }

 });
 function addmachinetosparepart(unid){
		var unid = unid;
		var check = $('#MACHINE_UNID_'+unid)[0].checked;
		var machine_unid = unid;
		var period = $("#PERIOD_"+unid).val();
		var datestart = $("#DATESTART_"+unid).val();
		var sparepart_qty = $("#SPAREPART_QTY_"+unid).val();
		var spartpart_unid = $("#SPARPART_UNID").val();
		var spartpart_code = $("#SPARPART_CODE").val();
		if (check) {
			if (sparepart_qty == 0 || sparepart_qty < 0 ) {
				$("#SPAREPART_QTY_"+unid).val(1);
				sparepart_qty = 1 ;
			}
			savemachine(machine_unid,spartpart_unid,spartpart_code,period,datestart,sparepart_qty);
		}

 }

 function deletemachine(data_machine){
	 var machine_unid =	$(data_machine).data('machine_unid');
	 var machine_code =	$(data_machine).data('machine_code');
	 var sparepart_unid =	$(data_machine).data('sparepart_unid');
	 var url = '{{route('SparPart.DeleteMachine')}}/'+machine_unid+'/'+sparepart_unid;

	 Swal.fire({
			 title: 'คุณต้องการลบรายการ',
			 text: 'Machine Code : '+machine_code+' นี้ มั้ย?',
			 icon: 'warning',
			 showCancelButton: true,
			 confirmButtonColor: '#3085d6',
			 cancelButtonColor: '#d33',
			 confirmButtonText: 'ใช่',
			 cancelButtonText: 'ไม่'
	 }).then(function(result) {
		 if (result.isConfirmed) {
			 $.ajax({
				 type: "GET",
				 url: url,
				 success: function (data) {
					 if (data.res) {
						 Swal.fire({
								 title: 'ลบสำเร็จ',
								 icon: 'success',
								 showCancelButton: false,
								 showConfirmButton: false,
								 timer: 1000
						 }).then(() => {
							 location.reload();
							 });
					 }else{
						 Swal.fire({
								 title: 'เกิดข้อผิลพลาด',
								 text: 'ไม่สามารถลบได้!',
								 icon: 'error',
								 confirmButtonColor: '#3085d6',
								 confirmButtonText: 'OK'
						 });
					 }
				 }
			 });
		 };
	 });

 }
</script>

@stop
{{-- ปิดส่วนjava --}}
