@extends('masterlayout.masterlayout')
@section('tittle','homepage')
@section('css')
	<link href={{ asset('/assets/fullcalendar/main.css') }} rel='stylesheet' />

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

	$MONTH_NAME_TH = array(0 =>'ALL',1 => "มกราคม",2 => "กุมภาพันธ์",3 =>"มีนาคม",4 => "เมษายน",5 =>"พฤษภาคม",6 =>"มิถุนายน",
									 7 =>"กรกฎาคม",8 =>"สิงหาคม",9 =>"กันยายน",10 =>"ตุลาคม",11 => "พฤศจิกายน",12 =>"ธันวาคม");
	$MONTH  = $MONTH != '' ? $MONTH : Carbon\Carbon::now()->isoformat('M') ;
	$YEAR 	= $YEAR  != '' ? $YEAR  : date('Y');
	@endphp

		<div class="content">
			<div class="panel-header bg-gradient">
				<div class="page-inner py-4 my-4">

						<div class="col-md-12">
								<div class="card">
									<div class="card-header bg-primary ">
										<form action='{{ url('machine/daily/list')}}' method="POST" id="FRM_CHECKSHEET" name="FRM_CHECKSHEET" enctype="multipart/form-data">
											@method('GET')
											@csrf
											<div class="row">
												<div class="col-md-12 col-lg-9 form-inline">
													<h4 class="card-title text-white">Daily CheckSheet ปี :</h4>
													<select class="form-control form-control-sm input-group filled text-info my-1 mx-3 col-4 col-md" id="YEAR" name="YEAR">
														@for ($y=date('Y')-2; $y < date('Y')+2; $y++)
															<option value="{{$y}}" {{  $YEAR == $y ? 'selected' : '' }}>{{$y}}</option>
														@endfor
													</select>
													<h4 class="card-title text-white"> เดือน : </h4>
													<select class="form-control form-control-sm input-group filled text-info my-1 mx-3 col-4 col-md" id="MONTH" name="MONTH">
															@for ($m=1; $m < 13; $m++)
																<option value="{{$m}}" {{ $MONTH == $m ? 'selected' : '' }}>{{$MONTH_NAME_TH[$m]}}</option>
															@endfor
													</select>
												<h4 class="card-title text-white">Line</h4>
													<select class="form-control form-control-sm input-group filled text-info my-1 mx-3 col col-md-2" id="MACHINE_LINE" name="MACHINE_LINE">
														<option value="0" >ALL</option>
														@for ($l=1; $l < 7; $l++)
															<option value="{{'L'.$l}}" {{ $MACHINE_LINE == 'L'.$l ? 'selected' : ''}}>L{{$l}}</option>
														@endfor
													</select>
												</div>
												<div class="col-md-6 col-lg-3 my-3">
													<div class="card-title text-white">
														<div class="input-group">
															<input type="text" id="SEARCH_MACHINE" name="SEARCH_MACHINE" class="ml-3 col-9 form-control form-control-sm"
															 value="{{ $MACHINE_CODE }}" placeholder="ค้นหา Machine No">
															<div class="input-group-append">
																<button type="submit" class="btn btn-search pr-1 btn-xs	">
																	<i class="fa fa-search search-icon"></i>
																</button>
															</div>
														</div>
													</div>
												</div>
											</div>
										</form>

									</div>
									@php
									  if (isset($DATA_CHECKSHEET)) {
									    $img_array = array();
									    $img_unidarray = array();
									    foreach ($DATA_CHECKSHEET as $index => $row_img) {
									      $img_array[$row_img->MACHINE_UNID] = $row_img->FILE_NAME;
									      $img_unidarray[$row_img->MACHINE_UNID] = $row_img->UNID;
									    }
									  }
									@endphp
									<div class="card-body">
									  <div class="col-md-12">
									    <div class="table-responsive">
									      <table class="table table-bordered table-head-bg-info table-bordered-bd-info">
									        <thead>
									          <tr>
									            <th  width="20px">#</th>
									            <th  width="50px">Machine NO.</th>
									            <th  width="250px">Machine Name</th>
									            <th  width="50px">LINE</th>
									            <th  width="90px">Upload</th>
									            <th width="120px">View</th>
									          </tr>
									        </thead>
									        <tbody>
									          @foreach ($DATA_MACHINE as $index => $row_machine)
															@php

																$MACHINE_UNID = $row_machine->UNID;
																$DAILRY_UNID 	= isset($img_unidarray[$MACHINE_UNID]) ? $img_unidarray[$MACHINE_UNID] : "";
																$STYLE_NONE   = isset($img_array[$MACHINE_UNID]) ? '' : 'none';
															@endphp
									          <tr>
									            <td class="text-center">{{ $DATA_MACHINE->firstItem() + $index }}</td>
									            <td>{{$row_machine->MACHINE_CODE}}</td>
									            <td>{{$row_machine->MACHINE_NAME_V2}}</td>
									            <td>{{$row_machine->MACHINE_LINE}}</td>
									            <td><button type="button" class="btn btn-secondary btn-block btn-sm my-1 BTN_UPLOAD" onclick="uploadimg(this)"
									              data-mccode="{{$row_machine->MACHINE_CODE}}"
									              data-mcunid="{{ $MACHINE_UNID }}"
									              data-toggle="modal"id="{{ $MACHINE_UNID }}" name="{{ $MACHINE_UNID }}"
									              >
									              <i class="fas fas fa-image fa-lg mr-1"></i>
									               Upload</button></td>
									            <td>

									              <button type="button" class="btn btn-primary btn-sm mx-1 my-1 view-img"
																onclick="window.open('{{ url('machine/daily/view/'.$DAILRY_UNID) }}', '_blank', 'width=1000,height=1000,resizable=yes,top=100,left=100,menubar=yes,toolbar=yes,scroll=yes');"
									                style="display:{{$STYLE_NONE}};"
																	>
									                <i class="fas fa-eye fa-lg"></i> View
									              </button>
									              <button type="button" class="btn btn-danger btn-sm mx-1 my-1"
									                onclick="deleteimg(this)"
									                data-imgunid="{{ isset($img_unidarray[$MACHINE_UNID]) ? $img_unidarray[$MACHINE_UNID] : '' }}"
									                data-mccode="{{ $row_machine->MACHINE_CODE }}"
									                style="display:{{$STYLE_NONE}};">
									                <i class="fas fa-trash fa-lg"></i> Delete
									              </button></td>
									          </tr>
									          @endforeach


									        </tbody>
									      </table>
									    </div>
									    {{ $DATA_MACHINE->appends(['SEARCH_MACHINE'=>$MACHINE_CODE])->links('pagination.default') }}
									  </div>
									</div>
								</div>
							</div>
				</div>
			</div>
		</div>

		<style>
		.close {
    float: right;
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1;
    color: #fff;
    text-shadow: 0 1px 0 #fff;
    opacity: .5;
}
		</style>
		{{-- เพิ่ม Template --}}
		@include('machine.dailycheck.modaluploadimg')
	{{-- view --}}
			@include('machine.dailycheck.modalviewimg')	




@stop
{{-- ปิดส่วนเนื้อหาและส่วนท้า --}}

{{-- ส่วนjava --}}
@section('javascript')
	<script src="{{ asset('assets/js/ajax/ajax-csrf.js') }}">
	</script>

	<script src="{{ asset('assets/js/useinproject/checksheet.js') }}">
	</script>


@stop
{{-- ปิดส่วนjava --}}
