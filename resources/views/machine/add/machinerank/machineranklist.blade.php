@extends('masterlayout.masterlayout')
@section('tittle','homepage')
@section('css')

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
      <div class="page-inner">
				<div class="py-12">
	        <div class="container mt--4">
						<div class="row">
							<div class="col-md-12">
								<div class="card "></div>
								<div class="row">
									<div class="col-md-7 col-lg-4 ">
										<div class="card">
											<div class="card-header bg-primary">
												<h4 class="ml-3 mt-2" style="color:white;" ><i class="fas fa-cubes fa-lg mr-1"></i>	รายการ Rank
													<button  id="popup" type="button" class="btn btn-warning float-right btn-sm" data-toggle="modal" data-target="#NewRank">
														<i class="fas fa-file" style="color:white;font-size:14px"> New</i>
													</button>
												</h4>
											</div>
											<div class="card-body">
												<ul class="nav nav-tab flex-column col-md-12 col-lg-12" id="tabActive" >
													@foreach ($datarank as $key => $dataset)
														<li>
															<a href="{{ url('machine/machinerank/list/'.$dataset->UNID) }}"  class="btn btn-primary btn-sm my-2" style="width:190px" >
																	<div class="form-inline">
																		<div class="col-md-4 col-lg-4">
																			Rank : {{ $dataset->MACHINE_RANK_CODE }}
																		</div>
																		<div class="col-md-6 col-lg-6">
																			ระยะเวลา : {{ $dataset->MACHINE_RANK_MONTH }} เดือน
																		</div>
																	</div>
															</a>
															<button  id="popup" type="button" class="btn btn-primary btn-link btn-sm" data-toggle="modal" data-target="#EditRank"
															 onclick="datarank('{{ $dataset->UNID}}','{{ $dataset->MACHINE_RANK_CODE }}','{{ $dataset->MACHINE_RANK_MONTH }}')">
																<i class="fas fa-edit fa-2x"> </i>
															</button>
															<a href="{{ url('machine/machinerank/delete/'.$dataset->UNID) }}" class="btn btn-danger btn-link btn-sm" >
															<i class="fas fa-trash" style="font-size:20px"></i> </a>
														</li>
													@endforeach
						        		</ul>
											</div>
										</div>
									</div>
									<div class="col-md-5 col-lg-8">
										<div class="" id="tabdetail" name="tabdetail">
											@if ($open > 0)
												<div class="card" >
													<div class="tab-content clearfix">
														<div class="tab-pane active" >
															<div class="card-header bg-primary">
																<h4 class="ml-3 mt-2" style="color:white;" ><i class="fas fa-cubes fa-lg mr-1"></i>Rank	: {{ $datafirst->MACHINE_RANK_CODE}}
																</h4>
															</div>
														</div>
													</div>
													<div class="card-body">
														<div class="row">
														<div class="col-md-12">
															<div class="table">
																<table class="table table-bordered table-head-bg-info table-bordered-bd-info table-hover">
																	<thead>
																		<tr>
																			<th colspan="6">รหัสเครื่องจักร</th>
																		</tr>
																	</thead>
																	<tbody>
																			<?php
																			$i=0;
																			foreach ($datamachine as $key=>$datasub)
  																		{
																				if (!fmod($i,3)) {
																					echo '<tr>';
																				}
																				echo '<td>'. $key+1 .'</td>';
  																			echo '<td>',$datasub->MACHINE_CODE,'</td>';
  																			$i++;
  																		}
																			 ?>
																	</tbody>
																</table>
																{{$datamachine->links('pagination.default')}}
															</div>
														</div>
													</div>
													</div>
												</div>
											@endif
										</div>
									</div>
								</div>
              </div>
						</div>
					</div>
  			</div>
			</div>
		</div>

		<!-- Modal -->
			@include('machine/add/machinerank/modalmachinerankedit')
		@include('machine/add/machinerank/modalmachinerank')


@stop
{{-- ปิดส่วนเนื้อหาและส่วนท้า --}}

{{-- ส่วนjava --}}
@section('javascript')

<script>
function datarank(unid,code,month){
	var unid = (unid) ;
	var code = (code) ;
	var month = (month);
	var _html=    '<div class="row ">'+
								'<div class="col-md-6 col-lg-12">  รายการ Rank  </div>'+
								'<div class="col-md-6 col-lg-12 mt-2 has-error">'+
								'<input type="hidden" class="form-control" id="UNID" name="UNID" value="'+unid+'" required>'+
								'<input type="text" class="form-control" id="MACHINE_RANK_CODE" name="MACHINE_RANK_CODE" value="'+code+'" required>'+
								'</div>'+
								'</div>'+
								'<div class="row mt-4">'+
								'<div class="col-md-6 col-lg-12">  ระยะเวลา (เดือน)  </div>'+
								'<div class="col-md-6 col-lg-12 mt-2 has-error">'+
								'<input type="number" class="form-control" id="MACHINE_RANK_MONTH" name="MACHINE_RANK_MONTH" value="'+month+'" required>'+
								'</div>'+
								'</div>';


$("#datarank").html(_html);
}
</script>


@stop
{{-- ปิดส่วนjava --}}
