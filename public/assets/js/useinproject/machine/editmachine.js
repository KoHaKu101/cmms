//**********function***********
// ปริ้นประวัติการซ่อม
function printhistory(u){
  var unid = (u);
  window.open('/machine/repairhistory/pdf/'+unid,'RepairHistory','width=1000,height=1000,resizable=yes,top=100,left=100,menubar=yes,toolbar=yes,scroll=yes');
};
function savemachine(sparepart_unid,machine_unid,machine_code,period,datestart,sparepart_qty){
  var url = "/machine/machinespart/save";
  var data = {SPARTPART_UNID : sparepart_unid,
              PERIOD : period,
              DATESTART : datestart,
              MACHINE_UNID : machine_unid,
              MACHINE_CODE : machine_code,
              SPAREPART_QTY : sparepart_qty,};
  $.ajax({
    type: "GET",
    url: url,
    data: data,
    success: function (data) {
      if (data.res == false) {
      Swal.fire({
        title: 'กรุณาระบุระยะเวลา แผน',
        text: "ใน ตั้งค่า -> CMMS",
        icon: 'error',
        showCancelButton: false,
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK!'
      }).then((result) => {
        if (result.isConfirmed) {
          location.reload();
            }
        });
      }
    }
  });
};
function addmachinetosparepart(unid){
   var unid = unid;
   var check = $('#SPAREPART_UNID'+unid)[0].checked;

   var sparepart_unid = unid;
   var period = $("#PERIOD_"+unid).val();
   var datestart = $("#DATESTART_"+unid).val();
   var sparepart_qty = $("#SPAREPART_QTY_"+unid).val();
   var machine_unid = $("#MACHINE_UNID").val();
   var machine_code = $("#MACHINE_CODE").val();
   if (check) {
     if (sparepart_qty == 0 || sparepart_qty < 0 ) {
       $("#SPAREPART_QTY_"+unid).val(1);
       sparepart_qty = 1 ;
     }
     savemachine(sparepart_unid,machine_unid,machine_code,period,datestart,sparepart_qty);
   }

};
// edit machine sparepart
function editsparepart(thisdata){
  var sparepartcode = $(thisdata).data('sparepartcode');
  var sparepartqty = $(thisdata).data('sparepartqty');
  var sparepartperiod = $(thisdata).data('sparepartperiod');
  var sparepartstatus = $(thisdata).data('sparepartstatus');
  var sparepartremark = $(thisdata).data('sparepartremark');
  var checkstatus =  sparepartstatus == '9' ? true : false ;
  var sparepartunid = $(thisdata).data('sparepartunid');
  var sparepartlastchange = $(thisdata).data('sparepartlastchange');
  var plan_date =  sparepartlastchange != '1900-01-01' ? sparepartlastchange : '';
  $('#SPAREPART_QTY').val(sparepartqty);
  $('#PERIOD').val(sparepartperiod);
  $('#STATUS').prop('checked',checkstatus);
  $('#REMARK').val(sparepartremark);
  $('#MACHINESPAREPART_UNID').val(sparepartunid);
  $('#PLAN_DATE').val(plan_date)
  if (sparepartcode != '') {
    $('#tital_name').html('SparPart Code : '+sparepartcode);
    $('#modal-machinesparepart').modal('show');
  }
};
//delete sparepart
function deletesparepart(thisdata){
  var machine_unid = $('#MACHINE_UNID').val();
  var sparepart_name = $(thisdata).data('sparepart_name');
  var sparepart_unid = $(thisdata).data('sparepart_unid');
  console.log(machine_unid+'|'+sparepart_name+'|'+sparepart_unid);
  var url = '/machine/machinespart/delete';
  var data = { MACHINE_UNID : machine_unid,
               SPAREPART_UNID : sparepart_unid,
  }
  Swal.fire({
    title: 'คุณต้องการลบ sparepart',
    text: sparepart_name+' มั้ย',
    showCancelButton: true,
    confirmButtonText: 'ใช่',
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        type: 'GET',
        url: url,
        data: data,
        success:function(data){
          if (data.res) {
            Swal.fire({
              icon: 'success',
              title: 'ลบรายการสำเร็จ',
              showConfirmButton: true,
              timer: 1500
            }).then(() => {
              location.reload();
            });
          }else {
            Swal.fire({
              icon: 'error',
              title: 'เกิดข้อผิดพลาด',
              text: 'ไม่พบข้อมูล',
              showConfirmButton: true,
              timer: 1500
            });
          }

        }
      });
    }
  });
};

  function openstatus(thisdata){
   var machine_unid = $('#MACHINE_UNID').val();
   var sparepart_unid = $(thisdata).data('sparepart_unid');
   var url = '/machine/machinespart/statusopen';
   var data = {MACHINE_UNID : machine_unid,
               SPAREPART_UNID : sparepart_unid};
   Swal.fire({
    title: 'ต้องการเปิดการใช้งานอะไหล่ ?',
    showDenyButton: true,
    showCancelButton: false,
    confirmButtonText: `ใช่`,
    denyButtonText: `ไม่`,
  }).then((result) => {
    /* Read more about isConfirmed, isDenied below */
    if (result.isConfirmed) {
      $.ajax({
        type: 'GET',
        url: url,
        data: data,
        success:function(data){
          if (data.res) {
            Swal.fire({
              icon: 'success',
              title: 'เปิดรายการสำเร็จ',
              showConfirmButton: true,
              timer: 1500
            }).then(() => {
              location.reload();
            });
          }else {
            Swal.fire({
              icon: 'error',
              title: 'เกิดข้อผิดพลาด',
              text: 'ไม่พบข้อมูล',
              showConfirmButton: true,
              timer: 1500
            });
          }
        }
    })
  };

})
};
//*****************************

