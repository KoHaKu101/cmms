var handlefunction = function() {
  $('input[type="file"]').on("change", function() {
    let filenames = [];
    let files = this.files;
    if (files.length > 1) {
      filenames.push("Total Files (" + files.length + ")");
    } else {
      for (let i in files) {
        if (files.hasOwnProperty(i)) {
          filenames.push(files[i].name);
        }
      }
    }
    $(this)
      .next(".custom-file-label")
      .html(filenames.join(","));
  });

  $('#FILE_NAME').change(function(){
   let file_img = new FileReader();
   file_img.onload = (file_location) => {
     file_img.src = file_location.target.result;

     $('#preview-image-before-upload').attr('src', file_img.src,'style="width: 80%;height:400px"');
   }
   file_img.readAsDataURL(this.files[0]);
  });


};



var FormPlugins = function() {
  "use strict";
  return{
    init: function (){
      handlefunction();
    }
  };
}();

  $(document).ready(function() {
    FormPlugins.init();

  });

  function uploadimg(data_img){
    var machine_unid = $(data_img).data('mcunid');
    var machine_code = $(data_img).data('mccode');
    var machine_line = $('#MACHINE_LINE').val();
    var search_machine = $('#SEARCH_MACHINE').val();
    var year				= $('#YEAR').val();
    var month				= $('#MONTH').val();
    $('#MACHINE_UNID').val(machine_unid);
    $('#CHECK_YEAR').val(year);
    $('#CHECK_MONTH').val(month);
    $('#MACHINE_CODE').val(machine_code);
    $('#MACHINE_LINE').val(machine_line);
    $('#SEARCH_MACHINE').val(search_machine);
    $('#title_text').html('MACHINE NO: ' +machine_code);
    if (machine_unid != '') {
      $('#UploadImg').modal('show');
    }
  };

  function viewimg(data_img){
    var checksheet_img = $(data_img).data('img');
    var machine_code = $(data_img).data('mccode');
    var year				= $('#YEAR').val();
    var month				= $('#MONTH').val();
    var path        = "../../image/checksheet/"+year+"/"+month+"/"+checksheet_img;


    $('#view_img').attr('src', path,'style="width: 80%;height:400px"');
    $('#title_view').html('MACHINE NO: ' +machine_code+' Daily CheckSheet :'+year+'-'+month);

    if (checksheet_img != '') {
      $('#ViewImg').modal('show');
    }

  };

  function deleteimg(data_img){
    var img_unid = $(data_img).data('imgunid');
    var machine_code = $(data_img).data('mccode');
    var year				= $('#YEAR').val();
    var month				= $('#MONTH').val();
    Swal.fire({
        title: 'คุณต้องการลบภาพเครื่อง'+machine_code+'มั้ย?',
        text: 'หากลบแล้วจะไม่สามารถกู้คืนมาได้!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes!'
    }).then(function(result) {
      if (result.isConfirmed) {
        $.ajax({
          type: "GET",
          url: '/machine/daily/deleteimg',
          data: { UNID : img_unid} ,
          dataType: 'JSON',
          success: function (data_result) {
            if (data_result.status == 'pass') {
              Swal.fire({
                  title: 'ลบสำเร็จ',
                  icon: 'success',
                  confirmButtonColor: '#3085d6',
                  confirmButtonText: 'Confirm'
              });
              $(document).ajaxStop(function(){
                window.location.reload();
              });
            }else if (data_result.status == 'fail') {
              Swal.fire({
                  title: 'เกิดข้อผิดพลาด',
                  text: 'ไม่สามารถลบได้!',
                  icon: 'error',
                  confirmButtonColor: '#3085d6',
                  confirmButtonText: 'Confirm'
              })
            }


          },



        });
            };
        });
      }

  $('#MACHINE_LINE').on('change',function(){
    $("#FRM_CHECKSHEET").submit();
  })
  $('#YEAR').on('change',function(){
    $("#FRM_CHECKSHEET").submit();
  })
  $('#MONTH').on('change',function(){
    $("#FRM_CHECKSHEET").submit();
  })
