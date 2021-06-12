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
				<!--ส่วนปุ่มด้านบน-->
				<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
					<div class="container">
						<div class="row">
							<div class="col-md-1">
								<a href="{{ url('machine/machinetypetable/list') }}">
									<button class="btn btn-warning  btn-xs ">
										<span class="fas fa-arrow-left fa-lg ">Back </span>
									</button>
								</a>
							</div>
							<div class="col-md-11 ">
								<form action="{{ route('machinetypetable.store') }}" method="POST" enctype="multipart/form-data">
									@csrf
									<button class="btn btn-primary btn-xs" type="submit">
										<span class="fas fa-save fa-lg ">	Save	</span>
									</button>
							</div>
						</div>
					</div>
				</div>
				<!--ส่วนกรอกข้อมูล-->
				<div class="py-12">
	        <div class="container mt-2">
						<div class="card">
							<div class="card-header bg-primary">
								<h4 class="ml-3 mt-2" style="color:white;" >ลงทะเบียนประเภทเครื่องจักร </h4>
							</div>

							<div class="card-body">
								<div class="row">
									<!-- ช่อง1-->
										<div class="col-md-6 col-lg-3">
											<div class="form-group mt-4">
												<img src="/assets/img/nobody.jpg" width="200" height="200px" class="mt-4">
													<input type="file" class="form-control mt-4" id="TYPE_ICON" name="TYPE_ICON" accept="image/*">
											</div>
										</div>
										<!-- ช่อง2-->
										<div class="col-md-6 col-lg-4">
											<div class="form-group has-error">
												<label for="TYPE_CODE">รหัสประเภทเครื่องจักร</label>
													<input type="text" class="form-control" id="TYPE_CODE" name="TYPE_CODE" placeholder="รหัสประเภทเครื่องจักร" required autofocus>

											</div>
											<div class="col-md-6 col-lg-12">
												<div class="form-group ">
													<label for="EMP_NAME">อธิบายเพิ่มเติม</label>
													<textarea class="form-control" id="TYPE_NOTE" name="TYPE_NOTE" rows="6"></textarea>
												</div>

											</div>


										</div>
										<!-- ช่อง3-->
										<div class="col-md-6 col-lg-4">
											<div class="form-group has-error">
												<label for="TYPE_NAME">ชื่อประเภทเครื่องจักร</label>
												<input type="text" class="form-control" id="TYPE_NAME" name="TYPE_NAME" placeholder="ชื่อประเภทเครื่องจักร" required autofocus>

											</div>
											<div class="form-check has-error">
												<label for="TYPE_STATUS">เปิด/ปิด</label><br>
												<label class="form-radio-label">
													<input class="form-radio-input" type="radio" name="TYPE_STATUS" value="9" checked="">
													<span class="form-radio-sign">เปิด</span>
												</label>
												<label class="form-radio-label ml-3">
													<input class="form-radio-input" type="radio" name="TYPE_STATUS" value="1">
													<span class="form-radio-sign">ปิด</span>
												</label>
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