$(document).ready(function() {
$('.pmlistdetail').addClass('hide');
  unidpmtemplate = $('#count').val();
//รายละเอียด
  $(".detail").each(function(){
    $(this).click(function(){
      id = $(this).attr('id');
      console.log(unidpmtemplate);
      $('.pmlistdetail').addClass('hide');
      $("#"+id+"-1").removeClass("hide");
;
      return false;
    });
  });

// เซฟวันที่และแสดงผล
$(document).on('change','.changedate',function(){

  var unid = $(this).data('dataunidpmlist');
  var lastdate = $('#PM_LAST_DATE_'+unid).val();
  var rank = $('#MACHINE_RANK_MONTH').val();
  if (rank) {
    var nextdate = moment(lastdate).add(rank,'M').format("DD/MM/YYYY");
    $('#PM_NEXT_DATE_'+unid).val(nextdate);
  }else {
    Swal.fire({
        title: 'กรุณาระบุ Rank',
        icon: 'warning',
        confirmButtonColor: '#3085d6',
        timer: 2000,
    });
  }
  });
$(document).on('click','#savedate',function(event){
  event.preventDefault();
  var pmmaster_template_unid         = $(this).data('dataunidpmlist');
  var pmmaster_template_lastdate     = $('#PM_LAST_DATE_'+pmmaster_template_unid).val();
  var machine_rank_month                   = $('#MACHINE_RANK_MONTH').val();
  var machine_unid                   = $('#MACHINE_UNID').val();
  if (machine_rank_month) {
  $.ajax({
    type:'POST',
    url: '/machine/system/check/storedate',
    datatype: 'json',
    data: {
      "_token": "{{ csrf_token() }}",
      "machine_unid"                : machine_unid,
      "pmmaster_template_unid"      : pmmaster_template_unid,
      "pmmaster_template_lastdate"  : pmmaster_template_lastdate,
      "machine_rank_month"          : machine_rank_month,
    } ,
    success:function(data){
      Swal.fire({
          title: 'บันทึกระยะเวลาสำเร็จ',
          icon: 'success',
          confirmButtonColor: '#3085d6',
          timer: 2000,
      });
    }
  });
  }else {
    Swal.fire({
        title: 'กรุณาระบุ Rank',
        icon: 'warning',
        confirmButtonColor: '#3085d6',
    });
  }
});
//ยืนยันการลบ PM Template
$(document).on('click','.delete-confirm', function (event) {
      Swal.fire({
          title: 'คุณต้องการลบข้อมูลหรือไม่?',
          text: 'หากลบข้อมูลแล้วจะไม่สามารถกู้คืนมาได้!',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes!'
      }).then(function(result) {
        if (result.isConfirmed) {
          var pmmaster_template_unid = [];
          $('#MACHINE_UNID').each(function(){

              var machineunid = $(this).val();

              $('.PM_TEMPLATE_UNID_REFREMOVE:checked').each(function(){
                  pmmaster_template_unid.push($(this).val());

                  window.location.href = '/machine/system/remove/'+pmmaster_template_unid+'/'+machineunid;
              });
          });
        }
      });
  });



//storetabe
  $(".tabselect").each(function(){
    $(this).click(function(){
      id = $(this).attr('id');
      url = window.location.href;
      localStorage.selectedTab = id;
      localStorage.url = url;
      $(".tabselect").removeClass("active show");
      $(this).addClass("active show");
      $('.tab-pane').removeClass("active");
      $("#"+id+"-1").addClass("active")
      return false;
    });
  });
  if (window.location.href == localStorage.url) {
    if (localStorage.selectedTab) {
      $('#tabLink').find("#"+(localStorage.selectedTab)+"").click();
      }
  }else {
    $("#home").addClass("active show");
    $("#home-1").addClass("active")
  };


// add sparepart
  $('.btn-addsparepart').on('click',function(){
      var machine_unid = $('#MACHINE_UNID').val();
    var url = "/machine/machinespart/getlistsparepart/"+machine_unid;
    $.ajax({
      type: "GET",
      url: url,
      success: function (data) {
        $('.data-machine').html(data.res);
        var table =	$('#machine_list').DataTable({
            "pageLength": 10,
            "bLengthChange": false,
            "bFilter": true,
            "bInfo": false,
            "bAutoWidth": false,
            columnDefs: [
            { orderable: false, targets:[0,1,2,3,4] }
          ]
          });
        $('#modal-addsparepart').modal('show');
        $("#machine_list").on("click", "input[type='checkbox']", function(){
          var tr = $(this)[0].closest("tr");
          var checkbox_val = $('#SPAREPART_UNID'+tr.id)[0].checked;
          var sparepart_unid = tr.id;
          var period = $("#PERIOD_"+tr.id).val();
          var datestart = $("#DATESTART_"+tr.id).val();
          var sparepart_qty = $("#SPAREPART_QTY_"+tr.id).val();
          var machine_unid = $("#MACHINE_UNID").val();
          var machine_code = $("#MACHINE_CODE").val();
          $('#SPAREPART_UNID'+sparepart_unid).attr('disabled',true);
          if (checkbox_val) {
            if (sparepart_qty == 0 || sparepart_qty < 0 ) {
             $("#SPAREPART_QTY_"+sparepart_unid).val(1);
             sparepart_qty = 1 ;
           }
           if (period != '') {
             savemachine(sparepart_unid,machine_unid,machine_code,period,datestart,sparepart_qty);
           }else {
             Swal.fire({
              icon: 'error',
              title: 'กรุณาระยุวาระการเปลี่ยน',
              timer: 1500,
            })
           }

          }
        });
      }
      });
  });
// close modal reload page
  $('#modal-addsparepart').on('hidden.bs.modal', function(){
    location.reload();
  });
// delete machine sparepart


});
