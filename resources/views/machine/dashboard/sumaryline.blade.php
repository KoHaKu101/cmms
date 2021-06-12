@extends('masterlayout.masterlayout')
@section('tittle','homepage')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/icofont/icofont.min.css') }}">
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
			<div class="panel-header bg-primary-gradient">
				<div class="page-inner py-5">
					<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
						<div>
							<h2 class="text-white pb-2 fw-bold">ค่าซ่อมในแต่ล่ะ LINE</h2>
						</div>
						</div>
				</div>
			</div>
			<div class="row">
				<div class="card mx-5 mt-3">

					<a href="{{ url()->previous() }}">
						<button class="btn btn-warning  btn-sm">
							<span class="fas fa-arrow-left fa-lg">Back </span>
						</button>
					</a>

			</div>
				</div>
			<div class="row mt--3">

				<div class="col-md-6">
					<div class="card">
						<div class="card-header">
							<div class="card-title">ค่าซ่อมประจำเดือน Line 1</div>
						</div>
						<div class="card-body">
							<div class="chart-container">
								<div id="price1"style="width: 650px;height:400px;"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
	        <div class="card">
	          <div class="card-header">
	            <div class="card-title">ค่าซ่อมประจำเดือน Line 2</div>
	          </div>
	          <div class="card-body">
	            <div class="chart-container">
	              <div id="price2"style="width: 650px;height:400px;"></div>
	            </div>
	          </div>
	        </div>
	      </div>
		</div>
  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <div class="card-title">ค่าซ่อมประจำเดือน Line 3</div>
        </div>
        <div class="card-body">
          <div class="chart-container">
            <div id="price3"style="width: 650px;height:400px;"></div>
          </div>
        </div>
      </div>
    </div>
		<div class="col-md-6">
	    <div class="card">
	      <div class="card-header">
	        <div class="card-title">ค่าซ่อมประจำเดือน Line 4</div>
	      </div>
	      <div class="card-body">
	        <div class="chart-container">
	          <div id="price4"style="width: 650px;height:400px;"></div>
	        </div>
	      </div>
	    </div>
	  </div>
</div>
<div class="row">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <div class="card-title">ค่าซ่อมประจำเดือน Line 5</div>
      </div>
      <div class="card-body">
        <div class="chart-container">
          <div id="price5"style="width: 650px;height:400px;"></div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <div class="card-title">ค่าซ่อมประจำเดือน Line 6</div>
      </div>
      <div class="card-body">
        <div class="chart-container">
          <div id="price6"style="width: 650px;height:400px;"></div>
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
<script type="text/javascript">
	var chartDom1 = document.getElementById('price1');
	var myChart1 = echarts.init(chartDom1,);
	var option;
	var dataAxis = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
	var data = [220, 182, 191, 234, 290, 330, 310, 123, 442, 321, 90, 149, 210, 122, 133, 334, 198, 123, 125, 220];
	var data1 = [300, 182, 191, 234, 290, 330, 310, 123, 442, 321, 90, 149, 210, 122, 133, 334, 198, 123, 125, 220];
	var yMax = 500;
	var dataShadow = [];
	for (var i = 0; i < data.length; i++) {	dataShadow.push(yMax);}
	option = {
		legend: {show: true,textStyle: {fontSize: 14 },
			data: ['ค่าอะไหล่','ค่าจ้าง SubContaine']
						},

		 xAxis: {
			data: dataAxis,
			axisLabel: {inside: false,textStyle:{color: 'black'} },
	axisTick: {show: false},
	axisLine: {show: false},z: 10
						},
		yAxis: {axisLine: {show: false},axisTick: {show: false},axisLabel: {textStyle: {color: 'black'} } },
		series: [
				{	name: 'ค่าอะไหล่',
				 	type: 'bar',
					itemStyle: {color: new echarts.graphic.LinearGradient(0, 0, 0, 1,[
												{offset: 0, color: '#83bff6'},
												{offset: 0.5, color: '#188df0'},
												{offset: 1, color: '#188df0'}
										])
										},
				emphasis: {itemStyle: {color: new echarts.graphic.LinearGradient(0, 0, 0, 1,[
														{offset: 0, color: '#2378f7'},
														{offset: 0.7, color: '#2378f7'},
														{offset: 1, color: '#83bff6'}
															])
															}
									},
						data: data},
				{name: 'ค่าจ้าง SubContaine',
						type: 'bar',
						itemStyle: {color: new echarts.graphic.LinearGradient(0, 0, 0, 1,[
												{offset: 0, color: '#FF9595'},
												{offset: 0.5, color: '#FF5656'},
												{offset: 1, color: '#FF1616'}
										])
										},
					 emphasis: {itemStyle: {color: new echarts.graphic.LinearGradient(0, 0, 0, 1,[
														{offset: 0, color: '#FF1616'},
														{offset: 0.7, color: '#FF5656'},
														{offset: 1, color: '#FF9595'}
												])
											}
										},
						data : data1}
					],

	};
	option && myChart1.setOption(option);
