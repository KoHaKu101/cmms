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
								{{-- @if ($MACHINE_LINE != NULL)
									@livewire('searchmachine',['MACHINE_LINE'=>$MACHINE_LINE])
								@else
									@livewire('searchmachine')
								@endif --}}




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
