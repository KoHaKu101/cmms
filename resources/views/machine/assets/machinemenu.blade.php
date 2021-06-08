@extends('masterlayout.masterlayout')
@section('tittle','homepage')
@section('css')
 <link href="{{asset('assets\css\cubeportfolio.css')}}" rel="stylesheet" type="text/css">
 	  <link href="{{asset('assets\css\portfolio.min.css')}}" rel="stylesheet" type="text/css">
	 <link href="{{asset('assets\css\customize.css')}}" rel="stylesheet" type="text/css">

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
      <div class="card">
        <div class="card-header ml-3">
            <a href="{{ route('machine.form') }}"><button class="btn btn-primary btn-xs">
              <span class="fas fa-file fa-lg">	New	</span>
            </button></a>
        </div>
        <div class="card-body">

          <div class="portfolio-content portfolio-1">

              <!--portfolio Grid-->
              <div id="js-grid-juicy-projects" class="cbp">
                <div class="row">
                  <div class="cbp-item movie" style="width:250px">
                      <div class="cbp-item-wrap" style="background-color: #aedee8b8;">
                          <div class="cbp-caption">
                              <div class="cbp-caption-defaultWrap">
                                <a href="{{url('machine/assets/machinelist')}}">
                                  <img src="{{asset('assets/img/bg-404.jpg')}}" alt="img3">
                                </a> </div>
                              <div class="cbp-caption-activeWrap">
                                  <a href="{{url('machine/assets/machinelist/')}}" style="width:254px" class=" btn" rel="nofollow" data-cbp-singlePage="projects">ทะเบียนเครื่องจักร</a>
                              </div>
                          </div>
                          <a href="{{url('machine/assets/machinelist')}}" style="color:black">
                          <div class="cbp-l-grid-projects-title uppercase text-center uppercase text-center">เครื่องจักรทั้งหมด</div>
                          <div class="cbp-l-grid-projects-desc uppercase text-center uppercase text-center"></div>
                          </a>
                      </div>
                  </div>
                  @foreach ($dataset as $key => $dataitem)

                    <!--portfolio 1-->
                    <div class="cbp-item movie">
                        <div class="cbp-item-wrap" style="background-color: #aedee8b8;">
                            <div class="cbp-caption">
                                <div class="cbp-caption-defaultWrap">
                                  <a href="{{url('machine/assets/machinelist/'.$dataitem->LINE_CODE)}}">
                                    <img src="{{asset('assets/img/bg-404.jpg')}}" alt="img3">
                                  </a>
                                  </div>
                                  <div class="cbp-caption-activeWrap">
                                      <a href="{{url('machine/assets/machinelist/'.$dataitem->LINE_CODE)}}" style="width:254px" class=" btn" rel="nofollow" data-cbp-singlePage="projects">ทะเบียนเครื่องจักร</a>
                                  </div>
                            </div>
                            <a href="{{url('machine/assets/machinelist/'.$dataitem->LINE_CODE)}}" style="color:black">
                            <div class="cbp-l-grid-projects-title uppercase text-center uppercase text-center">
                              เครื่องจักร {{ $dataitem->LINE_NAME }}</div>
                            <div class="cbp-l-grid-projects-desc uppercase text-center uppercase text-center"></div>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                  <!--/portfolio 1-->

              </div>
              <!-- /portfolio Grid-->

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


@stop
{{-- ปิดส่วนjava --}}
