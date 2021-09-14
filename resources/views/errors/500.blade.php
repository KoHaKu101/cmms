
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>500</title>
	<meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
	<link rel="icon" href="{{asset('/assets/img/icon.ico')}}" type="image/x-icon"/>

	<!-- Fonts and icons -->
	<script src="{{asset('assets/js/plugin/webfont/webfont.min.js')}}"></script>
	<script>
		WebFont.load({
			google: {"families":["Lato:300,400,700,900"]},
			custom: {"families":["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: ['../assets/css/fonts.min.css']},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
	</script>

	<!-- CSS Files -->
	<link rel="stylesheet" href="{{ asset('/assets/css/bootstrap.min.css')}}">
	<link rel="stylesheet" href="{{ asset('/assets/css/atlantis.css')}}">
</head>
<body class="page-not-found">
	<div class="wrapper not-found">
		<h1 class="animated fadeIn">500</h1>
		<div class="desc animated fadeIn"><span>ไม่มีสัญญาณอิตเตอร์เน็ตหรือเกิดเหตุขัดข้อง</span><br/>กรุณากลับไปที่หน้าหลักหรือติดต่อแอดมิน</div>
		<a href="{{route('dashboard')}}" class="btn btn-primary btn-back-home mt-4 animated fadeInUp">
			<span class="btn-label mr-2">
				<i class="flaticon-home"></i>
			</span>
			กลับไปที่หน้าหลัก
		</a>
	</div>
	<script src={{asset('assets/js/core/jquery.3.2.1.min.js')}}></script>
	<script src={{asset('assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js')}}></script>
	<script src={{asset('assets/js/core/popper.min.js')}}></script>
	<script src={{asset('assets/js/core/bootstrap.min.js')}}></script>
</body>
</html>
