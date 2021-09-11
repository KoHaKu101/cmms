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
			<div class="panel-header bg-gradient">
				<div class="page-inner py-4 my-4">
					<div class="card">
						<div class="row">
							<div class="col-md-6 text-black text-center">
								<div class="card">
									<div class="card-header bg-primary text-white">
										<h4 class="my-2">Repair/แจ้งซ่อม</h4>
									</div>
									<a href="{{ route('repair.repairsearch') }}">
										<div class="card-body">
											<img src="{{ asset('assets/img/userhomepage/repair.png') }}" class="ml-4"style="width:150px;height:150px">
										</div>
									</a>
								</div>
							</div>
								<div class="col-md-6 text-black text-center">
									<div class="card">
										<div class="card-header bg-primary text-white">
											<h4 class="my-2">Preventive Maintenance/ตรวจเช็คประจำเดือน</h4>
										</div>
										<a href="{{ route('pm.planlist') }}">
											<div class="card-body">
												<img src="{{ asset('assets/img/userhomepage/pm.png') }}" class="ml-4"style="width:150px;height:150px">
											</div>
										</a>
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
@stop
{{-- ปิดส่วนjava --}}
