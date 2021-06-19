<?php

use Illuminate\Support\Facades\Route;
//exprotcontroller
use App\Http\Controllers\Export\MachineExportController;
//ImprotController
use App\Http\Controllers\Import\MachineImportController;

//************************* Menu *************************************
use App\Http\Controllers\SettingMenu\MenuController;
use App\Http\Controllers\SettingMenu\MenuSubController;
//************************* Controller *******************************
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\CalendarController;
use App\Http\Controllers\Machine\MachineController;
use App\Http\Controllers\Machine\PersonalController;
use App\Http\Controllers\Machine\MachineRepairController;
use App\Http\Controllers\Machine\PositionEmpController;
use App\Http\Controllers\Machine\MachineManualController;
use App\Http\Controllers\Machine\SysCheckController;
use App\Http\Controllers\Machine\MailConfigController;
use App\Http\Controllers\Machine\DailyCheckController;
use App\Http\Controllers\Machine\MachineSparePartController;
use App\Http\Controllers\Machine\CookieController;
use App\Http\Controllers\Machine\PerMissionController;
//************************* Plan *************************************
use App\Http\Controllers\Plan\MachinePlanController;
use App\Http\Controllers\Plan\Report\PlanYearMachinePm;
use App\Http\Controllers\Plan\Report\PlanMonthMachinePm;
use App\Http\Controllers\Plan\Report\PlanYearMachinePdm;
use App\Http\Controllers\Plan\Report\PlanMonthMachinePdm;
use App\Http\Controllers\Plan\Report\FormPMMachine;
use App\Http\Controllers\Plan\ReportSparePartController;

//************************* add tabel *********************************
use App\Http\Controllers\MachineaddTable\MachineRankTableController;
use App\Http\Controllers\MachineaddTable\MachineTypeTableController;
use App\Http\Controllers\MachineaddTable\MachineRepairTableController;
use App\Http\Controllers\MachineaddTable\MachineStatusTableController;
use App\Http\Controllers\MachineaddTable\MachineSysTemTableController;
use App\Http\Controllers\MachineaddTable\SparPartController;
//************************* Search *********************************
use App\Http\Controllers\Search\RepairSearchController;

//****************************** PDF **********************************
use App\Http\Controllers\PDF\MachinePDFController;
use App\Http\Controllers\PDF\MachineRepairPDFController;
use App\Http\Controllers\PDF\UploadPdfController;
use App\Http\Controllers\PDF\MachineSystemCheckPDFController;
use App\Http\Controllers\PDF\MachineHistoryRepairPDFController;

//Model
use App\Models\Machine\Machine;
use App\Models\SettingMenu\Mainmenu;
use App\Models\SettingMenu\Menusubitem;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/machine/user/homepage');
})->middleware('auth');
//Logout
Route::get('/user/logout/',[MenuController::class,'Logout'])->name('user.logout');
Route::middleware(['auth:sanctum', 'verified']);
//user Page
Route::get('/machine/user/homepage',[MachineController::class,'UserHomePage'])->name('user.homepage');
Route::get('/machine/repair/pdf/{UNID}',        'App\Http\Controllers\PDF\MachineRepairPDFController@RepairPdf');

//Cookie
Route::get('/cookie/set',[CookieController::class,'setCookie'])->name('cookie.set');
Route::get('/cookie/get',[CookieController::class,'getCookie'])->name('cookie.get');
//repair

Route::get('machine/repair/repairlist'             ,[MachineRepairController::class,'Index'])->name('repair.list');
Route::get('machine/repair/fetchdata'             ,[MachineRepairController::class,'FetchData'])->name('repair.fetchdata');

  Route::get('machine/repair/form/{MACHINE_CODE}'  ,[MachineRepairController::class,'Create'])       ->name('repair.form');
  Route::get('machine/repair/repairsearch'         ,[MachineRepairController::class,'PrepareSearch'])->name('repair.repairsearch');

  Route::post('machine/repair/store/{MACHINE_UNID}',[MachineRepairController::class,'Store'])        ->name('repair.store');
  Route::get('machine/repair/edit/{UNID}'          ,[MachineRepairController::class,'Edit'])         ->name('repair.edit');
  Route::post('machine/repair/update/{UNID}'       ,[MachineRepairController::class,'Update'])       ->name('repair.update');
  Route::get('machine/repair/delete/{UNID}'        ,[MachineRepairController::class,'Delete'])       ->name('repair.delete');

  Route::post('machine/repair/select/selectrepairdetail',[MachineRepairController::class,'SelectRepairDetail'])->name('repair.selectrepairdetail');
  Route::get('machine/repair/empcallajax'              ,[MachineRepairController::class,'EMPCallAjax']) ->name('repair.empcallajax');
  Route::post('machine/repair/addtableworker'           ,[MachineRepairController::class,'AddTableWorker']) ->name('repair.addtableworker');
  Route::post('machine/repair/addsparepart'             ,[MachineRepairController::class,'AddSparePart'])->name('repair.addsparepart');

