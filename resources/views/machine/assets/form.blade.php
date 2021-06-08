@extends('masterlayout.masterlayout')
@section('tittle','homepage')
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
				<!--ส่วนปุ่มด้านบน-->
				<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
					<div class="container">
						<div class="row">
							<div class="form-group gx-4">
								<a href="{{ route('machine.list') }}">
									<button class="btn btn-warning  btn-xs ">
										<span class="fas fa-arrow-left fa-lg">Back </span>
									</button>
								</a>
							</div>
							<div class="form-group gx-4">
								<form action="{{ route('machine.store') }}" method="POST" enctype="multipart/form-data">
									@csrf
									<button class="btn btn-primary btn-xs" type="submit">
										<span class="fas fa-save fa-lg">	save	</span>
									</button>
							</div>
						</div>
					</div>
				</div>

				<!--ส่วนกรอกข้อมูล-->
				<div class="py-12">
	        <div class="container mt-2">
						<div class="card">
							<div class="card-header bg-primary">
								<h4 class="ml-3 mt-2" style="color:white;" >ลงทะเบียนเครื่องจักร </h4>
							</div>
							<div class="card-body">
								<div class="row">
									<!-- ช่อง1-->
										<div class="col-md-6 col-lg-3">
											<div class="form-group mt-4">
												<img src="/assets/img/nobody.jpg" width="200" height="200px" class="mt-4">
													<input type="file" class="form-control mt-4" id="MACHINE_ICON" name="MACHINE_ICON" accept="image/*">
											</div>
										</div>
										<!-- ช่อง2-->
										<div class="col-md-6 col-lg-4">
											<div class="form-group has-error">
												<label for="MACHINE_CODE">รหัสเครื่องจักร</label>
													<input type="text" class="form-control" id="MACHINE_CODE" name="MACHINE_CODE" placeholder="รหัสเครื่องจักร" required autofocus>
											</div>

											<div class="form-group">
												<label for="MACHINE_STARTDATE">วันที่เริ่มใช้งาน	</label>
												<input type="date" class="form-control" id="MACHINE_STARTDATE" name="MACHINE_STARTDATE" placeholder="วันที่เริ่มใช้งาน">
											</div>
											<div class="row ml-1 mt-2">
												<div class="form-group col-md-6 col-lg-6 has-error">
													<lebel>สถานะ</lebel>
													<select class="form-control" id="MACHINE_CHECK" name="MACHINE_CHECK" required autofocus>
														<option value>-แสดงทั้งหมด-</option>
														@foreach ($machinestatus as $key => $srow)
															<option value="{{ $srow->STATUS_CODE }}">{{$srow->STATUS_NAME}}</option>
														@endforeach
													</select>
												</div>
												<div class="form-group col-md-6 has-error">
													<lebel>ตำแหน่งเครื่อง</lebel>
													<select class="form-control" id="MACHINE_LINE" name="MACHINE_LINE" required autofocus>
														<option value> -แสดงทั้งหมด- </option>
														@foreach($machineline as $dataline)
														<option value="{{ $dataline->LINE_CODE  }}"> {{$dataline->LINE_NAME}} </option>
														@endforeach

												</select>
						  				</div>
											</div>
											<div class="form-group has-error ">
												<label for="MACHINE_TYPE">ชนิดเครื่องจักร</label>
												<select class="form-control form-control" id="MACHINE_TYPE" name="MACHINE_TYPE" required autofocus>
													<option value>--แสดงทั้งหมด--</option>
													@foreach($machinetype as $datatype)
													<option value="{{ $datatype->TYPE_CODE  }}"> {{$datatype->TYPE_NAME}} </option>
													@endforeach
												</select>
											</div>
										</div>
										<!-- ช่อง3-->
										<div class="col-md-12 col-lg-4">
											<div class="form-group has-error">
												<label for="MACHINE_NAME">ชื่อเครื่องจักร</label>
												<input type="text" class="form-control" id="MACHINE_NAME" name="MACHINE_NAME" placeholder="ชื่อเครื่องจักร" required autofocus>
											</div>
											<div class="form-group has-error">
												<label for="MACHINE_RVE_DATE">วันที่ Maintenance 	</label>
												<input type="date" class="form-control" id="MACHINE_RVE_DATE" name="MACHINE_RVE_DATE" placeholder="วันที่ Maintenance" required autofocus>
											</div>
											<div class="form-group has-error">
												<label for="PURCHASE_FORM">ซื้อจากบริษัท	</label>
												<input type="text" class="form-control" id="PURCHASE_FORM" name="PURCHASE_FORM" placeholder="ซื้อจากบริษัท" required autofocus>
											</div>
											<div class="form-group has-error">
												<label for="PURCHASE_FORM">Machine Rank	</label>
												<select class="form-control" id="MACHINE_RANK_MONTH" name="MACHINE_RANK_MONTH" required>
													<option value>กรุณาเลือก Rank</option>
													@foreach ($machinerank as $key => $datamachinerank)
														<option value="{{$datamachinerank->MACHINE_RANK_MONTH}}" >{{$datamachinerank->MACHINE_RANK_CODE}}</option>
													@endforeach

												</select>
											</div>

										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12 mt-2">
										<div class="card-body">
											@include('machine.assets.tab.styletab')

											<ul class="nav nav-pills justify-content-center mt--4">
  											<li>
    											<a id="home-tab" data-toggle="tab" href="#home" class="active" >ข้อมูลทั่วไป</a>
  											</li>
  											<li>
    											<a id="profile-tab" data-toggle="tab" href="#history" >ประวัติการแจ้งซ่อม</a>
  											</li>
  											<li>
    											<a id="messages-tab" data-toggle="tab" href="#plan" >แผนการปฎิบัติการ</a>
							  				</li>
							  				<li>
    											<a id="settings-tab" data-toggle="tab" href="#personal">พนักงานประจำเครื่อง</a>
  											</li>
  										</ul>
  										<div class="tab-content clearfix">
												<!-- ข้อมูลทั่วไป -->
												@include('machine.assets.tab.home')
												<!-- ประวัติการแจ้งซ่อม -->
												@include('machine.assets.tab.history')
												<!-- แผนการปฎิบัติการ -->
  											@include('machine.assets.tab.plan')
												<!-- พนักงานประจำเครื่อง -->
												@include('machine.assets.tab.personal')

									</div>
								</div>
							</div>
					</div>
				</div>
				</div>
			</div>
		</form>
	</div>
</div>

@stop
{{-- ปิดส่วนเนื้อหาและส่วนท้า --}}

{{-- ส่วนjava --}}
@section('javascript')

@stop
{{-- ปิดส่วนjava --}}
