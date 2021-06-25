<li class="nav-item dropdown hidden-caret">
  <a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fa fa-bell" id="count">

      <span class="notification">{{isset($data_count) ? $data_count : '0'}}</span>
    </i>

  </a>
  <ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown">
    <li>
      <div class="notif-scroll scrollbar-outer">
        <div class="notif-center" id="loaddatacode">
          
          @if (isset($data_name) != '')
            @if ($data_name->count() == '0')
              <center> <div class="notif-content">
               <span class="block" >รายการแจ้งซ่อม</span>
               </div> </center>
               <center> <div class="notif-content">
               <span class="block" >0 รายการ</span>
               </div> </center>
            @endif
            @foreach ($data_name as $key => $row)
              <a href="{{ url('/machine/repair/edit/'.$row->UNID) }}">
                  <div class="notif-icon notif-danger"> <i class="fa fa-wrench"></i> </div>
                  <div class="notif-content">
                  <span class="block" >Line :{{$row->MACHINE_LINE}} MC: {{ $row->MACHINE_CODE }}</span>
                    <span class="time">{{ $row->DOC_DATE }}</span>
                  </div>
                </a>
            @endforeach
          @endif

        </div>
      </div>
    </li>
    <li>
      <a class="see-all" href="{{route('repair.list')}}">See all notifications<i class="fa fa-angle-right"></i> </a>
    </li>
  </ul>
</li>
