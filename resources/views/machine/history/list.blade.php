@extends('masterlayout.masterlayout')
@section('tittle','homepage')
@section('css')
{{-- <link rel="stylesheet" href="{{asset('assets/css/bulma.min.css')}}"> --}}
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
@php
$months=array(0 =>'ALL',1 => "มกราคม",2 => "กุมภาพันธ์",3 =>"มีนาคม",4 => "เมษายน",5 =>"พฤษภาคม",6 =>"มิถุนายน",
								 7 =>"กรกฎาคม",8 =>"สิงหาคม",9 =>"กันยายน",10 =>"ตุลาคม",11 => "พฤศจิกายน",12 =>"ธันวาคม");
@endphp
	  <div class="content">
			<div class="page-inner">
				<div class="py-4">
						<div class="row">
							<div class="col-md-12">
								<div class="card ">
								  <div class="card-header bg-primary  ">
										<form action="{{ route('history.repairlist') }}" method="POST" enctype="multipart/form-data" id="FRM_HISTORY">
											@method('GET')
											@csrf
											<div class="row">
												<div class="col-md-2 text-white">
													<h4 class="my-2">ใบประวัติเครื่องจักร</h4>
												</div>
												<div class="col-md-6 form-inline">
													<lable class="text-white"> ประเภท </lable>
													<select class="form-control form-control-sm mx-2" id="DOC_TYPE" name="DOC_TYPE" onchange="submitform(this)">
														<option value>กรุณาเลือก</option>
														<option value="REPAIR" {{ $DOC_TYPE == 'REPAIR' ? 'selected' : ''  }} >Repair</option>
														<option value="PLAN_PM" {{ $DOC_TYPE == 'PLAN_PM' ? 'selected' : ''  }} >Preventive</option>
														<option value="PLAN_PDM" {{ $DOC_TYPE == 'PLAN_PDM' ? 'selected' : '' }}>Predictive</option>
													</select>
													<lable class="text-white"> ปี </lable>
													<select class="form-control form-control-sm mx-2" onchange="submitform(this)" id="DOC_YEAR" name="DOC_YEAR">
														@for ($YEAR= date('Y')-2; $YEAR < date('Y')+2; $YEAR++)
															<option value="{{ $YEAR }}" {{ $DOC_YEAR == $YEAR ? 'selected' : '' }}>{{$YEAR}}</option>
														@endfor
													</select>
													<lable class="text-white"> เดือน </lable>
													<select class="form-control form-control-sm mx-2" onchange="submitform(this)" id="DOC_MONTH" name="DOC_MONTH">
															<option value="0">ทั้งหมด</option>
														@for ($MONTH=1; $MONTH < 13; $MONTH++)
															<option value="{{ $MONTH }}" {{$DOC_MONTH == $MONTH ? 'selected' : ''}}>{{ $months[$MONTH] }}</option>
														@endfor
													</select>
												</div>
												<div class="col-md-4 form-inline">
													<label class="text-white">ค้นหา</label>
													<div class="input-group mt-1 mx-2">
									            <input type="search" id="SEARCH_MACHINE" name="SEARCH_MACHINE" class="form-control form-control-sm " value="{{ $SEARCH }}">
									            <div class="input-group-prepend">
									              <button type="submit" class="btn btn-search pr-1 btn-xs	SEARCH">
									                <i class="fa fa-search search-icon"></i>
									              </button>
									            </div>
									          </div>
												</div>
											</div>
										</form>
								  </div>
									@if ($DOC_TYPE == 'REPAIR')
										@include('machine.history.repair')
									@elseif ($DOC_TYPE == "PLAN_PM")
										@include('machine.history.planpm')
									@elseif($DOC_TYPE == "PLAN_PDM")
										@include('machine.history.planpdm')
									@endif

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

<script>
 function submitform(){
	 $('#FRM_HISTORY').submit();
 }
</script>

@stop
{{-- ปิดส่วนjava --}}
