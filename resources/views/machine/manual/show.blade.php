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
				<!--ส่วนปุ่มด้านบน-->
				<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
					<div class="container">
						<div class="row">
							<div class="col-md-1 mt-2">
								<a href="{{ url('machine/manual/manuallist') }}">
									<button class="btn btn-warning  btn-sm ">
										<span class="fas fa-arrow-left ">Back </span>
									</button>
								</a>
							</div>
						</div>
					</div>
				</div>
				<!--ส่วนกรอกข้อมูล-->
				<div class="py-12">
	        <div class="container mt-2">
						<div class="card">
							<div class="card-header bg-primary text-white">
								<div class="row">
									<div class="col-md-10">
										<div class="form-group form-inline ">
											<h4 class="mt-2">คู่มือเครื่อง {{ $dataset->MACHINE_CODE }}</h4>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group form-inline ">
											<button  id="popup" type="button" class="btn btn-warning float-right btn-sm "
												data-toggle="modal" data-target="#UPLOAD_MANUAL">
												<i class="fas fa-cloud-upload-alt" style="color:white;font-size:14px"> Upload</i>
											</button>
										</div>
									</div>
								</div>
							</div>
							<div class="card-body">
								<table class="table table-bordered table-head-bg-info table-bordered-bd-info">
									<thead>
										<tr>
											<th>#</th>
											<th width="300">ชื่อคู่มือ</th>
											<th width="100">ประเภทไฟล์</th>
											<th width="100">ขนาดไฟล์</th>
											<th width="300">Action</th>
											<th>วันที่อัปโหลด</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($dataupload as $key => $row)
											<tr>
		                    <td>  {{$key=1 , $key++}} </td>
		                    <td>  <h5>{{ $row->TOPIC_NAME }}</h5>  </td>
		                    <td>  <h5>{{ $row->FILE_EXTENSION }}</h5>  </td>

		                    <td>
		                      <div class="form-group form-inline">
		                        <h5>{{ $row->FILE_SIZE }}</h5>
		                        <h5>MB</h5>
		                      </div>
		                    </td>
		                    <td>
		                      <button type="button" class="btn btn-primary btn-sm mx-2"
														onclick="window.open('{{ url('machine/upload/view/'.$row->UNID) }}', '_blank', 'width=1000,height=1000,resizable=yes,top=100,left=100,menubar=yes,toolbar=yes,scroll=yes');">
		                        <i class="fas fa-eye fa-lg "></i>
		                      </button>
		                      <a href="{{ url('machine/upload/download/'.$row->UNID) }}">
		                        <button type="button"class="btn btn-success btn-sm mx-2"><i class="fas fa-download fa-lg"></i>	</button>
		                      </a>
		                        <button type="button" class="btn btn-warning  btn-sm mx-2" onclick="edituploadfile(this)"
		                        data-uploadunid="{{ $row->UNID }}"
		                        data-uploadtopicname="{{ $row->TOPIC_NAME }}">
		                          <i class="fas fa-edit fa-lg "></i>
		                        </button>
		                      <button type="button" class="btn btn-danger btn-sm mx-2"
		                        onclick="deleteupload(this)"
		                        data-uploadunid="{{ $row->UNID }}">
		                        <i class="fas fa-trash fa-lg "></i>	</button>
		                    </td>
		                    <td>
		                      <small>{{ $row->FILE_UPLOADDATETIME }}</small>
		                    </td>
		                  </tr>
										@endforeach

									</tbody>
								</table>
							</div>
						</div>
				</div>
			</div>
		</form>
	</div>
</div>






@include('machine.assets.modal.uploadmanue')
@stop
{{-- ปิดส่วนเนื้อหาและส่วนท้า --}}

{{-- ส่วนjava --}}
@section('javascript')
	<script>
	$(document).ready(function(){
		 $('#machinespartelist').DataTable({
				"pageLength": 10,
				"bLengthChange": false,
				"bFilter": true,
				"bInfo": false,
				"bAutoWidth": false,
				'bSort': false,

			});
			$('#addpmmachine').DataTable({
				 "pageLength": 10,
				 "bLengthChange": false,
				 "bFilter": true,
				 "bInfo": false,
				 "bAutoWidth": false,
				 'bSort': false,

			 });
			 $('#removepmmachine').DataTable({
					"pageLength": 10,
					"bLengthChange": false,
					"bFilter": true,
					"bInfo": false,
					"bAutoWidth": false,
					'bSort': false,

				});
	});
	function edituploadfile(thisdata){
		var uploadtopicname = $(thisdata).data('uploadtopicname');
		var uploadunid = $(thisdata).data('uploadunid');
		var url = '/machine/upload/update';
		$('#FRM_UPLOAD_MANUAL').attr('action',url);
		$('#FILE_UPLOAD').attr('required',false);
		$('#TOPIC_NAME').val(uploadtopicname);
		$('#UPLOAD_MANUAL_UNID').val(uploadunid);
		if (uploadunid != '') {
			 $('#UPLOAD_MANUAL').modal('show');
		}
	}
	$('#UPLOAD_MANUAL').on('hidden.bs.modal', function (e) {
		var url = "{{ route('machine.storeupload') }}";
		$('#FRM_UPLOAD_MANUAL').attr('action',url);
		$('#FILE_UPLOAD').attr('required',true);
		$('#TOPIC_NAME').val('');
		$('#UPLOAD_MANUAL_UNID').val('');
	});
	function	deleteupload(thisdata){
		var uploadunid = $(thisdata).data('uploadunid');
		 var url = "/machine/upload/delete/"+uploadunid;
		 Swal.fire({
			 title: 'คุณต้องการลบคู่มือนี้ ?',
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
