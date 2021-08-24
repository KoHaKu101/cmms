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
							<h2 class="text-white pb-2 fw-bold">Dashboard PM && PDM</h2>
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
								<div id="PlanPm" style="width:100%; height:380%;"></div>
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
										<button class="btn {{$color}} btn-sm ml-auto mr-auto" onclick="changepmline(this)"
										id="{{ 'BTN-PM-L'.$name }}"
										data-line="{{ 'L'.$name }}">{{ 'LINE '.$name }}</button>
									@endforeach
								</div>
							</div>
							<div class="card-body">
								<div class="row" id="NAME_PM_TABLE">
									<h5>ตารางรายการ PM ของ Line 1</h5>
								</div>
								<div class="table" id="CHANGE_PM_TABLE">
								</div>
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
							<div class="card-body" style="padding: 0.25rem;">
								<canvas id="CHART_CIRCLE_PM" width="380%" height="425	%"></canvas>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="page-inner mt--5">
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header bg-primary ">
								<div class="row">
									<div class="card-title text-white">
										แผน PDM ใน {{ ' ปี '.date('Y')+543 }}
									</div>
								</div>
							</div>
							<div class="card-body">
								<div id="PlanPdm" style="width:100%;height:380%;"></div>
							</div>
						</div>
					</div>

				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header bg-primary ">
								<div class="row">
									<div class="card-title text-white">
										กรุณาเลือก PDM ในแต่ละ Line
									</div>
								</div>

							</div>
							<div class="card-header">
								<div class="row">
									@foreach ($BTN_COLOR_LINE as $name => $color)
										<button class="btn {{$color}} btn-sm ml-auto mr-auto" onclick="changepdmline(this)"
										id="{{ 'BTN-PDM-L'.$name }}"
										data-line="{{ 'L'.$name }}">{{ 'LINE '.$name }}</button>
									@endforeach
								</div>
							</div>
							<div class="card-body">
								<div class="row" id="NAME_PDM_TABLE">

								</div>
								<div class="table" id="CHANGE_PDM_TABLE">
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
<script type="text/javascript">
	var Plan_pm = document.getElementById('PlanPm');
	var chart_Plan_pm = echarts.init(Plan_pm);
	var data_pm3	= [
										@for ($i=1; $i < 7; $i++)
											{{$PM_BAR_CHART[$i][0]->COUNT_MONTH3}},
										@endfor
									]
	var data_pm6	= [
										@for ($i=1; $i < 7; $i++)
										 {{$PM_BAR_CHART[$i][0]->COUNT_MONTH6}},
										@endfor
									]
	var data_pm12	= [
										@for ($i=1; $i < 7; $i++)
										 {{$PM_BAR_CHART[$i][0]->COUNT_MONTH12}},
										@endfor
									]

	@php
		$series_array = array('3'=>'3 เดือน','6'=>'6 เดือน','12'=>'12 เดือน');
		$color				= array('3'=>'rgba(233, 233, 13,1)','6'=>'rgba(216, 0, 250,1)','12'=> 'rgba(54, 255, 0,1)',);
		$shadowColor	= array('3'=>'rgba(102, 102, 4,1)','6'=>'rgba(73, 0, 85,1)','12'=> 'rgba(0, 72, 3,1)',);
	@endphp
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
	        left: '6%',
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
						lineHeight: 55,

				},
	     type: 'value',

	     minInterval:1,
			 boundaryGap: [0, 1]
	    },
	    series: [
				@foreach ($series_array as $key => $name)
					{
							name: '{{ $name }}',
							type: 'bar',
							barWidth:'30',
							data: data_pm{{$key}},
							label:{
									show:true,
									color:'black',
									position:'top',
							},
							itemStyle:{
		            color:'{{$color[$key]}}',
		            shadowColor:'{{$shadowColor[$key]}}',
								shadowOffsetX: 10,
								shadowBlur:4,
		        	}
					},
				@endforeach
	    ]
	};
	option && chart_Plan_pm.setOption(option);
