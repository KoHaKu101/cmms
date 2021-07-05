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
				<div class="py-4">
						<div class="row">
							<div class="col-md-12">
								<div class="card ">
								  <div class="card-header bg-primary  ">
										<div class="row">
											<div class="col-md-2 text-white">
												<h4 class="my-2">ใบประวัติเครื่องจักร</h4>
											</div>
											<div class="col-md-7 form-inline">
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
												<button type="button" class="btn btn-sm btn-warning my-1"><i class="fas fa-print" style="font-size:15px"> ทั้งหมด</i></button>
											</div>
											<div class="col-md-3 form-inline">
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
									<div class="card-body" hidden>
										<div class="row">
											<div class="col-md-12 table-responsive">
												<table class="table table-sm table-bordered table-head-bg-info table-bordered-bd-info">
													<style>
													.table>tbody>tr>td, .table>tbody>tr>th {
													  font-size: 0.75rem;
														vertical-align: text-top;
													}

													</style>
													<tbody>
														@foreach ($DATA_REPAIR_HEADER as $key => $row)
																<tr>
																	<th class="bg-info text-white " colspan="9" style="font-size:18px">MC-CODE : {{ $row->MACHINE_CODE }} </th>
																	<th class="bg-info text-white text-right" >
																		<button type="button" class="btn btn-sm btn-warning  my-1"
																		onclick="window.open('/machine/history/repairpdf/{{$row->MACHINE_UNID}}','RepairSaveprint','width=1000,height=1000,resizable=yes,top=100,left=100,menubar=yes,toolbar=yes,scroll=yes')">
																			<i class="fas fa-print" style="font-size:15px"></i>
																		</button>
																	</th>
																</tr>

																<tr class="bg-secondary text-white">
																	<td scope="col">#</td>
																	<td scope="col">วันที่แจ้ง</td>
																	<td scope="col">เอกสาร</td>
																	<td scope="col">อาการเสีย</td>
																	<td scope="col">วันที่ซ่อม</td>
																	<td scope="col">วิธีการแก้ไข</td>
																	<td scope="col">อะไหล่</td>
																	<td scope="col">ราคา</td>
																	<td scope="col">DownTime</td>
																	{{-- <td scope="col">อะไหล่</td> --}}
																	<td scope="col">ผู้รายงาน</td>

																</tr>
																@php
																	$i = 1 ;
																	$i_sub = 1 ;
																@endphp
															@foreach ($DATA_REPAIR->where('MACHINE_UNID','=',$row->MACHINE_UNID) as $index => $sub_row)
																<tr>
																	<td >{{$i++}}</td>
																	<td >{{date('d-m-Y',strtotime($sub_row->DOC_DATE))}}</td>
																	<td >{{$sub_row->DOC_NO}}</td>
																	<td style="width:20%">{{ $sub_row->REPAIR_REQ_DETAIL }}</td>
																	<td >{{ date('d-m-Y',strtotime($sub_row->REPAIR_DATE)) }}</td>
																	<td style="width:20%">{{ $sub_row->REPAIR_DETAIL }}</td>

																	<td style="width:16%">
																		@if ($DATA_SPAREPART->where('REPAIR_REQ_UNID','=',$sub_row->REPAIR_REQ_UNID)->count() == 0)
																			-
																		@endif
																		@foreach ($DATA_SPAREPART->where('REPAIR_REQ_UNID','=',$sub_row->REPAIR_REQ_UNID) as $key => $subsub_row)
																			{{ $i_sub++.'.'.$subsub_row->SPAREPART_NAME }} <br>
																		@endforeach
																	</td>
																	<td class="text-right">{{ $sub_row->TOTAL_COST != 0 ? number_format($sub_row->TOTAL_COST).' บาท' : '-'}} </td>
																	<td class="text-right" >{{ $sub_row->DOWN_TIME != 0 ? number_format($sub_row->DOWN_TIME).' นาที' : '-'}} </td>
																	{{-- <td>อะไหล่</td> --}}
																	<td >{{ $sub_row->INSPECTION_BY_TH}} </td>
																</tr>
															@endforeach
														@endforeach

													</tbody>
												</table>
											</div>
										</div>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-md-12  ">
												<table class="table table-sm table-bordered table-head-bg-info table-bordered-bd-info">
													<style>
													.table>tbody>tr>td, .table>tbody>tr>th {
													  font-size: 0.75rem;
														vertical-align: text-top;
													}
													</style>
													@foreach ($DATA_REPAIR_HEADER as $key => $row)
															<tr>
																<th class="bg-info text-white " colspan="6" style="font-size:18px">MC-CODE : {{ $row->MACHINE_CODE }} </th>
																<th class="bg-info text-white text-right" >
																	<button type="button" class="btn btn-sm btn-warning  my-1"
																	onclick="window.open('/machine/history/repairpdf/{{$row->MACHINE_UNID}}','RepairSaveprint','width=1000,height=1000,resizable=yes,top=100,left=100,menubar=yes,toolbar=yes,scroll=yes')">
																		<i class="fas fa-print" style="font-size:15px"></i>
																	</button>
																</th>
															</tr>

															<tr class="bg-secondary text-white">
																<td scope="col">#</td>
																<td scope="col">Rank</td>
																<td scope="col">ระยะรอบ(เดือน)</td>
																<td scope="col">วันที่ตามแผน</td>
																<td scope="col">วันที่ตรวจเช็ค</td>

																<td scope="col">เวลาหยุดเครื่อง</td>
																<td scope="col">หมายเหตุ</td>

															</tr>
															@php
																$i = 1 ;
																$i_sub = 1 ;
															@endphp
															<tr>
																<td style="width:16%">1</td>
																<td style="width:16%">A</td>
																<td style="width:16%">3 เดือน</td>
																<td style="width:16%">01-03-07</td>
																<td style="width:16%">03-03-07</td>
																<td style="width:16%">30 นาที</td>
																<td style="width:16%">-</td>
															</tr>
														{{-- @foreach ($DATA_REPAIR->where('MACHINE_UNID','=',$row->MACHINE_UNID) as $index => $sub_row) --}}

															{{-- <tr>
																<td >{{$i++}}</td>
																<td >{{date('d-m-Y',strtotime($sub_row->DOC_DATE))}}</td>
																<td >{{$sub_row->DOC_NO}}</td>
																<td style="width:20%">{{ $sub_row->REPAIR_REQ_DETAIL }}</td>
																<td >{{ date('d-m-Y',strtotime($sub_row->REPAIR_DATE)) }}</td>
																<td style="width:20%">{{ $sub_row->REPAIR_DETAIL }}</td>

																<td style="width:16%">
																	@if ($DATA_SPAREPART->where('REPAIR_REQ_UNID','=',$sub_row->REPAIR_REQ_UNID)->count() == 0)
																		-
																	@endif
																	@foreach ($DATA_SPAREPART->where('REPAIR_REQ_UNID','=',$sub_row->REPAIR_REQ_UNID) as $key => $subsub_row)
																		{{ $i_sub++.'.'.$subsub_row->SPAREPART_NAME }} <br>
																	@endforeach
																</td>
																<td class="text-right">{{ $sub_row->TOTAL_COST != 0 ? number_format($sub_row->TOTAL_COST).' บาท' : '-'}} </td>
																<td class="text-right" >{{ $sub_row->DOWN_TIME != 0 ? number_format($sub_row->DOWN_TIME).' นาที' : '-'}} </td>
																<td >{{ $sub_row->INSPECTION_BY_TH}} </td>
															</tr> --}}
														{{-- @endforeach --}}
													@endforeach
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

@stop
{{-- ปิดส่วนเนื้อหาและส่วนท้า --}}

{{-- ส่วนjava --}}
@section('javascript')



@stop
{{-- ปิดส่วนjava --}}
