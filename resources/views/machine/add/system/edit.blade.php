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
				<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
          <div class="container">
						<div class="row">
							<div class="col-md-12 gx-4">
								<a href="{{url('machine/pm/template/list/'.$datapmtemplate->UNID)}}">
										<button class="btn btn-warning  btn-xs ">
											<span class="fas fa-arrow-left fa-lg">Back </span>
										</button>
								</a>
							</div>
						</div>
          </div>
				</div>
				<div class="py-12">
	        <div class="container mt-2">
						<div class="row">
							<div class="col-md-12">
								<div class="card ">
									<form action="{{ url('machine/pm/template/update/'.$datapmtemplatelist->UNID) }}" method="post" enctype="multipart/form-data">
										@csrf
										<div class="card-header bg-primary">
											<h4 class="ml-3 mt-2" style="color:white;" >ประเภทรายการ : {{$datapmtemplate->PM_TEMPLATE_NAME}}, รายการ PM : {{$datapmtemplatelist->PM_TEMPLATELIST_NAME}}
												<a href="{{ url('/machine/pm/template/add/'.$datapmtemplate->UNID) }}">
												<button type="submit" class="btn btn-warning btn-sm float-right" name="save" value="new">
													<i class="fas fa-save" style="color:white;font-size:15px"> New</i>
												</button>
												</a>
										</h4>
										 </div>
										<div class="card-body">
										 	<div class="row">
											 	<div class="col-md-6 col-lg-3 has-error">
												 	<label> Inspection Item</label>
												 	<input type="text" class="form-control" name="PM_TEMPLATELIST_NAME" value="{{ $datapmtemplatelist->PM_TEMPLATELIST_NAME }}">
											 	</div>


												<div class="col-md-6 col-lg-2 has-error">
													<label> สถานะ</label>
													<div class="selectgroup w-100">
															<label class="selectgroup-item">
																<input type="radio" name="PM_TEMPLATELIST_STATUS" value="1" class="selectgroup-input" {{ $datapmtemplatelist->PM_TEMPLATELIST_STATUS == '1' ? 'checked' : ""}}>
																<span class="selectgroup-button">เปิด</span>
															</label>
															<label class="selectgroup-item">
																<input type="radio" name="PM_TEMPLATELIST_STATUS" value="2" class="selectgroup-input" {{ $datapmtemplatelist->PM_TEMPLATELIST_STATUS == '2' ? 'checked' : ""}}>
																<span class="selectgroup-button">ปิด</span>
															</label>
														</div>
												</div>
										 	</div>
											<div class="row">
												<div class="col-md-6 col-lg-10">
											</div>
											<div class="col-md-6 col-lg-1">
												<button class="btn btn-primary btn-sm" >
													<i class="fas fa-save" style="color:white;font-size:15px" name="save" value="save"> Save</i>
												</button>
											</div>

										</div>
										</div>
									</form>
											</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-8">
									<div class="card">
											<div class="card-header bg-primary">
												<input type="hidden" name="PM_TEMPLATELIST_UNID_REF" id="PM_TEMPLATELIST_UNID_REF" value="{{ $datapmtemplatelist->UNID }}">
												<h4 class="ml-3 mt-2" style="color:white;" > Inspection Check</h4>
											</div>
												<div class="card-body mt--3">
													<style>
													.table-responsive {
    												display: table;
														}
													</style>
													<div class="table-responsive mt--4">
														<table class="table table-hover table-bordered mt-4">
															<thead>
																<tr>
																	<th >ลำดับ</th>
																	<th >รายละเอียด</th>
																	<th>ค่า STD</th>
																	<th>Unit</th>
																	<th>ข้อมูล</th>
																	<th colspan="2"></th>
																</tr>
															</thead>
															<tbody>
															@foreach ($datapmtemplatedetail as $key => $dataitem)
																<tr>
																	@php
																	$STD = $dataitem->PM_DETAIL_STD;
																	$UNIT = $dataitem->PM_DETAIL_UNIT;
																		if (strtoupper($dataitem->PM_TYPE_INPUT) == 'RADIO') {
																			$STD = 'ผ่าน';
																			$UNIT = '-';
																		}

																	@endphp
																	<th scope="row">{{$key+1}}</th>
																	<td>{{$dataitem->PM_DETAIL_NAME}}</td>
																	<td>{{ $STD }}</td>
																	<td>{{ $UNIT }}</td>
																	<td>{{$dataitem->PM_TYPE}}</td>
																	<td >
																		<button type="button" class="btn btn-primary btn-block btn-sm my-1 edit"
																		onclick="editdetail('{{ $dataitem->UNID }}','{{ $dataitem->PM_DETAIL_NAME }}'
																		,'{{ $dataitem->PM_DETAIL_STD }}','{{ $dataitem->PM_TYPE_INPUT }}','{{ $dataitem->PM_DETAIL_UNIT }}'
																		,'{{ (double)$dataitem->PM_DETAIL_STD_MAX }}','{{ (double)$dataitem->PM_DETAIL_STD_MIN }}','{{ $dataitem->PM_DETAIL_STATUS_MAX }}','{{ $dataitem->PM_DETAIL_STATUS_MIN }}')">
																			<i class="fas fa-edit fa-lg">	</i>
																		</button>
																	</td>
																	<td >
																		<button type="button" class="btn btn-danger btn-block btn-sm my-1" onclick="deletedata('{{ $dataitem->UNID }}')" >
																			<i class="fas fa-trash fa-lg">	</i>
																		</button>

																	</td>
																</tr>
															@endforeach

															</tbody>
														</table>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-4">
									<div class="card">
										<div class="card-header bg-primary">
											<h4 class="ml-3 mt-2" style="color:white;" > เพิ่ม Inspection Check  </h4>
										 </div>
										<div class="card-body PM_CANCEL" >
											<form action="{{ route('pmtemplatedetail.store') }}" method="POST" id="FRM_SAVE" name="FRM_SAVE">
												@csrf
												<div class="form-group">
													<div class="row has-error">
														<label for="SYSTEM_CODE">รายละเอียด</label>
														<input type="hidden" id="DETAIL_UNID" name="DETAIL_UNID" value="1">
														<input type="hidden" id="PM_TEMPLATELIST_UNID_REF" name="PM_TEMPLATELIST_UNID_REF" value="{{ $datapmtemplatelist->UNID }}">
														<textarea class="form-control " id="PM_DETAIL_NAME" name="PM_DETAIL_NAME" rows="2" required autofocus></textarea >
													</div>
														<div class="row">
															<div class="col-md-6 has-error">
																<label for="PM_DETAIL_STD">ค่า STD</label>
																	<input type="number" class="form-control" id="PM_DETAIL_STD" name="PM_DETAIL_STD" step="any" required>
															</div>
															<div class="col-md-6">
																<label for="PM_DETAIL_UNIT">หน่วย</label>
																<input type="text" class="form-control" id="PM_DETAIL_UNIT" name="PM_DETAIL_UNIT" >
															</div>
														</div>
														<div class="row">
															<div class="col-md-12">
																<div class="form-group form-inline">
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="checkbox" id="PM_DETAIL_STATUS_MAX" name="PM_DETAIL_STATUS_MAX" value="true" onchange="statusmax()">
																			<span class="form-check-sign">ค่า MAX</span>
																		</label>
																	</div>
																	<div class="col-md-6 p-0">
																		<input type="number" class="form-control" id="PM_DETAIL_STD_MAX" name="PM_DETAIL_STD_MAX" step="any" disabled>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="col-md-12">
																<div class="form-group form-inline">
																	<div class="form-check">
																		<label class="form-check-label">
																			<input class="form-check-input" type="checkbox" id="PM_DETAIL_STATUS_MIN" name="PM_DETAIL_STATUS_MIN" value="true" onchange="statusmin()">
																			<span class="form-check-sign">ค่า MIN</span>
																		</label>
																	</div>
																	<div class="col-md-6 p-0">
																		<input type="number" class="form-control" id="PM_DETAIL_STD_MIN" name="PM_DETAIL_STD_MIN" step="any" disabled>
																	</div>
																</div>
															</div>
														</div>

											<div class="form-group" id="DETAIL">
												<label for="smallSelect" class="my-1">ข้อมูล</label>
													<select class="form-control form-control-sm" id="PM_TYPE_INPUT" name="PM_TYPE_INPUT" onchange="changetype()"required>
														<option value="">กรุณาเลือกประเภทการกรอก</option>
														<option value="number">กรอกค่าตัวเลข</option>
														<option value="radio">เลือก ผ่าน ไม่ผ่าน</option>
													</select>
												</div>
													<button type="submit" class="btn btn-primary mt-3">Save</button>
													<button type="button" id="CANCEL_EDIT" name="CANCEL_EDIT" onclick="exiteditdetail()"
													class="btn btn-danger float-right mt-3" hidden="true">Cancel</button>
												</div>

											</form>
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

<script src="{{ asset('assets/js/useinproject/addtable/systemedit.js') }}"></script>


@stop
{{-- ปิดส่วนjava --}}
