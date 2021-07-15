@extends('masterlayout.masterlayout')
@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
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
							<div class="col-md-1">
              @can('isAdmin')
								<a href="{{ url('machine/repair/repairlist') }}">
									<button class="btn btn-warning  btn-xs ">
										<span class="fas fa-arrow-left fa-lg">Back </span>
									</button>
								</a>
              @elsecan('isManager_Ma')
								<a href="{{ url('machine/repair/repairlist') }}">
									<button class="btn btn-warning  btn-xs ">
										<span class="fas fa-arrow-left fa-lg">Back </span>
									</button>
								</a>
							@elsecan('isManager_Pd')
								<a href="{{ route('pd.repairlist') }}">
									<button class="btn btn-warning  btn-xs ">
										<span class="fas fa-arrow-left fa-lg">Back </span>
									</button>
								</a>
              @else
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
				<!--ส่วนกรอกข้อมูล-->
				<div class="py-12">
	        <div class="container mt-2">
						<div class="card">
						  <div class="card-header">
						  <div class="row justify-content-md-center">
						    <div class="col-md-6 col-lg-5 ">
									<div id="control">
											<button onclick="openCamera()" class="w-50">Open Camera</button>
											<button onclick="capture()" class="w-50">Capture</button>
									</div>
									<div id="preview">
											<video id="camera" width="100%" height="auto" autoplay></video>
											<canvas id="canvas"></canvas>
									</div>
									<form action="{{ route('repair.repairsearch') }}" method="POST">
										@method('GET')
										@csrf
										<div class="input-group mb-3">
											<input type="text" class="form-control" id="search" name="search"
											 placeholder="กรอกรหัสเครื่อง / แสกนQR Code ที่นี้" autofocus>
											<div class="input-group-append">
												<span class="input-group-text" id="basic-addon2">
													<button type="submit" class="btn btn-primary btn-sm btn-link"><i class="fas fa-search"></i></button>
												</span>
											</div>
										</div>

									</form>

						    </div>
						  </div>
						  </div>
						  <div class="card-body">
						    <div class="row">
						      @if ($machine != NULL)
						        @foreach ($machine as $key => $dataset)
						        <div class="col-md-6 col-lg-3 ml-auto mr-auto">
						        <div class="card card-post card-round">
						        <div class="card-header bg-primary text-white">
						        <center><h4 class="mt-1"><b> {{$dataset->MACHINE_CODE}} </b></h4></center>
						        </div>
						        <div class="card-body">
						        <span>Machine Name : {{$dataset->MACHINE_NAME_TH}}</span><br/>
						        <span class="mt-3"> Line : {{$dataset->MACHINE_LINE}}</span><br/>
						        <a href="{{ url('machine/repair/form/'.$dataset->UNID)}}" class="btn btn-success btn-sm btn-block my-1">
						        <span style="font-size:13px">
						         <i class="fas fa-hand-pointer fa-lg mx-2"></i>แจ้งอาการเสีย
						          </span>
						        </a>
						        </div>
						        </div>
						        </div>
						        @endforeach
						      @else

						      @endif
						    </div>
						  </div>
						</div>
						{{-- @livewire('searchnewrepair') --}}
				</div>
				</div>
			</div>

	</div>



@stop
{{-- ปิดส่วนเนื้อหาและส่วนท้า --}}

{{-- ส่วนjava --}}
@section('javascript')
{{-- <script src="{{ asset('assets/js/instascan.min.js') }}"></script> --}}

<script>
        const RECENT_4_URLS = 'RECENT_4_URLS'
        const DEFAULT_URL = 'https://puuga.github.io/web-qr/'

        function readRecent4UrlsThenFillToElement () {
            const localStorage = window.localStorage
            if (localStorage.getItem(RECENT_4_URLS)) {
                const recent4Urls = JSON.parse(localStorage.getItem(RECENT_4_URLS))
                let content = ''
                for (const url of recent4Urls) {
                    content += makeHtmlResultContent(url)
                }
                const resultElement = document.getElementById('result')
                resultElement.innerHTML = resultElement.innerHTML + content
            }
        }

        async function openCamera () {
            // try open rear camera then default camera
            try {
                await openRearCamera()
            } catch (error) {
                try {
                    await openDefaultCamera()
                } catch (error) {
                    alert('No support or no permission, try again')
										console.log(error);
                }
            }
        }

        async function openRearCamera () {
            const constraints = (window.constraints = {
                audio: false,
                video: { facingMode: { exact: 'environment' } }
            });

            return await openCameraWith(constraints)
        }

        async function openDefaultCamera () {
            const constraints = (window.constraints = {
                audio: false,
                video: true
            });

            return await openCameraWith(constraints)
        }

        async function openCameraWith (constraint = {}) {
            try {
                const stream = await navigator.mediaDevices.getUserMedia(constraints)
                const cameraElement = document.getElementById('camera')
                cameraElement.srcObject = stream

                const videoTracks = stream.getVideoTracks()
                console.log('Got stream with constraints:', constraints)
                console.log('videoTracks: ' + videoTracks)
                console.log('Using video device: ' + videoTracks[0].label)
                return true
            } catch (error) {
                console.log(error);
                throw error
            }
        }

        async function capture () {
            const canvas = document.getElementById('canvas')
            const video = document.getElementById('camera')
            canvas.width = video.videoWidth
            canvas.height = video.videoHeight
            canvas.getContext('2d').drawImage(video, 0, 0, video.videoWidth, video.videoHeight)

            await detectQR(canvas)
        }

        async function detectQR (canvasElement = {}) {
            try {
                const barcodeDetector = new BarcodeDetector({formats: ['qr_code']})
                const barcodes = await barcodeDetector.detect(canvasElement)
                console.log('barcodes', barcodes)

                const resultElement = document.getElementById('result')
                if (barcodes.length === 0) {
                    resultElement.innerHTML = 'Not found any QR'
                } else {
                    let result = `Found ${barcodes.length} QR<br>`
                    for (const barcode of barcodes) {
                        console.log('barcode', barcode)
                        console.log('barcode.rawValue', barcode.rawValue)

                        if (barcode.rawValue.startsWith('http')) {
                            result += makeHtmlResultContent(barcode.rawValue)
                        } else {
                            result += `${barcode.rawValue}<br>`
                        }
                    }
                    resultElement.innerHTML = result
                }
            } catch {
                throw error
            }
        }

        function makeHtmlResultContent (url = '') {
            return `<button onclick="redirectTo('${url}')">GO !</button> ${url}<br>`
        }

        function redirectTo (url = DEFAULT_URL) {
            if (url !== DEFAULT_URL) {
                saveRecent(url)
            }

            window.location.href = url
        }

        function saveRecent (url = '') {
            const localStorage = window.localStorage
            const recent4Urls = JSON.parse(localStorage.getItem(RECENT_4_URLS)) || []

            if (recent4Urls.length === 4) {
                recent4Urls.pop()
            }

            recent4Urls.unshift(url)

            localStorage.setItem(RECENT_4_URLS, JSON.stringify(recent4Urls))
        }
    </script>
@stop
{{-- ปิดส่วนjava --}}
