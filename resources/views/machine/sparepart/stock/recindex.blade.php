@extends('masterlayout.masterlayout')
@section('tittle','homepage')
@section('css')
	<link href="{{asset('assets/css/select2.min.css')}}" rel="stylesheet" />

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
      <div class="page-inner ">
				<div class="py-12">
	        <div class="container mt-2">
						<form action="{{ route('sparepart.recsave') }}" method="post" enctype="multipart/form-data">
							@csrf
							<div class="card">
								<div class="card-header bg-primary text-white">
									<div class="row">
										<div class="col-md-12">
											<h4 class="my-1">รับอะไหล่เข้า</h4>
										</div>
									</div>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-md-2">
											<label>วันที่รับเข้า</label>
											<input  type="date" class="form-control form-control-sm my-2 " id="DOC_DATE" name="DOC_DATE" value="{{ date('Y-m-d') }}" required>
										</div>
										<div class="col-md-3">
											<label>รายการอะไหล่</label>
											<select class="form-control form-control-sm my-2" id="SPAREPART_UNID" name="SPAREPART_UNID" >
													<option value>กรุณาเลือก</option>
												@foreach ($DATA_SPAREPART as $index => $row_select)
													<option value="{{ $row_select->UNID }}">{{ $row_select->SPAREPART_CODE.' : '.$row_select->SPAREPART_NAME }}</option>
												@endforeach
											</select>
										</div>
										<div class="col-md-1">
											<label>จำนวน</label>
											<input  type="number" class="form-control form-control-sm my-2 " id="IN_TOTAL" name="IN_TOTAL" min='0' required>
										</div>
										<div class="col-md-2">
											<label>เลขที่เอกสาร</label>
											<input  type="text" class="form-control form-control-sm my-2 " id="DOC_NO" name="DOC_NO">
										</div>
										<div class="col-md-2">
											<label >ผู้บันทึก</label>
											<select class="form-control form-control-sm" id="RECODE_BY" name="RECODE_BY" >
													<option value>กรุณาเลือก</option>
												@foreach ($DATA_EMP as $index => $sub_row)
													<option value="{{ $sub_row->EMP_CODE }}">{{ $sub_row->EMP_NAME_TH }}</option>
												@endforeach
											</select>
										</div>
										<div class="col-md-2">
											<label class="text-white">ปุ่มกด .................</label>
											<button type="submit" class="btn btn-sm btn-primary my-2"><i class="fas fa-plus mr-2"></i>เพิ่ม</button>
										</div>
									</div>
								</div>
							</div>
						</form>
							<div class="card">
								<div class="card-header bg-primary text-white">
									<h4 class="my-1"> รายการที่เพิ่ม</h4>
								</div>
								<div class="crad-body mt-2">
									<table class="table table-bordered table-head-bg-info table-bordered-bd-info " id="datatable">
										<thead>
											<tr>
												<th>วันที่</th>
												<th>รหัส</th>
												<th>ชื่อ</th>
												<th>รุ่น</th>
												<th>จำนวน</th>
												<th>คงเหลือ</th>
												<th>หน่วย</th>
												<th>เลขที่เอกสาร</th>
												<th>action</th>
											</tr>
										</thead>
										<tbody>
											@foreach ($DATA_SPAREPART_REC as $key => $row)
												<tr>
													<td width="10%">{{ date('d-m-Y',strtotime($row->DOC_DATE)) }}</td>
													<td width="12%">{{$row->SPAREPART_CODE}}</td>
													<td width="20%">{{$row->SPAREPART_NAME}}</td>
													<td width="12%">{{$row->SPAREPART_MODEL}}</td>
													<td width="6%" class="text-center">{{$row->IN_TOTAL}}</td>
													<td width="6%" class="text-center">{{$row->TOTAL}}</td>
													<td width="6%">{{$row->SPAREPART_UNIT}}</td>
													<td width="12%">{{$row->DOC_NO}}</td>
													<td width="6%">
														<button type="button" class="btn btn-danger btn-sm btn-block my-1"
															onclick="deleterecsparepart(this)"
															data-unid="{{ $row->UNID }}"
														><i class="fas fa-trash"></i> ลบ</button>
													</td>
												</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>

					</div>
				</div>
			</div>
    </div>

		{{-- เพิ่มเครื่องจักร --}}





@stop
{{-- ปิดส่วนเนื้อหาและส่วนท้า --}}

{{-- ส่วนjava --}}
@section('javascript')
	<script src={{ asset('assets/js/ajax/ajax-csrf.js') }}></script>
	<script src="{{ asset('assets/js/ajax/appcommon.js') }}"></script>
	<script src="{{ asset('assets/js/select2.min.js') }}"></script>
	<script src="{{ asset('assets/js/plugin/datatables/datatables.min.js')}}"></script>
	<script>

		 $('#SPAREPART_UNID').select2({
			 containerCssClass: "mt-2"
		 });
		 $('#RECODE_BY').select2({
			 containerCssClass: "mt-2"
		 });
		 $('.select2').addClass('mt-2');
		 $('.select2').attr('required');
		 // $(document).ready(function(){
			 $('#datatable').DataTable({
					"pageLength": 10,
					"bLengthChange": false,
					"bFilter": true,
					"bInfo": false,
					"bAutoWidth": false,
					'bSort': false,

				});
			// });
		function deleterecsparepart(thisdate){
			var unid = $(thisdate).data('unid');
			var url  = "{{ route('sparepart.recdelete') }}?UNID="+unid;
			window.location.href = url;
		}
	</script>


@stop
{{-- ปิดส่วนjava --}}
