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
                          	<th>Menu Thai</th>
                          	<th>Menu English</th>
                          	<th>MENU Index</th>
														<th>MENU Status</th>
														<th>MENU Class</th>
														<th>MENU Link</th>
														<th>MENU Icon</th>
														<th>Action</th>
                        	</tr>
                      	</thead>
                      	<tbody>
                          @foreach ($data as $row)
                        		<tr>
                          		<td>  {{ $row->MENU_NAME }} </td>
                          		<td>  {{ $row->MENU_EN }} </td>
															<td>  {{ $row->MENU_INDEX }} </td>
															<td>  {{ $row->MENU_STATUS }} </td>
															<td>  {{ $row->MENU_CLASS }} </td>
															<td>  {{ $row->MENU_LINK }} </td>
															<td>  {{ $row->MENU_ICON }} </td>
															<td>
																<a href="{{ route('menu.edit',$row->UNID)  }}" class="btn  btnmenu btn-link"><i class="fab fa-whmcs fa-2x"></i></a>
																<a data-unid="{{ $row->UNID }}" onclick="deletemenu(this)" class="btn btn-danger btnmenu btn-link"><i class="fas fa-trash fa-2x"></i></a>
																<a href="{{ route('submenu.home',$row->UNID) }}" class="btn btn-info btnmenu btn-link"><i class="fas fa-clipboard-list fa-2x"></i></a>
															</td>
                        			</tr>
                        	@endforeach
                      	</tbody>
                    </table>
										{{ $data->links() }}
									</div>
								</div>
              </div>
						</div>
					</div>
  			</div>
			</div>
@include('machine.setting.menu.modalnewmenu')



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
