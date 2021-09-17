$('#startbtn').on('click',function(){
	const  music = document.getElementById("music");
	music.play();
});
function loaddata_table(){
  $.ajax({
         type:'GET',
         url: url,
         data: data,
         datatype: 'json',
         success:function(data){
           $('#result').html(data.html);
           $('#table_style').html(data.html_style);
         }
       });
     }
$(document).ready(function(){
    var title = document.title;
    function changeTitle(number) {
        var number = number
        var newTitle = title;
        if (number > '0') {
          var newTitle = '(' + number + ') ' + title;
        }
        document.title = newTitle;
    }
    var loaddata_table_all = function loaddata_table(){
      $.ajax({
             type:'GET',
             url: url,
             data: data,
             datatype: 'json',
             success:function(data){
               $('#result').html(data.html);
               $('#table_style').html(data.html_style);
               changeTitle(data.number);
               notifityicon(data.datarepair);
               var datacount = '<span class="notification">' +data.number+ '</span>';
               $("#count").html(datacount);
               if (data.newrepair) {
                 $('#startbtn').trigger('click');
                    Swal.fire({
                      icon : 'error',
                      title: '!! มีรายการแจ้งซ่อมใหม่ !!',
                      showCloseButton: false,
                      showCancelButton: false,
                      showconfirmButton: true,
                      confirmButtonText: 'ตกลง',
                    }).then((result) => {
                      if (result.isConfirmed) {
                        $.ajax({
                          type:'GET',
                           url: url_readenotify,
                           data: {STATUS:'1'
                                  ,UNID:data.UNID},
                           datatype: 'json',
                        });
                      }
                    })
               }
             }
           });
         }
    setInterval(loaddata_table_all,30000);
  loaddata_table();
});
function notifityicon(datarepair){
  if (datarepair != null) {
    var _html='';
    $.each(datarepair,function(key,row){
      var url = '/machine/repair/edit/'+row.UNID;
      _html += '<a href="'+url+'">'+
          '<div class="notif-icon notif-danger"> <i class="fa fa-wrench"></i> </div>'+
          '<div class="notif-content">'+
          '<span class="block" >Line :'+row.MACHINE_LINE+' MC: '+row.MACHINE_CODE+ '</span>' +
            '<span class="time">'+row.DOC_DATE+'</span>'+
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
}
