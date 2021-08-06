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
      <div class="page-inner ">
				<div class="py-12">
	        <div class="container mt-2">
						<div class="card">
							<form action="{{ route('sparepart.stock') }}" method="post" enctype="multipart/form-data">
								@method('GET')
								@csrf
							<div class="row">
									<div class="col-md-12">
										<div class="card-header bg-primary text-white">
											<div class="row">
												<div class="col-md-4 form-inline">
													<h4 class="mt-1 ">รายการอะไหล่ในสต็อก</h4>

												</div>
												<div class="col-md-8 form-inline">
													<h4 class="mt-1 ml-auto ">สถานะ : </h4>
													<select class="form-control form-control-sm mx-2" id="STATUS" name="STATUS" onchange="SUBMIT_BTN()">
														<option value="0"	{{ $STATUS == '0' ? 'selected' : ''}}>ทั้งหมด</option>
														<option value="1"	{{ $STATUS == '1' ? 'selected' : ''}}>มีสต็อก</option>
														<option value="2"	{{ $STATUS == '2' ? 'selected' : ''}}>หมด/ใกล้หมด</option>
													</select>
													<h4 class="mt-1 mx-2">แสดง : </h4>
													<select class="form-control form-control-sm mx-2" id="SORT_LIMIT" name="SORT_LIMIT" onchange="SUBMIT_BTN()">
														<option value="10" 	{{ $SORT_LIMIT 	== '10' 	? 'selected' :''}}>10</option>
														<option value="25" 	{{ $SORT_LIMIT 	== '25' 	? 'selected' :''}}>25</option>
														<option value="50" 	{{ $SORT_LIMIT 	== '50' 	? 'selected' :''}}>50</option>
														<option value="100" {{ $SORT_LIMIT 	== '100' 	? 'selected' :''}}>100</option>
													</select>
													<h4 class="mt-1 ml-auto ">ค้นหา : </h4>
													<div class="input-group mx-1 ">
						                <input type="search" id="SEARCH_SPAREPART" name="SEARCH_SPAREPART" class="form-control form-control-sm col-md-10" placeholder="ค้นหา........." value="{{ $SEARCH }}">
						                <div class="input-group-prepend">
						                  <button type="submit" class="btn btn-search pr-1 btn-xs	" id="BTN_SUBMIT">
						                    <i class="fa fa-search search-icon"></i>
						                  </button>
						                </div>
						              </div>
												</div>
											</div>
										</div>

									</div>

							</div>
							</form>
							<div class="row">
								<div class="col-md-12">
									<divl class="table">
											<table class="table table-bordered table-head-bg-info table-bordered-bd-info ">
												<thead>
													<tr>
														<th class="text-center">#</th>
														<th>รหัส</th>
														<th>ชื่อ</th>
														<th>รุ่น</th>
														<th>ขนาด</th>
														<th>สต็อกขั้นต่ำ</th>
														<th>ยอดคงเหลือ</th>
														<th>หน่วย</th>
														<th>ประวัติเบิกจ่าย</th>
													</tr>
												</thead>
												<tbody>
													@foreach ($DATA_SPAREPART as $key => $row)
														@php
															$BG_COLOR = $row->STOCK_MIN >= $row->LAST_STOCK ? 'bg-danger text-white': 'bg-success text-white';
														@endphp
														<tr>
															<td width="4%" class="text-center">{{$DATA_SPAREPART->firstItem() + $key}}</td>
															<td width="18%">{{$row->SPAREPART_CODE}}</td>
															<td width="20%">{{$row->SPAREPART_NAME}}</td>
															<td width="12%">{{$row->SPAREPART_MODEL}}</td>
															<td width="14%">{{$row->SPAREPART_SIZE}}</td>
															<td width="8%">{{$row->STOCK_MIN}}</td>
															<td width="8%" class="{{$BG_COLOR}}">{{$row->LAST_STOCK}}</td>
															<td width="6%">{{$row->UNIT}}</td>
															<td>
																<button class="btn btn-sm btn-secondary btn-block my-1"
																onclick="positionedPopup('{{ route('spareparthistory.pdf').'?UNID='.$row->UNID}}','myWindow');return false">
																	<i class="fas fa-print mx-1"></i>
																	Print
																</button>
															</td>
														</tr>
													@endforeach

												</tbody>
											</table>
											{{$DATA_SPAREPART->appends(['SEARCH_SPAREPART' => $SEARCH,'STATUS' => $STATUS,'SORT_LIMIT' => $SORT_LIMIT])->links('pagination.default')}}
									</divl>
								</div>
							</div>
						</div>
					</div>
				</div>
      </div>
		</div>

		{{-- เพิ่มเครื่องจักร --}}





@stop
{{-- ปิดส่วนเนื้อหาและส่วนท้า --}}

{{-- ส่วนjava --}}
@section('javascript')
	<script src={{ asset('assets/js/ajax/ajax-csrf.js') }}></script>
	<script src="{{ asset('assets/js/ajax/appcommon.js') }}"></script>
	<script>
		function SUBMIT_BTN(){
			$("#BTN_SUBMIT").click();
		}
	</script>


@stop
{{-- ปิดส่วนjava --}}