//group not user
Route::middleware('can:isAdminandManager')->group(function () {
//PDF FILE
Route::get('/machine/repairhistory/pdf/{UNID}', 'App\Http\Controllers\PDF\MachineHistoryRepairPDFController@RepairHistory');
Route::get('/machine/systemcheck/pdf/{UNID}',   'App\Http\Controllers\PDF\MachineSystemCheckPDFController@SystemCheckPdf');
  Route::get('/machine/assets/machineall/{LINE?}', [MachinePDFController::class,'MachinePDF']);
//Dashboard
Route::get('/machine/dashboard/sumaryline',[DashboardController::class,'Sumaryline'])->name('dashboard.sumaryline');
  Route::get('/machine/dashboard/dashboard',[DashboardController::class,'Dashboard']);
  Route::get('/machine',[DashboardController::class,'Dashboard']);
  Route::get('/machine/dashboard',[DashboardController::class,'Dashboard'])->name('dashboard.dashboard');
  Route::get('/dashboard',[DashboardController::class,'Dashboard'])->name('dashboard');
// calendar
 Route::get('/machine/calendar',[CalendarController::class,'Index']);
//Notification
Route::get('machine/repair/notificaiton' ,[DashboardController::class,'Notification']);
  Route::get('machine/repair/notificaitoncount' ,[DashboardController::class,'NotificationCount'])  ->name('repair.notificaitoncount');
//Export and import
Route::get('machine/export', [MachineExportController::class,'export']);

//assets
Route::get('machine/assets/machinelist'     ,[MachineController::class,'All'])  ->name('machine.list');
  Route::get('machine/assets/machine'            ,[MachineController::class,'Index'])  ->name('machine');
  Route::get('machine/assets/form'            ,[MachineController::class,'Create']) ->name('machine.form');
  Route::post('machine/assets/store'          ,[MachineController::class,'Store'])  ->name('machine.store');
  Route::get('machine/assets/edit/{UNID}'     ,[MachineController::class,'Edit'])   ->name('machine.edit');
  Route::post('machine/assets/update/{UNID}'  ,[MachineController::class,'Update']);
  Route::get('machine/assets/delete/{UNID}'   ,[MachineController::class,'Delete']) ->name('machine.delete');
//manual
Route::get('machine/manual/manuallist'        ,[MachineManualController::class,'Index'])  ->name('manual.list');
  Route::get('machine/manual/show/{UNID}'     ,[MachineManualController::class,'Show'])   ->name('manual.Show');
  Route::post('machine/upload/storeupload'     ,[MachineManualController::class,'StoreUpload']) ->name('machine.storeupload');
  Route::get('machine/upload/view/{UNID}'      ,[MachineManualController::class,'View']) ->name('upload.view');
  Route::get('machine/upload/download/{UNID}'  ,[MachineManualController::class,'Download']) ->name('upload.download');
  Route::post('machine/upload/update'          ,[MachineManualController::class,'Update']);
  Route::get('machine/upload/delete/{UNID}'    ,[MachineManualController::class,'Delete']) ->name('upload.delete');
//personal
Route::get('machine/personal/personallist'   ,[PersonalController::class,'Index'])  ->name('personal.list');
  Route::get('machine/personal/form'            ,[PersonalController::class,'Create']) ->name('personal.form');
  Route::post('machine/personal/store'          ,[PersonalController::class,'Store'])  ->name('personal.store');
  Route::get('machine/personal/edit/{UNID}'            ,[PersonalController::class,'Edit'])   ->name('personal.edit');
  Route::post('machine/personal/update/{UNID}'  ,[PersonalController::class,'Update']);
  Route::get('machine/personal/delete/{UNID}'   ,[PersonalController::class,'Delete']) ->name('personal.delete');
//daily checksheet
Route::get('machine/daily/list'                     ,[DailyCheckController::class,'DailyList'])  ->name('daily.list');
  Route::post('machine/daily/uploadimg'               ,[DailyCheckController::class,'CheckSheetUpload']) ->name('daily.upload');
  Route::get('machine/daily/deleteimg/{UNID?}'         ,[DailyCheckController::class,'DeleteImg']) ->name('daily.delete');
//***************************** tabledata ****************************************
//machinetypetable
Route::get('machine/machinetypetable/list'      ,[MachineTypeTableController::class,'Index'])  ->name('machinetypetable.list');
  Route::post('machine/machinetypetable/store'            ,[MachineTypeTableController::class,'Store']) ->name('machinetypetable.store');
  Route::get('machine/machinetypetable/form'            ,[MachineTypeTableController::class,'Create']) ->name('machinetypetable.form');
  Route::get('machine/machinetypetable/edit/{UNID}'     ,[MachineTypeTableController::class,'Edit'])   ->name('machinetypetable.edit');
  Route::post('machine/machinetypetable/update/{UNID}'  ,[MachineTypeTableController::class,'Update']);
  Route::post('machine/machinetypetable/changestatus/{UNID}'  ,[MachineTypeTableController::class,'ChangeStatusButton']);
  Route::get('machine/machinetypetable/delete/{UNID}'   ,[MachineTypeTableController::class,'Delete']) ->name('machinetypetable.delete');
//repair
Route::get('machine/repairtemplate/list/{UNID?}'        ,[MachineRepairTableController::class,'Index'])  ->name('repairtemplate.list');
  Route::post('machine/repairtemplate/save'          ,[MachineRepairTableController::class,'Save']) ->name('repairtemplate.save');
  Route::post('machine/repairtemplate/update'          ,[MachineRepairTableController::class,'Update']) ->name('repairtemplate.update');
  Route::post('machine/repairtemplate/delete'          ,[MachineRepairTableController::class,'Delete']) ->name('repairtemplate.delete');
  Route::post('machine/repairtemplate/subsave'          ,[MachineRepairTableController::class,'SubSave']) ->name('repairtemplate.subsave');
  Route::post('machine/repairtemplate/subupdate'          ,[MachineRepairTableController::class,'SubUpdate']) ->name('repairtemplate.subupdate');
  Route::post('machine/repairtemplate/subdelete'          ,[MachineRepairTableController::class,'SubDelete']) ->name('repairtemplate.subdelete');
//status
Route::get('machine/machinestatustable/list'      ,[MachineStatusTableController::class,'Index'])  ->name('machinestatustable.list');
  Route::post('machine/machinestatustable/store'            ,[MachineStatusTableController::class,'Store']) ->name('machinestatustable.store');
  Route::post('machine/machinestatustable/update/{UNID}'  ,[MachineStatusTableController::class,'Update']);
  Route::get('machine/machinestatustable/delete/{UNID}'   ,[MachineStatusTableController::class,'Delete']) ->name('machinestatustable.delete');
//Rank
  Route::get('machine/machinerank/list/{UNID?}'              ,[MachineRankTableController::class,'Index'])  ->name('machinerank.list');
    Route::post('machine/machinerank/store'          ,[MachineRankTableController::class,'Store']) ->name('machinerank.store');
    Route::post('machine/machinerank/update'  ,[MachineRankTableController::class,'Update']);
    Route::get('machine/machinerank/delete/{UNID}'   ,[MachineRankTableController::class,'Delete']) ->name('machinerank.delete');
//PM
Route::get('machine/pm/template/list/{UNID?}'                   ,[MachineSysTemTableController::class,'Index'])                     ->name('pmtemplate.list');
  Route::post('machine/pm/template/store'                       ,[MachineSysTemTableController::class,'StoreTemplate'])             ->name('pmtemplate.store');
  Route::post('machine/pm/template/storelist'                   ,[MachineSysTemTableController::class,'StoreList'])                 ->name('pmtemplate.storelist');
  Route::get('machine/pm/template/add/{UNID}'                   ,[MachineSysTemTableController::class,'PmTemplateAdd'])             ->name('pmtemplate.add');
  Route::get('machine/pm/templatelist/edit/{UNID}'              ,[MachineSysTemTableController::class,'PmTemplateListEdit'])        ->name('pmtemplate.edit');
  Route::post('machine/pm/template/storedetail'                 ,[MachineSysTemTableController::class,'PmTemplateDetailStore'])     ->name('pmtemplatedetail.store');
  Route::post('machine/pm/template/storedetailupdate'           ,[MachineSysTemTableController::class,'PmTemplateDetailUpdate'])    ->name('pmtemplatedetail.update');
  Route::post('machine/pm/template/updatepmtemplate'            ,[MachineSysTemTableController::class,'UpdateTemplate'])            ->name('pmtemplate.update');
  Route::post('machine/pm/template/update/{UNID}'               ,[MachineSysTemTableController::class,'UpdatePMList']);
  Route::get('machine/pm/template/deletepmdetail/{UNID}'        ,[MachineSysTemTableController::class,'DeletePMDetail']);
  Route::get('machine/pm/template/deletepmlist/{UNID}'          ,[MachineSysTemTableController::class,'DeletePMList']);
  Route::get('machine/pm/template/deletepmlistall/{UNID}'       ,[MachineSysTemTableController::class,'DeletePMListAll']);
  Route::get('machine/pm/template/deletepmtemplate/{UNID}'      ,[MachineSysTemTableController::class,'DeleteTemplate']);
  Route::get('machine/pm/template/deletemachinepm/{MC}/{UNID}'  ,[MachineSysTemTableController::class,'DeleteMachinePm']);
//sparepart
Route::get('machine/spart/list/{UNID?}'                      ,[SparPartController::class,'List']) ->name('SparPart.List');
  Route::post('machine/spart/save'                     ,[SparPartController::class,'Save']) ->name('SparPart.Save');
  Route::post('machine/spart/update'                   ,[SparPartController::class,'Update']) ->name('SparPart.Update');
  Route::get('machine/spart/delete'                   ,[SparPartController::class,'Delete']) ->name('SparPart.Delete');
  Route::get('machine/spart/savemachine'                   ,[SparPartController::class,'SaveMachine']) ->name('SparPart.SaveMachine');
  Route::get('machine/spart/deletemachine/{MACHINE_UNID?}/{SPAREPART_UNID?}' ,[SparPartController::class,'DeleteMachine']) ->name('SparPart.DeleteMachine');
  Route::get('machine/spart/machine/{UNID?}'                   ,[SparPartController::class,'GetMachineList']) ->name('SparPart.GetMachineList');
//sparepart report
Route::get('machine/spart/report'                            ,[ReportSparePartController::class,'Index']) ->name('SparPart.Report.Index');
  Route::post('machine/spart/report/planmonth'                 ,[ReportSparePartController::class,'Index']) ->name('SparPart.Report.planmonth');
  Route::get('machine/spart/report/planmonth/print'                 ,[ReportSparePartController::class,'PlanMonthPrint']) ->name('SparPart.Report.planmonthprint');
  Route::get('machine/spart/report/planmonth/form'                 ,[ReportSparePartController::class,'Form']) ->name('SparPart.Report.Form');
  Route::get('machine/spart/report/planmonth/save'                 ,[ReportSparePartController::class,'Save']) ->name('SparPart.Report.Save');
  Route::get('machine/spart/reportplanmonth/change'                 ,[ReportSparePartController::class,'PlanChange']) ->name('SparPart.Report.PlanChange');
  Route::get('machine/spart/report/planmonth/formimg'                 ,[ReportSparePartController::class,'FormImg']) ->name('SparPart.Report.FormImg');
  Route::post('machine/spart/planmonth/saveimg'                 ,[ReportSparePartController::class,'SaveImg']) ->name('SparPart.Report.SaveImg');
  Route::post('machine/spart/planmonth/deleteimg'                 ,[ReportSparePartController::class,'DeleteImg']) ->name('SparPart.Report.DeleteImg');
  Route::get('machine/spart/report/planpdm/list'                 ,[ReportSparePartController::class,'PlanPDMList']) ->name('SparPart.Report.PlanPDMList');
//machine sparepart
Route::get('machine/machinespart/getlistsparepart/{UNID}'     ,[MachineSparePartController::class,'GetListSparepart']) ->name('MachineSparPart.GetListSparepart');
Route::get('machine/machinespart/save'                        ,[MachineSparePartController::class,'Save']) ->name('MachineSparPart.Save');
Route::post('machine/machinespart/update'                     ,[MachineSparePartController::class,'Update']) ->name('MachineSparPart.Update');
Route::get('machine/machinespart/delete'                      ,[MachineSparePartController::class,'Delete']) ->name('MachineSparPart.Delete');
Route::get('machine/machinespart/statusopen'                  ,[MachineSparePartController::class,'StatusOpen']) ->name('MachineSparPart.StatusOpen');
//postion emp
route::get('machine/position/positionlist/{POSITION_CODE?}'  ,[PositionEmpController::class,'List']) ->name('position.list');
route::post('machine/position/save'                          ,[PositionEmpController::class,'Save']) ->name('position.save');
route::post('machine/position/update'                        ,[PositionEmpController::class,'Update']) ->name('position.update');
route::get('machine/position/delete'                        ,[PositionEmpController::class,'Delete']) ->name('position.delete');
  //***************************** PlanPm ****************************************
Route::get('machine/plan/planpm'                             ,[MachinePlanController::class,'PMPlanPrint']) ->name('plan.pm');
Route::post('machine/plan/planpmpdf'                         ,[MachinePlanController::class,'PdfPlanPm']) ->name('plan.pmpdf');
Route::get('machine/pdf/plan/planpm/{YEAR}'                  ,[PlanYearMachinePm::class,'PlanYearPDF']) ->name('plan.yearpdf');
Route::get('machine/pdf/plan/planpmmonth/{YEAR}/{MONTH?}'    ,[PlanMonthMachinePm::class,'PlanMonthPDF']) ->name('plan.monthpdf');
Route::get('machine/pm/planlist'                             ,[MachinePlanController::class,'PMPlanList'])  ->name('pm.planlist');
Route::post('machine/pm/planlist'                            ,[MachinePlanController::class,'PMPlanList']);
Route::get('machine/pm/plancheck/{UNID}'                     ,[MachinePlanController::class,'PMPlanCheckForm']) ->name('pm.plancheck');
Route::get('machine/pm/planedit/{UNID}'                      ,[MachinePlanController::class,'PMPlanEditForm']) ->name('pm.planedit');
Route::post('machine/pm/planlist/save'                       ,[MachinePlanController::class,'PMPlanListSave']) ->name('pm.planlistsave');
Route::post('machine/pm/planedit/update'                     ,[MachinePlanController::class,'PMPlanListUpdate']) ->name('pm.planlistupdate');
Route::post('machine/pm/planlist/upload'                     ,[MachinePlanController::class,'PMPlanListUpload']) ->name('pm.planlistupload');
Route::post('machine/pm/planlist/deleteimg'                  ,[MachinePlanController::class,'DeleteImg']) ->name('pm.deleteimg');
Route::get('machine/pm/planlist/print/{UNID}'                ,[FormPMMachine::class,'PDFForm']) ->name('pm.pdfform');

//ในedit machine
  Route::post('machine/system/check/storelist'          ,[SysCheckController::class,'StoreList'])   ->name('syscheck.storelist');
  Route::get('machine/system/remove/{UNID}/{MC}'        ,[SysCheckController::class,'DeletePMMachine'])   ->name('syscheck.remove');
  Route::post('machine/system/check/storedate'          ,[SysCheckController::class,'StoreDate']);
});
  //***************************** SETTING ****************************************
