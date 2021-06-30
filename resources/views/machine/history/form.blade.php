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
							<div class="col-md-1 mt-2">
								<a href="{{ url('machine/table/repairlist') }}">
									<button class="btn btn-primary  btn-sm ">
										<span class="fas fa-arrow-left ">Back </span>
									</button>
								</a>
							</div>
							<div class="col-md-1 mt-2 ">
								<form action="{{ route('tablerepair.store') }}" method="POST" enctype="multipart/form-data">
									@csrf
									<button class="btn btn-success btn-sm" type="submit">
										<span class="fas fa-file-medical ">	Save	</span>
									</button>
							</div>
						</div>
					</div>
				</div>
				<!--ส่วนกรอกข้อมูล-->
				<div class="py-12">
	        <div class="container mt-2">
						<div class="card">
							<div class="">
								<div class="form-inline bg-primary"><p style="color:white;font-size:17px" class="ml-4 mt-3">เพิ่มระบบรายการแจ้ง</p>
									<div class="btn-group ml-3" role="group	" aria-label="Basic example">
									</div>
									<div class="form-group form-inline ">
									</div>
								</div>
							</div>
							<div class="card-body">
								<div class="row">
										<!-- ช่อง1-->
										<div class="col-md-6 col-lg-4">
											<div class="form-group has-error">
												<label for="REPAIR_CODE">ลำดับรายการ*</label>
												<input type="text" class="form-control" id="REPAIR_CODE" name="REPAIR_CODE" placeholder="ลำดับรายการ**ห้ามซ้ำกัน**" required autofocus >
											</div>
										</div>
										<!-- ช่อง2-->
										<div class="col-md-6 col-lg-4">
											<div class="form-group has-error">
												<label for="REPAIR_NAME">ประเภทอาการ*	</label>
												<input type="text" class="form-control" id="REPAIR_NAME" name="REPAIR_NAME" placeholder="ประเภทอาการ" required autofocus>
											</div>
										</div>
										<!-- ช่อง3-->
										<div class="col-md-8 col-lg-4 ml-2">
											<div class="form-group">
												<label for="REPAIR_NOTE">รายละเอียดเพิ่มเติม</label>
												<textarea class="form-control" id="REPAIR_NOTE" name="REPAIR_NOTE" rows="4"></textarea>
											</div>
										</div>
										</div>
									</div>

								</div>
					</div>
				</div>
				</div>
			</div>
		</form>
	</div>
</div>



@include('masterlayout\tab\modal\scanqrcode')





@stop
{{-- ปิดส่วนเนื้อหาและส่วนท้า --}}

{{-- ส่วนjava --}}
@section('javascript')

@stop
{{-- ปิดส่วนjava --}}
