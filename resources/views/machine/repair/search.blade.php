@extends('masterlayout.masterlayout')
@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
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
				<!--ส่วนปุ่มด้านบน-->
				<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
					<div class="container">
						<div class="row">
							<div class="col-md-1">
              @can('isAdmin')
								<a href="{{ url('machine/repair/repairlist') }}">
									<button class="btn btn-warning  btn-xs ">
										<span class="fas fa-arrow-left fa-lg">Back </span>
									</button>
								</a>
              @elsecan('isManager_Ma')
								<a href="{{ url('machine/repair/repairlist') }}">
									<button class="btn btn-warning  btn-xs ">
										<span class="fas fa-arrow-left fa-lg">Back </span>
									</button>
								</a>
							@elsecan('isManager_Pd')
								<a href="{{ route('pd.repairlist') }}">
									<button class="btn btn-warning  btn-xs ">
										<span class="fas fa-arrow-left fa-lg">Back </span>
									</button>
								</a>
              @else
								<a href="{{ url('/machine/user/homepage') }}">
									<button class="btn btn-warning  btn-xs ">
										<span class="fas fa-arrow-left fa-lg">Back </span>
									</button>
								</a>
              @endcan

							</div>
						</div>
					</div>
				</div>
				<!--ส่วนกรอกข้อมูล-->
				<div class="py-12">
	        <div class="container mt-2">
						<div class="card">
						  <div class="card-header">
								<form action="{{ route('repair.repairsearch') }}" method="POST" id="FRM_SEARCH" enctype="multipart/form-data">
									@method('GET')
									@csrf
								  {{-- <div class="row justify-content-md-center"> --}}
										<div class="row justify-content-center">
											<div class="col-12 col-md-5 col-lg-4 ">
												<h3 >กรอกรหัสเครื่อง / แสกนQR Code</h3>
											</div>
										</div>
										<div class="row justify-content-center">
											<div class="col-7 col-md-6 col-lg-4 ">
												<div class="input-group mb-3">
													<input type="text" class="form-control" id="search" name="search"
													 placeholder="กรอกรหัสเครื่อง / แสกนQR Code ที่นี้" autofocus value="{{ $SEARCH }}">
													 <input type="file"  hidden id="QRCODE_FILE" name="QRCODE_FILE" accept="image/*" capture>
													<div class="input-group-append">
														<span class="input-group-text" id="basic-addon2">
															<button type="submit" class="btn btn-primary btn-sm btn-link"><i class="fas fa-search"></i></button>
														</span>
													</div>
												</div>
									    </div>
											<style>
											@media all and (min-width: 883px) {
													.show-btn{
															display: none;
													}
											}
											</style>

											<div class="col-5 col-md-2 col-lg-1 ">
												<button type="button" class="btn btn-warning show-btn"
												id="SCANQRCODE"
												>QR CODE</button>
											</div>
										</div>


								  {{-- </div> --}}
								</form>
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
						        <span>Machine Name : {{$dataset->MACHINE_NAME_TH}}</span><br/>
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

				</div>
				</div>
			</div>

	</div>



@stop
{{-- ปิดส่วนเนื้อหาและส่วนท้า --}}

{{-- ส่วนjava --}}
@section('javascript')
<script>
 $('#SCANQRCODE').on('click',function(){
	 $('#QRCODE_FILE').click();

 });
 $('#QRCODE_FILE').on('change',function(){
	 var file_qr =  $('#QRCODE_FILE').length ;

	 if (file_qr) {
		 $('#FRM_SEARCH').submit();
	 }
 });
</script>
@stop
{{-- ปิดส่วนjava --}}