</script>
<script type="text/javascript">
	var chartDom2 = document.getElementById('price2');
	var myChart2 = echarts.init(chartDom2,);
	var option;
	var dataAxis = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
	var data = [220, 182, 191, 234, 290, 330, 310, 123, 442, 321, 90, 149, 210, 122, 133, 334, 198, 123, 125, 220];
	var data1 = [300, 182, 191, 234, 290, 330, 310, 123, 442, 321, 90, 149, 210, 122, 133, 334, 198, 123, 125, 220];
	var yMax = 500;
	var dataShadow = [];
	for (var i = 0; i < data.length; i++) {	dataShadow.push(yMax);}
	option = {
		legend: {show: true,textStyle: {fontSize: 14 },
			data: ['ค่าอะไหล่','ค่าจ้าง SubContaine']
						},

		 xAxis: {
			data: dataAxis,
			axisLabel: {inside: false,textStyle:{color: 'black'} },
	axisTick: {show: false},
	axisLine: {show: false},z: 10
						},
		yAxis: {axisLine: {show: false},axisTick: {show: false},axisLabel: {textStyle: {color: 'black'} } },
		series: [
				{	name: 'ค่าอะไหล่',
				 	type: 'bar',
					itemStyle: {color: new echarts.graphic.LinearGradient(0, 0, 0, 1,[
												{offset: 0, color: '#83bff6'},
												{offset: 0.5, color: '#188df0'},
												{offset: 1, color: '#188df0'}
										])
										},
				emphasis: {itemStyle: {color: new echarts.graphic.LinearGradient(0, 0, 0, 1,[
														{offset: 0, color: '#2378f7'},
														{offset: 0.7, color: '#2378f7'},
														{offset: 1, color: '#83bff6'}
															])
															}
									},
						data: data},
				{name: 'ค่าจ้าง SubContaine',
						type: 'bar',
						itemStyle: {color: new echarts.graphic.LinearGradient(0, 0, 0, 1,[
												{offset: 0, color: '#FF9595'},
												{offset: 0.5, color: '#FF5656'},
												{offset: 1, color: '#FF1616'}
										])
										},
					 emphasis: {itemStyle: {color: new echarts.graphic.LinearGradient(0, 0, 0, 1,[
														{offset: 0, color: '#FF1616'},
														{offset: 0.7, color: '#FF5656'},
														{offset: 1, color: '#FF9595'}
												])
											}
										},
						data : data1}
					],

	};
	option && myChart2.setOption(option);
