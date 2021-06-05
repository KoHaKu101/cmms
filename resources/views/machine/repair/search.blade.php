@extends('masterlayout.masterlayout')
@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('tittle','homepage')
@section('css')


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
				<!--ส่วนปุ่มด้านบน-->
				<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
					<div class="container">
						<div class="row">
							<div class="col-md-1">
								<a href="{{ url('machine/repair/repairlist') }}">
									<button class="btn btn-warning  btn-xs ">
										<span class="fas fa-arrow-left fa-lg">Back </span>
									</button>
								</a>
							</div>
						</div>
					</div>
				</div>
				<!--ส่วนกรอกข้อมูล-->
				<div class="py-12">
	        <div class="container mt-2">
						<div class="card">
						  <div class="card-header">
						  <div class="row justify-content-md-center">
						    <div class="col-md-6 col-lg-5 ">
						      <h3 >กรอกรหัสเครื่อง / แสกนQR Code</h3>
									<form action="{{ route('repair.repairsearch') }}" method="POST">
										@method('GET')
										@csrf
										<div class="input-group mb-3">
											<input type="text" class="form-control" id="search" name="search"
											 placeholder="กรอกรหัสเครื่อง / แสกนQR Code ที่นี้" autofocus>
											<div class="input-group-append">
												<span class="input-group-text" id="basic-addon2">
													<button type="submit" class="btn btn-primary btn-sm btn-link"><i class="fas fa-search"></i></button>
												</span>
											</div>
										</div>

									</form>

						    </div>
						  </div>
						  </div>
						  <div class="card-body">
						    <div class="row">
						      @if ($machine != NULL)
						        @foreach ($machine as $key => $dataset)
						        <div class="col-md-6 col-lg-3 ml-auto mr-auto">
						        <div class="card card-post card-round">
						        <div class="card-header bg-primary text-white">
						        <center><h4 class="mt-1"><b> {{$dataset->MACHINE_CODE}} </b></h4></center>
						        </div>
						        <div class="card-body">
						        <span>Machine Name : {{$dataset->MACHINE_NAME}}</span><br/>
						        <span class="mt-3"> Line : {{$dataset->MACHINE_LINE}}</span><br/>
						        <a href="{{ url('machine/repair/form/'.$dataset->UNID)}}" class="btn btn-success btn-sm btn-block my-1">
						        <span style="font-size:13px">
						         <i class="fas fa-hand-pointer fa-lg mx-2"></i>แจ้งอาการเสีย
						          </span>
						        </a>
						        </div>
						        </div>
						        </div>
						        @endforeach
						      @else
						        
						      @endif
						    </div>
						  </div>
						</div>
						{{-- @livewire('searchnewrepair') --}}
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
