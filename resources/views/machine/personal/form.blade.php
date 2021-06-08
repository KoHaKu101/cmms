@extends('masterlayout.masterlayout')
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
								<a href="{{ url('machine/personal/personallist') }}">
									<button class="btn btn-warning  btn-xs ">
										<span class="fas fa-arrow-left fa-lg ">Back </span>
									</button>
								</a>
							</div>
							<div class="col-md-11 ">
								<form action="{{ route('personal.store') }}" method="POST" enctype="multipart/form-data">
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
								<h4 class="ml-3 mt-2" style="color:white;" >ลงทะเบียนพนักงาน </h4>
							</div>
							<div class="card-body">
								<div class="row">
									<!-- ช่อง1-->
										<div class="col-md-6 col-lg-3">
											<div class="form-group mt-4">
												<img src="/assets/img/nobody.jpg" width="200" height="200px" class="mt-4">
													<input type="file" class="form-control mt-4" id="EMP_ICON" name="EMP_ICON" accept="image/*">

											</div>
										</div>
										<!-- ช่อง2-->
										<div class="col-md-6 col-lg-4">
											<div class="form-group has-error">
												<label for="EMP_CODE">รหัสพนักงาน</label>
													<input type="text" class="form-control" id="EMP_CODE" name="EMP_CODE" placeholder="รหัสพนักงาน" required autofocus>

											</div>


											<div class="row ml-1 mt-2">

												<div class="form-group col-md-12 has-error">
													<lebel>ประจำ LINE</lebel>
													<select class="form-control form-control" id="EMP_GROUP" name="EMP_GROUP">
													<option value>--แสดงทั้งหมด--</option>
													@foreach($datalineselect as $dataline)
													<option value="{{ $dataline->LINE_NAME}}"> {{$dataline->LINE_NAME}} </option>
													@endforeach
												</select>
						  				</div>
											</div>

										</div>
										<!-- ช่อง3-->
										<div class="col-md-6 col-lg-4">
											<div class="form-group has-error">
												<label for="EMP_NAME">ชื่อพนักงาน</label>
												<input type="text" class="form-control" id="EMP_NAME" name="EMP_NAME" placeholder="ชื่อพนักงาน" required autofocus>
											</div>
											<div class="form-group col-md-12 has-error">
												<lebel>ตำแหน่งงาน</lebel>
												<select class="form-control form-control" id="POSITION" name="POSITION" required>
												<option value>--แสดงทั้งหมด--</option>
												<option value="SUPER">หัวหน้างาน</option>
												<option value="FULLTIME">พนักงานประจำ</option>
												<option value="DAILY">พนักงานรายวัน</option>
												</select>
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
