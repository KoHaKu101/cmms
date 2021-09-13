<!DOCTYPE html>
<html lang="en">
{{-- <html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml"><head> --}}
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8 ; charset=iso8859">
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
</head>
<body>
	<div class="wrapper sidebar_minimize">
    <div class="card">
      <div class="card-header bg-primary text-center text-white">
        <h4>รับงาน</h4>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6 ml-auto mr-auto">
            <label>ผู้รับงาน</label>
            <select class="form-control form-contron-sm col-md-8">
              @for ($i=1; $i < 10; $i++)
                <option>{{ $i }}</option>
              @endfor

            </select>
          </div>
        </div>
      </div>
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

	@include('/errorsweetalert/errormessed')

	<script type="text/javascript" src="{{ asset('assets/js/atlantis.min.js') }}"></script>
		@include('sweetalert::alert')

</body>
</html>
