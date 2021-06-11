@extends('masterlayout.masterlayout')
@section('tittle','homepage')
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
	<style>
		/* The switch - the box around the slider */
		.switch {
			position: relative;
			display: inline-block;
			width: 48px;
			height: 22px;
		}

		/* Hide default HTML checkbox */
		.switch input {
			opacity: 0;
			width: 0;
			height: 0;
		}

		/* The slider */
		.slider {
			position: absolute;
			cursor: pointer;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background-color: #ccc;
			-webkit-transition: .4s;
			transition: .4s;
		}

		.slider:before {
				position: absolute;
				content: "";
				height: 15px;
				width: 15px;
				left: 4px;
				bottom: 3px;
				background-color: white;
				-webkit-transition: .4s;
				transition: .4s;
		}

		input:checked + .slider {
			background-color: #2196F3;
		}

		input:focus + .slider {
			box-shadow: 0 0 1px #2196F3;
		}

		input:checked + .slider:before {
			-webkit-transform: translateX(26px);
			-ms-transform: translateX(26px);
			transform: translateX(26px);
		}

		/* Rounded sliders */
		.slider.round {
			border-radius: 34px;
		}

		.slider.round:before {
			border-radius: 50%;
		}
    .btn-mute{
      background: #949494!important;
      border-color: #ababab!important;
    }
	</style>

	  <div class="content">
      <div class="page-inner">
				<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
          <div class="container">
						<div class="row">
              <div class="col-md-6">
                <div class="card">
                  <div class="card-header bg-primary">
                    <div class="row">
                      <div class="col-8 col-md-8 text-white">
                        <h4 class="my-1">รายการตำแหน่ง</h4>
                      </div>
                      <div class="col-4 col-md-4 text-right">
                        <button type="button" class="btn btn-warning btn-sm btn-block" id="BTN_NEW"><i class="fas fa-plus"></i> เพิ่มรายการ</button>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="table">
                      <table class="table table-bordered table-head-bg-info table-bordered-bd-info">
    										<thead>
    											<tr>
    												<th>#</th>
    												<th>ตำแหน่ง</th>
    												<th>ต้องการ</th>
    												<th>ปัจจุบัน</th>
                            <th>สถานะ</th>
                            <th width="160px">action</th>
    											</tr>
    										</thead>
    										<tbody>
                          @foreach ($DATA_POSITION as $index => $row)
                            @php
                              $STATUS_COLOR = $row->STATUS == 9 ?  "btn-success" : "btn-mute";
                              $STATUS_TEXT  = $row->STATUS == 9 ?  "แสดง" : "ซ่อน";
                            @endphp
                            <tr>
      												<td>{{$row->EMP_POSITION_INDEX}}</td>
      												<td>{{$row->EMP_POSITION_NAME}}</td>
      												<td class="text-center">{{$row->EMP_POSITION_LIMIT}} คน</td>
      												<td class="text-center">{{$COUNT_EMP->where('POSITION','=',$row->EMP_POSITION_CODE)->count()}} คน</td>
                              <td><button type="button" class="btn {{ $STATUS_COLOR }} btn-block btn-sm my-1 text-white">{{ $STATUS_TEXT }}</button></td>
                              <td>
                                <div class="row">
                                  <div class="col-md-12 form-inline">
                                    <a href="{{ route('position.list',$row->EMP_POSITION_CODE) }}" class="btn btn-primary btn-sm mx-1 my-1">
                                      <i class="fas fa-eye"></i>
                                    </a>
                                    <button class="btn btn-warning btn-sm mx-1 my-1 BTN_EDIT"
                                    data-position_unid="{{ $row->UNID }}"
                                    data-position_name="{{ $row->EMP_POSITION_NAME }}"
                                    data-position_limit="{{ $row->EMP_POSITION_LIMIT }}"
                                    data-position_remark="{{ $row->REMARK }}"
                                    data-position_status="{{ $row->STATUS }}">
                                      <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm mx-1 my-1"
                                      onclick="deleteposition(this)"
                                      data-unid="{{ $row->UNID }}">
                                      <i class="fas fa-trash"></i>
                                    </button>
                                  </div>

                                </div>

                              </td>
      											</tr>
                          @endforeach

    										</tbody>
    									</table>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                @if (isset($DATA_EMP))
                  <div class="card">
                    <div class="card-header bg-primary text-white ">
                      <h4 class="my-1">ตำแหน่ง หัวหน้าแผนก</h4>
                    </div>
                    <div class="card-body">
                      <table class="table table-bordered table-head-bg-info table-bordered-bd-info ">
    										<thead>
    											<tr>
    												<th >#</th>
    												<th width="100px">รหัส</th>
    												<th >ชื่อ</th>
    											</tr>
    										</thead>
    										<tbody>
                          @foreach ($DATA_EMP as $key => $subrow)
                            <tr>
      												<td>{{$key+1}}</td>
      												<td>{{$subrow->EMP_CODE}}</td>
      												<td>{{$subrow->EMP_NAME}}</td>
      											</tr>
                          @endforeach



    										</tbody>
    									</table>
                    </div>
                  </div>
                @endif

              </div>
						</div>
          </div>
				</div>
  			</div>
			</div>
			<div class="modal fade" id="NEW_POSITION" tabindex="-1" role="dialog" aria-labelledby="exampleModalLalavel" aria-hidden="true">
			  <div class="modal-dialog " role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <h5 class="modal-title" id="Title_Name">เพิ่มรายการตำแหน่ง</h5>
			      </div>
			      <form action="{{ route('position.save') }}" id="FRM_POSITION" name="FRM_POSITION" method="post" enctype="multipart/form-data">
			        @csrf

			        <div class="modal-body">
			          <div class="card-body ml-2">
			            <div class="row ">
			              <div class="col-md-6 col-lg-6">ตำแหน่ง</div>
                    <div class="col-md-6 col-lg-6">จำนวนที่ต้องการ</div>
			              <div class="col-md-6 col-lg-6 mt-2 has-error">
			                <input type="text" class="form-control" id="EMP_POSITION_NAME" name="EMP_POSITION_NAME" required>
			              </div>
                    <div class="col-md-6 col-lg-6 mt-2 has-error">
			                <input type="number" class="form-control" id="EMP_POSITION_LIMIT" name="EMP_POSITION_LIMIT" min='1' value="1" required>
			              </div>
			            </div>
									<div class="row my-2">
										<div class="col-md-12">
											<label for="comment">คำอธิบาย</label>
											<textarea class="form-control" id="REMARK" name="REMARK"rows="3"></textarea>
										</div>

									</div>

									<div class="row mt-3">
				            <div class="col-md-12 ml-2">
				              <label for="comment" class="mr-2">Status</label>
				              <!-- Rounded switch -->
				              <label class="switch">
				                <input type="checkbox" id="STATUS" name="STATUS" value="9" checked>
				                <span class="slider round"></span>
				              </label>
				            </div>
				          </div>
			          </div>
			        </div>
			        <div class="modal-footer">
			          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			          <button type="submit" class="btn btn-primary" id="SAVE_MAIN">Save</button>
			        </div>
			      </form>
			    </div>
			  </div>
			</div>

			<div class="modal fade" id="NEW_SUBREPAIR" tabindex="-1" role="dialog" aria-labelledby="exampleModalLalavel" aria-hidden="true">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <h5 class="modal-title" id="Title_SUBREPAIR">เพิ่มรายการ</h5>
			      </div>
			      <form action="{{ route('repairtemplate.subsave') }}" id="FRM_SAVESUB" name="FRM_SAVESUB" method="post" enctype="multipart/form-data">
			        @csrf
			        <div class="modal-footer">
			          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			          <button type="submit" class="btn btn-primary"  id="SAVE_SUB">Save</button>
			        </div>
			      </form>
			    </div>
			  </div>
			</div>
