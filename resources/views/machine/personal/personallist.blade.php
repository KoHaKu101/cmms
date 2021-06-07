@extends('masterlayout.masterlayout')
@section('tittle','homepage')
@section('css')
{{-- <link rel="stylesheet" href="{{asset('assets/css/bulma.min.css')}}"> --}}
@endsection
{{-- ส่วนหัว --}}
@section('Logoandnavbar')

	@include('masterlayout.logomaster')
	@include('masterlayout.navbar.navbarmaster')

@stop
{{-- ปิดท้ายส่วนหัว --}}

{{-- ส่วนเมนู --}}
@section('sidebar')

	@include('masterlayout.sidebar.sidebarmaster0')

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

								<a href="{{ route('personal.form') }}"><button class="btn btn-primary  btn-xs">
									<span class="fas fa-file fa-lg">	New	</span>
								</button></a>
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
									<div class="card-header bg-primary form-inline ">
											@method('GET')
											@csrf
											<h4 class="ml-3 mt-2 " style="color:white;" ><i class="fas fa-cog fa-lg mr-1"></i> พนักงานซ่อมบำรุง </h4>
												<div class="input-group ml-4">
													<input type="text" id="SEARCH"  name="SEARCH"  class="form-control form-control-sm" value="{{ $SEARCH }}">
													<div class="input-group-prepend">
														<button type="submit" class="btn btn-search pr-1 btn-xs	">
															<i class="fa fa-search search-icon"></i>
														</button>
													</div>
												</div>
									</div>
								</form>

									{{-- content --}}
									<div class="container mt-4">
										<div class="row">
											@foreach ($dataset as $key => $dataitem)
												@php
												$EMP_ICON = $dataitem->EMP_ICON != '' ?	'image/emp/'.$dataitem->EMP_ICON : 'assets/img/no_image1200_900.png';
												$POSITION = array(''=>'','SUPER'=>'หัวหน้างาน','FULLTIME'=>'พนักงานประจำ','DAILY'=>'พนักงานรายวัน');

												@endphp
												<div class="col-md-6 col-lg-3">
													<div class="card card-post card-round">
														<img class="card-img-top" src="{{ asset($EMP_ICON) }}" height="185px"alt="Card image cap">
														<div class="card-body">
															<div class="separator-solid"></div>
																<h3 class="card-text my-1">{{ $dataitem->EMP_NAME2 }}</h3>
																<h5 >รหัสพนักงาน : {{ $dataitem->EMP_CODE }}</h5>
																<h5 >ตำแหน่งงาน : {{ $POSITION[$dataitem->POSITION] }}</h5>
																<h5 >ประจำ {{ $dataitem->EMP_GROUP }} </h5>
																<div class="row">
																	<a href="{{ url('machine/personal/edit/'.$dataitem->UNID) }}">
																		<span style="color: green;">
																			<i class="fas fa-edit fa-lg mx-1 my-1">แก้ไขข้อมูล</i>
																		</span>
																	</a>
																	<a style="cursor:pointer"
																	data-unid="{{ $dataitem->UNID }}"	onclick="deletepersonal(this)"
																		 class="ml-3 float-right">
																		<span style="color: Tomato;">
																			<i class="fas fa-trash fa-lg mx-1 my-1">	Delete</i>
																		</span>
																	</a>
																</div>
														</div>

													</div>
												</div>
											@endforeach
										</div>
										{{ $dataset->links() }}
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
