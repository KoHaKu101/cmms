@extends('masterlayout.masterlayout')
@section('tittle','homepage')
@section('css')
<script type="text/javascript" src="{{asset('/assets/js/useinproject/echarts.min.js')}}"></script>

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
	</style>
	@php
		$MONTH_NAME_TH = array(0 =>'ALL',1 => "มกราคม",2 => "กุมภาพันธ์",3 =>"มีนาคม",4 => "เมษายน",5 =>"พฤษภาคม",6 =>"มิถุนายน",
										 7 =>"กรกฎาคม",8 =>"สิงหาคม",9 =>"กันยายน",10 =>"ตุลาคม",11 => "พฤศจิกายน",12 =>"ธันวาคม");
		$CURRENT_MONTH = $MONTH_NAME_TH[date('n')].' ปี '.date('Y')+543;
		$BTN_COLOR_LINE = array('LINE 1'=>'btn-info','LINE 2'=>'btn-warning','LINE 3'=>'btn-green'
													 ,'LINE 4'=>'btn-danger','LINE 5'=>'btn-pink','LINE 6'=>'btn-primary');
	@endphp
	<audio id="music" src="{{asset('assets/sound/mixkit-arabian-mystery-harp-notification-2489.wav')}}" ></audio>
	<button type="button" style="display:none;" id="startbtn"></button>
		<div class="content">
			<div class="panel-header bg-primary-gradient">
				<div class="page-inner py-5">
					<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
						<div>
							<h2 class="text-white pb-2 fw-bold">Dashboard PM && PDM</h2>
						</div>
						</div>
				</div>
			</div>
			<div class="page-inner mt--5">
				<div class="row">
					<div class="col-sm-12">
						<div class="card">
							<div class="card-header">
								<div class="row">
									<div class="col-md-8 ">
										<div class="card-title form-inline">
											<div class="text-left mr-auto">
												แผน PM ในแต่ละ Line
											</div>
											<div class="mr-auto">
												เดือน {{$CURRENT_MONTH}}
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="card-body">
								<canvas id="PlanPm" width="1150%" height="380%"></canvas>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="page-inner mt--5">
				<div class="row">
					<div class="col-md-8">

						<div class="card">
							<div class="card-header bg-primary ">
								<div class="row">
									<div class="card-title text-white">
										กรุณาเลือก PM ในแต่ละ Line
									</div>
								</div>

							</div>
							<div class="card-header">
								<div class="row">
									@foreach ($BTN_COLOR_LINE as $name => $color)
										<button class="btn {{$color}} btn-sm ml-auto mr-auto">{{ $name }}</button>
									@endforeach
								</div>
							</div>
							<div class="card-body">
								<div class="row">
									<h5>ตารางรายการ PM ของ Line 1</h5>
								</div>
								<table class="table table-bordered table-head-bg-info table-bordered-bd-info ">
											<thead>
												<tr>
													<th scope="col">#</th>
													<th scope="col">Machine NO.</th>
													<th scope="col">Machine Name</th>
													<th scope="col">ความถี่ (เดือน)</th>
													<th scope="col">สถานะ</th>
													<th scope="col">ผู้ตรวจสอบ</th>
													<th scope="col">วันที่ตรวจสอบ</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>1</td>
													<td>MC–005</td>
													<td>KITAMURA</td>
													<td>3</td>
													<td>ยังไม่ได้ตรวจสอบ</td>
													<td>อนุลักษณ์ รัตนประเสริฐ</td>
													<td>22 / 07 / 2021</td>
												</tr>
												<tr>
													<td>2</td>
													<td>Jacob</td>
													<td>Thornton</td>
													<td>@fat</td>
													<td>Jacob</td>
													<td>Thornton</td>
													<td>@fat</td>
												</tr>
												<tr>
													<td>3</td>
													<td>Jacob</td>
													<td>Thornton</td>
													<td>@fat</td>
													<td>Jacob</td>
													<td>Thornton</td>
													<td>@fat</td>
												</tr>
											</tbody>
										</table>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="card">
							<div class="card-header bg-primary">
								<div class="card-title text-white">
										จำนวน
								</div>
							</div>
							<div class="card-body">
								<canvas id="CHART_CIRCLE_PM"></canvas>
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
<script type="text/javascript">
	var Plan_pm = document.getElementById('PlanPm');
	var chart_Plan_pm = echarts.init(Plan_pm);
	var option;
	option = {
	    tooltip: {
					show:true,
	        trigger: 'axis',
	        axisPointer: {
	            type: 'shadow'
	        }
	    },
	    grid: {
	        left: '3%',
	        right: '0%',
	        bottom: '6%',
					top:'3%',
	        containLabel: true
	    },
			legend: {
	 	      show:true,
					bottom:'-5',
	 	  },
	    xAxis: {
	        type: 'category',
	        data: ['Line 1', 'Line 2', 'Line 3', 'Line 4', 'Line 5', 'Line 6']
	    },
	    yAxis: {
				name:'จำนวนเครื่องจักร (เครื่อง)',
				nameLocation:'center',
				nameTextStyle:{
						fontSize:'16',
						lineHeight: 55
				},
	     type: 'value',
	        boundaryGap: [0, 0.01]
	    },
	    series: [
	        {
	            name: '3 เดือน',
	            type: 'bar',
							barWidth:'30',
	            data: [1, 2, 1, 2, 1, 3],
							label:{
					 	      show:true,
					 				color:'black',
					 	      position:'top',
					 	  }
	        },
	        {
	            name: '6 เดือน',
	            type: 'bar',
							barWidth:'30',
	            data: [1, 2, 2, 2, 1, 3],
							label:{
					 	      show:true,
					 				color:'black',
					 	      position:'top',
					 	  }
	        },
	        {
	            name: '12 เดือน',
	            type: 'bar',
							barWidth:'30',
	            data: [1, 2, 1, 2, 1, 3],
							label:{
					 	      show:true,
					 				color:'black',
					 	      position:'top',
					 	  }
	        },


	    ]
	};

	option && chart_Plan_pm.setOption(option);
</script>
<script>

</script>


@stop
{{-- ปิดส่วนjava --}}