@stop
{{-- ปิดส่วนเนื้อหาและส่วนท้า --}}

{{-- ส่วนjava --}}
@section('javascript')
	<script src="{{ asset('assets/js/ajax/ajax-csrf.js') }}"></script>
	<script>
			$('#BTN_NEW').on('click',function(){
        var url = "{{ route('position.save') }}";
        $('#FRM_POSITION').trigger("reset");
        $('#FRM_POSITION').attr('action',url);
        $('#NEW_POSITION').modal('show');
      });
      $('.BTN_EDIT').on('click',function(){
        var position_unid = $(this).data('position_unid');
        var position_name = $(this).data('position_name');
        var position_limit = $(this).data('position_limit');
        var position_remark = $(this).data('position_remark');
        var position_status = $(this).data('position_status');
        var checkstatus = position_status == '9' ? true : false ;
        var url = "{{ route('position.update') }}?UNID="+position_unid;
        // $('#UNID').val(position_unid);
        $('#EMP_POSITION_NAME').val(position_name);
        $('#EMP_POSITION_LIMIT').val(position_limit);
        $('#REMARK').val(position_remark);
        $('#STATUS').attr('checked',checkstatus);
        $('#FRM_POSITION').attr('action',url);
        if (position_name != '') {
          $('#NEW_POSITION').modal('show');
        }

      });
      function deleteposition(thisdata){
        var unid = $(thisdata).data('unid');
        var url  = "{{ route('position.delete') }}?UNID="+unid;
        if (unid != '') {
          Swal.fire({
            title: 'คุณต้องการลบตำแหน่ง ?',
            text: "หากลบแล้วไม่สามมารถกู้คืนได้!",
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
        }else {
          Swal.fire('กรุณาลองใหม่อีกครั้ง');
        }
      }
	</script>
@stop
{{-- ปิดส่วนjava --}}
