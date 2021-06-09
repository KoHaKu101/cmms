@extends('masterlayout.masterlayout')
@section('tittle','homepage')
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
        <div class="page-header">
          <h4 class="page-title">Edit</h4>
        </div>
				<div class="py-12">
	        <div class="container">
						<div class="row">
                    <div class="col-md-8">
        							<div class="card">
        								<div class="card-header"> Edit </div>
                        <div class="card-body">
                          <form action="{{ url('machine/setting/menu/update/'.$data->UNID) }}" method="POST">
                            @csrf
                            <div class="form-group">
                          		<label for="MENU_NAME">Menu Thai</label>
                          		<input type="text"  class="form-control" id="MENU_NAME" name="MENU_NAME" placeholder="Menu Thai" value="{{ $data->MENU_NAME }}">
                              <input type="hidden" id="UNID" name="UNID"  value="{{ $data->UNID }}">
                        		</div>
														<div class="form-group">
															<label for="MENU_EN">Menu English</label>
															<input type="text"  class="form-control" id="MENU_EN" name="MENU_EN" placeholder="Menu English" value="{{ $data->MENU_EN }}">
														</div>
														<div class="form-group">
															<label for="MENU_INDEX">MENU Index</label>
															<input type="number" min="1"  class="form-control" id="MENU_INDEX" name="MENU_INDEX" placeholder="MENU Index" value="{{ $data->MENU_INDEX }}">
														</div>
														<div class="form-group">
															<label for="MENU_STATUS">MENU Status</label>
															<input type="text"  class="form-control" id="MENU_STATUS" name="MENU_STATUS" placeholder="MENU Status" value="{{ $data->MENU_STATUS }}">
														</div>
														<div class="form-group">
															<label for="MENU_CLASS">MENU Class</label>
															<input type="text"  class="form-control" id="MENU_CLASS" name="MENU_CLASS" placeholder="MENU Class" value="{{ $data->MENU_CLASS }}">
														</div>
														<div class="form-group">
															<label for="MENU_LINK">MENU Link</label>
															<input type="text"  class="form-control" id="MENU_LINK" name="MENU_LINK" placeholder="MENU Link" value="{{ $data->MENU_LINK }}">
														</div>
                      			<div class="form-group">
															<label for="MENU_ICON">MENU Icon</label>
															<input type="text" class="form-control" id="MENU_ICON" name="MENU_ICON"  placeholder="MENU Icon" value="{{ $data->MENU_ICON }}">
														</div>
														<a href="{{ url()->previous() }}" class="btn btn-danger">Cancel</a>
														<button tpye="submit" class="btn btn-success">Update</button>
              						</form>
												</div>
											</div>
										</div>
						</div>
					</div>
  			</div>
			</div>
		</div>

		{{-- ปิดส่วนท้าย --}}
@stop
{{-- ปิดส่วนเนื้อหาและส่วนท้า --}}

{{-- ส่วนjava --}}
@section('javascript')

@stop
{{-- ปิดส่วนjava --}}
