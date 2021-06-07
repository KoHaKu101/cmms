@extends('masterlayout.masterlayout')
@section('tittle','แจ้งซ่อม')
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
								<a href="{{ route('dashboard') }}">
								<button class="btn btn-warning  btn-xs ">
									<span class="fas fa-arrow-left fa-lg">Back </span>
								</button>
								</button></a>
								<a href="{{ route('repair.repairsearch') }}"><button class="btn btn-primary  btn-xs">
									<span class="fas fa-file fa-lg">	New	</span>
								</button></a>

							</div>
						</div>
          </div>
				</div>
				<div class="py-12">
	        <div class="container mt-2">
						<div class="row">
							<div class="col-md-12">
								<div class="card ">

								  <div class="card-header bg-primary form-inline ">
										<form action="{{ route('repair.list') }}" method="POST" enctype="multipart/form-data">
											@method('GET')
											@csrf
								        <div class="row ">
								          <div class="col-md-5">
								            <h4 class="ml-3 mt-2 " style="color:white;" ><i class="fas fa-toolbox fa-lg mr-1"></i> แจ้งซ่อม </h4>
								          </div>
								          <div class="col-md-7">
								              <div class="input-group mt-1">
								                <input  type="search" id="SEARCH"  name="SEARCH" class="form-control form-control-sm" placeholder="ค้นหา........."
																value="{{ $SEARCH }}">
								                <div class="input-group-prepend">
								                  <button type="submit" class="btn btn-search pr-1 btn-xs	">
								                    <i class="fa fa-search search-icon"></i>
								                  </button>
								                </div>
								              </div>
								          </div>
								        </div>
											</form>
								  </div>
								  <div id="result"class="card-body">
								    <div class="table-responsive" id="dynamic_content">
								      <table class="display table table-striped table-hover">
								        <thead class="thead-light">
								          <tr>
								            <th scope="col">เลขที่เอกสาร </th>
								            <th></th>
								            <th scope="col">รหัสเครื่อง </th>
								            <th scope="col">ชื่อเครื่องจักร</th>
								            <th scope="col">Line</th>
								            <th scope="col">วันที่เอกสาร</th>
								            <th scope="col">สถานะเครื่องจักร</th>
								            <th scope="col">สถานะซ่อมแซ่ม</th>
								            <th scope="col" style="width:100px"></th>
								          </tr>
								        </thead>

								        <tbody>
								          @foreach ($dataset as $key => $row)

								            <tr>
								              <td style="width:200px">
								                <a href="{{ route('repair.edit',[$row->UNID]) }}" class="btn btn-secondary btn-block btn-sm my-1 " style="width:180px;height:30px">
								                  <span class="btn-label float-left">
								                    <i class="fas fa-eye mx-1"></i>{{ $row->DOC_NO }}
								                  </span>
								                </a>
								              </td>
								              <td style="width:50px">
								                <button type="button"class="btn btn-primary btn-block btn-sm my-1 " onclick="pdfrepair('{{ $row->UNID }}')"
								                style="width:50px;height:30px">
								                  <span class="">
								                    <i  style="font-size:17px"class="icon-printer "></i>
								                  </span>
								                </button>
								              </td>
								              <td >  				{{ $row->MACHINE_CODE }}		     </td>
								              <td >  				{{ $row->MACHINE_NAME }}		    </td>
								              <td >  				{{ $row->MACHINE_LINE }}	    </td>
								              <td >      		{{ $row->DOC_DATE }}          </td>
								              <td >  				{{ $row->MACHINE_STATUS == '1' ? 'เครื่องหยุดทำงาน' : 'เครื่องทำงาน'}}	    </td>

								                @if ($row->CLOSE_STATUS ===  '9')
								                  <td style="width:120px">
								                    <button type="button"class="btn btn-success btn-block btn-sm my-1 " style="width:120px;height:30px">
								                      <span class="btn-label float-left">
								                        <i class="fas  mx-1"></i>กำลังดำเนินการ
								                      </span>
								                    </button>
								                  </td>
								                  <td style="width:90px">
								                    <button  id="popup" type="button" class="btn btn-danger btn-block btn-sm my-1" data-toggle="modal" data-target="#CloseRepair"
								                    style="width:90px;height:30px">

								                      <span class="btn-label">
								                        <i class="fas fa-clipboard-check mx-1"></i>ปิดเอกสาร
								                      </span>
								                    </button>
								                @elseif ($row->CLOSE_STATUS === '1')
								                  <td style="width:100px">
								                    <button type="button" class="btn btn-primary btn-block btn-sm my-1 " style="width:120px;height:30px">
								                      <span class="btn-label float-left">
								                        <i class="fas  mx-1"></i>เรียบร้อยแล้ว
								                      </span>
								                    </button>
								                    </td>
								                    <td style="width:90px">

								                    </td>
								                @else
								                  <td style="width:100px">
								                      <button type="button" class="btn btn-danger btn-block btn-sm my-1 " style="width:120px;height:30px">
								                        <span class="btn-label float-left">
								                          <i class="fas  mx-1"></i>สถานะไม่แน่ชัด
								                        </span>
								                      </button>
								                      </td>
								                      <td style="width:90px">

								                      </td>
								                @endif
								              </tr>
								            @endforeach


								        </tbody>
								    </table>

								  </div>
									{{$dataset->appends(['SEARCH' => $SEARCH])->links('pagination.default')}}
								    </div>
								</div>
								</div>
              </div>

						</div>
					</div>
  			</div>
			</div>
		</div>
		@include('machine.repair.repairclosemodal')

@stop
{{-- ปิดส่วนเนื้อหาและส่วนท้า --}}

{{-- ส่วนjava --}}
@section('javascript')

<script>

 function closework(u){

	var unid = (u);
	console.log(unid);
		Swal.fire({
			title: 'ต้องการปิดเอกสารมั้ย?',
			text: "หากทำการปิดเอกสารแล้วไม่สามารถแก้ไขได้ ต้องทำการสร้างใหม่เท่านั้น!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes!'
		}).then((result) => {
			if (result.isConfirmed) {
			window.location.href = "/machine/repair/delete/"+unid;
			}
		})

	}
</script>
<script type="text/javascript">
// var button = document.getElementById('button');
	function pdfrepair(m){
		console.log(m);
		var unid = (m);
		window.open('/machine/repair/pdf/'+unid,'Repairprint','width=1000,height=1000,resizable=yes,top=100,left=100,menubar=yes,toolbar=yes,scroll=yes');
	}


</script>
@stop
{{-- ปิดส่วนjava --}}
