$(document).ready(function(){
  var notifity = function (){
    $.ajax({
      url:'/machine/repair/notificaiton',
      method:'GET',
      dataType:'json',
      success:function(res){
        if (res != null) {
          var _html='';

          $.each(res.datarepair,function(notificaiton,datarepair){
            var url = '/machine/repair/edit/'+datarepair.UNID;
            _html += '<a href="'+url+'">'+
                '<div class="notif-icon notif-danger"> <i class="fa fa-wrench"></i> </div>'+
                '<div class="notif-content">'+
                '<span class="block" >Line :'+datarepair.MACHINE_LINE+' MC: '+datarepair.MACHINE_CODE+ '</span>' +
                  '<span class="time">'+datarepair.DOC_DATE+'</span>'+
                '</div>'+
              '</a>';
          });

        }else {
          var _html='';
           _html +=
           '<center> <div class="notif-content">'+
            '<span class="block" >' +'รายการแจ้งซ่อม'+ '</span>' +
            '</div> </center>'+
            '<center> <div class="notif-content">'+
            '<span class="block" >' +'0 รายการ'+ '</span>' +
            '</div> </center>';  }
        $("#loaddatacode").html(_html);
     // Ask for new notifications every second
      }
      });
    }
    setInterval(notifity,50000);

    var count = function (){
      $.ajax({
        url:'/machine/repair/notificaitoncount',
        method:'GET',
        dataType:'json',

        success:function(data){

            var datacount = '<span class="notification">' +data.datacount+ '</span>';
            $("#count").html(datacount);




        }
        });
      }
      setInterval(count,50000);
  });
