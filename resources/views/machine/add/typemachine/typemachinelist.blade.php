@extends('masterlayout.masterlayout')
@section('tittle','homepage')
@section('css')
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link href="{{asset('assets\css\cubeportfolio.css')}}" rel="stylesheet" type="text/css">
  	  <link href="{{asset('assets\css\portfolio.min.css')}}" rel="stylesheet" type="text/css">
 	 <link href="{{asset('assets\css\customize.css')}}" rel="stylesheet" type="text/css">
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
	</style>

	  <div class="content">
      <div class="page-inner">
				<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
          <div class="container">
						<div class="row">
							<div class="col-md-12 gx-4">

							</div>
						</div>
          </div>
				</div>
				<div class="py-12">
	        <div class="container mt-2">
						<div class="row">
							<div class="col-md-12">
								<div class="card ">
									<form action="{{ route('machinetypetable.list') }}" method="POST" enctype="multipart/form-data">
										@method('GET')
										@csrf
										<div class="card-header bg-primary ">
											<div class="row">
												<div class="col-md-7 form-inline">
													<h4 class="mx-2 my-2" style="color:white;" ><i class="fas fa-industry fa-lg mr-1"></i> ประเภทเครื่องจักร </h4>

														<div class="input-group mx-2 my-2">
															<input type="text" id="SEARCH"  name="SEARCH" class="form-control form-control-sm" value="{{ $SEARCH }}">
															<div class="input-group-prepend">
																<button type="submit" class="btn btn-search pr-1 btn-xs	">
																	<i class="fa fa-search search-icon"></i>
																</button>
															</div>
														</div>

												</div>

												<div class="col-md-5">
													<a href="{{ route('machinetypetable.form') }}"><button type="button" class="btn btn-warning  btn-xs my-2 float-right">
														<span class="fas fa-file fa-lg">	New	</span>
													</button></a>
												</div>
											</div>



											</div>
										</form>

									<div id="result"class="card-body">
										<div class="container-fluid ml-4">

													 <div class="main">

														 <!--portfolio -->
														 <div class="portfolio-content portfolio-1">
																 <!--portfolio Grid-->
																 <div id="js-grid-juicy-projects" class="cbp">
																	 @foreach ($dataset as $key => $dataitem)

																		 @php
																		 	$TYPE_ICON = $dataitem->TYPE_ICON != '' ? 'image/machinetype/'.$dataitem->TYPE_ICON : '/assets/img/no_image1200_900.png'
																		 @endphp

									                 <div class="cbp-item movie">
									                     <div class="cbp-item-wrap" style="border-style: solid;border-color: cadetblue;background: #c9fff0;">
									                         <div class="cbp-caption">
									                             <div class="cbp-caption-defaultWrap">
									                               <a href="{{url('machine/machinetypetable/edit/'.$dataitem->UNID)}}">
									                                 <img src="{{asset($TYPE_ICON)}}" alt="img3" style="height:166.5px">
									                               </a> </div>
																								 <style>
																								 .cbp-item .btn {
																												width: 100%;
																											}
																								 </style>
									                             <div class="cbp-caption-activeWrap">
									                                 <div class="cbp-l-caption-alignCenter">
									                                     <div class="cbp-l-caption-body">
																														 <a href="{{url('machine/machinetypetable/edit/'.$dataitem->UNID)}}" class=" btn" rel="nofollow" data-cbp-singlePage="projects">รายละเอียด</a>


									                                     </div>
									                                 </div>
									                             </div>
									                         </div>
									                         <div class="cbp-l-grid-projects-desc uppercase text-left uppercase  mx-2 my-3">ประเภทเครื่อง : {{$dataitem->TYPE_NAME}}</div>
																					 <div class="cbp-l-grid-projects-desc uppercase text-left uppercase  mx-2 my--3">
																						 สถานะ :
																						 <label class="switch">
															                 <input type="checkbox" id="STATUS_{{ $dataitem->UNID }}" name="STATUS_{{ $dataitem->UNID }}" value="9"
																							 onchange="changestatus(this)"
																							 data-unid="{{ $dataitem->UNID }}"
																							  {{ $dataitem->TYPE_STATUS == '9' ? 'checked' : ''}}>
															                 <span class="slider round"></span>
															               </label></div>

																						 <div class="row">
																							 <div class="col col-md-5 my-2">
																								 <a href="{{ url('machine/machinetypetable/edit/'.$dataitem->UNID) }}"
																									 class="btn btn-primary btn-sm " style="height: 40px;width:90px;font-size:13px;line-height:40px;"><span class="fas fa-edit "> Edit</span></a>
																							 </div>
																							 <div class="col col-md-6 my-2">
																								 <button type="button" onclick="deletemachinetype(this)"
																								data-unid="{{ $dataitem->UNID }}"
																								data-name="{{ $dataitem->TYPE_NAME }}"
																								class="btn btn-danger btn-sm mx-2 "
																								style="height: 40px; width:90px;font-size:13px;line-height:40px;"><span class="fas fa-trash"> Delete</span></button>

																							 </div>
																						 </div>
																			 </div>
									                 </div>
																	 @endforeach

																		 <!--/portfolio 1-->

																 </div>
																 <!-- /portfolio Grid-->
																 <!--portfolio loadMore-->
																 <div id="js-loadMore-juicy-projects" class="cbp-l-loadMore-button">
																		 <a href="loadMorePortfolio.html" class="cbp-l-loadMore-link hvr-underline-from-center text-uppercase" rel="nofollow">
																				 <span class="cbp-l-loadMore-defaultText">load more</span>
																				 <span class="cbp-l-loadMore-loadingText">loading...</span>
																				 <span class="cbp-l-loadMore-noMoreLoading">not load more</span>
																		 </a>
																 </div>
																 <!-- /portfolio loadMore-->
														 </div>
														 <!-- /portfolio -->
													 </div>
												 </div>

										</div>
										{{ $dataset->appends(['SEARCH'=>$SEARCH])->links('pagination.default') }}

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
	<script src="{{ asset('assets/js/porfolio/jquery.cubeportfolio.js') }}"></script>
	<script src="{{ asset('assets/js/porfolio/portfolio-1.js') }}"></script>
	<script src="{{ asset('assets/js/porfolio/retina.min.js') }}"></script>
	<script src={{ asset('assets/js/ajax/ajax-csrf.js') }}></script>
	<script>
	function deletemachinetype(thisdata){
		var unid = $(thisdata).data('unid');
		var name = $(thisdata).data('name');
		var url = '/machine/machinetypetable/delete/'+unid;
		Swal.fire({
				title: 'ต้องการลบ '+name+' นี้มั้ย?',
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
	function changestatus(thisdata){
		var unid =  $(thisdata).data('unid');
		var changestatus = $('#STATUS_'+unid+':checked').val();
		var url = '/machine/machinetypetable/changestatus/'+unid;
		$.ajax({
				type:'POST',
				url: url,
				datatype: 'json',
				data: {
					"_token": "{{ csrf_token() }}",
					"TYPE_STATUS" : changestatus,
				} ,

			});
	}
	</script>
@stop
{{-- ปิดส่วนjava --}}
