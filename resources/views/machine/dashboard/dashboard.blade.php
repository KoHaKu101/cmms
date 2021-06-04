@extends('masterlayout.masterlayout')
@section('tittle','homepage')
@section('css')
<script type="text/javascript" src="{{asset('/assets/js/useinproject/echarts.min.js')}}"></script>

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
			<div class="panel-header bg-primary-gradient">
				<div class="page-inner py-5">
					<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
						<div>
							<h2 class="text-white pb-2 fw-bold">Dashboard</h2>
						</div>
						</div>
				</div>
			</div>
			<div class="page-inner mt--5">
				<div class="row">
					<div class="col-sm-6 col-md-3">
						<div class="card card-stats card-primary card-round">
							<div class="card-body">
								<div class="row">
									<div class="col-5">
										<div class="icon-big text-center">
											<i class="fas fa-industry"></i>
										</div>
									</div>
									<div class="col-7 col-stats">
										<div class="numbers">
											<p class="card-category">เครื่องจักรทั้งหมด</p>
											<h4 class="card-title">{{$dataset->where('MACHINE_CHECK','!=','4')->count()}}</h4>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-md-3">
						<div class="card card-stats card-success card-round">
							<div class="card-body">
								<div class="row">
									<div class="col-5">
										<div class="icon-big text-center">
											<i class="fas fa-user-check"></i>
										</div>
									</div>
									<div class="col-7 col-stats">
										<div class="numbers">
											<p class="card-category">เครื่องเปิดใช้งาน</p>

											<h4 class="card-title">{{$dataset->where('MACHINE_CHECK','=','2')->count()}}</h4>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-md-3">
						<div class="card card-stats card-warning card-round">
							<div class="card-body">
								<div class="row">
									<div class="col-5">
										<div class="icon-big text-center">
											<i class="fas fa-user-clock"></i>
										</div>
									</div>
									<div class="col-7 col-stats">
										<div class="numbers">
											<p class="card-category">เครื่องรอขึ้นงาน</p>
											<h4 class="card-title">{{$dataset->where('MACHINE_CHECK','=','1')->count()}}</h4>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-md-3">
						<div class="card card-stats card-danger card-round">
							<div class="card-body">
								<div class="row">
									<div class="col-5">
										<div class="icon-big text-center">
											<i class="fas fa-toolbox fa-lg"></i>
										</div>
									</div>
									<div class="col-7 col-stats">
										<div class="numbers">
											<p class="card-category">เครื่องแจ้งซ่อม</p>
											<h4 class="card-title"> {{ $datarepair->where('CLOSE_STATUS','=','9')->count() }}</h4>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="page-inner mt--5">
				<div class="row">
					<div class="col-md-12">
					<div class="card full-height">
						<div class="card-body">
							<div class="card-title">เครื่องจักรในแต่ล่ะ LINE </div>

							<div class="d-flex flex-wrap justify-content-around pb-2 pt-4">
								<div class="px-2 pb-2 pb-md-0 text-center">
									<div id="circles-1"></div>
									<h6 class="fw-bold mt-3 mb-0">Line 1</h6>
								</div>
								<div class="px-2 pb-2 pb-md-0 text-center">
									<div id="circles-2"></div>
									<h6 class="fw-bold mt-3 mb-0">Line 2</h6>
								</div>
								<div class="px-2 pb-2 pb-md-0 text-center">
									<div id="circles-3"></div>
									<h6 class="fw-bold mt-3 mb-0">Line 3</h6>
								</div>
								<div class="px-2 pb-2 pb-md-0 text-center">
									<div id="circles-4"></div>
									<h6 class="fw-bold mt-3 mb-0">Line 4</h6>
								</div>
								<div class="px-2 pb-2 pb-md-0 text-center">
									<div id="circles-5"></div>
									<h6 class="fw-bold mt-3 mb-0">Line 5</h6>
								</div>
								<div class="px-2 pb-2 pb-md-0 text-center">
									<div id="circles-6"></div>
									<h6 class="fw-bold mt-3 mb-0">Line 6</h6>
								</div>

							</div>
						</div>
					</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-5">
							<div class="card full-height">
								<div class="card-header">
									<div class="card-head-row">
										<div class="card-title">แจ้งซ่อม</div>
										<div class="card-tools">
											<ul class="nav nav-pills nav-secondary nav-pills-no-bd nav-sm" id="pills-tab" role="tablist">
												<li class="nav-item">
													<a class="nav-link active" id="pills-today" data-toggle="pill" href="#pills-today" role="tab" aria-selected="true">Today</a>
												</li>
											</ul>
										</div>
									</div>
								</div>
								<div class="card-body">

									@foreach($datarepairlist as $dataitem)
										{{-- @for($i = 1; $i < 9-$data_set ; $i++) --}}
										<div class="row">
									<div class="d-flex col-md-6 col-lg-1">
										<input type="hidden" value="1">
										<div class="avatar avatar-online">
											<span class="avatar-title rounded-circle border border-white bg-danger" style="width:50px"><i class="fa fa-wrench"></i></span>
										</div>


									</div>
									<div class="flex-1 ml-3 pt-1 col-md-6 col-lg-7">
										<h4 class="text-uppercase fw-bold mb-1">{{$dataitem->MACHINE_CODE}}
										<span class="text-success pl-3">	{{$dataitem->MACHINE_STATUS == '9' ? 'ทำงานปกติ' : 'หยุดทำงาน'}}
										</span></h4>

										<span class="text-muted" >{{ $dataitem->REPAIR_SUBSELECT_NAME }}</span>
									</div>
									<div class="float-right pt-1 col-md-6 col-lg-3">
										<h5 class="text-muted">{{$dataitem->DOC_DATE}}</h5>
									</div>
									</div>
									<hr>
									{{-- @endfor --}}
								@endforeach

							</div>
						</div>
					</div>
					<div class="col-md-7" >
						<div class="card">
							<div class="card-header">
								<div class="card-title">แจ้งซ่อมแต่ล่ะ LINE</div>
							</div>
							<div class="card-body">
								<div class="chart-container">
									<div id="repair" style="width: 650px;height:350px;"></div>
								</div>
							</div>
						</div>
						<div class="card">
							<div class="card-header">
									<div class="card-title">ค่าซ่อมประจำเดือน
										<a href="{{url('machine/dashboard/sumaryline')}}" class="btn btn-primary float-right" style="color:white">ค่าซ่อมแต่ล่ะ Line</a>
									</div>
							</div>
							<div class="card-body">
								<div class="chart-container">
									<div id="price" style="width: 650px;height:350px;"></div>
								</div>
							</div>
						</div>
					</div>

			</div>
		</div>
		<footer class="footer">
			<div class="container-fluid">
				<nav class="pull-left">
					<ul class="nav">
						<li class="nav-item">
							<a class="nav-link" href="https://www.themekita.com">
								ThemeKita
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#">
								Help
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#">
								Licenses
							</a>
						</li>
					</ul>
				</nav>
				<div class="copyright ml-auto">
					2018, made with <i class="fa fa-heart heart text-danger"></i> by <a href="https://www.themekita.com">ThemeKita</a>
				</div>
			</div>
		</footer>

