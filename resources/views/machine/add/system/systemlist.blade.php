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

	  <div class="content">
      <div class="page-inner">
				<div class="py-12">
	        <div class="container mt--4">
						<div class="row">
							<div class="col-md-12">
								<div class="card "></div>
								<div class="row">
									<div class="col-md-6 col-lg-4 ">
										<div class="card">
											<div class="card-header bg-primary">
												<h4 class="ml-3 mt-2" style="color:white;" ><i class="fas fa-cubes fa-lg mr-1"></i>	ประเภทรายการ (Template)
													<button  id="popup" type="button" class="btn btn-warning float-right btn-sm" data-toggle="modal" data-target="#Newtemplate">
														<i class="fas fa-file" style="color:white;font-size:14px"> New</i>
													</button>
												</h4>
											</div>
											<div class="card-body">
												<ul class="nav nav-tab flex-column col-md-6 col-lg-12" id="tabActive" >
													@foreach ($datapmtemplate as $key => $dataset)
														<li>
															<a href="{{ url('machine/pm/template/list/'.$dataset->UNID) }}"  class="btn btn-primary text-left btn-sm my-2" style="width:190px" > {{ $dataset->PM_TEMPLATE_NAME }} </a>
															<button class="btn btn-primary btn-link btn-sm" type="button" data-toggle="modal" data-target="#Edittemplate" onclick="datapmachine('{{ $dataset->UNID}}','{{$dataset->PM_TEMPLATE_NAME }}')">
															<i class="fas fa-edit fa-2x"></i> </button>
															<button class="btn btn-danger btn-link btn-sm" type="button	" onclick="deletecheckboxpm('{{ $dataset->UNID }}')">
															<i class="fas fa-trash" style="font-size:20px"></i> </button>
														</li>
													@endforeach
						        		</ul>
											</div>
										</div>
									</div>
									<div class="col-md-6 col-lg-8">
										<div class="" id="tabdetail" name="tabdetail">
											@if ($countdetail > 0)
												<div class="card" >
													<div class="tab-content clearfix">
														<div class="tab-pane active" >
															<div class="card-header bg-primary">
																<h4 class="ml-3 mt-2" style="color:white;" ><i class="fas fa-cubes fa-lg mr-1"></i>รายการ : {{ $datapmtemplatefirst->PM_TEMPLATE_NAME }}
																	<a href="{{ url('/machine/pm/template/add/'.$datapmtemplatefirst->UNID) }}">
																		<button class="btn btn-warning btn-sm float-right mx-2 " type="button	">
																		<i class="fas fa-file" style="color:white;font-size:14px"> เพิ่มรายการ PM</i> </button>
																	</a>
																</h4>
															</div>
														</div>
													</div>
													<div class="card-body">
														<div class="col-md-12">
															<div class="table">
																<table class="table table-bordered table-head-bg-info table-bordered-bd-info">
																	<thead>
																		<tr>
																			<th width="50px">##</th>
																			<th scope="col">Inspection Item</th>

																			<th style="width:120px">Action</th>
																		</tr>
																	</thead>
																	<tbody>

																		@foreach ($datapmtemplatelist as $key => $dataitem)
																			<tr>
																				<td class="text-center"> {{ $key+1 }}</td>
																				<td>{{$dataitem->PM_TEMPLATELIST_NAME}}</td>

																				<td>
																					<div class="form-inline">
																						<a href="{{ url('/machine/pm/templatelist/edit/'.$dataitem->UNID) }}">
																							<button class="btn btn-primary btn-link btn-sm " type="button	">
																							<i class="fas fa-edit fa-2x"></i> </button>
																						</a>
																						<button class="btn btn-danger btn-link btn-sm my-1" type="button" onclick="deletecheckbox('{{ $dataitem->UNID }}','{{ $dataitem->PM_TEMPLATELIST_NAME }}')" >
																							<i class="fas fa-trash" style="font-size:20px"> </i>
																						</button>
																					</div>
																				</td>
																			</tr>
																		@endforeach
																	</tbody>
																</table>
															</div>
														</div>
													</div>
												</div>
												<div class="card" >
													<div class="card-header bg-primary">
														<h4 class="ml-3 mt-2" style="color:white;" ><i class="fas fa-cubes fa-lg mr-1"></i>	รายการเครื่องจักรที่ใช้งาน
														</h4>
													</div>
													<div class="card-body">
														<div class="col-md-12">
															<div class="table">
																<table class="table table-bordered table-head-bg-info table-bordered-bd-info">
																	<thead>
																		<tr>
																			<th style="width:46.67px">##</th>
																			<th scope="col">รหัสเครื่องจักร</th>
																			<th scope="col">ชื่อเครื่องจักร</th>
																			<th scope="col">LINE</th>
																			<th style="width:46.67px">Action</th>
																		</tr>
																	</thead>
																	<tbody>
																		@if ($datamachine)
																			@php
																				$machine_name = array();
																				foreach ($arraymachine as $index => $array_row) {
																						$machine_name[$array_row->UNID] = $array_row->MACHINE_NAME;
																						$machine_line[$array_row->UNID] = $array_row->MACHINE_LINE;
																				}
																			@endphp
																			@foreach ($datamachine as $key => $datarow)
																				<tr>
																					<td class="text-center"> {{ $key+1 }}</td>
																					<td>{{$datarow->MACHINE_CODE}}</td>
																					<td>{{$machine_name[$datarow->MACHINE_UNID]}}</td>
																					<td>{{$machine_line[$datarow->MACHINE_UNID]}}</td>
																					<td>
																						<div class="form-inline">
																							<button class="btn btn-danger btn-link btn-sm my-1" type="button"
																							 onclick="deletemachinepm('{{ $datarow->MACHINE_CODE }}','{{$datarow->MACHINE_UNID}}','{{ $dataitem->PM_TEMPLATE_UNID_REF }}')">
																								<i class="fas fa-trash" style="font-size:20px"> </i>
																							</button>
																						</div>
																					</td>
																				</tr>
																			@endforeach
																		@endif
																	</tbody>
																</table>
																{{ $datamachine->links('pagination.default') }}
															</div>
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
  			</div>
			</div>
		</div>

		<!-- Modal -->
		@include('machine/add/system/modalpmtemplate')
		@include('machine/add/system/modalpmtemplatelist')


@stop
{{-- ปิดส่วนเนื้อหาและส่วนท้า --}}

{{-- ส่วนjava --}}
@section('javascript')

<script>

</script>

<script src="{{ asset('assets/js/useinproject/addtable/systemlist.js') }}">

</script>

@stop
{{-- ปิดส่วนjava --}}
