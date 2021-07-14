@extends('masterlayout.masterlayout')
@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('tittle','แจ้งซ่อม')
@section('css')
		<link href="{{asset('assets/css/select2.min.css')}}" rel="stylesheet">
		<link href="{{ asset('assets/css/_wizard.scss') }}" >

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
	.select2 select2-container select2-container--default select2-container--above select2-container--open{
		width: 200px
	}

	</style>
	<div class="content">


			<div class="container">
				<div class="page-inner">
						<div class="card">
							<div class="col-md-12">
								<div class="row mx-1 my-2">
									<a href="{{ route('repair.repairsearch') }}" class="btn btn-warning btn-sm "><span class="fas fa-arrow-left fa-lg mr-1"></span> Back</a>
								</div>
								<div class="card-header text-center bg-primary ">
									<h3 class="card-title text-white"><b>แจ้งซ่อม เครื่อง</b> {{$datamachine->MACHINE_CODE}}</h3>
								</div>
							</div>
								<div class="card-body">
									<div class="row">
										<div class="col-md-8 ml-auto mr-auto" id="tabactive">
											<ul class=" nav nav-pills nav-primary">
												<li class="step">
													<a class="nav-link active repair"  href="#repair"  aria-expanded="true" id="step1" data-toggle="tab"><i class="fa fa-user mr-0"></i> แจ้งอาการ</a>
												</li>
												<li class="step">
													<a class="nav-link repairdetail" href="#repairdetail" id="step2"  ><i class="fa fa-file mr-2"></i> รายละเอียด</a>
												</li>
												<li class="step">
													<a class="nav-link empname" href="#empname" id="step3"  ><i class="fa fa-file mr-2"></i> ผู้แจ้ง</a>
												</li>
												<li class="step">
													<a class="nav-link priority" href="#priority" id="step4"  ><i class="fa fa-file mr-2"></i> ความเร่งด่วน</a>
												</li>
												<li class="step">
													<a class="nav-link summary" href="#summary" id="step5"  ><i class="fa fa-map-signs mr-2"></i> สรุป</a>
												</li>
											</ul>
										</div>
									</div>
									<style>
						      .card-stats .card-body {
						        padding: 0px!important;
						        }
						      </style>
									<div class="tab-content my-4">
										<div class="tab-pane active " id="repair">
											<div class="row">
												@foreach ($dataset as $index => $row)
												<div class="col-6 col-sm-6 col-md-4 col-lg-3">
													<a  onclick="selectrepair(this)"  data-unid="{{$row->UNID}}"
														style="cursor:pointer">
													<div class="card card-stats card-primary card-round">
														<div class="card-body">
															<div class="row">
																<div class="col-3">
																	<div class="icon-big ml-2">
																		<i class="fas fa-briefcase"></i>
																	</div>
																</div>
																<div class="col-9 col-stats">
																	<div class="numbers">
																		<p class="card-category">{{$row->REPAIR_MAINSELECT_NAME}}</p>
																	</div>
																</div>
															</div>
														</div>
													</div>
													</a>
												</div>
												@endforeach

											</div>
											<div class="card-action text-center">
												<a href="{{ route('repair.repairsearch') }}" class="btn btn-warning mx-1 my-1"
												><i class="fas fa-arrow-alt-circle-left mr-1"></i>Previous</a>
												<button type="button" class="btn btn-primary mx-1 my-1"
												onclick="nextstep(this)"
												data-step="step2">Next <i class="fas fa-arrow-alt-circle-right ml-1"></i></button>
											</div>
										</div>
										<div class="tab-pane" id="repairdetail">
										</div>
										<div class="tab-pane " id="empname">
											<div class="row">
												<div class="col-md-12 ">
													<div class="form-group text-center">
															<div class="col-md-12 has-error ml-auto mr-auto my-2">
																	@foreach ($data_emp as $key => $row)
																		<button type="button" class="btn btn-info mx-1 my-1 text-left" style="width:200px"
																		onclick="selectemp(this)"
																		data-empcode="{{ $row->EMP_CODE }}"
																		data-empname="{{ $row->EMP_TH_NAME_FIRST }}"
																		>
																			{{ 'รหัส '.$row->EMP_CODE.' : ชื่อ '.$row->EMP_TH_NAME_FIRST }}</button>
																	@endforeach
															</div>
													</div>
													<div class="card-action text-center">
														<button type="button" class="btn btn-warning mx-1 my-1"
														onclick="previousstep(this)"
														data-step="step2"><i class="fas fa-arrow-alt-circle-left mr-1"></i> Previous</button>
														<button type="button" class="btn btn-primary mx-1 my-1"
														onclick="nextstep(this)"
														data-step="step4">Next <i class="fas fa-arrow-alt-circle-right ml-1"></i></button>
													</div>
												</div>
											</div>
										</div>
										<div class="tab-pane " id="priority">
											<div class="col-md-6 ml-auto mr-auto">
												<div class="row">
													<div class="col-sm-6 col-md-6">
														<a  onclick="selectpriority(this)"  data-priority="1" style="cursor:pointer">
														<div class="card card-stats card-primary card-round">
															<div class="card-body">
																<div class="row">
																	<div class="col-5">
																		<div class="icon-big text-center">
																			<i class="flaticon-users"></i>
																		</div>
																	</div>
																	<div class="col-7 col-stats">
																		<div class="numbers">
																			<p class="card-category" style="font-size:20px">ไม่เร่งด่วน</p>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</a >
													</div>
													<div class="col-sm-6 col-md-6">
														<a  onclick="selectpriority(this)"  data-priority="9" style="cursor:pointer">
														<div class="card card-stats card-danger card-round">
															<div class="card-body ">
																<div class="row">
																	<div class="col-5">
																		<div class="icon-big text-center">
																			<i class="fab fa-hotjar fa-lg" ></i>
																		</div>
																	</div>
																	<div class="col-7 col-stats">
																		<div class="numbers">
																			<p class="card-category" style="font-size:20px">เร่งด่วน</p>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</a >
													</div>
												</div>
											</div>
											<div class="card-action text-center">
												<button type="button" class="btn btn-warning mx-1 my-1"
												onclick="previousstep(this)"
												data-step="step3">Previous</button>
												<button type="button" class="btn btn-primary mx-1 my-1"
												onclick="nextstep(this)"
												data-step="step5">Next <i class="fas fa-arrow-alt-circle-right ml-1"></i></button>
											</div>
										</div>
										<div class="tab-pane " id="summary">
											<form action="{{ route('repair.store',$datamachine->UNID) }}" method="post" id="FRM_SENDREPORT" name="FRM_SENDREPORT">
												@csrf
												<div class="row">
													<div class="col-md-6 ml-auto mr-auto">
															<table class="table table-bordered table-bordered-bd-info">
																<tbody>
																	<tr>
																		<td width="80px" style="background:#aab7c1;color:black;"><h5 class="my-1"> MC-NO </h5></td>
																		<td> {{$datamachine->MACHINE_CODE}} LINE:{{$datamachine->MACHINE_LINE}}</td>
																	</tr>
																	<tr>
																		<td style="background:#aab7c1;color:black;"><h5 class="my-1">พนักงาน</h5>  </td>
																		<td id="summaryemp"> </td>
																	</tr>
																	<tr>
																		<td style="background:#aab7c1;color:black;"><h5 class="my-1">อาการ</h5>  </td>
																		<td id="summaryrepair"> </td>
																	</tr>
																	<tr>
																		<td style="background:#aab7c1;color:black;"><h5 class="my-1">ระดับ</h5>  </td>
																		<td id="summarypriority"> </td>
																	</tr>
																</tbody>
															</table>
													</div>
												</div>
												<div class="card-action text-center">
													<button type="button" class="btn btn-warning mx-1 my-1"
													onclick="previousstep(this)"
													data-step="step4">
														<i class="fas fa-arrow-alt-circle-left mr-1"></i>Previous</button>
													<button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i>Save</button>
												</div>
											</form>
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
	<script src="{{ asset('assets/js/ajax/ajax-csrf.js') }}"></script>
	<script src="{{ asset('../../assets/js/plugin/datatables/datatables.min.js')}}"></script>
	<script>
	$(".tabactive").each(function(){
		$(this).click(function(){
			id = $(this).attr('id');
			url = window.location.href;
			$(".nav-link").removeClass("active");
			$(this).addClass("active show");
			$('.tab-pane').removeClass("active");
			$("#"+id).addClass("active");

		});
	});
		function setcookie(value,name){
			var urlcookie = "{{ route('cookie.set') }}";
			var data = {"_token": "{{ csrf_token() }}",VALUE : value,NAME : name}
			$.ajax({
				type:'GET',
				url: urlcookie,
				datatype: 'json',
				data: data ,
				success:function(res){
						}
					});
		}
		function selectrepair(thisdata){
			var unid = $(thisdata).data('unid');
			$('#step2').removeAttr('data-toggle');
			$('#step3').removeAttr('data-toggle');
			$('#step4').removeAttr('data-toggle');
			$('#step5').removeAttr('data-toggle');
			event.preventDefault();
			var url = "{{route('repair.selectrepairdetail')}}";
			var data = {"_token": "{{ csrf_token() }}",UNID : unid};
			var name = 'selectmainrepair';
			if (unid) {
				$.ajax({
					type:'POST',
					url: url,
					datatype: 'json',
					data: data ,
					success:function(data){
						setcookie(unid,name);
						$('#repairdetail').html(data.html);
						$('#step2').attr('data-toggle','tab');
						$('#step2').click();
							}
						});
			}

		}
		function selectrepairdetail(thisdata){
			$('#step3').removeAttr('data-toggle');
			$('#step4').removeAttr('data-toggle');
			$('#step5').removeAttr('data-toggle');
			var unid = $(thisdata).data('unid');
			var empname = $(thisdata).data('name');
			var name = 'selectsubrepair';
			setcookie(unid,name);
			$('#summaryrepair').html(empname);
			$('#step3').attr('data-toggle','tab');
			$('#step3').click();
		}
		function selectemp(thisdata){
			$('#step4').removeAttr('data-toggle');
			$('#step5').removeAttr('data-toggle');
	    var emp_code = $(thisdata).data('empcode');
			var emp_name = $(thisdata).data('empname');
			var name = 'empcode';
			setcookie(emp_code,name);

			$('#summaryemp').html(emp_code+' '+emp_name);
			$('#step4').attr('data-toggle','tab');
			$('#step4').click();
		}
		function selectpriority(thisdata){
			$('#step5').removeAttr('data-toggle');
	    var priority = $(thisdata).data('priority');
			var checkpriority = priority == '9' ? 'เร่งด่วน' : 'ไม่เร่งด่วน';
			var name = 'priority';
			setcookie(priority,name);
			$('#summarypriority').html(checkpriority);
			$('#step5').attr('data-toggle','tab');
			$('#step5').click();

		}
		function previousstep(thisdata){
			var step = $(thisdata).data('step');
			$('#'+step).click();
		}
		function nextstep(thisdata){
			var step = $(thisdata).data('step');
			$('#'+step).click();
		}
	// $(document).ready(function(){
	// 	var table =	$('.step1').DataTable({
	// 			"pageLength": 10,
	// 			"bLengthChange": false,
	// 			"bFilter": true,
	// 			"bInfo": false,
	// 			"bAutoWidth": false,
	// 			columnDefs: [
	// 			{ orderable: false, targets:[0,1,2,3,4] }
	// 		]
	// 		});
	// });

	</script>


@stop
{{-- ปิดส่วนjava --}}
