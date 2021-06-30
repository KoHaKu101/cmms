@extends('masterlayout.masterlayout')
@section('tittle','homepage')
@section('css')
{{-- <link rel="stylesheet" href="{{asset('assets/css/bulma.min.css')}}"> --}}
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


				<div class="py-4">
	        <div class="container mt-2">
						<div class="row">
							<div class="col-md-12">
								<div class="card ">
								  <div class="card-header bg-primary  ">
										<div class="row">
											<div class="col-md-2 text-white">
												<h4 class="my-2">ใบประวัติเครื่องจักร</h4>
											</div>
											<div class="col-md-6 form-inline">
												<lable class="text-white"> ประเภท </lable>
												<select class="form-control form-control-sm mx-2">
													<option>กรุณาเลือก</option>
													<option>Preventive</option>
													<option>Predictive</option>
													<option>Repair</option>
												</select>
												<lable class="text-white"> ปี </lable>
												<select class="form-control form-control-sm mx-2">
													@for ($YEAR= date('Y')-2; $YEAR < date('Y')+2; $YEAR++)
														<option {{$YEAR == date('Y') ? 'selected' : '' }}>{{$YEAR}}</option>
													@endfor
												</select>
												<lable class="text-white"> เดือน </lable>
												<select class="form-control form-control-sm mx-2">
													@for ($i=1; $i < 13; $i++)
														<option {{$i == date('n') ? 'selected' : ''}}>{{ $i }}</option>
													@endfor
												</select>
											</div>
											<div class="col-md-4 form-inline">
												<label class="text-white">ค้นหา</label>
												<div class="input-group mt-1 mx-2">
								            <input type="search" id="SEARCH" name="SEARCH" class="form-control form-control-sm " value="">
								            <div class="input-group-prepend">
								              <button type="submit" class="btn btn-search pr-1 btn-xs	SEARCH">
								                <i class="fa fa-search search-icon"></i>
								              </button>
								            </div>
								          </div>
											</div>
										</div>
								  </div>
									<div class="card-body">
										<div class="row">
											<div class="col-md-12">

												<table class="table table-sm table-bordered table-head-bg-info table-bordered-bd-info">
													{{-- <thead>

													</thead> --}}
													<style>
													.table>tbody>tr>td, .table>tbody>tr>th {
													  font-size: 0.75rem;
													}
													</style>
													<tbody>
														@foreach ($DATA_REPAIR_HEADER as $key => $row)
																<tr>
																	<td class="bg-info text-white " colspan="8" style="font-size:18px">MC-CODE : {{ $row->MACHINE_CODE }} </td>
																	<td class="bg-info text-white "><button type="button" class="btn btn-sm btn-warning btn-block  my-1"><i class="fas fa-print" style="font-size:15px"></i></button></td>
																</tr>
																<tr class="bg-secondary text-white">
																	<td scope="col">#</td>
																	<td scope="col">วันที่แจ้ง</td>
																	<td scope="col">เอกสาร</td>
																	<td scope="col">อาการเสีย</td>
																	<td scope="col">วิธีการแก้ไข</td>
																	<td scope="col">อะไหล่</td>
																	<td scope="col">วันที่ซ่อม</td>
																	<td scope="col">DownTime</td>
																	{{-- <td scope="col">อะไหล่</td> --}}
																	<td scope="col">ราคา</td>
																</tr>
																@php
																	$i = 1 ;
																	$i_sub = 1 ;
																@endphp
															@foreach ($DATA_REPAIR->where('MACHINE_UNID','=',$row->MACHINE_UNID) as $index => $sub_row)
																<tr>
																	<td >{{$i++}}</td>
																	<td >{{date('d-m-Y',strtotime($sub_row->DOC_DATE))}}</td>
																	<td >{{$sub_row->MACHINE_REPORT_NO}}</td>
																	<td style="width:20%">{{ $sub_row->REPAIR_SUBSELECT_NAME }}</td>
																	<td style="width:20%">{{ $sub_row->REPAIR_DETAIL }}</td>
																	<td style="width:20%">

																		@foreach ($DATA_SPAREPART->where('REPAIR_REQ_UNID','=',$sub_row->UNID) as $key => $subsub_row)
																			{{ $i_sub++.'.'.$subsub_row->SPAREPART_NAME }} <br>
																		@endforeach
																	</td>
																	<td >{{ date('d-m-Y',strtotime($sub_row->WORKERIN_START_DATE)) }}</td>
																	<td class="text-right" >{{ $sub_row->DOWNTIME }} นาที</td>
																	{{-- <td>อะไหล่</td> --}}
																	<td >{{ number_format($sub_row->TOTAL_COST_SPAREPART) }}</td>
																</tr>
															@endforeach
														@endforeach

													</tbody>
												</table>
											</div>
										</div>
									</div>
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
{{-- <script>
$(document).ready(function(){
	var table = $('datatable').DataTable({
			'processing' : true,
			'serverSide' : true,
			'ajax': "{{ route('machine.list') }}",
			'column':[
				{'data': 'MACHINE_LOCATION'},
				{'data': 'MACHINE_NAME'},
				{'data': 'MACHINE_CODE'}
			],
	});

  $("#myInput").keyup (function() {
		table.column($)
    var value = $(this).val().toLowerCase();
    $("#myTable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script> --}}


@stop
{{-- ปิดส่วนjava --}}
