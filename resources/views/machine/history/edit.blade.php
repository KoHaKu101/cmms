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
								<a href="{{ url('machine/table/sparepartlist') }}">
									<button class="btn btn-primary  btn-sm ">
										<span class="fas fa-arrow-left ">Back </span>
									</button>
								</a>
							</div>
							<div class="col-md-1 mt-2 ">
								<form action="{{ url('machine/table/update/'.$dataset->UNID) }}" method="POST" enctype="multipart/form-data">
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
								<div class="form-inline bg-primary"><p style="color:white;font-size:17px" class="ml-4 mt-3">แก้ไขระบบรายการแจ้ง</p>
									<div class="btn-group ml-3" role="group" aria-label="Basic example">
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
												<label for="SPAREPART_CODE">รหัสอะไหล่*</label>
												<input type="text" class="form-control" id="SPAREPART_CODE" name="SPAREPART_CODE" value="{{$dataset->SPAREPART_CODE}}">
											</div>
										</div>
										<!-- ช่อง2-->
										<div class="col-md-6 col-lg-4">
											<div class="form-group has-error">
												<label for="SPAREPART_NAME">ชื่อรายการอะไหล่*	</label>
												<input type="text" class="form-control" id="SPAREPART_NAME" name="SPAREPART_NAME" value="{{$dataset->SPAREPART_NAME}}">
											</div>
										</div>
										<div class="col-md-6 col-lg-4">
											<div class="form-group has-error">
												<label for="SPAREPART_PRICE">ราคา	</label>
												<input type="text" class="form-control" id="SPAREPART_PRICE" name="SPAREPART_PRICE" value="{{$dataset->SPAREPART_PRICE}}">
											</div>
										</div>
										<!-- ช่อง3-->
										<div class="col-md-8 col-lg-4 ml-2">
											<div class="form-group">
												<label for="SPAREPART_NOTE">รายละเอียดเพิ่มเติม</label>
												<textarea class="form-control" id="SPAREPART_NOTE" name="SPAREPART_NOTE" rows="4" value="{{ $dataset->SPAREPART_NOTE }}"></textarea>
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