@stop
{{-- ปิดส่วนเนื้อหาและส่วนท้า --}}

{{-- ส่วนjava --}}
@section('javascript')

<script type="text/javascript" src="{{asset('/echart/echarts-en.common.min.js')}}"></script>
<script src="{{asset('/assets/js/plugin/chart.js/chart.min.js')}}"></script>
<script src="{{asset('/assets/js/plugin/chart-circle/circles.min.js')}}"></script>
	{{-- แจ้งซ่อมแต่ล่ะLine--}}
<script type="text/javascript" src="{{ asset('assets/js/useinproject/dashboard/repair.js') }}">
	</script>
	{{-- ค่าใช้จ่าย--}}
<script type="text/javascript" src="{{ asset('assets/js/useinproject/dashboard/repairpay.js') }}">
	</script>
<script type="text/javascript" >
	Circles.create({
	  id:'circles-1',
	  radius:45,
	  value:{{$data_line->where('MACHINE_LINE','L1')->count()}},
	  maxValue:500,
	  width:10,
	  text: {{$data_line->where('MACHINE_LINE','L1')->count()}},
	  colors:['#585963', '#14BAFD'],
	  duration:400,
	  wrpClass:'circles-wrp',
	  textClass:'circles-text',
	  styleWrapper:true,
	  styleText:true
	})
	Circles.create({
	  id:'circles-2',
	  radius:45,
	  value:{{$data_line->where('MACHINE_LINE','L2')->count()}},
	  maxValue:500,
	  width:10,
	  text: {{$data_line->where('MACHINE_LINE','L2')->count()}},
	  colors:['#585963', '#FF944F'],
	  duration:400,
	  wrpClass:'circles-wrp',
	  textClass:'circles-text',
	  styleWrapper:true,
	  styleText:true
	})
	Circles.create({
	  id:'circles-3',
	  radius:45,
	  value:{{$data_line->where('MACHINE_LINE','L3')->count()}},
	  maxValue:500,
	  width:10,
	  text: {{$data_line->where('MACHINE_LINE','L3')->count()}},
	  colors:['#585963', '#BAFF4F'],
	  duration:400,
	  wrpClass:'circles-wrp',
	  textClass:'circles-text',
	  styleWrapper:true,
	  styleText:true
	})
	Circles.create({
	  id:'circles-4',
	  radius:45,
	  value:{{$data_line->where('MACHINE_LINE','L4')->count()}},
	  maxValue:500,
	  width:10,
	  text: {{$data_line->where('MACHINE_LINE','L4')->count()}},
	  colors:['#585963', '#FF4F4F'],
	  duration:400,
	  wrpClass:'circles-wrp',
	  textClass:'circles-text',
	  styleWrapper:true,
	  styleText:true
	})
	Circles.create({
	  id:'circles-5',
	  radius:45,
	  value:{{$data_line->where('MACHINE_LINE','L5')->count()}},
	  maxValue:500,
	  width:10,
	  text: {{$data_line->where('MACHINE_LINE','L5')->count()}},
	  colors:['#585963', '#FF4FCF'],
	  duration:400,
	  wrpClass:'circles-wrp',
	  textClass:'circles-text',
	  styleWrapper:true,
	  styleText:true
	})
	Circles.create({
	  id:'circles-6',
	  radius:45,
	  value:{{$data_line->where('MACHINE_LINE','L6')->count()}},
	  maxValue:500,
	  width:10,
	  text: {{$data_line->where('MACHINE_LINE','L6')->count()}},
	  colors:['#585963', '#4F62FF'],
	  duration:400,
	  wrpClass:'circles-wrp',
	  textClass:'circles-text',
	  styleWrapper:true,
	  styleText:true
	})

	</script>


@stop
{{-- ปิดส่วนjava --}}
