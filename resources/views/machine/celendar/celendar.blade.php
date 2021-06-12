@extends('masterlayout.masterlayout')
@section('tittle','homepage')
@section('css')
	<link href={{ asset('/assets/fullcalendar/main.css') }} rel='stylesheet' />

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
			<div class="panel-header bg-primary-gradient">
				<div class="page-inner py-3">
					<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
						<div>
							<h2 class="text-white pb-2 fw-bold">Calendar (ปฏิทิน)</h2>
						</div>
						</div>
						<div class="card">
							<div class="card-body">
								<div id="calendar">
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
	<script src='{{ asset('/assets/fullcalendar/main.js')}}'></script>
<script>
	document.addEventListener('DOMContentLoaded', function() {
		var calendarEl = document.getElementById('calendar');
		var calendar = new FullCalendar.Calendar(calendarEl, {

			headerToolbar: {
        left: 'title',
        right: 'dayGridMonth,listYear prev,next'
      },
			dayMaxEvents: true,
			events: [
				@if (isset($datamasterimpsgroup))


				@foreach ($datamasterimpsgroup as $key => $data)
					@foreach ($datamachine->where('MACHINE_CODE',$data->MACHINE_CODE) as $key => $datasub)
					{
						title: '{{ $data->MACHINE_CODE }} : {{ $data->PM_TEMPLATELIST_NAME }}',
						url: '{{ url('machine/system/check/'.$datasub->UNID.'/'.$data->UNID) }}',
						start: '{{ $data->PM_NEXT_DATE }}',
					},
					@endforeach
				@endforeach

				@endif

			]
		});
	calendar.setOption('locale', 'th');
	calendar.render();
	});
</script>

@stop
{{-- ปิดส่วนjava --}}