</script>
<script type="text/javascript">
	var Plan_pdm 		   = document.getElementById('PlanPdm');
	var chart_Plan_pdm = echarts.init(Plan_pdm);
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
					left: '6%',
					right: '0%',
					bottom: '6%',
					top:'3%',
					containLabel: true
			},
			xAxis: {
					type: 'category',
					data: [

								@for ($i = 1;$i < 13;$i++)
									'{{ $MONTH_NAME_TH[$i] }}',
								@endfor
							 ]
			},
			yAxis: {
				name:'จำนวนเครื่องจักร (เครื่อง)',
				nameLocation:'center',
				nameTextStyle:{
						fontSize:'16',
						lineHeight: 55
				},
			 	type: 'value',
	     	minInterval:1,
				boundaryGap: [0,1]
			},
			series: [{
						name: '3 เดือน',
						type: 'bar',
						barWidth:'40',
						data: [
							@for ($i=1; $i < 13; $i++)
								'{{ $PDM_BAR_CHART[$i] }}',
							@endfor
						],
						label:{
								show:true,
								color:'black',
								position:'top',
						},
						itemStyle:{
							color:'rgba(134, 48, 227)',
							shadowOffsetX: 10,
							shadowBlur:4,
								shadowColor:'rgba(46, 7, 88,1)',
						}
				}],
	};
	option && chart_Plan_pdm.setOption(option);
</script>
<script>
	$('#BTN-PM-L1').click();
	$('#BTN-PDM-L1').click();
	function changepmline(thisdata){
		var Line = $(thisdata).data('line');
		var url  = "{{ route('dashboard.tablepm') }}";
		$.ajax({
			type:'GET',
			url: url,
			data:{LINE:Line},
			datatype: 'json',
			success:function(result){
				$('#CHANGE_PM_TABLE').html(result.html);
				$('#NAME_PM_TABLE').html('<h5>ตารางรายการ PM ของ '+result.LINE+'</h5>');
				$('#data_table_pm').DataTable({
						"pageLength": 5,
						"bLengthChange": false,
						"bFilter": true,
						"bInfo": false,
						"bAutoWidth": false,
						columnDefs: [
						{ orderable: false, targets:[0,1,2,3,4,5,6] }
					]
					});
					chart_circle_pm(result.LINE,result.data['complete'],result.data['waiting'],result.data['nocomplete']);
			}

		});
	}
	function changepdmline(thisdata){
		var Line = $(thisdata).data('line');
		var url  = "{{ route('dashboard.tablepdm') }}";
		$.ajax({
			type:'GET',
			url: url,
			data:{LINE:Line},
			datatype: 'json',
			success:function(result){
				$('#CHANGE_PDM_TABLE').html(result.html);
				$('#NAME_PDM_TABLE').html('<h5>ตารางรายการ PDM ของ '+result.LINE+'</h5>');
				$('#data_table_pdm').DataTable({
						"pageLength": 10,
						"bLengthChange": false,
						"bFilter": true,
						"bInfo": false,
						"bAutoWidth": false,
						columnDefs: [
						{ orderable: false, targets:[0,1,2,3,4,5,6] }
					]
					});
			}

		});
	}
</script>
<script>
	function chart_circle_pm(namechart,complete,waiting,nocomplete){

	var chartDom = document.getElementById('CHART_CIRCLE_PM');
	var myChart = echarts.init(chartDom);
	var option;
	var data = [
									complete 	 != 0 ? {value: complete, 	name: 'ตรวจสอบแล้ว'} : '',
									waiting 	 != 0 ? {value: waiting, 		name: 'กำลังดำเนินการ'}	:'',
									nocomplete != 0 ? {value: nocomplete, name: 'ยังไม่ได้ตรวจสอบ'}	:'',
							]
	option = {
		title: {
				text: 'การทำตามแผน PM '+namechart,
				top:'20',
				left: 'center'
		},
		tooltip: {
				trigger: 'item'
		},
		legend: {

				bottom: '50',
		},
		grid: {
				left: '0%',
				right: '10%',
				bottom: '0%',
				top:'6%',
				containLabel: true
		},
		series: [
				{
						name: '访问来源',
						type: 'pie',
						radius: '50%',
						label: {
									show: true,
									position:'inside',
									formatter:'{c}',
									 fontSize:'15',
									fontWeight:'bold',
									color:'#000000',
							},
						data: data,
						color:['rgba(66, 198, 72,1)','rgba(231, 172, 27,1)','rgba(216, 31, 31,1)'],
						emphasis: {
								itemStyle: {
										shadowOffsetX: 0,
										shadowColor: 'rgba(0, 0, 0, 0.5)'
								}
						}
				}
		],
		itemStyle:{
			shadowBlur: 1,
			shadowColor: "rgba(10, 10, 10, 1)",
			shadowOffsetY: 7
		}

	};
	option && myChart.setOption(option);
	}
</script>

@stop
{{-- ปิดส่วนjava --}}
