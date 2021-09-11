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
          <h4 class="page-title">Submenu</h4>
        </div>
				<div class="py-12">
	        <div class="container">
						<div class="row">
							<div class="col-md-8">
								<div class="card">
										<div class="card-header"><h3> Submenu </h4></div>
											<div class="table-responsive ml-2">
                      <table class="display table table-striped table-hover" >
                      	<thead>
                        	<tr>
                          	<th >Submenu</th>
                          	<th >Index</th>
														<th >Status</th>
														<th >Class</th>
														<th >Link</th>
														<th >Icon</th>
														<th >Action</th>
                        	</tr>
                      	</thead>

                      	<tbody>
													@foreach ($datasubmenu as $row)
                        		<tr>
                          		<td scope="row">  {{ $row->SUBMENU_NAME }} </td>
															<td>  						{{ $row->SUBMENU_INDEX }} </td>
															<td>  						{{ $row->SUBMENU_STATUS }} </td>
															<td>  						{{ $row->SUBMENU_CLASS }} </td>
															<td>  						{{ $row->SUBMENU_LINK }} </td>
															<td>  						{{ $row->SUBMENU_ICON }} </td>
															<td>
																<a href="{{ route('submenu.edit',$row->UNID) }}" class="btn btn-link"><i class="fab fa-whmcs fa-2x"></i></a>
																<a  data-unid="{{ $row->UNID }}"onclick="deletemenu(this)"class="btn btn-danger btn-link"><i class="fas fa-trash fa-2x"></i></a></a>
															</td>
                        			</tr>
                        	@endforeach
                      	</tbody>
                    </table>
									</div>
								</div>
								<div class=" mt-4">
									<a href="{{ route('menu.home') }}" class="btn btn-success">กลับ</a>
								</div>
              </div>
              <div class="col-md-4">
  							<div class="card">
  								<div class="card-header"> FormSubmenu </div>
                  <div class="card-body">
                    <form action="{{ route('submenu.store') }}" method="POST">
                      @csrf
                  		<div class="form-group">
                    		<label for="SUBMENU_NAME">Submenu Thai</label>
                    		<input type="text"  class="form-control" id="SUBMENU_NAME" name="SUBMENU_NAME" placeholder="Menu Thai">
												<input type="hidden"  id="SUBUNID_REF" name="SUBUNID_REF" value="{{ $MAINMENU_UNID }}">
                  		</div>
											<div class="form-group">
												<label for="SUBMENU_EN">Submenu English</label>
												<input type="text"  class="form-control" id="SUBMENU_EN" name="SUBMENU_EN" placeholder="Menu English">
											</div>
											<div class="form-group">
												<label for="SUBMENU_INDEX">Submenu Index</label>
												<input type="number" min="1" value="1" class="form-control" id="SUBMENU_INDEX" name="SUBMENU_INDEX" placeholder="MENU Index">
											</div>
											<div class="form-group">
												<label for="SUBMENU_STATUS">Submenu Status</label>
												<input type="text"  class="form-control" id="SUBMENU_STATUS" name="SUBMENU_STATUS" placeholder="MENU Status">
											</div>
											<div class="form-group">
												<label for="SUBMENU_CLASS">Submenu Class</label>
												<input type="text"  class="form-control" id="SUBMENU_CLASS" name="SUBMENU_CLASS" placeholder="MENU Class">
											</div>
											<div class="form-group">
												<label for="SUBMENU_LINK">Submenu Link</label>
												<input type="text"  class="form-control" id="SUBMENU_LINK" name="SUBMENU_LINK" placeholder="MENU Link" required>
											</div>
                			<div class="form-group">
												<label for="SUBMENU_ICON">Submenu Icon</label>
												<input type="text" class="form-control" id="SUBMENU_ICON" name="SUBMENU_ICON"  placeholder="MENU Icon">
											</div>
											<button tpye="submit" class="btn btn-success">Submit</button>
        						</form>
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
	function deletemenu(thisdata){
		var unid = $(thisdata).data('unid');
		var url = '/machine/setting/submenu/delete/'+unid;
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
