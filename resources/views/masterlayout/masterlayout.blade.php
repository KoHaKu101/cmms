<!DOCTYPE html>
<html lang="en">
{{-- <html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml"><head> --}}

<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8 ; charset=iso8859">
	@yield('meta')
	<title>{{config('app.name')}}</title>
	<meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="icon" href="{{asset ('assets/img/icon.ico') }}" type="image/x-icon"/>

	<!-- Fonts and icons -->
	<script src="{{ asset ('/assets/js/plugin/webfont/webfont.min.js') }}"></script>
	<script>
	WebFont.load({
		google: {"families":["Lato:300,400,700,900"]},
		custom: {"families":["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: ['/assets/css/fonts.min.css']},
		active: function() {
			sessionStorage.fonts = true;
		}
	});
	</script>

	<!-- CSS Files -->
	<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/atlantis.min.css') }}">

  @yield('css')
</head>

<body>

	<div class="wrapper sidebar_minimize">
		@if ($errors->count() > 0)
			<div id="Errorsystem" style="display:none;" class="alert alert-danger">
				@foreach ($errors->all() as $error)
						{{ $error }}<br/>
				@endforeach
			</div>
		@endif
		<div class="main-header">

			@can('isAdminandManager')
				@include('masterlayout.logomaster')
				@include('masterlayout.navbar.navbarmaster')
			@else
				@include('masterlayout.navbar.navbarmasterforuser')
			@endcan
			@yield('Logoandnavbar')

		</div>
		@yield('sidebar')
		@can('isAdminandManager')
			@include('masterlayout.sidebar.sidebarmaster')
	  @else
	  @endcan
    <div class="main-panel">
    @yield('contentandfooter')

    </div>

	</div>
	<!--   Core JS Files   -->
	<script type="text/javascript" src="{{ asset('/assets/js/core/jquery.3.2.1.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/assets/js/core/popper.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/assets/js/core/bootstrap.min.js') }}"></script>
	<!-- jQuery UI -->
	<script type="text/javascript" src="{{ asset('/assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js') }}"></script>
	<!-- jQuery Scrollbar -->
	<script type="text/javascript" src="{{ asset('/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
	<!-- jQuery Sparkline -->
	<script type="text/javascript" src="{{ asset('/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script>
	<!-- Datatables -->
	<script type="text/javascript" src="{{ asset('/assets/js/plugin/datatables/datatables.min.js') }}"></script>

	<script type="text/javascript" src="{{ asset('/assets/js/plugin/sweetalert/sweetalert2.js') }}"></script>
	@can('isAdminandMA')
		<script type="text/javascript" src="{{ asset('assets/js/useinproject/dashboard/notifity.js') }}"></script>
		<script>
			$(document).ready(function(){
				var url = "{{route('machine.config.send')}}";
				$.ajax({
					 type: "GET",
					 url: url,
					});
			})
		</script>
		<script>
			$(document).ready(function(){
					var url = "{{ route('machine.config.linenotify') }}";
					var send_masseng_line = function send_masseng_line(){
						$.ajax({
									 type:'GET',
									 url: url,
									 datatype: 'json',
									 success:function(data){
									 }
								 });
							 }
					setInterval(send_masseng_line,10000);
				send_masseng_line();
			});
		</script>
	@else
		{{--  --}}
	@endcan

	@include('/errorsweetalert/errormessed')


	<script type="text/javascript" src="{{ asset('assets/js/atlantis.min.js') }}"></script>
		@include('sweetalert::alert')

@yield('javascript')

</body>
</html>