</script>
<script type="text/javascript">
	var chartDom3 = document.getElementById('price3');
	var myChart3 = echarts.init(chartDom3,);
	var option;
	var dataAxis = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
	var data = [220, 182, 191, 234, 290, 330, 310, 123, 442, 321, 90, 149, 210, 122, 133, 334, 198, 123, 125, 220];
	var data1 = [300, 182, 191, 234, 290, 330, 310, 123, 442, 321, 90, 149, 210, 122, 133, 334, 198, 123, 125, 220];
	var yMax = 500;
	var dataShadow = [];
	for (var i = 0; i < data.length; i++) {	dataShadow.push(yMax);}
	option = {
		legend: {show: true,textStyle: {fontSize: 14 },
			data: ['ค่าอะไหล่','ค่าจ้าง SubContaine']
						},

		 xAxis: {
			data: dataAxis,
			axisLabel: {inside: false,textStyle:{color: 'black'} },
	axisTick: {show: false},
	axisLine: {show: false},z: 10
						},
		yAxis: {axisLine: {show: false},axisTick: {show: false},axisLabel: {textStyle: {color: 'black'} } },
		series: [
				{	name: 'ค่าอะไหล่',
				 	type: 'bar',
					itemStyle: {color: new echarts.graphic.LinearGradient(0, 0, 0, 1,[
												{offset: 0, color: '#83bff6'},
												{offset: 0.5, color: '#188df0'},
												{offset: 1, color: '#188df0'}
										])
										},
				emphasis: {itemStyle: {color: new echarts.graphic.LinearGradient(0, 0, 0, 1,[
														{offset: 0, color: '#2378f7'},
														{offset: 0.7, color: '#2378f7'},
														{offset: 1, color: '#83bff6'}
															])
															}
									},
						data: data},
				{name: 'ค่าจ้าง SubContaine',
						type: 'bar',
						itemStyle: {color: new echarts.graphic.LinearGradient(0, 0, 0, 1,[
												{offset: 0, color: '#FF9595'},
												{offset: 0.5, color: '#FF5656'},
												{offset: 1, color: '#FF1616'}
										])
										},
					 emphasis: {itemStyle: {color: new echarts.graphic.LinearGradient(0, 0, 0, 1,[
														{offset: 0, color: '#FF1616'},
														{offset: 0.7, color: '#FF5656'},
														{offset: 1, color: '#FF9595'}
												])
											}
										},
						data : data1}
					],

	};
	option && myChart3.setOption(option);
</script>
<script type="text/javascript">
	var chartDom4 = document.getElementById('price4');
	var myChart4 = echarts.init(chartDom4,);
	var option;
	var dataAxis = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
	var data = [220, 182, 191, 234, 290, 330, 310, 123, 442, 321, 90, 149, 210, 122, 133, 334, 198, 123, 125, 220];
	var data1 = [300, 182, 191, 234, 290, 330, 310, 123, 442, 321, 90, 149, 210, 122, 133, 334, 198, 123, 125, 220];
	var yMax = 500;
	var dataShadow = [];
	for (var i = 0; i < data.length; i++) {	dataShadow.push(yMax);}
	option = {
		legend: {show: true,textStyle: {fontSize: 14 },
			data: ['ค่าอะไหล่','ค่าจ้าง SubContaine']
						},

		 xAxis: {
			data: dataAxis,
			axisLabel: {inside: false,textStyle:{color: 'black'} },
	axisTick: {show: false},
	axisLine: {show: false},z: 10
						},
		yAxis: {axisLine: {show: false},axisTick: {show: false},axisLabel: {textStyle: {color: 'black'} } },
		series: [
				{	name: 'ค่าอะไหล่',
				 	type: 'bar',
					itemStyle: {color: new echarts.graphic.LinearGradient(0, 0, 0, 1,[
												{offset: 0, color: '#83bff6'},
												{offset: 0.5, color: '#188df0'},
												{offset: 1, color: '#188df0'}
										])
										},
				emphasis: {itemStyle: {color: new echarts.graphic.LinearGradient(0, 0, 0, 1,[
														{offset: 0, color: '#2378f7'},
														{offset: 0.7, color: '#2378f7'},
														{offset: 1, color: '#83bff6'}
															])
															}
									},
						data: data},
				{name: 'ค่าจ้าง SubContaine',
						type: 'bar',
						itemStyle: {color: new echarts.graphic.LinearGradient(0, 0, 0, 1,[
												{offset: 0, color: '#FF9595'},
												{offset: 0.5, color: '#FF5656'},
												{offset: 1, color: '#FF1616'}
										])
										},
					 emphasis: {itemStyle: {color: new echarts.graphic.LinearGradient(0, 0, 0, 1,[
														{offset: 0, color: '#FF1616'},
														{offset: 0.7, color: '#FF5656'},
														{offset: 1, color: '#FF9595'}
												])
											}
										},
						data : data1}
					],

	};
	option && myChart4.setOption(option);
