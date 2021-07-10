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
										<form action="{{ route('history.repairlist') }}" method="POST" enctype="multipart/form-data">
											@method('GET')
											@csrf
											<div class="row">
												<div class="col-md-2 text-white">
													<h4 class="my-2">ใบประวัติเครื่องจักร</h4>
												</div>
												<div class="col-md-7 form-inline">
													<lable class="text-white"> ประเภท </lable>
													<select class="form-control form-control-sm mx-2" id="DOC_TYPE" name="DOC_TYPE">
														<option value>กรุณาเลือก</option>
														<option value="REPAIR" {{ $DOC_TYPE == 'REPAIR' ? 'selected' : ''  }} >Repair</option>
														<option value="PLAN_PM" {{ $DOC_TYPE == 'PLAN_PM' ? 'selected' : ''  }} >Preventive</option>
														<option value="">Predictive</option>
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
										</form>
								  </div>
									@if ($DOC_TYPE == 'REPAIR')
										<div class="card-body">
											<div class="row">
												<div class="col-md-12 table-responsive">
													<table class="table table-sm table-bordered table-head-bg-info table-bordered-bd-info">
														<style>
														.table>tbody>tr>td{
														  font-size: 0.75rem;
															vertical-align: baseline;
															word-break: break-all;
														}
														.table td.text-aliginup{
															vertical-align: baseline !important;
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
																		<td scope="col">ผู้รายงาน</td>
																	</tr>
																	@php
																		$i = 1 ;
																		$i_sub = 1 ;
																	@endphp
																@foreach ($DATA_REPAIR->where('MACHINE_UNID','=',$row->MACHINE_UNID) as $index => $sub_row)
																	<tr>
																		<td class="text-aliginup">{{$i++}}</td>
																		<td class="text-aliginup">{{date('d-m-Y',strtotime($sub_row->DOC_DATE))}}</td>
																		<td class="text-aliginup">{{$sub_row->DOC_NO}}</td>
																		<td class="text-aliginup"style="width:20%">{{ $sub_row->REPAIR_REQ_DETAIL }}</td>
																		<td class="text-aliginup">{{ date('d-m-Y',strtotime($sub_row->REPAIR_DATE)) }}</td>
																		<td class="text-aliginup"style="width:20%">{{ $sub_row->REPAIR_DETAIL }}</td>

																		<td class="text-aliginup"style="width:16%">
																			@if ($DATA_SPAREPART->where('REPAIR_REQ_UNID','=',$sub_row->REPAIR_REQ_UNID)->count() == 0)
																				-
																			@endif
																			@foreach ($DATA_SPAREPART->where('REPAIR_REQ_UNID','=',$sub_row->REPAIR_REQ_UNID) as $key => $subsub_row)
																				{{ $i_sub++.'.'.$subsub_row->SPAREPART_NAME }} <br>
																			@endforeach
																		</td>
																		<td class="text-right text-aliginup">{{ $sub_row->TOTAL_COST != 0 ? number_format($sub_row->TOTAL_COST).' บาท' : '-'}} </td>
																		<td class="text-right text-aliginup" >{{ $sub_row->DOWN_TIME != 0 ? number_format($sub_row->DOWN_TIME).' นาที' : '-'}} </td>
																		<td class="text-aliginup">{{ $sub_row->INSPECTION_BY_TH}} </td>
																	</tr>
																@endforeach
															@endforeach

														</tbody>
													</table>
												</div>
											</div>
										</div>
									@elseif ($DOC_TYPE == "PLAN_PM")
										<div class="card-body">
											<div class="row">
												<div class="col-md-12  ">
													<table class="table table-sm table-striped 	table-bordered table-head-bg-info table-bordered-bd-info ">
														<style>
														.table>tbody>tr>td{
														  font-size: 0.75rem;
															vertical-align: baseline;
															word-break: break-all;
														}
														.table td.text-aliginup{
															vertical-align: baseline !important;
														}
														</style>
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
																	<td class="text-center">#</td>
																	<td class="text-center">Rank</td>
																	<td >ระยะรอบ(เดือน)</td>
																	<td >วันที่ตามแผน</td>
																	<td >วันที่ตรวจเช็ค</td>
																	<td >เวลาหยุดเครื่อง</td>
																	<td >ประเภทเครื่องจักร</td>
																	<td >รายการตรวจเช็ค</td>
																	<td >หมายเหตุ/รายละเอียดเพิ่มเติม</td>
																	<td >ผู้ตรวจเช็ค</td>
																</tr>
																@php
																	$no = 1 ;
																	$sub_no = 1 ;
																@endphp

																@foreach ($DATA_PLANPM as $key => $row_plan)
																	{{-- {{ dd($row_plan->PM_PLAN_UNID) }} --}}
																	@php
																	$DATA_MACHINE_PLAN = $DATA_MACHINE_PLAN->where('UNID','=',$row_plan->PM_PLAN_UNID)->first();
																	@endphp

																	<tr>
																		<td style="width:2%" class="text-center text-aliginup">1</td>
																		<td style="width:4%" class="text-center text-aliginup">{{$DATA_MACHINE_PLAN->PLAN_RANK}}</td>
																		<td style="width:8%" class="text-aliginup">{{$DATA_MACHINE_PLAN->PLAN_PERIOD}} เดือน</td>
																		<td style="width:8%" class="text-aliginup">{{date('d-m-Y',strtotime($row_plan->DOC_DATE))}}</td>
																		<td style="width:8%" class="text-aliginup">{{date('d-m-Y',strtotime($row_plan->REPAIR_DATE))}}</td>
																		<td style="width:8%" class="text-aliginup">{{$row_plan->DOWN_TIME}}</td>
																		<td style="width:10%" class="text-aliginup">{{ $DATA_MACHINE_PLAN->PM_MASTER_NAME }}</td>
																		<td style="width:16%" class="text-aliginup">
																			@foreach ($DATA_MASTERTEMPLAT->where('PM_PLAN_UNID','=',$row_plan->PM_PLAN_UNID) as $key => $subrow_plan)
																				{{ $sub_no++.'. '.$subrow_plan->PM_MASTER_LIST_NAME."\n" }}<br>
																			@endforeach
																		</td>
																		<td style="width:26%" class="text-aliginup">-</td>
																		<td style="width:8%" class="text-aliginup">สุบรรณ์</td>
																	</tr>
																@endforeach

														@endforeach
													</table>
												</div>
											</div>
										</div>
									@endif


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
