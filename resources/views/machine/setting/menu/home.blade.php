@extends('masterlayout.masterlayout')
@section('tittle','homepage')
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
				<div class="page-header">
          <h4 class="page-title">MENU</h4>
        </div>
				<div class="py-12">

						<div class="row">
							<div class="col-md-12">
								<div class="card">
										<div class="card-header">
											<div class="row">
												<div class="col-md-10">
													<h3> Menu </h3>
												</div>
												<div class="col-md-2">
													<button type="button" class="btn btn-info my-1 float-right btn-sm" id="BTN_NEWMENU">NewMenu</button>

												</div>
											</div>
										</div>
											<div class="table-responsive">
                      <table class="display table table-striped table-hover ml-4" >
                      	<thead>
                        	<tr>

                          	<th scope="col">Menu Thai</th>
                          	<th scope="col">Menu English</th>
                          	<th scope="col">MENU Index</th>
														<th scope="col">MENU Status</th>
														<th scope="col">MENU Class</th>
														<th scope="col">MENU Link</th>
														<th scope="col">MENU Icon</th>
														<th scope="col">Action</th>

                        	</tr>
                      	</thead>

                      	<tbody>
                          {{-- @php($i = 1) --}}
                          @foreach ($data as $row)

                        		<tr>

                          		<td scope="row">  {{ $row->MENU_NAME }} </td>
                          		<td>  						{{ $row->MENU_EN }} </td>
															<td>  						{{ $row->MENU_INDEX }} </td>
															<td>  						{{ $row->MENU_STATUS }} </td>
															<td>  						{{ $row->MENU_CLASS }} </td>
															<td>  						{{ $row->MENU_LINK }} </td>
															<td>  						{{ $row->MENU_ICON }} </td>
															<td>
																<a href="{{ url('machine/setting/menu/edit/'.$row->UNID) }}" class="btn  btnmenu btn-link"><i class="fab fa-whmcs fa-2x"></i></a>
																<a data-unid="{{ $row->UNID }}" onclick="deletemenu(this)" class="btn btn-danger btnmenu btn-link"><i class="fas fa-trash fa-2x"></i></a>
																<a href="{{ url('machine/setting/submenu/home/'.$row->UNID) }}" class="btn btn-info btnmenu btn-link"><i class="fas fa-clipboard-list fa-2x"></i></a>
															</td>

                        			</tr>
                        	@endforeach

                      	</tbody>
                    </table>
										{{ $data->links() }}
									</div>

								</div>
              </div>
							<div class="modal fade" id="NewMenu" tabindex="-1" role="dialog" aria-labelledby="exampleModalLalavel" aria-hidden="true">
							  <div class="modal-dialog " role="document">
							    <div class="modal-content">
							      <div class="modal-header">
							        <h5 class="modal-title" id="exampleModalLabel">Formmenu</h5>
							        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							          <span aria-hidden="true">&times;</span>
							        </button>
							      </div>
							      <form action="{{ route('menu.store') }}" method="post" enctype="multipart/form-data">
							        @csrf
							        <div class="modal-body">
												<div class="row">
													<div class="col-md-6">
														<label for="MENU_NAME">Menu Thai</label>
														<input type="text"  class="form-control" id="MENU_NAME" name="MENU_NAME" placeholder="Menu Thai">
													</div>
													<div class="col-md-6">
														<label for="MENU_EN">Menu English</label>
														<input type="text"  class="form-control" id="MENU_EN" name="MENU_EN" placeholder="Menu English">
													</div>
													<div class="col-md-3">
														<label for="MENU_INDEX">MENU Index</label>
														<input type="number" min="1" value="1" class="form-control" id="MENU_INDEX" name="MENU_INDEX" placeholder="MENU Index">
													</div>
													<div class="col-md-3">
														<label for="MENU_STATUS">MENU Status</label>
														<input type="text"  class="form-control" id="MENU_STATUS" name="MENU_STATUS" >
													</div>
													<div class="col-md-6">
														<label for="MENU_CLASS">MENU Class</label>
														<input type="text"  class="form-control" id="MENU_CLASS" name="MENU_CLASS" placeholder="MENU Class">
													</div>
													<div class="col-md-6">
														<label for="MENU_LINK">MENU Link</label>
														<input type="text"  class="form-control" id="MENU_LINK" name="MENU_LINK" placeholder="MENU Link">
													</div>
													<div class="col-md-6">
														<label for="MENU_ICON">MENU Icon</label>
														<input type="text" class="form-control" id="MENU_ICON" name="MENU_ICON"  placeholder="MENU Icon">

													</div>
												</div>

							        </div>
							        <div class="modal-footer">
							          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							          <button type="submit" class="btn btn-primary">Save</button>
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
	<script>
	$('#BTN_NEWMENU').on('click',function(){
		$('#NewMenu').modal('show');
	});
	function deletemenu(thisdata){
		var unid = $(thisdata).data('unid');
		var url = '/machine/setting/menu/delete/'+unid;
		Swal.fire({
			  title: 'ต้องการลบเมนูนี้มั้ย?',
			  icon: 'warning',
			  showCancelButton: true,
			  confirmButtonColor: '#3085d6',
			  cancelButtonColor: '#d33',
			  confirmButtonText: 'ใช่!'
			}).then((result) => {
			  if (result.isConfirmed) {
					window.location.href = url;
			  }
			});
	}
	</script>

@stop
{{-- ปิดส่วนjava --}}