//config
  Route::get('machine/config/home'                  ,[MailConfigController::class,'Index'])->name('machine.config');
  Route::post('machine/config/save'                  ,[MailConfigController::class,'Save'])->name('machine.save');
  Route::post('machine/config/savealert'                  ,[MailConfigController::class,'SaveAlert'])->name('machine.savealert');
  Route::post('machine/config/update'                  ,[MailConfigController::class,'Update'])->name('machine.update');
//MenuController
Route::get('machine/setting/menu/home'              ,[MenuController::class,'Home'])   ->name('menu.home');
  Route::post('machine/setting/menu/add'              ,[MenuController::class,'AddMenu'])->name('menu.store');
  Route::get('machine/setting/menu/edit/{UNID}'       ,[MenuController::class,'Edit']);
  Route::post('machine/setting/menu/update/{UNID}'    ,[MenuController::class,'Update']);
  Route::get('machine/setting/menu/delete/{UNID}'     ,[MenuController::class,'Delete']);
//submenucontroller
Route::get('machine/setting/submenu/home/{UNID}'    ,[MenuSubController::class,'subhome'])->name('submenu.home');
  Route::post('machine/setting/submenu/add'           ,[MenuSubController::class,'AddMenu'])->name('submenu.store');
  Route::get('machine/setting/submenu/edit/{UNID}'    ,[MenuSubController::class,'Edit']);
  Route::post('machine/setting/submenu/update/{UNID}' ,[MenuSubController::class,'Update']);
  Route::get('machine/setting/submenu/delete/{UNID}'   ,[MenuSubController::class,'Delete']);
// admin config permisssion
Route::middleware('can:isAdmin')->group(function () {
    Route::get('machine/config/permission'                ,[PerMissionController::class,'Home'])->name('permission.home');
    Route::post('machine/config/permission/store'           ,[PerMissionController::class,'Store'])->name('permission.store');
    Route::post('machine/config/permission/update'        ,[PerMissionController::class,'Update'])->name('permission.update');
    Route::get('machine/config/permission/confirm'        ,[PerMissionController::class,'Confirm'])->name('permission.confirm');
    Route::get('machine/config/permission/delete'        ,[PerMissionController::class,'Delete'])->name('permission.delete');
});
