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
		$BTN_COLOR_LINE = array('1'=>'btn-info','2'=>'btn-warning','3'=>'btn-green'
													 ,'4'=>'btn-danger','5'=>'btn-pink','6'=>'btn-primary');
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
										<button class="btn {{$color}} btn-sm ml-auto mr-auto" onclick="changepmline(this)"
										id="{{ 'BTN-L'.$name }}"
										data-line="{{ 'L'.$name }}">{{ 'LINE '.$name }}</button>
									@endforeach
								</div>
							</div>
							<div class="card-body">
								<div class="row" id="NAME_TABLE">
									<h5>ตารางรายการ PM ของ Line 1</h5>
								</div>
								<div class="table" id="CHANGE_TABLE">
									<table class="table table-bordered table-head-bg-info table-bordered-bd-info " id="data_table_pm">
										{{-- <thead>
											<tr>
												<th >#</th>
												<th width="12%" class="text-center">MC-CODE</th>
												<th >MC-NAME</th>
												<th width="10%">รอบ(เดือน)</th>
												<th width="16%">สถานะ</th>
												<th width="16%">ผู้ตรวจสอบ</th>
												<th width="14%">วันที่ตรวจสอบ</th>
											</tr>
										</thead>
										<tbody>
											@foreach ($DATA_PM_TABLE as $key => $row)
												@php
													$USER_CHECK  = $PM_USER_CHECK->where('PM_PLAN_UNID','=',$row->UNID)->first();
													$STATUS_TEXT = $row->PLAN_STATUS == 'COMPLETE' ? 'ตรวจสอบแล้ว' : ($row->PLAN_STATUS == 'EDIT' ? 'กำลังดำเนินการ' : 'ยังไม่ได้ตรวจสอบ');
													$STATUS_BG 	 = $row->PLAN_STATUS == 'COMPLETE' ? 'bg-success' : ($row->PLAN_STATUS == 'EDIT' ? 'bg-warning' : 'bg-danger');
													$CHECK_BY    = isset($USER_CHECK->PM_USER_CHECK) ? $USER_CHECK->PM_USER_CHECK : '-';
												@endphp
												<tr>
													<td class="text-center">{{ $key+1 }}</td>
													<td class="text-center">{{$row->MACHINE_CODE}}</td>
													<td>{{$row->MACHINE_NAME}}</td>
													<td class="text-center">{{$row->PLAN_PERIOD}}</td>
													<td class="{{ $STATUS_BG }} text-white" >{{$STATUS_TEXT}}</td>
													<td>{{$CHECK_BY}}</td>
													<td>{{$row->COMPLETE_DATE}}</td>
												</tr>
											@endforeach
										</tbody> --}}
									</table>
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
<script src="{{ asset('../../assets/js/plugin/datatables/datatables.min.js')}}"></script>

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
						data: data_pm3,
						label:{
								show:true,
								color:'black',
								position:'top',
						},
						itemStyle:{
	            color:'rgba(233, 233, 13,1)',
	            shadowColor:'rgba(102, 102, 4,1)',
	        	}
				},
	        {
	            name: '6 เดือน',
	            type: 'bar',
							barWidth:'30',
	            data: data_pm6,
							label:{
					 	      show:true,
					 				color:'black',
					 	      position:'top',
					 	  },
							itemStyle:{
		            color:'rgba(216, 0, 250,1)',
		            shadowColor:'rgba(73, 0, 85,1)',
		        	}
	        },
	        {
	            name: '12 เดือน',
	            type: 'bar',
							barWidth:'30',
	            data: data_pm12,
							label:{
					 	      show:true,
					 				color:'black',
					 	      position:'top',
					 	  },
							itemStyle:{
		            color:'rgba(54, 255, 0,1)',
		            shadowColor:'rgba(0, 72, 3,1)',
		        	}
	        },


	    ]
	};
	option && chart_Plan_pm.setOption(option);
</script>
<script>
	$('#BTN-L1').click();
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
	function changepmline(thisdata){
		var Line = $(thisdata).data('line');
		var url  = "{{ route('dashboard.tablepm') }}";
		$.ajax({
			type:'GET',
			url: url,
			data:{LINE:Line},
			datatype: 'json',
			success:function(result){
				$('#CHANGE_TABLE').html(result.html);
				$('#NAME_TABLE').html('ตารางรายการ PM ของ'+result.LINE);
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
