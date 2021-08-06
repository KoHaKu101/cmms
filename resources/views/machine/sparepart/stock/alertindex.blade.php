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
							<form action="{{ route('sparepart.alert') }}" method="post" enctype="multipart/form-data">
								@method('GET')
								@csrf
							<div class="row">
								<div class="col-md-12">
									<div class="card-header bg-primary text-white">
										<div class="row">
											<div class="col-md-8 form-inline">
												<h4 class="mt-1 ">รายการอะไหล่ ที่ต้องสั่งซื้อ </h4>
												<h4 class="mt-1 ml-auto mx-1">Size </h4>
												<select class="from-control form-control-sm" id="SORT_LIMIT" name="SORT_LIMIT" onchange="submit_btn()">
													<option value="10" 	{{ $SORT_LIMIT 	== '10' 	? 'selected' :''}}>10</option>
													<option value="25" 	{{ $SORT_LIMIT 	== '25' 	? 'selected' :''}}>25</option>
													<option value="50" 	{{ $SORT_LIMIT 	== '50' 	? 'selected' :''}}>50</option>
													<option value="100" {{ $SORT_LIMIT 	== '100' 	? 'selected' :''}}>100</option>
												</select>
											</div>
											<div class="col-md-4 form-inline">
												<h4 class="mt-1">ค้นหา : </h4>
												<div class="input-group mx-1">
					                <input type="text" id="SEARCH_SPAREPART" name="SEARCH_SPAREPART" class="form-control form-control-sm " placeholder="ค้นหา........." value="{{ $SEARCH }}">
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
														<th width="4%"	class="text-center">#</th>
														<th width="10%"	> รหัส</th>
														<th width="23%"	> ชื่อ</th>
														<th width="17%"	> รุ่น</th>
														<th width="14%"	> ขนาด</th>
														<th width="8%"	> สต็อกขั้นต่ำ</th>
														<th width="8%"	> ยอดคงเหลือ</th>
														<th width="5%"> หน่วย</th>
													</tr>
												</thead>
												<tbody>
													@foreach ($DATA_SPAREPART as $key => $row)
														<tr>
															<td  class="text-center">{{$DATA_SPAREPART->firstItem() + $key}}</td>
															<td >{{$row->SPAREPART_CODE}}</td>
															<td >{{$row->SPAREPART_NAME}}</td>
															<td >{{$row->SPAREPART_MODEL}}</td>
															<td >{{$row->SPAREPART_SIZE}}</td>
															<td >{{$row->STOCK_MIN}}</td>
															<td >{{$row->LAST_STOCK}}</td>
															<td >{{$row->UNIT}}</td>

														</tr>
													@endforeach

												</tbody>
											</table>
											{{$DATA_SPAREPART->appends(['SEARCH_SPAREPART'=>$SEARCH,'SORT_LIMIT' => $SORT_LIMIT])->links('pagination.default')}}
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
		function submit_btn(){
			$('#BTN_SUBMIT').click();
		}
	</script>


@stop
{{-- ปิดส่วนjava --}}
