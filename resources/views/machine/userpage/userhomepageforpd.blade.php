@extends('masterlayout.masterlayout')
@section('tittle','homepage')
@section('css')
	<link href={{ asset('/assets/fullcalendar/main.css') }} rel='stylesheet' />

{{-- <link rel="stylesheet" href="{{ asset('assets/icofont/icofont.min.css') }}"> --}}
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
											<h4 class="my-2">Repair List/รายการแจ้งซ่อม</h4>
										</div>
										<a href="{{ route('pd.repairlist') }}">
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
<script>
	$(document).on('click','#reportpm',function(){
		alert('1');
		window.open('/machine/pdf/plan/planpm','PdfPlanPm','width=1000,height=1000,resizable=yes,top=100,left=100,menubar=yes,toolbar=yes,scroll=yes');
	});
</script>

@stop
{{-- ปิดส่วนjava --}}
