@extends('masterlayout.masterlayout')
@section('tittle','homepage')
@section('css')
{{-- <link rel="stylesheet" href="{{asset('assets/css/bulma.min.css')}}"> --}}
@endsection
{{-- ส่วนหัว --}}
@section('Logoandnavbar')

	@include('masterlayout.logomaster')
	@include('masterlayout.navbar.navbarmaster')

@stop
{{-- ปิดท้ายส่วนหัว --}}

{{-- ส่วนเมนู --}}
@section('sidebar')

	@include('masterlayout.sidebar.sidebarmaster0')

@stop
{{-- ปิดส่วนเมนู --}}

	{{-- ส่วนเนื้อหาและส่วนท้า --}}
@section('contentandfooter')

	  <div class="content">
      <div class="page-inner">
				<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
          <div class="container">
						<div class="row">
							<div class="col-md-12 gx-4">

							</div>
						</div>
          </div>
				</div>
				<div class="py-12">
	        <div class="container mt-2">
						<div class="row">
							<div class="col-md-12">
								<div class="card ">
									<div class="card-header bg-primary ">
										<form action="{{ url('machine/manual/manuallist') }}" method="POST" id="FRM_MANUALLIST">
											@method('GET')
											@csrf
											<div class="row text-white">
												<div class="col-md-8 form-inline">
													<h4 class="ml-3 mt-2 " ><i class="fas fa-book-open fa-lg mr-1"></i> คู่มือ </h4>
													<label class="text-white mx-3">LINE : </label>
													<select class="form-control form-control-sm col-md-2 mx-2" id="MACHINE_LINE" name="MACHINE_LINE"
													onchange="changeline()">
															<option value> ทั้งหมด</option>
														@foreach ($LINE as $key => $row_line)
															<option value="{{ $row_line->LINE_CODE }}" {{ $MACHINE_LINE == $row_line->LINE_CODE ? 'selected' : ''}}>
																 {{ $row_line->LINE_NAME }}</option>
														@endforeach
													</select>
												</div>
												<div class="col-md-4 form-inline">
													<label class="text-white mx-3">SEARCH : </label>
													<div class="input-group ">
														<input type="text" id="SEARCH"  name="SEARCH" class="form-control form-control-sm" value="{{ $SEARCH }}">
														<div class="input-group-prepend">
															<button type="submit" class="btn btn-search pr-1 btn-xs SEARCH">
																<i class="fa fa-search search-icon"></i>
															</button>
														</div>
													</div>

												</div>
											</div>
											</form>
									</div>

									<div id="result"class="card-body">
										<div class="table-responsive">
                      <table class="display table table-striped table-hover">
                      	<thead class="thead-light">
                        	<tr>
														<th>LINE</th>
                            <th>MC-CODE	</th>
                          	<th>Machine Name	</th>
														<th>MC-TYPE</th>
														<th>จำนวนไฟล์คู่มือ</th>
                        	</tr>
                      	</thead>
                      	<tbody >
													@foreach ($dataset as $key => $row)
                        		<tr>
															<td width="50px">{{ $row->MACHINE_LINE }}</td>
															<td width="100px">
																<a href="{{ url('machine/manual/show/'.$row->UNID) }}"
																	class="btn btn-secondary btn-block btn-sm mx-1 my-1 text-left">
																	<i class="fas fa-eye fa-lg mr-1"></i>{{ $row->MACHINE_CODE }}</a>  </td>
															<td >{{ $row->MACHINE_NAME_TH }}  </td>
															<td width="200px">{{ $row->MACHINE_TYPE }}  </td>
															<td width="120px" class="text-center">{{ $data_upload->where('UPLOAD_UNID_REF','=',$row->UNID)->count() }}</td>
                        			</tr>
                        	@endforeach
                      	</tbody>
                    </table>
									</div>
										</div>
										{{ $dataset->appends(['SEARCH' => $SEARCH,'MACHINE_LINE' => $MACHINE_LINE])->links('pagination.default') }}

								</div>

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
	<script>
		function changeline(){
			$('.SEARCH').click();
		}

	</script>


@stop
{{-- ปิดส่วนjava --}}
