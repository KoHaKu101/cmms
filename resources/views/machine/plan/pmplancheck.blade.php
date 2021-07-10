@extends('masterlayout.masterlayout')
@section('tittle','homepage')
@section('css')

<link rel="stylesheet" href="{{ asset('assets/css/useinproject/_magnific-popup.scss')}}">
<link href="{{asset('assets/css/select2.min.css')}}" rel="stylesheet" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
.selectgroup-input-check:checked+.selectgroup-button-check {
	border-color: #ffffff;
	z-index: 1;
	color: #ffffff;
	background: rgb(1 214 47 / 83%);
	}
.selectgroup-input-times:checked+.selectgroup-button-times {
	border-color: #ffffff;
	z-index: 1;
	color: #ffffff;
	background: tomato;
	}
	.deleteimg {
	  color: #fff;
	  position: absolute;
		padding: 10px;
	  top: 0px;
	  left: 12px;

	}
	button.mfp-close{
		overflow: visible;
cursor: pointer;
background: #ff000000;
border: 0;
-webkit-appearance: none;
display: block;
outline: none;
padding: 0;
z-index: 1046;
box-shadow: none;
touch-action: manipulation;
left: auto;
	}
	.mfp-close{
		color: white;
	}
	.mfp-container {
	  color: #fff;
	  position: absolute;
	  top: 10px;
	  left: 10px;

	}
	.mfp-content {
		text-align: center;

	}
	.bg-muted{
		background-color:#696969;
		color:#696969;
	}