</script>
<script type="text/javascript">
	var chartDom5 = document.getElementById('price5');
	var myChart5 = echarts.init(chartDom5,);
	var option;
	var dataAxis = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
	var data = [220, 182, 191, 234, 290, 330, 310, 123, 442, 321, 90, 149, 210, 122, 133, 334, 198, 123, 125, 220];
	var data1 = [300, 182, 191, 234, 290, 330, 310, 123, 442, 321, 90, 149, 210, 122, 133, 334, 198, 123, 125, 220];
	var yMax = 500;
	var dataShadow = [];
	for (var i = 0; i < data.length; i++) {	dataShadow.push(yMax);}
	option = {
		legend: {show: true,textStyle: {fontSize: 14 },
			data: ['ค่าอะไหล่','ค่าจ้าง SubContaine']
						},

		 xAxis: {
			data: dataAxis,
			axisLabel: {inside: false,textStyle:{color: 'black'} },
	axisTick: {show: false},
	axisLine: {show: false},z: 10
						},
		yAxis: {axisLine: {show: false},axisTick: {show: false},axisLabel: {textStyle: {color: 'black'} } },
		series: [
				{	name: 'ค่าอะไหล่',
				 	type: 'bar',
					itemStyle: {color: new echarts.graphic.LinearGradient(0, 0, 0, 1,[
												{offset: 0, color: '#83bff6'},
												{offset: 0.5, color: '#188df0'},
												{offset: 1, color: '#188df0'}
										])
										},
				emphasis: {itemStyle: {color: new echarts.graphic.LinearGradient(0, 0, 0, 1,[
														{offset: 0, color: '#2378f7'},
														{offset: 0.7, color: '#2378f7'},
														{offset: 1, color: '#83bff6'}
															])
															}
									},
						data: data},
				{name: 'ค่าจ้าง SubContaine',
						type: 'bar',
						itemStyle: {color: new echarts.graphic.LinearGradient(0, 0, 0, 1,[
												{offset: 0, color: '#FF9595'},
												{offset: 0.5, color: '#FF5656'},
												{offset: 1, color: '#FF1616'}
										])
										},
					 emphasis: {itemStyle: {color: new echarts.graphic.LinearGradient(0, 0, 0, 1,[
														{offset: 0, color: '#FF1616'},
														{offset: 0.7, color: '#FF5656'},
														{offset: 1, color: '#FF9595'}
												])
											}
										},
						data : data1}
					],

	};
	option && myChart5.setOption(option);
</script>
<script type="text/javascript">
	var chartDom6= document.getElementById('price6');
	var myChart6 = echarts.init(chartDom6,);
	var option;
	var dataAxis = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
	var data = [220, 182, 191, 234, 290, 330, 310, 123, 442, 321, 90, 149, 210, 122, 133, 334, 198, 123, 125, 220];
	var data1 = [300, 182, 191, 234, 290, 330, 310, 123, 442, 321, 90, 149, 210, 122, 133, 334, 198, 123, 125, 220];
	var yMax = 500;
	var dataShadow = [];
	for (var i = 0; i < data.length; i++) {	dataShadow.push(yMax);}
	option = {
		legend: {show: true,textStyle: {fontSize: 14 },
			data: ['ค่าอะไหล่','ค่าจ้าง SubContaine']
						},

		 xAxis: {
			data: dataAxis,
			axisLabel: {inside: false,textStyle:{color: 'black'} },
	axisTick: {show: false},
	axisLine: {show: false},z: 10
						},
		yAxis: {axisLine: {show: false},axisTick: {show: false},axisLabel: {textStyle: {color: 'black'} } },
		series: [
				{	name: 'ค่าอะไหล่',
				 	type: 'bar',
					itemStyle: {color: new echarts.graphic.LinearGradient(0, 0, 0, 1,[
												{offset: 0, color: '#83bff6'},
												{offset: 0.5, color: '#188df0'},
												{offset: 1, color: '#188df0'}
										])
										},
				emphasis: {itemStyle: {color: new echarts.graphic.LinearGradient(0, 0, 0, 1,[
														{offset: 0, color: '#2378f7'},
														{offset: 0.7, color: '#2378f7'},
														{offset: 1, color: '#83bff6'}
															])
															}
									},
						data: data},
				{name: 'ค่าจ้าง SubContaine',
						type: 'bar',
						itemStyle: {color: new echarts.graphic.LinearGradient(0, 0, 0, 1,[
												{offset: 0, color: '#FF9595'},
												{offset: 0.5, color: '#FF5656'},
												{offset: 1, color: '#FF1616'}
										])
										},
					 emphasis: {itemStyle: {color: new echarts.graphic.LinearGradient(0, 0, 0, 1,[
														{offset: 0, color: '#FF1616'},
														{offset: 0.7, color: '#FF5656'},
														{offset: 1, color: '#FF9595'}
												])
											}
										},
						data : data1}
					],

	};
	option && myChart6.setOption(option);
</script>
@stop
{{-- ปิดส่วนjava --}}
