@extends('masterlayout.masterlayout')
@section('tittle','แจ้งซ่อม')
@section('meta')
	<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
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
								@can('isUser')
									<a href="{{ url('/machine/user/homepage') }}">
										<button class="btn btn-warning  btn-xs ">
											<span class="fas fa-arrow-left fa-lg">Back </span>
										</button>
									</a>
	              @endcan
							</div>
						</div>
          </div>
				</div>
				<div class="py-12">
	        <div class="container mt-2">
						<div class="row">
							<div class="col-md-12">
								<div class="card ">

								  <div class="card-header bg-primary  ">
										<form action="{{ route('repair.list') }}" method="POST" enctype="multipart/form-data">
											@method('GET')
											@csrf
								        <div class="row ">
								          <div class="col-md-2">
								            <h4 class="ml-3 mt-2 " style="color:white;" ><i class="fas fa-toolbox fa-lg mr-1"></i> ค้นหาเอกสาร </h4>
								          </div>
								          <div class="col-md-8">
								              <div class="input-group mt-1">
								                <input  type="search" id="SEARCH"  name="SEARCH" class="form-control form-control-sm col-md-3" placeholder="ค้นหา........."
																value="{{ $SEARCH }}">
								                <div class="input-group-prepend">
								                  <button type="submit" class="btn btn-search pr-1 btn-xs	">
								                    <i class="fa fa-search search-icon"></i>
								                  </button>
								                </div>
								              </div>
								          </div>
													<div class="col-md-2 text-right">
														<a href="{{ route('repair.repairsearch') }}"class="btn btn-warning  btn-xs mt-1">
															<span class="fas fa-file fa-lg">	แจ้งซ่อม	</span>
														</a>
													</div>

								        </div>
											</form>

								  </div>
								  <div id="result"class="card-body">
								    <div class="table-responsive" id="dynamic_content">
								      <table class="display table table-striped table-hover">
								        <thead class="thead-light">
								          <tr>
								            <th scope="col">เลขที่เอกสาร </th>

								            <th scope="col">รหัสเครื่อง </th>
								            <th scope="col">ชื่อเครื่องจักร</th>
								            <th scope="col">Line</th>
								            <th scope="col">วันที่เอกสาร</th>
								            <th scope="col">สถานะเครื่องจักร</th>
								            <th scope="col">สถานะงาน</th>
								            <th scope="col" style="width:100px"></th>
														<th scope="col" >ผู้รับงาน</th>
														<th scope="col" >วันที่รับงาน</th>
								          </tr>
								        </thead>

								        <tbody>
								          @foreach ($dataset as $key => $row)

								            <tr>
								              <td style="width:200px">
								                <a href="{{ route('repair.edit',[$row->UNID]) }}" class="btn btn-secondary btn-block btn-sm my-1 " style="width:180px;height:30px">
								                  <span class="btn-label float-left">
								                    <i class="fas fa-eye mx-1"></i>{{ $row->DOC_NO }}
								                  </span>
								                </a>
								              </td>
								              {{-- <td style="width:50px"> --}}
								                {{-- <button type="button"class="btn btn-primary btn-block btn-sm my-1 " onclick="pdfrepair('{{ $row->UNID }}')"
								                style="width:50px;height:30px">
								                  <span class="">
								                    <i  style="font-size:17px"class="icon-printer "></i>
								                  </span>
								                </button> --}}
								              {{-- </td> --}}
								              <td >  				{{ $row->MACHINE_CODE }}		     </td>
								              <td >  				{{ $row->MACHINE_NAME }}		    </td>
								              <td >  				{{ $row->MACHINE_LINE }}	    </td>
								              <td >      		{{ $row->DOC_DATE }}          </td>
								              <td >  				{{ $row->MACHINE_STATUS == '1' ? 'เครื่องหยุดทำงาน' : 'เครื่องทำงาน'}}	    </td>

								                @if ($row->CLOSE_STATUS ===  '9')
								                  <td style="width:120px">
								                    <button type="button"class="btn btn-success btn-block btn-sm my-1 " style="width:120px;height:30px">
								                      <span class="btn-label text-center">
								                        <i class="fas  mx-1"></i>รอรับงาน
								                      </span>
								                    </button>
								                  </td>
								                  <td style="width:90px">
																		@can('isAdmin')
																			<button onclick="REC_WORK()" type="button"
																			class="btn btn-danger btn-block btn-sm my-1"
																		 style="width:90px;height:30px">

																			 <span class="btn-label">
																				 <i class="fas fa-clipboard-check mx-1"></i>รับงาน
																			 </span>
																		 </button>
																		@elsecan('isManager')
																			<button  onclick="btn_closeform()" type="button"
																			class="btn btn-danger btn-block btn-sm my-1"
																		 style="width:90px;height:30px">
																			 <span class="btn-label">
																				 <i class="fas fa-clipboard-check mx-1"></i>ปิดเอกสาร
																			 </span>
																		 </button>
																		@else

																		@endcan

								                @elseif ($row->CLOSE_STATUS === '1')
								                  <td style="width:100px">
								                    <button type="button" class="btn btn-primary btn-block btn-sm my-1 " style="width:120px;height:30px">
								                      <span class="btn-label float-left">
								                        <i class="fas  mx-1"></i>เรียบร้อยแล้ว
								                      </span>
								                    </button>
								                    </td>
								                    <td style="width:90px">

								                    </td>
								                @else
								                  <td style="width:100px">
								                      <button type="button" class="btn btn-danger btn-block btn-sm my-1 " style="width:120px;height:30px">
								                        <span class="btn-label float-left">
								                          <i class="fas  mx-1"></i>สถานะไม่แน่ชัด
								                        </span>
								                      </button>
								                      </td>
								                      <td style="width:90px">

								                      </td>
								                @endif
																<td >สุบรรณ</td>
								              </tr>
								            @endforeach


								        </tbody>
								    </table>

								  </div>
									{{$dataset->appends(['SEARCH' => $SEARCH])->links('pagination.default')}}
								    </div>
								</div>
								</div>
              </div>

						</div>
					</div>
  			</div>
			</div>
		</div>
		@include('machine.repair.repairclosemodal')

