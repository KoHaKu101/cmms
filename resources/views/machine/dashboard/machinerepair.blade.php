@extends('masterlayout.masterlayout')
@section('tittle','homepage')
@section('css')
{{-- <script type="text/javascript" src="{{asset('/assets/js/useinproject/echarts.min.js')}}"></script> --}}

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
	<style>
	 .btn-green{
		 background:#13FF49;
		 border-color:#13FF49;
		 color:white;
	 }
	 .btn-pink{
		 background:#F32AFC;
		 border-color:#F32AFC;
		 color:white;
	 }
	 #btntop {
		 display: none;
		 position: fixed;
		 bottom: 42px;
		 right: 25px;
		 z-index: 99;
		 font-size: 13px;
		 border: none;
		 outline: none;
		 background-color: #1572e8;
		 color: white;
		 cursor: pointer;
		 padding: 12px;
		 border-radius: 8px;
	 }

	 #btntop:hover {
		 background-color: #003C89;
	 }
	</style>
	@php
		$MONTH_NAME_TH = array(0 =>'ALL',1 => "มกราคม",2 => "กุมภาพันธ์",3 =>"มีนาคม",4 => "เมษายน",5 =>"พฤษภาคม",6 =>"มิถุนายน",
										 7 =>"กรกฎาคม",8 =>"สิงหาคม",9 =>"กันยายน",10 =>"ตุลาคม",11 => "พฤศจิกายน",12 =>"ธันวาคม");
		$CURRENT_MONTH = $MONTH_NAME_TH[date('n')].' ปี '.date('Y')+543;
		$BTN_COLOR_LINE = array('1'=>'btn-info','2'=>'btn-warning','3'=>'btn-green'
													 ,'4'=>'btn-danger','5'=>'btn-pink','6'=>'btn-primary');
	@endphp
	<button onclick="topFunction()" id="btntop" title="Go to top"><i class="fas fa-arrow-circle-up fa-lg"></i></button>
		<div class="content">
			<div class="panel-header bg-primary-gradient">
				<div class="page-inner py-5">
					<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
						<div>
							<h2 class="text-white pb-2 fw-bold">Dashboard MachineRepair</h2>
						</div>
						</div>
				</div>
			</div>
			<div class="page-inner mt--4">
				<div class="row">
					<div class="col-md-3 my-2">
						<a href="{{ route('dashboard.dashboard') }}">
							<button class="btn btn-warning btn-sm"><i class="fas fa-arrow-left mx-2"></i>Back</button>
						</a>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<div class="card">
							<div class="card-header bg-primary">
								<div class="row">
									<div class="col-md-8 ">
										<div class="card-title form-inline text-white">
											<div class="text-left mr-auto ">
												เครื่องจักร เสียมากสุด ในแต่ล่ะ Line
											</div>
											<div class="mr-auto">
												เดือน {{$CURRENT_MONTH}}
											</div>
										</div>
									</div>

								</div>
							</div>
							<div class="card-body">

							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="page-inner mt--5">

				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header bg-primary">
								<div class="card-title">
									<div class="row">
										<div class="col-md-8 ">
											<div class="card-title form-inline text-white">
												<div class="text-left mr-auto ">
													 รายละเอียด เครื่องจักรเสียบ่อย
												</div>
												<div class="mr-auto">
													เดือน {{$CURRENT_MONTH}}
												</div>
											</div>
										</div>

									</div>
								</div>
							</div>
							<div class="card-body">
								<div class="table">
									<table class="table table-bordered table-head-bg-info table-bordered-bd-info" id="table_dowtime">
										<thead>
											<tr>
												<th width="2%">No.</th>
												<th width="7%" class="text-center">MC-CODE</th>
												<th width="15%">MC-NAME</th>
												<th width="15%">สาเหตุ / อาการที่เสีย</th>
												<th width="15%">วิธีแก้ไข</th>
												<th width="6%">เวลา(นาที)</th>
												<th width="6%">รวม(นาที)</th>

											</tr>
										</thead>
										<tbody>

										</tbody>
									</table>
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

{{-- <script type="text/javascript" src="{{asset('/echart/echarts-en.common.min.js')}}"></script> --}}
<script src="{{asset('/assets/js/plugin/chart.js/echarts.js')}}"></script>
<script src="{{ asset('../../assets/js/plugin/datatables/datatables.min.js')}}"></script>
<script src="{{ asset('assets/js/btntop.js') }}"></script>
<script src="{{ asset('assets/js/dataTables.rowsGroup.js')}}"></script>

<script>
//
// $('#table_dowtime').DataTable({
// 		'rowsGroup': [0,1,2,6],
// 		"pageLength": 20,
// 		"bLengthChange": false,
// 		"bFilter": true,
// 		"bInfo": false,
// 		"bAutoWidth": false,
// 		searching: false,
// 		paging: false,
// 		columnDefs: [
// 		{ orderable: false, targets:[0,1,2,3,4,5,6] }
// 	]
// 	});
</script>


@stop
{{-- ปิดส่วนjava --}}
