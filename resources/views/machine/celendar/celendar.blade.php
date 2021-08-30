@extends('masterlayout.masterlayout')
@section('tittle','homepage')
@section('css')
<link href={{ asset('/assets/fullcalendar/main.css') }} rel='stylesheet' />

@endsection
{{-- ส่วนหัว --}}
@section('Logoandnavbar')

{{-- @include('masterlayout.logomaster') --}}
{{-- @include('masterlayout.navbar.navbarmaster')  --}}

@stop
{{-- ปิดท้ายส่วนหัว --}}

{{-- ส่วนเมนู --}}
@section('sidebar')

{{-- @include('masterlayout.sidebar.sidebarmaster')  --}}

@stop
{{-- ปิดส่วนเมนู --}}

{{-- ส่วนเนื้อหาและส่วนท้า --}}
@section('contentandfooter')

<div class="content">
    <div class="panel-header bg-primary-gradient">
        <div class="py-3 page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="pb-2 text-white fw-bold">Calendar (ปฏิทิน)</h2>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div id="calendar">
                    </div>
                </div>
            </div>
        </div>

    </div>


</div>

<!-- Modal -->
<div class="modal fade" id="calendarmodal" tabindex="-1" role="dialog">
    <!--กำหนด id ให้กับ modal-->
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="calendarmodal-title">Modal title</h5>
                <!--กำหนด id ให้ส่วนหัวข้อ-->
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="calendarmodal-detail">
                <!-- กำหนด id ให้ส่วนรายละเอียด-->
                Modal detail
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>




@stop
{{-- ปิดส่วนเนื้อหาและส่วนท้า --}}

{{-- ส่วนjava --}}
@section('javascript')
<script src='{{ asset('/assets/fullcalendar/main.js')}}'></script>

<script>
    function setcookie(name, value) {
        var urlcookie = "{{ route('cookie.set') }}";
        var data = {
            "_token": "{{ csrf_token() }}",
            NAME: name,
            VALUE: value
        }
        $.ajax({
            type: 'POST',
            url: urlcookie,
            datatype: 'json',
            data: data,
            success: function(res) {}
        });
    }
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {

            headerToolbar: {
                left: 'title',
                right: 'dayGridMonth,listYear prev,next'
            },
            eventClick: function(info) {
                var eventObj = info.event;
                if (eventObj.id) {
                    var url = eventObj.id;
                    $.ajax({
                        type: "GET",
                        url: url,
                        success: function(Result) {
                            $('#calendarmodal-detail').html(Result.html);
                            $('#calendarmodal').modal('show');
                        },
                        dataType: 'JSON'
                    });
                }
            },
            dayMaxEvents: true,
            events: [
              @foreach($DATA_MACHINEPLANPM as $key => $row)
                @php
                $COLOR = '#FF0000';
                if ($row->PLAN_STATUS == 'COMPLETE') {
                    $COLOR = '#00FF2E';
                }
                @endphp{
                    title: '{{ 'ตรวจเช็คเครื่อง: '.$row->MACHINE_CODE }}',
                    url: '{{ url(' /machine/pm/plancheck/'.$row->UNID) }}',
                    start: '{{ $row->PLAN_DATE }}',
                    color: '{{ $COLOR }}',
                },
                @endforeach
              @foreach($DATA_PMPLANSPAREPART as $key => $sub_row)
                @php
                $COLOR = '#FF0000';
                if ($sub_row->STATUS == 'COMPLETE') {
                    $COLOR = '#00FF2E';
                }
                @endphp  {
                    title: '{{ 'เปลี่ยนอะไหล่: '.$sub_row->MACHINE_CODE }}',
                    url: '{{route('SparPart.Report.Index')}}?MACHINE_SEARCH={{ $sub_row->MACHINE_CODE }}&DOC_YEAR={{ $sub_row->DOC_YEAR }}&DOC_MONTH={{ $sub_row->DOC_MONTH }}',
                    start: '{{ $sub_row->PLAN_DATE }}',
                    color: '{{ $COLOR }}',
                },
                @endforeach
            ],
        });
        calendar.setOption('locale', 'th');
        calendar.render();

        var cookie_style = '{{ Cookie::get('
        style_calendar ') }}';
        if (cookie_style == '2') {
            console.log($('.fc-listYear-button'));
            $('.fc-listYear-button').trigger('click');
            console.log($('.fc-listYear-button').click());

        } else {
            console.log($('.fc-dayGridMonth-button'));
            $('.fc-dayGridMonth-button').click();

        }
        $('.fc-dayGridMonth-button').on('click', function(event) {
            event.preventDefault();
            setcookie('style_calendar', '1');
        });
        $('.fc-listYear-button').on('click', function(event) {
            event.preventDefault();
            setcookie('style_calendar', '2');
            console.log('1');
        });
    });
</script>

@stop
{{-- ปิดส่วนjava --}}
