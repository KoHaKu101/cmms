@extends('masterlayout.masterlayout')
@section('tittle','homepage')
@section('css')
	<link href={{ asset('/assets/fullcalendar/main.css') }} rel='stylesheet' />
@endsection
{{-- ส่วนหัว --}}
@section('Logoandnavbar')

@stop
{{-- ปิดท้ายส่วนหัว --}}

{{-- ส่วนเมนู --}}
@section('sidebar')
@stop
{{-- ปิดส่วนเมนู --}}

	{{-- ส่วนเนื้อหาและส่วนท้า --}}
@section('contentandfooter')

		<div class="content">
			<div class="panel-header bg-gradient">
				<div class="page-inner py-4 my-4">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header bg-primary">
								<h4 class="card-title text-white">Predictive Plan	: รายงานประจำปี</h4>
							</div>
							<div class="card-body text-center">
								<div class="row ">
									@for ($m=date('Y')-2; $m < date('Y')+2; $m++)
										<div class="col-sm-6 col-md-3">
											<div class="card card-stats card-round">
												<div class="card-body"
												onclick="positionedPopup('{{ route('SparPart.Report.planmonthprint').'?DOC_YEAR='.$m}}','myWindow');return false"
													style="cursor:pointer;" >
													<div class="row align-items-center">
														<div class="col-icon">
															<div class="icon-big text-center icon-success bubble-shadow-small">
																<i class="flaticon-graph"></i>
															</div>
														</div>
														<div class="col col-stats ml-3 ml-sm-0">
															<div class="numbers">
																<p class="card-category">รายงานปี</p>
																<h4 class="card-title">{{$m}}</h4>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									@endfor
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="card">
							<div class="card-header bg-primary form-inline">
								<h4 class="card-title text-white">Predictive Plan	: รายงานประจำเดือน  ปี:</h4>
								<select class="form-control form-control-sm input-group filled text-info mx-3" id="PLAN_YEAR" name="PLAN_YEAR">
									@for ($m=date('Y')-2; $m < date('Y')+2; $m++)
										<option value="{{$m}}" {{ date('Y') == $m ? 'selected' : '' }}>{{$m}}</option>
									@endfor
								</select>
							</div>
							<div class="card-body ">
								<div class="row row-projects">
									@php
									$months=array(0 =>'ALL',1 => "มกราคม",2 => "กุมภาพันธ์",3 =>"มีนาคม",4 => "เมษายน",5 =>"พฤษภาคม",6 =>"มิถุนายน",
																	 7 =>"กรกฎาคม",8 =>"สิงหาคม",9 =>"กันยายน",10 =>"ตุลาคม",11 => "พฤศจิกายน",12 =>"ธันวาคม");
									@endphp
									@for ($i=1; $i < 13; $i++)
										<div class="col-sm-6 col-lg-2 text-center">
											<div class="card"onclick="planmonthpdf('{{ $i }}')"
											style="cursor:pointer;">
												<div class="p-2">
													<img class="card-img-top rounded" src="{{asset('../assets/img/12zodiac/'.$i.'.png')}}" alt="Product 5" style="width:100px">
												</div>
												<div class="card-body pt-2 ">
													<h4 class="mb-1 fw-bold">{{$months[$i]}}</h4>

													<button class="btn btn-primary btn-block" ><i class="fas fa-print"> Print</i></button>
												</div>
											</div>
										</div>
									@endfor
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
<script src="{{ asset('assets/js/ajax/appcommon.js') }}"></script>
<script>
 function planmonthpdf(month){
	 var year = $('#PLAN_YEAR').val();

	 var url  = "{{ route('SparPart.Report.planmonthprint') }}?DOC_YEAR="+year+"&DOC_MONTH="+month;

	 window.open(url , 'PLANPDM', 'width=1000,height=1000,resizable=yes,top=100,left=100,menubar=yes,toolbar=yes,scroll=yes');
 }
</script>

@stop
{{-- ปิดส่วนjava --}}