</style>
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
							</div>
	          </div>
					</div>
					<div class="py-12">
	        	<div class="container mt-2">
							<a href="{{ route('pm.planlist') }}" class="btn btn-warning btn-sm my-2"><i class="fas fa-arrow-left"></i></a>
							@if ($PM_PLANSHOW->PLAN_STATUS != 'NEW')

								<a onclick="window.open('{{ route('pm.pdfform',$PM_PLANSHOW->UNID) }}', '_blank', 'location=yes,height=570,width=520,scrollbars=yes,status=yes');" class="btn btn-default btn-sm float-right my-2"><i class="fas fa-print fa-lg"> print</i></a>

								<a href="{{ route('pm.planedit',$PM_PLANSHOW->UNID) }}" class="btn btn-danger btn-sm float-right mx-1 my-2"><i class="fas fa-retweet fa-lg"> Void</i></a>

							@endif
							<div class="row">
								<div class="col-md-12">
									<div class="card ">
										@if ($PM_PLANSHOW->PLAN_STATUS != 'NEW')
											@php
											$resultinput = array();
											$result = array();
											foreach ( $PM_DETAIL as $key => $value){
												 $resultinput[$value->PM_MASTER_DETAIL_UNID] = $value->PM_MASTER_DETAIL_INPUT;
												 $result[$value->PM_MASTER_DETAIL_UNID] = $value->PM_MASTER_DETAIL_RESULT;
											}
											@endphp
										@endif
											<form action="{{ route('pm.planlistsave')}}" method="post" id="FRM_PLANCHECK" name="FRM_PLANCHECK">
												@csrf
												<input type='hidden' class="form-control" id="PM_PLAN_UNID" name="PM_PLAN_UNID" value="{{$PM_PLANSHOW->UNID}}">
												<input type='hidden' class="form-control" id="MACHINE_PLAN_UNID" name="MACHINE_PLAN_UNID" value="{{$PM_PLANSHOW->MACHINE_UNID}}">
												<input type='hidden' class="form-control" id="PM_MASTER_UNID" name="PM_MASTER_UNID" value="{{$PM_PLANSHOW->PM_MASTER_UNID}}">
												<div class="card-header bg-primary text-white">
													<div class="form-group">
														<h4 class="my-1">Preventive Machine</h4>
													</div>
												</div>
												<div class="card-body">
													<div class="row">
														<div class="col-md-2">
															<label>รหัสเครื่องจักร</label>
															<input type="text" class="form-control form-control-sm my-1" id="MACHINE_CODE" name="MACHINE_CODE" value="{{$PM_PLANSHOW->MACHINE_CODE}}" readonly>
														</div>
														<div class="col-md-3">
															<label>ประเภทเครื่องจักร</label>
															<input type="text" class="form-control form-control-sm my-1" id="PM_MASTER_NAME" name="PM_MASTER_NAME" value="{{$PM_PLANSHOW->PM_MASTER_NAME}}" readonly>
														</div>
														<div class="col-md-1">
															<label>รอบ</label>
															<input type="text" class="form-control form-control-sm my-1" value="{{$PM_PLANSHOW->PLAN_PERIOD}} เดือน" readonly>
														</div>
													</div>
													<div class="row">
														<div class="col-md-2">
															<label>วันที่ตามแผน</label>
															<input type="text" class="form-control form-control-sm my-1" id="PLAN_DATE" name="PLAN_DATE" value="{{$PM_PLANSHOW->PLAN_DATE}}" readonly>
														</div>

														@if ($PM_PLANSHOW->PLAN_STATUS != 'NEW' )
															<div class="col-md-2">
																<label>วันที่ทำการตรวจเช็ค</label>
																<input type="text" class="form-control form-control-sm my-1" id="CHECK_DATE" name="CHECK_DATE"
																value='{{ $PM_USER_AND_NOTE->CHECK_DATE }}' readonly>
														  </div>
															<div class="col-md-2">
																<label>เริ่ม</label>
																<input type="time" class="form-control form-control-sm my-1" id="START_TIME" name="START_TIME" value="{{date('H:i',strtotime($PM_PLANSHOW->START_TIME))}}" readonly>
															</div>
															<div class="col-md-2">
																<label>เสร็จ</label>
																<input type="time" class="form-control form-control-sm my-1" id="END_TIME" name="END_TIME" value="{{date('H:i',strtotime($PM_PLANSHOW->END_TIME))}}" readonly>
															</div>
														@else
															<div class="col-md-2">
																<label>วันที่ทำการตรวจเช็ค</label>
																<input type="date" class="form-control form-control-sm my-1" id="CHECK_DATE" name="CHECK_DATE"
																value='{{date('Y-m-d')}}'>
														  </div>
															<div class="col-md-2">
																<label>เริ่ม</label>
																<input type="time" class="form-control form-control-sm my-1" id="START_TIME" name="START_TIME" value="{{date('H:i')}}" required>
															</div>
															<div class="col-md-2">
																<label>เสร็จ</label>
																<input type="time" class="form-control form-control-sm my-1" id="END_TIME" name="END_TIME" value="{{date('H:i')}}" required>
															</div>
														@endif

													</div>
												</div>
												@foreach ($PM_PLAN as $key => $dataset)
													<div class="card-header bg-danger text-white">
														<h4 class="my-1">รายการตรวจเช็ค {{ $dataset->PM_MASTER_NAME  }}</h4>
													</div>
												@endforeach
												<div class="row">
													<div class="col-md-12">
														<div class="card">
															<div class="card-body">
																@php
																$item_index = 0;
																@endphp
																@foreach ($PM_LIST as $key => $dataitem)
																	@php
															 			$item_index++ ;
																  @endphp
																	<div class="card-title fw-mediumbold bg-primary text-white">{{ $item_index }}. {{ $PM_PLANSHOW->PLAN_STATUS != 'NEW' ? $dataitem->PM_MASTER_LIST_NAME : $dataitem->PM_TEMPLATELIST_NAME}}</div>
																	<div class="card-list">
																		@php
																			$subitem_index = 1;
																			$PM_TEMPLATELIST_UNID = $PM_PLANSHOW->PLAN_STATUS != 'NEW' ? $dataitem->PM_MASTER_LIST_UNID : $dataitem->PM_TEMPLATELIST_UNID_REF ;
																			$PM_DETAO_WHERE = $PM_PLANSHOW->PLAN_STATUS != 'NEW' ? $PM_DETAIL->where('PM_MASTER_LIST_UNID','=',$PM_TEMPLATELIST_UNID) : $PM_DETAIL->where('PM_TEMPLATELIST_UNID_REF','=',$PM_TEMPLATELIST_UNID);
																		@endphp
																		@foreach ($PM_DETAO_WHERE as $number => $datasubitem)
																			@php
																			 $checkrow = $PM_PLANSHOW->PLAN_STATUS ;
																				$TYPE_INPUT =  	$checkrow  != 'NEW' ? $datasubitem->PM_MASTER_DETAIL_TYPE_INPUT : $datasubitem->PM_TYPE_INPUT;
																				$DETAIL_UNID = 	$checkrow  != 'NEW' ? $datasubitem->PM_MASTER_DETAIL_UNID : $datasubitem->UNID;
																				$STATUS_MAX = 	$checkrow  != 'NEW' ? $datasubitem->PM_STATUS_STD_MAX : $datasubitem->PM_DETAIL_STATUS_MAX;
																				$STATUS_MIN = 	$checkrow  != 'NEW' ? $datasubitem->PM_STATUS_STD_MIN : $datasubitem->PM_DETAIL_STATUS_MIN;
																				$STD = 					$checkrow  != 'NEW' ? $datasubitem->PM_MASTER_DETAIL_VALUE_STD : $datasubitem->PM_DETAIL_STD ;
																				$STD_MAX =  		$checkrow  != 'NEW' ? (double)$datasubitem->PM_MASTER_DETAIL_VALUE_STD_MAX : (double)$datasubitem->PM_DETAIL_STD_MAX;
																				$STD_MIN =  		$checkrow  != 'NEW' ? (double)$datasubitem->PM_MASTER_DETAIL_VALUE_STD_MIN : (double)$datasubitem->PM_DETAIL_STD_MIN;
																				if (strtoupper($TYPE_INPUT) == 'RADIO') {
																					$STD = 'ผ่าน';
																					$STD_MAX = '-';
																					$STD_MIN = '-';
																				}
																			@endphp

																			<div class="row">
																				<div class="col-12 col-sm-12 col-md-12 col-lg-5 my-1 ">
																					<div class="info-user">
																						<div class="username ">{{ $item_index.'.'.$subitem_index++ }} {{$PM_PLANSHOW->PLAN_STATUS != 'NEW' ? $datasubitem->PM_MASTER_DETAIL_NAME :$datasubitem->PM_DETAIL_NAME}}</div>
																					</div>
																				</div>
																				<div class="col-10 col-sm-12 col-md-12 col-lg-6 my-1 mx-3">
																					<div class="row">
																						<ul class="nav nav-pills nav-secondary nav-pills-no-bd nav-sm" id="pills-tab" role="tablist">
																							<div class="col-4 col-sm-2 col-md-2 col-lg-2">
																								<li class="nav-item">
																									<label>มาตรฐาน</label>
																									<input type="text" readonly class="form-control-sm form-control-plaintext bg-success text-white text-center my-1"
																							 			id="PM_MASTER_DETAIL_VALUE_STD" name="PM_MASTER_DETAIL_VALUE_STD" style="width:60px;"
																							 			value="{{ $STD }} ">
																								</li>
																							</div>
																							<div class="col-4 col-sm-2 col-md-2 col-lg-2">
																								<li class="nav-item">
																									<label>MAX</label>
																									<input type="text" readonly class="form-control-sm form-control-plaintext {{$STATUS_MAX == 'true' ? 'bg-danger text-white' : 'bg-muted' }}  text-center my-1"
																										id="PM_MASTER_DETAIL_VALUE_STD_MAX" name="PM_MASTER_DETAIL_VALUE_STD_MAX" style="width:60px;"
																										value="{{$STD_MAX}}">
																								</li>
																							</div>
																							<div class="col-4 col-sm-2 col-md-2 col-lg-2">
																								<li class="nav-item">
																									<label>MIN</label>
																									<input type="text" readonly class="form-control-sm form-control-plaintext {{$STATUS_MIN== 'true' ? 'bg-warning text-white' : 'bg-muted' }}  text-center my-1"
																									 	id="PM_MASTER_DETAIL_VALUE_STD_MIN" name="PM_MASTER_DETAIL_VALUE_STD_MIN" style="width:60px;"
																									 	value="{{$STD_MIN}}">
																								</li>
																							</div>
																							<div class="col-8 col-sm-4 col-md-3 col-lg-4">
																								<li class="nav-item">
																									<label>ผลตรวจ</label>

																									@if ($TYPE_INPUT == 'number')
																										<input class="{{ isset($resultinput[$DETAIL_UNID]) ? "form-control-plaintext" : "form-control" }} form-control-sm my-1 bg-secondary  text-white" placeholder="input" type="{{ $TYPE_INPUT }}" id="INPUT[{{$datasubitem->UNID}}]"
																											name="INPUT[{{$datasubitem->UNID}}]"
																											value="{{ isset($resultinput[$DETAIL_UNID]) ? $resultinput[$DETAIL_UNID] : "" }}"
																											style="width:100px;" {{ isset($resultinput[$DETAIL_UNID])  ? "readonly" : "required"  }}  step="any">
																									@elseif ($TYPE_INPUT == 'radio')
																										@if (isset($resultinput[$DETAIL_UNID]) && $resultinput[$DETAIL_UNID] == 1)
																											<div>
																												<label class="selectgroup-item">
																													<input type="{{$TYPE_INPUT}}" id="INPUT[{{$DETAIL_UNID}}]" name="INPUT[{{$DETAIL_UNID}}]" value="1" class="selectgroup-input selectgroup-input-check" checked disabled>
																													<span class="selectgroup-button selectgroup-button-check selectgroup-button-icon my-1 mx-2"><i class="fa fa-check"></i></span>
																												</label>
																												<label class="selectgroup-item">
																													<input type="{{$TYPE_INPUT}}" class="selectgroup-input selectgroup-input-times" disabled>
																													<span class="selectgroup-button selectgroup-button-times selectgroup-button-icon my-1 "><i class="fa fa-times"></i></span>
																												</label>
																											</div>
																										@elseif (isset($resultinput[$DETAIL_UNID]) && $resultinput[$DETAIL_UNID] == 0)
																											<div>
																												<label class="selectgroup-item">
																													<input type="{{$TYPE_INPUT}}" id="INPUT[{{$DETAIL_UNID}}]" name="INPUT[{{$DETAIL_UNID}}]" value="1" class="selectgroup-input selectgroup-input-check"  disabled >
																													<span class="selectgroup-button selectgroup-button-check selectgroup-button-icon my-1 mx-2"><i class="fa fa-check"></i></span>
																												</label>
																												<label class="selectgroup-item">
																													<input type="{{$TYPE_INPUT}}" id="INPUT[{{$DETAIL_UNID}}]" name="INPUT[{{$DETAIL_UNID}}]" value="0" class="selectgroup-input selectgroup-input-times" checked disabled>
																													<span class="selectgroup-button selectgroup-button-times selectgroup-button-icon my-1 "><i class="fa fa-times"></i></span>
																												</label>
																											</div>
																										@else
																											<div>
																												<label class="selectgroup-item">
																													<input type="{{$datasubitem->PM_TYPE_INPUT}}" id="INPUT[{{$DETAIL_UNID}}]" name="INPUT[{{$DETAIL_UNID}}]" value="1" class="selectgroup-input selectgroup-input-check"  >
																													<span class="selectgroup-button selectgroup-button-check selectgroup-button-icon my-1 mx-2"><i class="fa fa-check"></i></span>
																												</label>
																												<label class="selectgroup-item">
																													<input type="{{$datasubitem->PM_TYPE_INPUT}}" id="INPUT[{{$DETAIL_UNID}}]" name="INPUT[{{$DETAIL_UNID}}]" value="0" class="selectgroup-input selectgroup-input-times" >
																													<span class="selectgroup-button selectgroup-button-times selectgroup-button-icon my-1 "><i class="fa fa-times"></i></span>
																												</label>
																											</div>
																										@endif
																									@else
																										<label>กรุณาตั้งค่าการกรอกข้อมูล</label>

																									@endif
																								</li>
																							</div>
																							<div class="col-4 col-sm-2 col-md-2 col-lg-2">
																								<li class="nav-item ">
																									<label>ประเมิน</label>
																									@if (isset($resultinput[$DETAIL_UNID]))
																										<button type="button" class="btn btn-icon btn-round {{$result[$DETAIL_UNID] == 'PASS' ?'btn-success' : 'btn-danger'}} btn-sm my-1 mx-2" disabled>
																											<i class="{{$result[$DETAIL_UNID] == 'PASS' ?'fa fa-check' : 'fa fa-times'}}"></i>
																										</button>
																									@else
																										<button type="button" class="btn btn-icon btn-round btn-default btn-sm my-1 mx-2" disabled>
																											<i class="fas fa-question"></i>
																										</button>
																									@endif
																								</li>
																							</div>
																						</ul>
																					</div>
																				</div>
																			</div>
																			<div class="separator-dashed"></div>
																		@endforeach
																	</div>
																@endforeach
															</div>
															<div class="row">
																<div class="col-md-12">
																	<div class="form-group col-md-10 ml-auto mr-auto">
																				<label for="PM_MASTERPLPAN_REMARK">รายการอะไหล่ที่เปลี่ยน</label>
																				<div class="row">
																					@if (!isset($SPAREPART_RESULT) )
																						<div class="col-md-6 SPAREPART">
																							<label>อะไหล่</label>
																							<select class="form-control form-control-sm " id="SPAREPART" name="SPAREPART">
																								@foreach ($SPAREPART as $number => $sparepart_row)
																									<option value="{{ $sparepart_row->UNID }}"
																										data-cost="{{ $sparepart_row->SPAREPART_COST }}">
																										{{$sparepart_row->SPAREPART_CODE.' '.$sparepart_row->SPAREPART_NAME}}
																									</option>
																								@endforeach
																							</select>
																						</div>
																						<div class="col-6 col-sm-4 col-md-2">
																							<label>จำนวน</label>
																								<input type="number" class="bg-info text-white form-control form-control-sm" id="TOTAL_SPAREPART" name="TOTAL_SPAREPART">
																						</div>
																						<div class="col-6 col-sm-4 col-md-2">
																							<label>ราคา</label>
																								<input type="number" class="bg-info text-white form-control form-control-sm" id="COST_SPAREPART" name="COST_SPAREPART">
																						</div>
																						<div class="col-6 col-sm-4 col-md-2">
																							<label class="text-white"> แอบมองอยุ่นะ</label>
																							<button type="button" class="btn btn-sm btn-primary mx-2" id="add_sparepart"><i class="fas fa-plus mr-2"></i>เพิ่ม</button>
																						</div>
																					@endif

																					<div class="col-md-12">
																						<div class="table table-responsive" >
																							<table class="table table-bordered table-head-bg-info table-bordered-bd-info mt-2">
																								<thead>
																									<tr>
																										<th width="4%">#</th>
																										<th width="16%">รหัส</th>
																										<th>รายการ</th>
																										<th width="5%" class="text-center">จำนวน</th>
																										<th width="10%" class="text-center">ราคา</th>
																										<th width="15%" class="text-center">ราคาทั้งหมด</th>
																										<th width="18%">action</th>
																									</tr>
																								</thead>
																								<tbody id="RESULT_SPAREPART">
																									@if (isset($SPAREPART_RESULT) > 0)
																										@foreach ($SPAREPART_RESULT as $key => $sparepart)
																											<tr>
																												<td>{{ $key+1 }}</td>
																												<td>{{$sparepart->SPAREPART_CODE}}</td>
																												<td>{{$sparepart->SPAREPART_NAME}}</td>
																												<td class="text-center">{{$sparepart->TOTAL_PIC}}</td>
																												<td class="text-right">{{number_format($sparepart->SPAREPART_COST).' บาท'}}</td>
																												<td class="text-right">{{number_format($sparepart->TOTAL_COST).' บาท'}}</td>

																												<td>-</td>
																											</tr>
																										@endforeach
																									@endif
																								</tbody>
																							</table>
																						</div>
																					</div>
																				</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="col-md-10 ml-auto mr-auto">
																	<div class="form-group">
																		<label for="PM_MASTERPLPAN_REMARK">ข้อเสนอแนะ</label>
																		<textarea class="form-control" id="PM_MASTERPLPAN_REMARK" name="PM_MASTERPLPAN_REMARK" rows="3" {{ $PM_PLANSHOW->PLAN_STATUS != 'NEW' ? 'Readonly' : '' }}>{{ $PM_PLANSHOW->PLAN_STATUS != 'NEW' ? $PM_USER_AND_NOTE->PM_MASTERPLPAN_REMARK : ''}}</textarea>
																	</div>
																</div>

															</div>

															<div class="card-action">
																<div class="row form-inline">
																	<div class="form-group col-12 col-md-10">
																		<label for="inlineinput" class="col-md-3 col-form-label">ผู้ทำการตรวจเช็ค</label>
																		<div class="col-md-4 p-0">
																			<input type="text" class="form-control input-full" id="PM_USER_CHECK" name="PM_USER_CHECK"
																			 placeholder="กรุณาใส่ชื่อ" value="{{ $PM_PLANSHOW->PLAN_STATUS != 'NEW' ? $PM_USER_AND_NOTE->PM_USER_CHECK : ''}}"
																			 style="width:100px;" {{  $PM_PLANSHOW->PLAN_STATUS != 'NEW' ? "readonly" : "required"  }}>
																		</div>
																	</div>
																	<div class="col-12 col-md-2">
																		<a href="{{ route('pm.planlist') }}" class="btn btn-info btn-sm my2"><i class="fas fa-fast-backward fa-md"></i> Back</a>
																		@if ($PM_USER_AND_NOTE->count() > 0)
																			<a href="{{ route('pm.planedit',$PM_PLANSHOW->UNID) }}" class="btn btn-danger btn-sm my-2"><i class="fas fa-retweet fa-md"> Void</i></a>
																		@else
																			<button class="btn btn-success btn-sm my2" type="submit" {{  $PM_PLANSHOW->PLAN_STATUS != 'NEW' ? "disabled" : ""  }}><i class="fas fa-save fa-md"> Save</i> </button>
																		@endif

																	</div>
																</div>
																<div class="row">
																	<div class="col-lg-12">
																		<div class="form-control form-inline">
																			<input type="text" readonly class="form-control-sm form-control-plaintext bg-success text-white text-center my-1"
																			style="width:80px;" value="มาตรฐาน">
																			<input type="text" readonly class="form-control-sm form-control-plaintext bg-danger text-white text-center my-1 mx-2"
																		  style="width:60px;" value="MAX">
																		  <input type="text" readonly class="form-control-sm form-control-plaintext bg-warning text-white text-center my-1"
 																		  style="width:60px;" value="MIN">
																		  <input type="text" readonly class="form-control-sm form-control-plaintext bg-secondary text-white text-center my-1 mx-2"
	 																		style="width:80px;" value="ผลประเมิน">
																			<input type="text" readonly class="form-control-sm form-control-plaintext bg-muted text-white text-center my-1"
	 																		style="width:80px;" value="ไม่มีค่า">
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</form>
												<div class="row">
													<div class="col col-lg-12">
														<form action="{{ route('pm.planlistupload') }}" id="FRM_UPLOAD" name="FRM_UPLOAD" method="POST" enctype="multipart/form-data">
															@csrf
															<div class="col col-lg-12 form-inline">
																<div class="form-group mx-1">
																	<label for="exampleFormControlFile1">แนบรูปภาพปฏิบัติงาน</label>
																	<input type="file" class="form-control-file" id="FILE_NAME" name="FILE_NAME"
																	accept="image/*"required>
																	<input type="hidden" id="IMG_PLAN_UNID"name="IMG_PLAN_UNID"
																	value="{{$PM_PLANSHOW->UNID}}" >
																</div>
															</div>
															<div class="col col-lg-12">
																<button class="btn btn-primary btn-block" id="BTN_UPLOAD" name="BTN_UPLOAD"
															 	type="submit" @if (Session::has('autofocus')) autofocus @endif >Upload</button>
															</div>
														</form>
													</div>
												</div>
												<div class="row row-projects">
													<div class="col col-lg">
														<div class="card">
															<div class="card-body">
																<div class="row image-gallery" id="IMG_SHOW">
																	@if (isset($PLAN_UPLOAD_IMG))
																		@foreach ($PLAN_UPLOAD_IMG as $INDEXIMG => $IMG)
																			<a href="{{asset('../../image/planresult/'.$IMG->UNID_REF.'/'.$IMG->FILE_NAME.'?t='.time())}}"
																				class="col-6 col-md-2 my-1 mx--4 hv-100" id="{{$IMG->UNID}}" data-imgunid="{{$IMG->UNID}}">
																				<img src="{{asset('../../image/planresult/'.$IMG->UNID_REF.'/'.$IMG->FILE_NAME.'?t='.time())}}"
																 				style="width: 290px;height: 100;left: 0px; top: 0px;" class="img-fluid">
																			</a>
			  														@endforeach
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
					</div>
				</div>
			</div>

