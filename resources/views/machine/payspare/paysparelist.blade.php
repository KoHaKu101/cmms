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

	  <div class="content">
      <div class="page-inner">
				<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
          <div class="container">
						<div class="row">
							<div class="col-md-12 gx-4">
								<a href="{{ route('dashboard') }}">
								<button class="btn btn-primary  btn-xs ">
									<span class="fas fa-arrow-left fa-lg">Back </span>
								</button>
							</a>
							</div>
						</div>
          </div>
				</div>
				<div class="py-12">
	        <div class="container mt-2">
						<div class="row">
							<div class="col-md-8">
								<div class="card ">
                	@if(session('success'))
                  	<div class="alert alert-success alert-dismissible fade show" role="alert">
  											<strong>{{ session('success') }}</strong>
  											<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    										<span aria-hidden="true">&times;</span>
  											</button>
										</div>
									@endif
									<div class="">
										<div class="form-inline bg-primary ">
											<h4 class="ml-3 mt-2" style="color:white;" ><i class="fas fa-toolbox fa-lg mr-1"></i> รายการเบิกจ่าย </h4>
											<div class="btn-group ml-3" role="group" aria-label="Basic example">
											</div>
											<div class="form-group form-inline ">
												<div class="input-group ml-4">
													<input type="text" id="search_text"  name="search_text"onkeyup="myFunction()" class="form-control form-control-sm">
													<div class="input-group-prepend">
														<button type="submit" class="btn btn-search pr-1 btn-xs	">
	              							<i class="fa fa-search search-icon"></i>
	            							</button>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div id="result"class="card-body">
										<div class="table-responsive">
                      <table class="display table table-striped table-hover">
                      	<thead class="thead-light">
                        	<tr>

                            <th scope="col"></th>
                          	<th scope="col">รหัสอะไหล่</th>
                          	<th scope="col">รายการอะไหล่</th>
                          	<th style="width:50px">เบิก(จำนวน)</th>
														<th scope="col">ผู้ทำการเบิก</th>
														<th scope="col">สถานนะ</th>
														<th scope="col"></th>
                        	</tr>
                      	</thead>

                      	<tbody >
                          {{-- @php($i = 1) --}}
													{{-- @foreach ($dataset as $key => $dataitem) --}}
                        		<tr>

															<td style="width:80px">
																<a href="{{ url('machine/table/edit/') }}">
																	<button type="button" class="btn btn-secondary btn-sm my-1 mx-2" style="height:30px;width:80px">

																			<i class="fas fa-eye fa-lg float-left"></i>

																	</button>
																</a>
															</td>
															<td style="width:200px">RE6402-0023	</td>
															<td style="width:200px">สายพาน</td>
															<td style="width:50px"> 10 </td>
															<td style="width:100px"> นาย ข</td>
															<td style="width:100px"> ไม่มี stock </td>
															<td style="white-space:nowrap">
																<a href="{{ url('machine/table/delete/'	) }}">
																	<button type="button" class="btn btn-danger btn-sm my-1" style="width:40px">

																		<i class="fas fa-trash fa-lg">	</i>

																	</button>
																</a>
															</td>
                        			</tr>
                        	{{-- @endforeach --}}
                      	</tbody>
                    </table>
									</div>
										</div>
								</div>
								</div>
								<div class="col-md-4">
									<div class="card">
										<div class="card-header bg-primary">
											<h4 class="ml-3 mt-2" style="color:white;" ><i class="fas fa-toolbox fa-lg mr-1"></i> เพิ่มรายการ </h4>
										 </div>
										<div class="card-body">
											<form action="{{ route('tablesparepart.store') }}" method="POST">
												@csrf
												<div class="form-group has-error">
													<label for="SPAREPART_CODE">โค้ดอะไหล่</label>
													<input type="text"  class="form-control" id="SPAREPART_CODE" name="SPAREPART_CODE" placeholder="โค้ดอะไหล่" required autofocus>
												
												</div>
												<div class="form-group has-error">
													<label for="SPAREPART_NAME">ชื่ออะไหล่</label>
													<input type="text"  class="form-control" id="SPAREPART_NAME" name="SPAREPART_NAME" placeholder="ชื่ออะไหล่" required autofocus>
												
												</div>
												<div class="form-group has-error">
													<label for="SPAREPART_PRICE">ราคา</label>
													<input type="text"  class="form-control" id="SPAREPART_PRICE" name="SPAREPART_PRICE" placeholder="ราคา" required autofocus>
												
												</div>
												<div class="form-group">
													<label for="SPAREPART_NOTE">รายละเอียดเพิ่มเติม</label>
													<textarea class="form-control" id="SPAREPART_NOTE" name="SPAREPART_NOTE" rows="4" placeholder="สามารถกรอกรายละเอียดเพิ่มเติมได้"></textarea>
												</div>


												<button tpye="submit" class="btn btn-success">Submit</button>
											</form>
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
{{-- <script>
$(document).ready(function(){
	var table = $('datatable').DataTable({
			'processing' : true,
			'serverSide' : true,
			'ajax': "{{ route('machine.list') }}",
			'column':[
				{'data': 'MACHINE_LOCATION'},
				{'data': 'MACHINE_NAME'},
				{'data': 'MACHINE_CODE'}
			],
	});

  $("#myInput").keyup (function() {
		table.column($)
    var value = $(this).val().toLowerCase();
    $("#myTable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script> --}}


@stop
{{-- ปิดส่วนjava --}}
