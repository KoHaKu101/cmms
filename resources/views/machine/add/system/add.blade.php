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
				<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
          <div class="container">
						<div class="row">
							<div class="col-md-12 gx-4">
									<a href="{{ url('machine/pm/template/list/'.$datapmtemplate->UNID) }}">
										<button class="btn btn-warning  btn-xs ">
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
							<div class="col-md-12">
								<div class="card ">
                	@if(session('success'))
									@endif
									<form action="{{ route('pmtemplate.storelist') }}" method="post" enctype="multipart/form-data" id="FRM_SAVE_PM">
						        @csrf
										<div class="card-header bg-primary">
											<h4 class="ml-3 mt-2" style="color:white;" > ประเภทรายการ {{ $datapmtemplate->PM_TEMPLATE_NAME }} : รายการ
											</h4>
										 </div>
										<div class="card-body">
										 	<div class="row">
											 	<div class="col-md-6 col-lg-3 has-error">
												 	<label> Inspection Item</label>
													<input type="hidden" class="form-control" name="PM_TEMPLATE_UNID_REF" value="{{ $datapmtemplate->UNID }}"  >
												 	<input type="text" class="form-control" name="PM_TEMPLATELIST_NAME" required >
											 	</div>
										 	</div>
											<div class="row save_btn">
												<div class="col-md-6 col-lg-9">
												</div>
												<div class="col-md-6 col-lg-1">
													<button class="btn btn-primary btn-sm" id="BTN_SUBMIT">
														<i class="fas fa-save" style="color:white;font-size:15px" name="save" value="save"> Save</i>
													</button>
												</div>
												<div class="col-md-6 col-lg-1">
													<button class="btn btn-primary btn-sm " name="save" value="new" id="BTN_SUBMIT_NEW">
														<i class="fas fa-save" style="color:white;font-size:15px"> Save and New</i>
													</button>
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
	<script>
	$('#FRM_SAVE_PM').submit(function(){
		$('.save_btn').html("<div class='col-md-12 col-lg-12 text-right'>Please Wait...</div>").attr('disabled', 'disabled');
		return true;
	});
	</script>
@stop
{{-- ปิดส่วนjava --}}