@stop
{{-- ปิดส่วนเนื้อหาและส่วนท้า --}}

{{-- ส่วนjava --}}
@section('javascript')
	<script src="{{ asset('assets/js/ajax/ajax-csrf.js') }}"></script>
	<script src="{{ asset('assets/js/useinproject/jquery.ui.touch-punch.min.js') }}"></script>
	<script src="{{ asset('assets/js/useinproject/bootstrap-toggle.min.js') }}"></script>
	<script src="{{ asset('assets/js/useinproject/jquery.magnific-popup.min.js') }}"></script>
	<script src="{{ asset('assets/js/useinproject/appcommon.js') }}"></script>
	<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script>
$(document).ready(function(){
	$('#SPAREPART').select2({
		placeholder: "กรุณาเลือก",
		width:'100%',
	 containerCssClass:'bg-info text-white',
	});
});
var sparepart_total = {};
var arr_spare_total = [];
var sparepart_cost = {};
var arr_spare_cost = [];

function loop_tabel_sparepart(unid,total,cost){
	arr_spare_total.push({unid:unid,total:total});
	arr_spare_cost.push({unid:unid,cost:cost});
	$.each(arr_spare_total,function(key, value){
		sparepart_total[value.unid] = value.total;
	});
	$.each(arr_spare_cost,function(key, value){
		sparepart_cost[value.unid]  = value.cost;
	});

	var url = "{{ route('pm.sparepart') }}";
	$.ajax({
			 type:'POST',
			 url: url,
			 datatype: 'json',
			 data: {SPAREPART 		 : sparepart_total,
			 				SPAREPART_COST : sparepart_cost},
			 success:function(data){
				 $('#RESULT_SPAREPART').html(data.html);
			 }
		 });
	 };

	$('#SPAREPART').on('change',function(){
		var unid = $('#SPAREPART option:selected').val();
		var cost = $('#SPAREPART option:selected').data('cost');
		$('#TOTAL_SPAREPART').val('1');
		$('#COST_SPAREPART').val(cost);
	});
	$('#add_sparepart').on('click',function(){
		var unid = $('#SPAREPART').val();
		var total = $('#TOTAL_SPAREPART').val();
		var cost = $('#COST_SPAREPART').val();
		loop_tabel_sparepart(unid,total,cost);
	});
	function editsparepart(thisdata){
		var unid = $(thisdata).data('unid');
		var cost = $(thisdata).data('cost');
		var total = $(thisdata).data('total');
		$('#TOTAL_SPAREPART').val(cost);
		$('#COST_SPAREPART').val(total);
		$('#SPAREPART').val(unid);
		$('#SPAREPART').select2({
			width:'100%',
		}).trigger('change');
	};
	function removesparepart(thisdata){
		var unid = $(thisdata).data('unid');
		for( var i = 0; i < arr_spare_total.length; i++){
				if ( arr_spare_total[i].unid == unid) {
						arr_spare_total.splice(i, 1);
						arr_spare_cost.splice(i,1);
				}
		}
		loop_tabel_sparepart(unid);
	}
	$(".image-gallery a").click(function(e) {
  	var file = $(this).attr("href");
		var imgunid = $(this).data("imgunid");
  		$.magnificPopup.open({
	    items: {
      	src: $('<img src="' + file + '" class="col col-lg-6"/><button type="button" class="deleteimg btn btn-danger" ><i class="fas fa-trash"></i></button>'),
      	type: 'inline'
    	},
    	closeBtnInside: false,
  		});
  		e.preventDefault();
			$(".deleteimg").on('click',function(e){
				e.preventDefault();
				Swal.fire({
  				title: 'ต้องการลบรูปใช่มั้ย?',
  				showDenyButton: true,
  				confirmButtonText: `Delete`,
  				denyButtonText: `Cancel`,
				}).then((result) => {
  			if (result.isConfirmed) {

					$.ajax({
  					type: "POST",
  					url: '{{route('pm.deleteimg')}}',
						dataType: 'JSON',
  					data: {"_token": "{{ csrf_token() }}",
									  'imgunid' : imgunid},
  					success: function(data){
							if (data.result = 'true') {
								var imgunid = data.imgunid;
								$("#"+imgunid).remove();
							}

						}
					});
  			}
		})
		});

	});
	$('#FRM_UPLOAD').submit(function(e){
		e.preventDefault();
		var url  = "{{ route('pm.planlistupload') }}";
		var formData = new FormData(this);
		$.ajax({
			type:'POST',
			url: url,
			data: formData,
			cache:false,
			contentType: false,
			processData: false,
			success: function(data){
				if (data.result = 'true') {
					Swal.fire({
					  icon: 'success',
					  title: 'อัปโหลดไฟล์สำเร็จ',
					  timer: 1500,
					});
					$('#IMG_SHOW').html(data.html);
					$(".image-gallery a").click(function(e) {
				  	var file = $(this).attr("href");
						var imgunid = $(this).data("imgunid");
				  		$.magnificPopup.open({
					    items: {
				      	src: $('<img src="' + file + '" class="col-6 col-md-2 my-1 mx--4 hv-100"/><button type="button" class="deleteimg btn btn-danger" ><i class="fas fa-trash"></i></button>'),
				      	type: 'inline'
				    	},
				    	closeBtnInside: false,
				  		});
				  		e.preventDefault();
							$(".deleteimg").on('click',function(e){
								e.preventDefault();
								Swal.fire({
				  				title: 'ต้องการลบรูปใช่มั้ย?',
				  				showDenyButton: true,
				  				confirmButtonText: `Delete`,
				  				denyButtonText: `Cancel`,
								}).then((result) => {
				  			if (result.isConfirmed) {

									$.ajax({
				  					type: "POST",
				  					url: '{{route('pm.deleteimg')}}',
										dataType: 'JSON',
				  					data: {"_token": "{{ csrf_token() }}",
													  'imgunid' : imgunid},
				  					success: function(data){
											if (data.result = 'true') {
												var imgunid = data.imgunid;
												$("#"+imgunid).remove();
											}

										}
									});
				  			}
						})
						});

					});
				}
			}
		});
	});
	</script>


@stop
{{-- ปิดส่วนjava --}}
