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
  <link href="{{asset('assets/css/select2.min.css')}}" rel="stylesheet" />
	<!-- CSS Files -->
	<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/atlantis.min.css') }}">
</head>
<body>
	<div class="wrapper sidebar_minimize">
    <div class="card">
      <div class="card-header bg-primary text-center text-white">
        <h3>รับงาน</h3>
      </div>

        <div class="card-body">
          <div class="row">
            <div class="col-md-6 ml-auto mr-auto">
              <table class="table table-bordered table-head-bg-info table-bordered-bd-info">
                <thead>
                  <tr>
                    <th colspan="4">รายละเอียดแจ้งซ่อม</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td width="25%" style="background:#aab7c1;color:black;">MC-Code</td>
                    <td>{{ $REPAIR->MACHINE_CODE }}</td>
                    <td width="15%" style="background:#aab7c1;color:black;">LINE</td>
                    <td width="18%">{{ $REPAIR->MACHINE_LINE}}</td>
                  </tr>
                  <tr>
                    <td width="25%" style="background:#aab7c1;color:black;">MC-Name</td>
                    <td colspan="3">SUSUKI</td>
                  </tr>
                  <tr>
                    <td width="25%" style="background:#aab7c1;color:black;">อาการ</td>
                    <td colspan="3">{{$REPAIR->MACHINE_NAME_TH}}</td>
                  </tr>
                  <tr>
                    <td width="25%" style="background:#aab7c1;color:black;">พนักงานแจ้ง</td>
                    <td >{{$REPAIR->EMP_NAME_TH}}</td>
                    <td width="15%" style="background:#aab7c1;color:black;">ระดับ</td>
                    <td width="18%">{{$REPAIR->PRIORITY == 9 ? 'เร่งด่วน' : 'ปกติ'}}</td>
                  </tr>
                  <tr>
                    <td width="25%" style="background:#aab7c1;color:black;">เวลาแจ้งซ่อม</td>
                    <td colspan="3">วันที่ : {{ date('d-m-Y',strtotime($REPAIR->DOC_DATE)) }} เวลา : {{$REPAIR->REPAIR_REQ_TIME}}</td>
                  </tr>
                  @if (isset($REPAIR->NOTE_RENEW))
                    <tr>
                      <td width="25%" style="background:#aab7c1;color:black;">แจ้งใหม่</td>
                      <td colspan="3">{{$REPAIR->NOTE_RENEW}}</td>
                    </tr>
                  @endif

                </tbody>
              </table>
            </div>
          </div>
          <form action="{{ route('confirm.save') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <div class="col-md-6 ml-auto mr-auto">
                <input type="hidden" id="UNID" name="UNID" value="{{ $REPAIR->UNID}}">
                <label>ผู้รับงาน</label>
                <div class="row mt-2">
                  <div class="col-8 col-md-8">
                    @if (isset($REPAIR->INSPECTION_CODE))
                      <input type="text" class="form-control form-control-sm" disabled value="{{ $REPAIR->INSPECTION_NAME_TH }}">
                    @else
                      <select class="form-control form-contron-sm " id="EMP_UNID" name="EMP_UNID" required>
                          <option value>กรุณาเลือก</option>
                        @foreach ($DATA_EMPNAME as $key => $row_emp)
                          <option value="{{ $row_emp->UNID }}">
                            {{ $row_emp->EMP_CODE.' : '.$row_emp->EMP_NAME_TH }}
                          </option>
                        @endforeach
                      </select>
                    @endif

                  </div>
                  @if (Session::get('closewindow') != true)
                    <div class="col col-md-4 text-right">
                      <button type="submit" class="btn btn-primary btn-sm "><i class="fas fa-save mr-1"></i> Save</button>
                    </div>
                  @endif
                </div>
              </div>
            </div>
          </form>
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
  <script src="{{ asset('assets/js/select2.min.js') }}"></script>

		@include('sweetalert::alert')
  <script>
    $(document).ready(function(){
      @if (Session::get('closewindow'))
      Swal.fire({
          icon:'{{ $ICON }}',
          title: '{{$TEXT}}',
          timer:'3000',
        }).then(() => {
          window.close();
      });
      @endif
     $('#INSPECTION_NAME').select2({
       width:'100%'
     });
   })
  </script>

</body>
</html>
