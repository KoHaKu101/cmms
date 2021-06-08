var button = document.getElementById('buttonprint');
var line = $('#MACHINE_LINE').val();
button.addEventListener('click', function(){
  window.open('/machine/assets/machineall?LINE='+line,'Repairprint','width=1000,height=1000,resizable=yes,top=100,left=100,menubar=yes,toolbar=yes,scroll=yes');
});
function printhistory(u){
  console.log(u);
  var unid = (u);
  window.open('/machine/repairhistory/pdf/'+unid,'RepairHistory','width=1000,height=1000,resizable=yes,top=100,left=100,menubar=yes,toolbar=yes,scroll=yes');
}