@stop
{{-- ปิดส่วนเนื้อหาและส่วนท้า --}}

{{-- ส่วนjava --}}
@section('javascript')
<script src="{{ asset('assets/js/ajax/ajax-csrf.js') }}"></script>
<script>
	$(".tabactive").each(function(){
		$(this).click(function(){
			id = $(this).attr('id');
			url = window.location.href;
			$(".nav-link").removeClass("active");
			$(this).addClass("active show");
			$('.tab-pane').removeClass("active");
			$("#"+id).addClass("active");

		});
	});
	// $(document).ready(function(){
	function REC_WORK(){
		$('#RepairForm').modal({backdrop: 'static', keyboard: false});
		$('#RepairForm').modal('show');
	}


	// });
	$('#closestep_1').on('click',function(){
		$('#CloseForm').modal({backdrop: 'static', keyboard: false});
		$('#RepairForm').modal('hide');
		$('#CloseForm').modal('show');
	});
	$('#step2_in').on('click',function(){
		$('#step2').html('<i class="fa fa-file mr-2"></i>ช่างภายใน');
		$('#step2').attr('href','#WORK_STEP2_IN');
		$('#step2').removeClass('WORK_STEP2_SUP');
		$('#step2').addClass('WORK_STEP2_IN');
		$('#step2').attr('hidden',false);
		$('#step2').click();
		$('#step3_partchange').on('click',function(){
			$('#step3').html('<i class="fa fa-file mr-2"></i>อะไหล่');
			$('#step3').attr('hidden',false);
			$('#step3').click();
		});
	});
	$('#step2_sup').on('click',function(){
		$('#step2').html('<i class="fa fa-file mr-2"></i>ช่างภายนอก');
		$('#step2').attr('href','#WORK_STEP2_SUP');
		$('#step2').removeClass('WORK_STEP2_IN');
		$('#step2').addClass('WORK_STEP2_SUP');
		$('#step2').attr('hidden',false);
		$('#step2').click();
		$('#step3_partchange').on('click',function(){
			$('#step3').html('<i class="fa fa-file mr-2"></i>อะไหล่');
			$('#step3').attr('hidden',false);
			$('#step3').click();
		});
	});
	function cancelform(){
		jQuery(document).off('focusin.modal');
		Swal.fire({
			title: 'สาเหตุการยกเลิก',
			input: 'text',
		}).then((result) => {
				 $('#CloseForm').modal('hide');
		});
	}
	function step_final(){
		$('#step4').html('<i class="fa fa-file mr-2"></i>ปิดเอกสาร');
		$('#step4').attr('hidden',false);
		$('#step4').click();
	};
	function step_result(){
			$('#step5').html('<i class="fa fa-map-signs mr-2"></i> สรุป');
			$('#step5').attr('hidden',false);
			$('#step5').click();
	}
	function withdraw(thisdata){
		var unid = $(thisdata).data('unid');
		jQuery(document).off('focusin.modal');
		if (unid == '1') {
			Swal.fire({
				title: 'จำนวนเบิก',
				input: 'number',
			});
		}else if (unid == '2') {
			Swal.fire({
				title: 'อะไหล่หมด',
				icon: 'error',
				confirmButtonText:'รอการสั่งซื้อ',
				timer:'1000',
			});
			$('#buypart').attr('hidden',false);
		}else {
			Swal.fire({
				title: 'อะไหล่ใกล้จะหมด',
				icon: 'warning',
				confirmButtonText:'OK',
				timer:'1000',
			});
		}
	}
 function buypart(){
	 $('#step3_waitpart').html('<i class="fa fa-file mr-2"></i>รออะไหล่');
	 $('#step3_waitpart').attr('hidden',false);
	 $('#step3_waitpart').click();
 }
 function closework(u){

	var unid = (u);
	console.log(unid);
		Swal.fire({
			title: 'ต้องการปิดเอกสารมั้ย?',
			text: "หากทำการปิดเอกสารแล้วไม่สามารถแก้ไขได้ ต้องทำการสร้างใหม่เท่านั้น!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes!'
		}).then((result) => {
			if (result.isConfirmed) {
			window.location.href = "/machine/repair/delete/"+unid;
			}
		})

	}
 function btn_closeform(){
	 $('#CloseForm').modal({backdrop: 'static', keyboard: false});
	 $('#CloseForm').modal('show');
 }
</script>

<script type="text/javascript">
// var button = document.getElementById('button');
	function pdfrepair(m){
		console.log(m);
		var unid = (m);
		window.open('/machine/repair/pdf/'+unid,'Repairprint','width=1000,height=1000,resizable=yes,top=100,left=100,menubar=yes,toolbar=yes,scroll=yes');
	}


</script>
@stop
{{-- ปิดส่วนjava --}}
