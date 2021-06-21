@extends('masterlayout.masterlayout')
@section('tittle','Machine')
@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">

@endsection
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
				<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
          <div class="container">
						<div class="row">
							<div class="col-md-12 gx-4">
								<a href="{{ route('machine') }}">
									<button class="btn btn-warning  btn-xs ">
										<span class="fas fa-arrow-left fa-lg"> Back </span>
									</button>
								</a>
								<a href="{{ route('machine.form') }}"><button class="btn btn-primary  btn-xs">
									<span class="fas fa-file fa-lg">	New	</span>
								</button></a>
								<a href="{{ url('machine/export/') }}">
								<button class="btn btn-primary  btn-xs">
									<span class="fas fa-file-export fa-lg">	ExportAll	</span>
								</button>
								</a>
								<button class="btn btn-primary  btn-xs" type="button" id="buttonprint">
									<input type="hidden" id="MACHINE_LINE" name="MACHINE_LINE" value="{{ $MACHINE_LINE }}">
									<span class="fas fa-print fa-lg">	Print	</span>
								</button>

							</div>
						</div>
          </div>
				</div>
				<div class="py-12">
	        <div class="container mt-2">
						<div class="row">
							<div class="col-md-12">
								<div class="card ">
								  <div class="card-header bg-primary  ">
										<form action="{{ route('machine.list',$MACHINE_LINE) }}" method="POST" enctype="multipart/form-data" id="FRM_SEARCH">
											@method('GET')
											@csrf
								    <div class="row ">

											<div class="col-md-8 form-inline ">
												<label class="text-white">Line : </label>
												 <select class="form-control form-control-sm mt-1 mx-1" id="LINE" name="LINE" onchange="changeline()">
													 <option value="0">ทั้งหมด</option>
													 @foreach ($LINE as $index => $row_line)
														 <option value="{{ $row_line->LINE_CODE }}" {{ $MACHINE_LINE == $row_line->LINE_CODE ? 'selected' : '' }}>{{ $row_line->LINE_NAME }}</option>
													 @endforeach
												 </select>
												<label class="text-white mx-2">Rank : </label>
												 <select class="form-control form-control-sm mt-1 mx-1" id="MACHINE_RANK_CODE" name="MACHINE_RANK_CODE" onchange="changerank()">
													 <option value="0" >ทั้งหมด</option>
													 @foreach ($RANK as $index => $row_rank)
														 <option value="{{ $row_rank->MACHINE_RANK_CODE }}" {{ $MACHINE_RANK_CODE == $row_rank->MACHINE_RANK_CODE ? 'selected' : '' }}>{{ $row_rank->MACHINE_RANK_CODE }}</option>
													 @endforeach
												 </select>
												<label class='text-white mx-2'>สถานะการใช้งาน : </label>
												<select class="form-control form-control-sm mt-1 mx-1" name="MACHINE_CHECK" id="MACHINE_CHECK" onchange="changerank()">
														<option value="0">-ทั้งหมด-</option>
														<option value="1" {{ $MACHINE_CHECK == "1" ? 'selected': '' }}>หยุด/เสีย</option>
														<option value="2" {{ $MACHINE_CHECK == "2" ? 'selected': '' }}>ทำงาน</option>
														<option value="3" {{ $MACHINE_CHECK == "3" ? 'selected': '' }}>รอผลิต</option>
														<option value="4" {{ $MACHINE_CHECK == "4" ? 'selected': '' }}>แผนผลิต</option>
													</select>

														<label class="text-white mx-2">สถานะ : </label>
														<select class="form-control form-control-sm mt-1 mx-1" id="MACHINE_STATUS" name="MACHINE_STATUS" onchange="changerank()" >
															<option value="9" {{ $MACHINE_STATUS == "9" ? 'selected' : "" }}>แสดง</option>
															<option value="1" {{ $MACHINE_STATUS == "1" ? 'selected' : "" }}>ซ่อน</option>
														</select>

											</div>
								      <div class="col-md-4 form-inline">
												<label class="text-white mx-2">SEARCH : </label>
								          <div class="input-group mt-1">
								            <input type="search" id="SEARCH"  name="SEARCH" class="form-control form-control-sm"
														value="{{ isset($SEARCH) ? $SEARCH : '' }}">
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
								  <div id="result"class="card-body">
								    <div class="table-responsive">
									      <table class=" table table-bordered table-head-bg-info table-bordered-bd-info  table-hover ">
								        <thead class="thead-light">
								          <tr>
								            <th width="25px">ลำดับ</th>
														<th width="25px">LINE</th>
								            <th width="130px">MC-CODE</th>
								            <th width="300px">Machine Name</th>
														<th width="200px">MC-TYPE</th>
														<th width="30px">Rank</th>
														<th >เช็คเมื่อ</th>
														<th >ซ่อมเมื่อ</th>
								            <th >ประวัติ</th>
								            <th>แจ้งซ่อม</th>
								          </tr>
								        </thead>

								        <tbody >
								          @foreach ($machine as $key => $row)
														@php
														$PLAN_LAST_DATE = '';
														$REPAIR_LAST_DATE = '';
														  if (isset($row->PLAN_LAST_DATE)) {
															 $PLAN_LAST_DATE = $row->PLAN_LAST_DATE == '1900-01-01' ? '' : $row->PLAN_LAST_DATE;
														  }
															if (isset($row->REPAIR_LAST_DATE)) {
																$REPAIR_LAST_DATE = $row->REPAIR_LAST_DATE == '1900-01-01' ? '' : $row->REPAIR_LAST_DATE;
															}
														@endphp
								            <tr class="mt-4">
								              <td >
								                <center>{{ $key+1 }}</center>
								              </td>
															<td >  {{ $row->MACHINE_LINE }}  </td>
								                <td >
								                <a href="{{ url('machine/assets/edit/'.$row->UNID) }}">
								                  <button type="button" class="btn btn-secondary btn-sm btn-block my-1" style="width:130px">
								                    <span class="float-left">
								                      <i class="fas fa-eye fa-lg  mx-1 mt-1"></i>{{ $row->MACHINE_CODE }}
								                    </span>
								                  </button>
								                </a>
								              </td>
								              <td> {{ $row->MACHINE_NAME_TH }}  </td>
															<td> {{ $row->MACHINE_TYPE_TH }}  </td>
															<td> {{ $row->MACHINE_RANK_CODE }}  </td>
															<td> {{ $PLAN_LAST_DATE }}  </td>
															<td> {{ $REPAIR_LAST_DATE }}  </td>
								              <td>
								                  <button type="button" class="btn btn-secondary btn-sm btn-block my-1"
								                  onclick="printhistory( '{{$row->UNID}}' )" id="button" style="width:80px">
								                    <span class="float-left">

								                      <i  style="font-size:17px"class="icon-printer mx-1 mt-1"></i>
								                      Print
								                    </span>
								                  </button>
								              </td>
								              <td>
								                <a href="{{ url('machine/repair/form/'.$row->UNID) }}">
								                  <button type="button" class="btn btn-danger btn-sm btn-block my-1" style="width:70px">
								                    <span class="float-left">
								                      แจ้ง <i class="fas fa-wrench fa-lg mx-1 mt-1"></i>
								                    </span>
								                  </button>
								                </a>
								              </td>
								              </tr>
								          @endforeach

								        </tbody>

								    </table>
								    {{ $machine->appends( ['SEARCH' => $SEARCH])->links('pagination.default') }}

								  </div>
								    </div>
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

	<script src="{{ asset('assets/js/useinproject/machine/machineprintlist.js') }}">

	</script>
	<script>
		function changeline(){
			$('.SEARCH').click();
		}
		function changerank(){
			$('.SEARCH').click();
		}
	</script>


@stop
{{-- ปิดส่วนjava --}}
