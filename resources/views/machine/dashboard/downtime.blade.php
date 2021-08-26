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
							<h2 class="text-white pb-2 fw-bold">Dashboard DownTime</h2>
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
												รายละเอียด DownTime สูงสุด
											</div>
											<div class="mr-auto">
												เดือน {{$CURRENT_MONTH}}
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-inline">
											<button class="btn btn-secondary btn-sm mx-1 ml-auto"><i class="fas fa-file-excel fa-lg mx-1"></i>Excel</button>
											<button class="btn btn-secondary btn-sm mx-1 " onclick="printdowntime(this)" data-type="downtime"><i class="fas fa-print fa-lg mx-1"></i> Print</button>
										</div>
									</div>
								</div>
							</div>
							<div class="card-body">
								<div class="table">
									<table class="table table-bordered table-head-bg-info table-bordered-bd-info">
										<thead>
											<tr>
												<th width="2%">No.</th>
												<th width="7%" class="text-center">MC-CODE</th>
												<th width="15%">MC-NAME</th>
												<th width="15%">สาเหตุ / อาการที่เสีย</th>
												<th width="15%">วิธีแก้ไข</th>
												<th width="10%" class="text-center">ตรวจสอบ(นาที)</th>
												<th width="10%" class="text-center">ซื้ออะไหล่(นาที)</th>
												<th width="6%">ซ่อม(นาที)</th>
												<th width="6%">รวม(นาที)</th>
												<th width="12%">ผู้ดำเนินการ</th>
											</tr>
										</thead>
										<tbody>
											@foreach ($DATA_REPAIR as $index => $row)
												@php
													$INSPECTION_RESULT_TIME = $row->INSPECTION_RESULT_TIME > 0 ? number_format($row->INSPECTION_RESULT_TIME) : '-';
													$SPAREPART_RESULT_TIME  = $row->SPAREPART_RESULT_TIME  > 0 ? number_format($row->SPAREPART_RESULT_TIME) : '-';
													$WORK_RESULT_TIME 			= $row->WORKERIN_RESULT_TIME 	 > 0 ? $row->WORKERIN_RESULT_TIME  : $row->WORKEROUT_RESULT_TIME;
													$WORK_RESULT_TIME 			= $WORK_RESULT_TIME != 0 ? number_format($WORK_RESULT_TIME) : '-';
												@endphp
												<tr>
													<td class="text-center">{{$index+1}}</td>
													<td class="text-center">{{$row->MACHINE_CODE}}</td>
													<td >{{$row->MACHINE_NAME}}</td>
													<td >{{$row->REPAIR_SUBSELECT_NAME}}</td>
													<td >{{$row->REPAIR_DETAIL}}</td>
													<td class="text-center">{{$INSPECTION_RESULT_TIME}}</td>
													<td class="text-center">{{$SPAREPART_RESULT_TIME}}</td>
													<td class="text-center">{{$WORK_RESULT_TIME}}</td>
													<td class="text-center">{{number_format($row->DOWNTIME)}}</td>
													<td >{{$row->CLOSE_BY_TH}}</td>
												</tr>
											@endforeach
										</tbody>
									</table>
								</div>
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
													 DownTime รวม สูงสุด
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
								<div id="downtime_sum_chart" style="width:100%; height:380%;"></div>
							</div>

						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header bg-primary">
								<div class="card-title">
									<div class="row">
										<div class="col-md-8 ">
											<div class="card-title form-inline text-white">
												<div class="text-left mr-auto ">
													 รายละเอียด DownTime รวม สูงสุด
												</div>
												<div class="mr-auto">
													เดือน {{$CURRENT_MONTH}}
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-inline">
												<button class="btn btn-secondary btn-sm mx-1 ml-auto"><i class="fas fa-file-excel fa-lg mx-1"></i>Excel</button>
												<button class="btn btn-secondary btn-sm mx-1 "	onclick="printdowntime(this)" data-type="sumdowntime"><i class="fas fa-print fa-lg mx-1"></i> Print</button>
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
											@foreach ($DATA_SUM_DOWNTIME as $sub_index => $sub_row)
												@php
													$DOWNTIME_ALL 				 = 0;
													$REPAIR_SUM   				 = $DATA_REPAIR_SUM->where('MACHINE_UNID','=',$sub_row->MACHINE_UNID);
													$ROW_SPAN   					 = count($REPAIR_SUM);
													$number 							 = 1;
													$NUMBER_SUBSELECT_NAME = 1;
													$NUMBER_REPAIR_DETAIL  = 1;
												@endphp
												@foreach ($REPAIR_SUM  as $subsub_index => $subsub_row)
													@php
														$DOWNTIME = $subsub_row->DOWNTIME == 0 ? '-' : number_format($subsub_row->DOWNTIME);
													@endphp
													<tr >
														<td class="text-center" >{{ $sub_index+1 }}</td>
														<td class="text-center" >{{$sub_row->MACHINE_CODE}}</td>
														<td >{{$sub_row->MACHINE_NAME}}</td>
														<td>{{$NUMBER_SUBSELECT_NAME++ .'. '.$subsub_row->REPAIR_SUBSELECT_NAME."\n"}}</td>
														<td >{{$NUMBER_REPAIR_DETAIL++ .'. '.$subsub_row->REPAIR_DETAIL."\n"}}</td>
														<td class="text-center" >{{ $DOWNTIME }}</td>
														<td class="text-center" >{{ number_format($sub_row->DOWNTIME)}}</td>
													</tr>
												@endforeach
											@endforeach
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
	var Downtime = document.getElementById('downtime_sum_chart');
	var downtime_sum_chart = echarts.init(Downtime);
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
	var data = [
							@for ($i=1; $i < 8; $i++)
							@php
								$DOWNTIME_COUNT = $array_downtime_count[$i];
							@endphp
							{value:"{{	$DOWNTIME_COUNT	}}",
			        	itemStyle:{
			            color:color_rgba['{{$i}}'],
			            shadowColor:color_shadow['{{$i}}'] ,
			        	}
							},
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
				legend: {
			      show:true,
			  },
		    grid: {
		        left: '6%',
		        right: '0%',
		        bottom: '6%',
						top:'3%',
		        containLabel: true
		    },

		    xAxis: {
						show:true,

		        data: [@for ($i=1; $i < 8; $i++)
										'{{ $array_downtime_name[$i] }}',
									@endfor],

		    },
		    yAxis: {
					name:'ระยะเวลา (นาที)',
					nameLocation:'center',
					nameTextStyle:{
							fontSize:'16',
							lineHeight: 55,

					},
		     type: 'value',
		     minInterval:1,
		    },
		    series: [
						{
								type: 'bar',
								barWidth:'30',
								data: data,
								label:{
										show:true,
										color:'black',
										position:'top',
								},
								itemStyle:{
									shadowOffsetX: 10,
									shadowBlur:4,
			        	}
						},

		    ]
		};
		option && downtime_sum_chart.setOption(option);
</script>
<script>
function printdowntime(thisdata){
	var type = $(thisdata).data('type');
	var url  = "{{ route('dashboard.downtime.print') }}?TYPE="+type;
	window.open(url,'PDFDowntime','width=1000,height=1000,resizable=yes,top=100,left=100,menubar=yes,toolbar=yes,scroll=yes');
};
$('#table_dowtime').DataTable({
		'rowsGroup': [0,1,2,6],
		"pageLength": 20,
		"bLengthChange": false,
		"bFilter": true,
		"bInfo": false,
		"bAutoWidth": false,
		searching: false,
		paging: false,
		columnDefs: [
		{ orderable: false, targets:[0,1,2,3,4,5,6] }
	]
	});
</script>


@stop
{{-- ปิดส่วนjava --}}
