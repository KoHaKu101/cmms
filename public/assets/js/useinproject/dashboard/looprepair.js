$('#startbtn').on('click',function(){
const  music = document.getElementById("music");
music.play();
});

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
             url: urldashboard,
             datatype: 'json',
             success:function(data){
               changeTitle('0');
               $('#NEW_REPAIR').html(data.html)
               if (data.newrepair) {
                 changeTitle(data.number);
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
                           url: urlnotify,
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
      setInterval(loaddata_table_all,10000);
});
