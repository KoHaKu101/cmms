@extends('masterlayout.masterlayout')
@section('tittle','homepage')
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
				<div class="py-12">
	        <div class="container">
						<div class="row">
							<div class="col-md-6">
								<div class="card">
									<div class="card-header bg-primary text-white">
										<h4>Mail Server Setup</h4>
									</div>
									<div class="card-body">
										<form action="{{ url('machine/config/save') }}" method="POST" id="FRM_MAILSETUP" name="FRM_MAILSETUP" >
											@csrf
											@if(count($datamail) == 1 )
												@foreach ($datamail as $key => $dataset)
														<div class="form-group form-inline">
															<label for="MAILHOST" class="col-md-3 col-form-label">Host Name</label>
															<div class="col-md-9 p-0">
																<input type="hidden" id="UNID" name="UNID" value="{{ $dataset->UNID}}">
																<input type="text" class="form-control form-control-sm input-full" id="MAILHOST" name="MAILHOST" placeholder="Enter Input" value="{{ $dataset->MAILHOST}}" autocomplete="off">
															</div>
														</div>
														<div class="form-group form-inline">
															<label for="MAILPORT" class="col-md-3 col-form-label">Port</label>
															<div class="col-md-9 p-0">
																<input type="text" class="form-control form-control-sm input-full" id="MAILPORT" name="MAILPORT" value="{{ $dataset->MAILPORT }}" >
															</div>
														</div>
														<div class="form-group form-inline">
															<label for="EMAILADDRESS" class="col-md-3 col-form-label">Email Admin</label>
															<div class="col-md-9 p-0">
																<input type="email" class="form-control form-control-sm input-full" id="EMAILADDRESS" name="EMAILADDRESS" value="{{ $dataset->EMAILADDRESS }}" >
															</div>
														</div>
														<div class="form-group form-inline">
															<label for="MAILPASSWORD" class="col-md-3 col-form-label">Password</label>
															<div class="col-md-9 p-0">
																<input type="password" class="form-control form-control-sm input-full" id="MAILPASSWORD" name="MAILPASSWORD" value="{{ $dataset->MAILPASSWORD }}" autocomplete="off">
															</div>
														</div>
														<div class="form-group form-inline">
															<label for="MAILPROTOCOL" class="col-md-3 col-form-label">Security Protocol</label>
															<div class="col-md-9 p-0">
																<input type="text" class="form-control form-control-sm input-full" id="MAILPROTOCOL" name="MAILPROTOCOL"value="{{ $dataset->MAILPROTOCOL }}" >
															</div>
															</div>
														<div class="form-group text-center">
															<button class="btn btn-success btn-sm ">Save</button>
														</div>
												@endforeach
											@else
													<div class="form-group form-inline">
														<label for="MAILHOST" class="col-md-3 col-form-label">Host Name</label>
														<div class="col-md-9 p-0">
															<input type="text" class="form-control form-control-sm input-full" id="MAILHOST" name="MAILHOST"placeholder="Enter Input" value=""required>
														</div>
													</div>
													<div class="form-group form-inline">
														<label for="MAILPORT" class="col-md-3 col-form-label">Port</label>
														<div class="col-md-9 p-0">
															<input type="text" class="form-control form-control-sm input-full" id="MAILPORT" name="MAILPORT" placeholder="Enter Input" required>
														</div>
													</div>
													<div class="form-group form-inline">
														<label for="EMAILADDRESS" class="col-md-3 col-form-label">Email Admin</label>
														<div class="col-md-9 p-0">
															<input type="email" class="form-control form-control-sm input-full" id="EMAILADDRESS" name="EMAILADDRESS" placeholder="Email" required>
														</div>
													</div>
													<div class="form-group form-inline">
														<label for="MAILPASSWORD" class="col-md-3 col-form-label">Password</label>
														<div class="col-md-9 p-0">
															<input type="password" class="form-control form-control-sm input-full" id="MAILPASSWORD" name="MAILPASSWORD" placeholder="Password" required>
														</div>
													</div>
													<div class="form-group form-inline">
														<label for="MAILPROTOCOL" class="col-md-3 col-form-label">Security Protocol</label>
														<div class="col-md-9 p-0">
															<input type="text" class="form-control form-control-sm input-full" id="MAILPROTOCOL" name="MAILPROTOCOL"placeholder="Enter Input" required>
														</div>
													</div>
													<div class="form-group text-center">
														<button class="btn btn-success btn-sm ">Save</button>
													</div>
											@endif
										</form>
									</div>
								</div>
              </div>
							<div class="col-md-6">
								<div class="card">
									<div class="card-header bg-primary text-white">
										<h4>Alert Mail</h4>
									</div>
									<div class="card-body">
										@if (count($dataalertmail) == 1 )
											@foreach ($dataalertmail as $key => $dataitem)
												<form action="{{ route('machine.savealert') }}" method="POST" id="FRM_ALERTMAIL" name="FRM_ALERTMAIL">
													@csrf
													<div class="form-group form-inline">
															<label for="MAILALEAT1" class="col-md-3 col-form-label">Email Aleart 1</label>
															<div class="col-md-9 p-0">
																<input type="hidden" class="form-control form-control-sm input-full" id="UNID" name="UNID" value="{{ $dataitem->EMAILADDRESS1 }}">
																<input type="text" class="form-control form-control-sm input-full" id="MAILALEAT1" name="MAILALEAT1" value="{{ $dataitem->EMAILADDRESS1 }}">
															</div>
														</div>
														<div class="form-group form-inline">
																<label for="MAILALEAT2" class="col-md-3 col-form-label">Email Aleart 2</label>
																<div class="col-md-9 p-0">
																	<input type="text" class="form-control form-control-sm input-full" id="MAILALEAT2" name="MAILALEAT2" value="{{ $dataitem->EMAILADDRESS2 }}">
																</div>
															</div>
															<div class="form-group form-inline">
																	<label for="MAILALEAT3" class="col-md-3 col-form-label">Email Aleart 3</label>
																	<div class="col-md-9 p-0">
																		<input type="email" class="form-control form-control-sm input-full" id="MAILALEAT3" name="MAILALEAT3" value="{{ $dataitem->EMAILADDRESS3 }}">
																	</div>
																</div>
																<div class="form-group form-inline">
																		<label for="MAILALEAT4" class="col-md-3 col-form-label">Email Aleart 4</label>
																		<div class="col-md-9 p-0">
																			<input type="text" class="form-control form-control-sm input-full" id="MAILALEAT4" name="MAILALEAT4" value="{{ $dataitem->EMAILADDRESS4 }}">
																		</div>
																	</div>
																	<div class="form-group form-inline">
																			<label for="MAILALEAT5" class="col-md-3 col-form-label">Email Aleart 5</label>
																			<div class="col-md-9 p-0">
																				<input type="text" class="form-control form-control-sm input-full" id="MAILALEAT5" name="MAILALEAT5" value="{{ $dataitem->EMAILADDRESS5 }}">
																			</div>
																		</div>
																		<div class="form-group text-center">
																				<button class="btn btn-success btn-sm ">Save</button>
																			</div>

												</form>
											@endforeach
										@else
											<form action="{{ route('machine.savealert') }}" method="POST" id="FRM_ALERTMAIL" name="FRM_ALERTMAIL">
												@csrf
												<div class="form-group form-inline">
														<label for="MAILALEAT1" class="col-md-3 col-form-label">Email Aleart 1</label>
														<div class="col-md-9 p-0">
															<input type="text" class="form-control form-control-sm input-full" id="MAILALEAT1" name="MAILALEAT1"	placeholder="Email">
														</div>
													</div>
													<div class="form-group form-inline">
															<label for="MAILALEAT2" class="col-md-3 col-form-label">Email Aleart 2</label>
															<div class="col-md-9 p-0">
																<input type="text" class="form-control form-control-sm input-full" id="MAILALEAT2" name="MAILALEAT2"	placeholder="Email">
															</div>
														</div>
														<div class="form-group form-inline">
																<label for="MAILALEAT3" class="col-md-3 col-form-label">Email Aleart 3</label>
																<div class="col-md-9 p-0">
																	<input type="email" class="form-control form-control-sm input-full" id="MAILALEAT3" name="MAILALEAT3"	placeholder="Email">
																</div>
															</div>
															<div class="form-group form-inline">
																	<label for="MAILALEAT4" class="col-md-3 col-form-label">Email Aleart 4</label>
																	<div class="col-md-9 p-0">
																		<input type="text" class="form-control form-control-sm input-full" id="MAILALEAT4" name="MAILALEAT4"	placeholder="Email">
																	</div>
																</div>
																<div class="form-group form-inline">
																		<label for="MAILALEAT5" class="col-md-3 col-form-label">Email Aleart 5</label>
																		<div class="col-md-9 p-0">
																			<input type="text" class="form-control form-control-sm input-full" id="MAILALEAT5" name="MAILALEAT5"	placeholder="Email">
																		</div>
																	</div>
																	<div class="form-group text-center">
																			<button class="btn btn-success btn-sm ">Save</button>
																		</div>

											</form>
										@endif

									</div>
								</div>
              </div>

						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="card">
									<div class="card-header bg-primary text-white">
										<h4>Alert Mail</h4>
									</div>
									<div class="card-body">
										<form action="{{ url('machine/config/update') }}" method="POST" id="ALERTNADPLND" name="ALERTNADPLND" >
											@csrf
											<div class="row">
												<div class="col-md-6 col-lg-6">
													<div class="form-group">
															<label for="AUTOMAIL" class="col-md-3 col-form-label">Auto Mail Alert(day)</label>
															<div class="col-md-9 p-0">
																<input type="hidden" id="UNID" name="UNID" value="{{ isset($datamail[0]->UNID) ? $datamail[0]->UNID : '' }}">
																<input type="number" class="form-control form-control-sm input-full"  id="AUTOMAIL" name="AUTOMAIL" value="{{ isset($dataset->AUTOMAIL) != NULL ? $dataset->AUTOMAIL : "7" }}" min="1" max="90">
															</div>
														</div>
												</div>
												<div class="col-md-6 col-lg-6">
													<div class="form-group">
															<label for="AUTOPLAN" class="col-md-3 col-form-label">Auto Plan(day)</label>
															<div class="col-md-9 p-0">
																<input type="number" class="form-control form-control-sm input-full"  id="AUTOPLAN" name="AUTOPLAN" value="{{ isset($dataset->AUTOPLAN) != NULL ? $dataset->AUTOPLAN : "730" }}" min="1" max="1000">
															</div>
														</div>
												</div>
											</div>
																<div class="form-group text-right">
																		<button class="btn btn-success btn-sm ">Save</button>
																	</div>

										</form>
									</div>
								</div>
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

@stop
{{-- ปิดส่วนjava --}}
