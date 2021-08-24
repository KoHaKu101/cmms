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
	 .hide-display{
		 display:none;
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
	<button onclick="topFunction()" id="btntop" title="Go to top"><i class="fas fa-arrow-circle-up fa-lg"></i></button>
	<audio id="music" src="{{asset('assets/sound/mixkit-arabian-mystery-harp-notification-2489.wav')}}" ></audio>
	<button type="button" style="display:none;" id="startbtn"></button>
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
											<h4 class="card-title">{{$machine_all}}</h4>
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

											<h4 class="card-title">{{$machine_ready}}</h4>
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
											<h4 class="card-title">{{$machine_wait}}</h4>
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
											<h4 class="card-title"> {{ $datarepair }}</h4>
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

													<a class="nav-link active" id="pills-today" data-toggle="pill" href="#pills-today" role="tab" aria-selected="true">See More...</a>
												</li>
											</ul>
										</div>
									</div>
								</div>
								<div class="card-body" id="NEW_REPAIR">
									@foreach($datarepairlist as $dataitem)
										@php
											$NEW_IMG               = $dataitem->STATUS_NOTIFY  == 9 ? '<img src="'.asset('assets/img/new.gif').'" class="mt--2" width="40px" height="40px">': '' ;
										@endphp
										@can('isAdmin')
											<a href="{{ route('repair.list').'?SEARCH_MACHINE='.$dataitem->DOC_NO }}"style="text-decoration:none;">
										@elsecan('isManager_Ma')
											<a href="{{ route('repair.list').'?SEARCH_MACHINE='.$dataitem->DOC_NO }}"style="text-decoration:none;">
										@elsecan('isManager_Pd')
										@endcan
											<div class="row">
												<div class="d-flex col-md-6 col-lg-1">
													<input type="hidden" value="1">
													<div class="avatar avatar-online">
														<span class="avatar-title rounded-circle border border-white {{$dataitem->PRIORITY == '9' ? 'bg-danger' : 'bg-warning'}}" style="width:50px"><i class="fa fa-wrench"></i></span>
													</div>
												</div>
												<div class="flex-1 ml-3 pt-1 col-md-6 col-lg-7">
													<h4 class="text-uppercase fw-bold mb-1 " style="color:#6c757d;">{{$dataitem->MACHINE_CODE}}
													<span class="{{$dataitem->MACHINE_STATUS == '1' ? 'text-danger' : 'text-warning'}} pl-3">
														@if ($dataitem->PRIORITY == '9')
															<img src="{{asset('assets/css/flame.png')}}" class="mt--2" width="20px" height="20px">
														@endif
														 	{{$dataitem->MACHINE_STATUS == '1' ? 'หยุดทำงาน' : 'ทำงานปกติ'}}{!! $NEW_IMG !!}
													</span></h4>
													<span class="text-muted" >{{ $dataitem->REPAIR_SUBSELECT_NAME }}</span>
												</div>
												<div class="float-right pt-1 col-md-6 col-lg-3">
													<h5 class="text-muted">{{$dataitem->DOC_DATE}}</h5>
												</div>
										</div>
										<hr>
										</a>

								@endforeach

							</div>
						</div>
					</div>
						@php
							$MONTH_NAME_TH = array(0 =>'ALL',1 => "มกราคม",2 => "กุมภาพันธ์",3 =>"มีนาคม",4 => "เมษายน",5 =>"พฤษภาคม",6 =>"มิถุนายน",
															 7 =>"กรกฎาคม",8 =>"สิงหาคม",9 =>"กันยายน",10 =>"ตุลาคม",11 => "พฤศจิกายน",12 =>"ธันวาคม");
							$CURRENT_MONTH = $MONTH_NAME_TH[date('n')].' ปี '.date('Y')+543;
						@endphp
					<div class="col-md-7" >
						<div class="card">
							<div class="card-header row">
								<div class="col-md-8 ">
									<div class="card-title">แจ้งซ่อมแต่ล่ะ LINE : เดือน {{ $CURRENT_MONTH }}</div>
								</div>
								<div class="col-md-4 d-flex justify-content-end">
									<ul class="nav nav-pills nav-secondary nav-pills-no-bd nav-sm" id="pills-tab" role="tablist">
										<li class="nav-item">
											<a class="nav-link active" id="pills-today" data-toggle="pill" href="#pills-today" role="tab" aria-selected="true">See More...</a>
										</li>
									</ul>
								</div>
							</div>
							<div class="card-body">
								<div class="chart-container">
									<div id="repair" style="width: 650px;height:350px;"></div>
								</div>
							</div>
						</div>
					</div>

			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="card"  >
						<div class="card-header">
							<div class="row">
								<div class="col-md-9 form-inline">
									<div class="card-title">การทำแผน</div>
									<select class="mx-2 form-control form-control-sm " id="SELECT_CHART">
										<option value="PM">PM</option>
										<option value="PDM">PDM</option>
									</select>
									<div class="card-title">เดือน {{ $CURRENT_MONTH  }}</div>
								</div>
								<div class="col-md-3 d-flex justify-content-end">
									<ul class="nav nav-pills nav-secondary nav-pills-no-bd nav-sm" id="pills-tab" role="tablist">
										<li class="nav-item">
											<a class="nav-link active"   href="{{ route('dashboard.pm') }}" role="tab" aria-selected="true">See More...</a>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="card-body" >
							<div class="row">
								<div class="col-md-4">
									<canvas id="ChartPM3" height="290%" width="270%"></canvas>
								</div>
								<div class="col-md-4">
									<canvas id="ChartPM6" height="290%" width="270%"></canvas>
								</div>
								<div class="col-md-4">
									<canvas id="ChartPM12" height="290%" width="270%"></canvas>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="card"  >
						<div class="card-header">
							<div class="row">
								<div class="col-md-9 form-inline">
									<div class="card-title">Down Time สูงที่สุด </div>
									<div class="card-title mx-4">เดือน {{ $CURRENT_MONTH  }}</div>
								</div>
								<div class="col-md-3 d-flex justify-content-end">
									<ul class="nav nav-pills nav-secondary nav-pills-no-bd nav-sm" id="pills-tab" role="tablist">
										<li class="nav-item">
											<a class="nav-link active"  href="{{ route('dashboard.downtime') }}" role="tab" aria-selected="true">See More...</a>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="card-body" >
							{{-- <div class="row"> --}}
								{{-- <div class="col-md-12"> --}}
									<div id="ChartDownTime" style="height:290%;width=550%;"></div>

								{{-- </div> --}}
							{{-- </div> --}}
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="card">
						<div class="card-header">
							<div class="row">
								<div class="col-md-9 form-inline">
									<div class="card-title">เครื่องจักรเสียสูงสุด </div>

									<div class="card-title mx-4">เดือน {{ $CURRENT_MONTH  }}</div>
								</div>
								<div class="col-md-3 d-flex justify-content-end">
									<ul class="nav nav-pills nav-secondary nav-pills-no-bd nav-sm" id="pills-tab" role="tablist">
										<li class="nav-item">
											<a class="nav-link active" id="pills-today" data-toggle="pill" href="#pills-today" role="tab" aria-selected="true">See More...</a>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="card-body">
							<div id="repair_top5" style="width: 600px;height:350px;"></div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="card">
						<div class="card-header">
							<div class="row">
								<div class="col-md-12 form-inline">
									<div class="card-title">รายการแจ้งซ่อมสูงสุด </div>

									<div class="card-title mx-4">เดือน {{ $CURRENT_MONTH  }}</div>
								{{-- </div> --}}
								{{-- <div class="col-md-3 d-flex justify-content-end"> --}}
									<ul class="nav nav-pills nav-secondary nav-pills-no-bd nav-sm ml-auto" id="pills-tab" role="tablist">
										<li class="nav-item">
											<a class="nav-link active" id="pills-today" data-toggle="pill" href="#pills-today" role="tab" aria-selected="true">See More...</a>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="card-body">
							<div id="repair_detail_top5" style="width: 600px;height:350px;"></div>
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
<script src="{{asset('/assets/js/plugin/chart-circle/circles.min.js')}}"></script>
<script>
	$(document).ready(function(){
		var value_complete 	 = {3 :{{  $data_complete[3]}}
													 ,6 :{{  $data_complete[6]}}
													 ,12:{{  $data_complete[12]}},}
		var value_uncomplete = {3	:{{  $data_uncomplete[3]}}
													 ,6	:{{  $data_uncomplete[6]}}
													 ,12:{{  $data_uncomplete[12]}},}
		chart_pm(value_complete,value_uncomplete);
	});
	// $('#PM_DASHBOARD').on('click',function(){
	// 	window.location.href = "{{ route('dashboard.pm') }}";
	// });
	$("#SELECT_CHART").on('change',function(){
		var slectval = $('#SELECT_CHART').val();
		if (slectval == 'PDM') {
			var value_complete	 	= "{{ $count_pdm->COMPLETE == null ? '0' : $count_pdm->COMPLETE}}";
			var value_uncomplete	= "{{ $count_pdm->NOCOMPLETE == null ? '0' : $count_pdm->NOCOMPLETE}}";
			$('#ChartPM3').addClass('hide-display');
			$('#ChartPM12').addClass('hide-display');
			chart_pdm(value_complete,value_uncomplete);
		}else {
			var value_complete 	 = {3 :{{  $data_complete[3]}}
														 ,6 :{{  $data_complete[6]}}
														 ,12:{{  $data_complete[12]}},}
			var value_uncomplete = {3	:{{  $data_uncomplete[3]}}
														 ,6	:{{  $data_uncomplete[6]}}
														 ,12:{{  $data_uncomplete[12]}},}
			$('#ChartPM3').removeClass('hide-display');
			$('#ChartPM12').removeClass('hide-display');
			chart_pm(value_complete,value_uncomplete);
		}
	});
</script>
<script>
	{{--  Use In looprepair--}}
	var urldashboard = "{{ route('dashboard.notificationrepair') }}";
	var urlnotify = "{{ route('repair.readnotify')}}";
	{{--  Use In pmplanchart--}}
</script>

@can('isAdmin')
	<script src="{{ asset('/assets/js/useinproject/dashboard/looprepair.js') }}"></script>
@elsecan('isManager_Ma')
	<script src="{{ asset('/assets/js/useinproject/dashboard/looprepair.js') }}"></script>
@elsecan('isManager_Pd')
@endcan
{{-- PLan PM ในแต่ละเดือน --}}
<script src="{{ asset('/assets/js/useinproject/dashboard/pmplanchart.js') }}"></script>
<script src="{{ asset('/assets/js/useinproject/dashboard/pdmplanchart.js') }}"></script>
{{-- Down Time --}}
<script>
	var DowmTime  = document.getElementById('ChartDownTime');
	var DowmTimeChart 	= echarts.init(DowmTime);
  var color_rgba = {1:'rgba(255, 45, 45, 1)'
									 ,2:'rgba(255, 45, 217, 1)'
									 ,3:'rgba(255, 131, 40, 1)'
									 ,4:'rgba(255, 255, 40, 1)'
									 ,5:'rgba(24, 137, 231, 1)'
									 ,6:'rgba(49, 249, 58, 1)'
									 ,7:'rgba(155, 155, 155, 1)'}
	var color_shadow = {1:"rgba(89, 4, 4, 1)"
										 ,2:"rgba(93, 16, 79,1)"
										 ,3:"rgba(144, 61, 0,1)"
										 ,4:"rgba(134, 134, 0,1)"
										 ,5:"rgba(9, 90, 158,1)"
										 ,6:"rgba(1, 171, 9,1)"
										 ,7:"rgba(55, 55, 55,1)"}
	var option;
	option = {
		tooltip: {},
	  legend: {
	      show:true,
	  },
	  xAxis: {
	    data:[
				@for ($i=1; $i < 8; $i++)
				@php
					$MACHINE_CODE = '';
					if (array_key_exists($i,$downtime_machine_code)) {
						$MACHINE_CODE = $downtime_machine_code[$i];
					}
				@endphp
					'{{$MACHINE_CODE}}',
				@endfor
			],
	    show:true,
	    axisLabel:{
	    fontSize :'10'
	    }
	  },
	  yAxis: {
	      name:'ระยะเวลา (นาที)',
	      nameLocation:'center',
	      nameTextStyle:{
	          fontSize:'16',
	          lineHeight: 55
	      },

 	     minInterval:1,
	  },
		grid: {
        left: '7%',
        right: '0%',
        bottom: '0%',
				top:'6%',
        containLabel: true
    },
	  series: [{
	    type: "bar",
			barWidth:'30',
	    data:[
				@for ($D=1; $D < 8; $D++)
				@php
					$DOWNTIME = '';
					if (array_key_exists($D,$downtime_machine)) {
						$DOWNTIME = $downtime_machine[$D];
					}
				@endphp
					{value:"{{$DOWNTIME}}",
	        	itemStyle:{
	            color:color_rgba[{{$D}}],
	            shadowColor:color_shadow[{{$D}}] ,
	        	}
					},
			  	@endfor
			 ],
	    itemStyle: {
				shadowBlur:4,
	      shadowOffsetX: 10,
	    }
	  }],
	  label:{
	      show:true,
				color:'black',
	      position:'top',
	  }
	}
	option && DowmTimeChart.setOption(option);

</script>
{{-- เครื่องจักรเสีย บ่อย --}}
<script>
	var DowmTime  = document.getElementById('repair_top5');
	var myChart 	= echarts.init(DowmTime);
  var color_rgba = {1:'rgba(255, 45, 45, 1)'
									 ,2:'rgba(255, 255, 40, 1)'
									 ,3:'rgba(24, 137, 231, 1)'
									 ,4:'rgba(49, 249, 58, 1)'
									 ,5:'rgba(155, 155, 155, 1)'}
	var color_shadow = {1:"rgba(89, 4, 4, 1)"
										 ,2:"rgba(134, 134, 0,1)"
										 ,3:"rgba(9, 90, 158,1)"
										 ,4:"rgba(1, 171, 9,1)"
										 ,5:"rgba(55, 55, 55,1)"}
	var option;
	option = {
	  tooltip: {
	      show :true,
		  trigger: 'item',
	  },
	  legend: {
	      show:true,
	  },
		grid: {
        left: '8%',
        right: '10%',
        bottom: '0%',
				top:'6%',
        containLabel: true
    },
	  xAxis: {
	    data:[
				@for ($i=1; $i < 6; $i++)
				@php
					$MACHINE_CODE = '';
					if (array_key_exists($i,$array_count_machine)) {
						$MACHINE_CODE = $array_count_machine[$i];
					}
				@endphp
					'{{$MACHINE_CODE}}',
				@endfor
			],
	    show:true,
	    axisLabel:{
	    	fontSize :'10'
			},
	  },
	  yAxis: {
	      name:'จำนวน (ครั้ง)',
	      nameLocation:'center',
	      nameTextStyle:{
	          fontSize:'16',
	          lineHeight: 55
	      },

 	     minInterval:1,

	  },
	  series: [{
	    type: "bar",
			barWidth:'30',
	    data:[
				@for ($D=1; $D < 6; $D++)
				@php
					$COUNT_REPAIR = '';
					if (array_key_exists($D,$array_count_repair)) {
						$COUNT_REPAIR = $array_count_repair[$D];
					}
				@endphp
					{value:"{{$COUNT_REPAIR}}",
	        	itemStyle:{
	            color:color_rgba[{{$D}}],
							shadowBlur:4,
	            shadowColor:color_shadow[{{$D}}] ,
	        	}
					},
				@endfor
				],
	    itemStyle: {
	      shadowOffsetX: 10,

	    }
	  }],
	  label:{
	      show:true,
				color:'black',
	      position:'top',
	  }
	}

	option && myChart.setOption(option);

</script>
{{-- Top 5 อาการเสีย บ่อย --}}
<script>
	var Repair_detail  = document.getElementById('repair_detail_top5');
	var myChart 	= echarts.init(Repair_detail);
	var color_rgba = {1:'rgba(255, 45, 45, 1)'
									 ,2:'rgba(255, 255, 40, 1)'
									 ,3:'rgba(24, 137, 231, 1)'
									 ,4:'rgba(49, 249, 58, 1)'
									 ,5:'rgba(155, 155, 155, 1)'}
	var color_shadow = {1:"rgba(89, 4, 4, 1)"
										 ,2:"rgba(134, 134, 0,1)"
										 ,3:"rgba(9, 90, 158,1)"
										 ,4:"rgba(1, 171, 9,1)"
										 ,5:"rgba(55, 55, 55,1)"}
	 var option;
 	option = {
 	  tooltip: {
 	      show :true,
 		  trigger: 'item',
 	  },
 	  legend: {
 	      show:true,
 	  },
 	  xAxis: {
 	    data:[
				@for ($i=1; $i < 6; $i++)
				@php
					$REPAIR_DETAIL = '';
					if (array_key_exists($i,$array_count_name)) {
						$REPAIR_DETAIL = $array_count_name[$i];
						if (iconv_strlen($REPAIR_DETAIL) > 14) {
							$REPAIR_DETAIL = wordwrap($REPAIR_DETAIL,20, '\n');
						}
					}
				@endphp
					'{{$REPAIR_DETAIL}}',
				@endfor
 			],
 	    show:true,
 	    axisLabel:{
 	    fontSize :'10',
			interval: 0,
 	    }
 	  },
		grid: {
				left: '8%',
				right: '10%',
				bottom: '0%',
				top:'6%',
				containLabel: true
		},
 	  yAxis: {
 	      name:'จำนวน (ครั้ง)',
 	      nameLocation:'center',
 	      nameTextStyle:{
 	          fontSize:'16',
 	          lineHeight: 55
 	      },

 	     minInterval:1,

 	  },
 	  series: [{
 	    type: "bar",
 			barWidth:'30',
 	    data:[
				@for ($D=1; $D < 6; $D++)
				@php
					$COUNT_DETAIL = '';
					if (array_key_exists($D,$array_count_detail)) {
						$COUNT_DETAIL = $array_count_detail[$D];
					}
				@endphp
					{value:"{{$COUNT_DETAIL}}",
	        	itemStyle:{
	            color:color_rgba[{{$D}}],
	            shadowColor:color_shadow[{{$D}}] ,
	        	}
					},
				@endfor
 				],
 	    itemStyle: {
 	      shadowOffsetX: 10,
				shadowBlur:4,

 	    }
 	  }],
 	  label:{
 	      show:true,
 				color:'black',
 	      position:'top',
 	  }
 	}

 	option && myChart.setOption(option);

</script>
{{-- แจ้งซ่อมแต่ล่ะLine --}}
<script type="text/javascript">
	var chartDom = document.getElementById('repair');
	var myChart  = echarts.init(chartDom);
	var option;
	option = {
		legend: {show: true,textStyle: {
      fontSize: 14
    }},
		tooltip: {},
		dataset: {
			source: [
			['product', 'Line1', 'Line2', 'Line3','Line4', 'Line5', 'Line6'],
			['แจ้งซ่อมในแต่ล่ะ LINE',
				@foreach ($array_repair as $key => $value)
				{{ $value }},
				@endforeach
				],
		]
	},
	xAxis: {type: 'category'},
	yAxis: {type: 'value',minInterval: 1, min: 0},
	series: [
		{type: 'bar',color: '#14BAFD',
		label: {position: "top",show: true,fontSize: 16,color: 'black'},},
		{type: 'bar',color: '#FF944F',
		label: {position: "top",show: true,fontSize: 16,color: 'black'},},
		{type: 'bar',color: '#BAFF4F',
		label: {position: "top",show: true,fontSize: 16,color: 'black'},},
		{type: 'bar',color: '#FF4F4F',
		label: {position: "top",show: true,fontSize: 16,color: 'black'},},
		{type: 'bar',color: '#FF4FCF',
		label: {position: "top",show: true,fontSize: 16,color: 'black'},},
		{type: 'bar',color: '#4F62FF',
		label: {position: "top",show: true,fontSize: 16,color: 'black'},}
	]
	};
	option && myChart.setOption(option);
	</script>
<script type="text/javascript" >
	@php
		$array_color = array('L1'=>'#14BAFD','L2'=>'#FF944F','L3'=>'#BAFF4F','L4'=>'#FF4F4F','L5'=>'#FF4FCF','L6'=>'#4F62FF');
		$i = 1;
	@endphp
	@foreach ($array_line as $key => $value)
		Circles.create({
			id:"{{ 'circles-'.$i++ }}",
			radius:45,
			value:{{$value}},
			maxValue:500,
			width:10,
			text: {{$value}},
			colors:['#585963', "{{ $array_color[$key] }}"],
			duration:400,
			wrpClass:'circles-wrp',
			textClass:'circles-text',
			styleWrapper:true,
			styleText:true
		})
	@endforeach
</script>
{{-- Button go to top --}}
<script src="{{ asset('assets/js/btntop.js') }}"></script>

@stop
{{-- ปิดส่วนjava --}}
