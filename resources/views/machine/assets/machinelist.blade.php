@extends('masterlayout.masterlayout')
@section('tittle','Machine')
@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">

@endsection
@section('css')

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
								    <div class="row ">
								      <div class="col-md-3 col-lg-2">
								        <h4 class="ml-3 mt-2 " style="color:white;" ><i class="fas fa-wrench fa-lg mr-1"></i> เครื่องจักร </h4>
								      </div>
								      <div class="col-md-3">
												<form action="{{ route('machine.list',$MACHINE_LINE) }}" method="POST" enctype="multipart/form-data">
													@method('GET')
													@csrf
								          <div class="input-group mt-1">
								            <input type="search" id="SEARCH"  name="SEARCH" class="form-control form-control-sm"
														value="{{ isset($SEARCH) ? $SEARCH : '' }}">
								            <div class="input-group-prepend">
								              <button type="submit" class="btn btn-search pr-1 btn-xs	">
								                <i class="fa fa-search search-icon"></i>
								              </button>
								            </div>
								          </div>
												</form>
								      </div>
								    </div>
								  </div>
								  <div id="result"class="card-body">
								    <div class="table-responsive">
								      <table class="display table table-striped table-hover">
								        <thead class="thead-light">
								          <tr>
								            <th >ลำดับ</th>
								            <th ></th>
								            <th scope="col">LINE</th>
								            <th scope="col">Name</th>


								            <th scope="col">แผนการผลิต</th>
								            <th scope="col">ประวัติการซ่อม</th>
								            <th>แจ้งซ่อม</th>

								          </tr>
								        </thead>

								        <tbody >
								          @foreach ($machine as $key => $row)
								            <tr class="mt-4">
								              <td style="width:25px">
								                <center>{{ $key+1 }}</center>
								              </td>
								                <td style="width:170px;">
								                <a href="{{ url('machine/assets/edit/'.$row->UNID) }}">
								                  <button type="button" class="btn btn-secondary btn-sm btn-block my-1" style="width:130px">
								                    <span class="float-left">
								                      <i class="fas fa-eye fa-lg  mx-1 mt-1"></i>{{ $row->MACHINE_CODE }}
								                    </span>
								                  </button>
								                </a>
								              </td>
								              <td >  {{ $row->MACHINE_LINE }}  </td>
								              <td style="width:400px;">              {{ $row->MACHINE_NAME_V2 }}  </td>
								              <td style="width:100px;">
								                <a href="#">
								                  <button type="button" class="btn btn-secondary btn-sm btn-block my-1" style="width:80px">
								                    <span class="float-left">
								                      <i  style="font-size:17px"class="icon-printer mx-1 mt-1"></i>
								                      Print
								                    </span>
								                  </button>
								                </a>
								              </td>
								              <td style="width:120px;">
								                  <button type="button" class="btn btn-secondary btn-sm btn-block my-1"
								                  onclick="printhistory( '{{$row->UNID}}' )" id="button" style="width:120px">
								                    <span class="float-left">

								                      <i  style="font-size:17px"class="icon-printer mx-1 mt-1"></i>
								                      Print ประวัติ
								                    </span>
								                  </button>
								              </td>
								              <td>
								                <a href="{{ url('machine/repair/form/'.$row->UNID) }}">
								                  <button type="button" class="btn btn-danger btn-sm btn-block my-1" style="width:130px">
								                    <span class="float-left">
								                      <i class="fas fa-wrench fa-lg mx-1 mt-1"></i>
								                      แจ้งซ่อม: {{ $row->MACHINE_CODE }}
								                    </span>
								                  </button>
								                </a>
								              </td>
								              </tr>
								          @endforeach

								        </tbody>

								    </table>
								    {{ $machine->appends( ['SEARCH' => $SEARCH])->links() }}

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


@stop
{{-- ปิดส่วนjava --}}
