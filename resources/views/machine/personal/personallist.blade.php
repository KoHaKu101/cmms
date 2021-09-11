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
						<div class="row">
							<div class="col-md-12 gx-4">


							</div>
						</div>
          </div>
				</div>
				<div class="py-12">
	        <div class="container mt-2">
						<div class="row">
							<div class="col-md-12">
								<div class="card ">
									<form action="{{ route('personal.list')}}" method="POST" enctype="multipart/form-data">
									<div class="card-header bg-primary  ">
											@method('GET')
											@csrf
											<div class="row">
												<div class="col-md-10 form-inline">
													<h4 class="ml-3 mt-2 " style="color:white;" ><i class="fas fa-cog fa-lg mr-1"></i> พนักงานซ่อมบำรุง </h4>

													<div class="input-group ml-4  mt-1">
														<input type="text" id="SEARCH"  name="SEARCH"  class="form-control form-control-sm" value="{{ $SEARCH }}">
														<div class="input-group-prepend">
															<button type="submit" class="btn btn-search pr-1 btn-xs	">
																<i class="fa fa-search search-icon"></i>
															</button>
														</div>
													</div>
												</div>
												<div class="col-md-2 text-right">
													<a href="{{ route('personal.form') }}" class="btn btn-warning btn-xs mt-2">
														<span class="fas fa-file fa-lg">	New	</span>
													</a>
												</div>
											</div>


									</div>
								</form>

									{{-- content --}}
									<div class="container mt-4">
										<div class="row">

											@php
												$EMP_POSITION = array();
												$EMP_PAY_TYPE = array();
												foreach ($data_emppostion as  $row_position){
													$EMP_POSITION[$row_position->POSITION_CODE] = $row_position->POSITION_NAME;
												}
												foreach ($data_emppaytype as $row_paytype) {
													$EMP_PAY_TYPE[$row_paytype->TYPE_CODE]= $row_paytype->TYPE_NAME;
												}

											@endphp
											@foreach ($dataset as $key => $dataitem)
												@php
											   $EMP_ICON = $dataitem->EMP_ICON != '' ?	'image/emp/'.$dataitem->EMP_ICON : 'assets/img/no_image1200_900.png';
												 $POSITION = $dataitem->POSITION_CODE;
												 $PAY_TYPE = $dataitem->EMP_TYPE;
												 // dd($PAY_TYPE);
												 if (array_key_exists($POSITION,$EMP_POSITION) && array_key_exists($PAY_TYPE,$EMP_PAY_TYPE)) {
													 $POSITION = $EMP_POSITION[$dataitem->POSITION_CODE];
													 $PAY_TYPE = $EMP_PAY_TYPE[$dataitem->EMP_TYPE];
												 }else {
												 	$POSITION = "";
													$PAY_TYPE = "";
												 }
												@endphp
												<div class="col-md-6 col-lg-3">
													<div class="card card-post card-round">
														<a href="{{ url('machine/personal/edit/'.$dataitem->UNID) }}">
															<img class="card-img-top" src="{{ asset($EMP_ICON) }}" width="50px" height="220px"alt="Card image cap">
														</a>
														<div class="card-body" style="background: #eef1c5;">
																<h3 class="card-text my-1"><b>{{ $dataitem->EMP_NAME_TH }}</b></h3>
																<h5 >รหัสพนักงาน : {{ $dataitem->EMP_CODE }}</h5>
																<h5 >ตำแหน่ง : {{ $POSITION }}</h5>
																<h5 >ประจำ : {{ $dataitem->EMP_LINE }} </h5>
																<h5 >ประเภท : {{ $PAY_TYPE }} </h5>
																<div class="row">
																	<div class="col-6 col-md-6">
																		<a href="{{ url('machine/personal/edit/'.$dataitem->UNID) }}" class=" my-1 btn btn-primary btn-sm  btn-block">
																				<i class="fas fa-edit fa-lg  "> Edit</i>
																		</a>
																	</div>
																	<div class="col-6 col-md-6">
																		<a  class="btn btn-danger btn-sm text-white btn-block my-1 "
																		data-unid="{{ $dataitem->UNID }}"	onclick="deletepersonal(this)">
																				<i class="fas fa-trash fa-lg   ">	Delete</i>
																		</a>
																	</div>


																</div>
														</div>

													</div>
												</div>
											@endforeach
										</div>
										{{ $dataset->appends(['SEARCH'=>$SEARCH])->links('pagination.default') }}
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
<script>
function deletepersonal(thisdata){
var unid = $(thisdata).data('unid');
var url = '/machine/personal/delete/'+unid;
Swal.fire({
		title: 'ต้องการลบบุคคลนี้มั้ย?',
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'ใช่!'
	}).then((result) => {
		if (result.isConfirmed) {
			window.location.href = url;
		}
	});
}
</script>

@stop
{{-- ปิดส่วนjava --}}
