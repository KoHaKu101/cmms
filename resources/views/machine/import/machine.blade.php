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
				<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
          <div class="container">
						<div class="row">
							<div class="col-md-12 gx-4">
								<a href="{{ url('machine/assets/machinelist') }}">
								<button class="btn btn-primary  btn-xs ">
									<span class="fas fa-arrow-left fa-lg">Back </span>
								</button>
							</a>
							</div>
						</div>
          </div>
				</div>
				<div class="py-12">
	        <div class="container mt-2">
						<div class="row">
							<div class="col-md-12">
								<div class="card ">
									<div class="card-header">
										<h4>Import</h4>

									</div>
									<div class="card-body">
										@if(session('status'))
												<div class="alert alert-success" role="alert">
													{{ session('status') }}
												</div>
										@endif
									</div>
									<form action={{ url('users/import') }} method="post" enctype="multipart/form-data">
										@csrf
											<div class="form-group">
												<input type="file" name="file">
												<button type="submit" class="btn btn-primary" >import
												</button>
											</div>

									</form>


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

@stop
{{-- ปิดส่วนjava --}}
