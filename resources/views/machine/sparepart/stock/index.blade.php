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
							<div class="row">
								<div class="col-md-12">

									<div class="card-header bg-primary text-white">
										<div class="row">
											<div class="col-md-8 form-inline">
												<h4 class="mt-1 ">รายการอะไหล่ : </h4>
												{{-- <h4 class="mx-2 mt-1">จำนวน</h4>
												<select class="form-control form-control-sm mx-2">
													<option>น้อยไปหามาก</option>
													<option>มากไปหาน้อย</option>
												</select> --}}
											</div>
											<div class="col-md-4 form-inline">
												<h4 class="mt-1">ค้นหา : </h4>
												<div class="input-group mx-1">
					                <input type="search" id="SEARCH" name="SEARCH" class="form-control form-control-sm " placeholder="ค้นหา........." value="">
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
														<th>Action</th>
													</tr>
												</thead>
												<tbody>
													@foreach ($DATA_SPAREPART as $key => $row)
														<tr>
															<td width="4%" class="text-center">{{$key+1}}</td>
															<td width="18%">{{$row->SPAREPART_CODE}}</td>
															<td width="20%">{{$row->SPAREPART_NAME}}</td>
															<td width="12%">{{$row->SPAREPART_MODEL}}</td>
															<td width="14%">8M-1432-50mm</td>
															<td width="8%">{{$row->STOCK_MIN}}</td>
															<td width="8%">{{$row->LAST_STOCK}}</td>
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
	


@stop
{{-- ปิดส่วนjava --}}