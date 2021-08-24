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
use App\Http\Controllers\Machine\MachineManualController;
use App\Http\Controllers\Machine\SysCheckController;
use App\Http\Controllers\Machine\MailConfigController;
use App\Http\Controllers\Machine\DailyCheckController;

use App\Http\Controllers\Machine\CookieController;
use App\Http\Controllers\Machine\PerMissionController;
//************************* History ************************************
use App\Http\Controllers\Machine\HistoryController;
//************************* Repair *************************************
use App\Http\Controllers\Machine\PDRepairController;
use App\Http\Controllers\Machine\MachineRepairController;
use App\Http\Controllers\Machine\RepairCloseFormController;

//************************* Plan *************************************
use App\Http\Controllers\Plan\MachinePlanController;
use App\Http\Controllers\Plan\Report\PlanYearMachinePm;
use App\Http\Controllers\Plan\Report\PlanMonthMachinePm;
use App\Http\Controllers\Plan\Report\PlanYearMachinePdm;
use App\Http\Controllers\Plan\Report\PlanMonthMachinePdm;
use App\Http\Controllers\Plan\Report\FormPMMachine;
//************************* sparepart *********************************
use App\Http\Controllers\Plan\ReportSparePartController;
use App\Http\Controllers\Machine\SparepartController;
use App\Http\Controllers\Machine\MachineSparePartController;

//************************* add tabel *********************************
use App\Http\Controllers\MachineaddTable\MachineRankTableController;
use App\Http\Controllers\MachineaddTable\MachineTypeTableController;
use App\Http\Controllers\MachineaddTable\MachineRepairTableController;
use App\Http\Controllers\MachineaddTable\MachineStatusTableController;
use App\Http\Controllers\MachineaddTable\MachineSysTemTableController;
use App\Http\Controllers\MachineaddTable\SparPartController;
use App\Http\Controllers\MachineaddTable\CompanyController;
//****************************** PDF **********************************
use App\Http\Controllers\PDF\MachinePDFController;
use App\Http\Controllers\PDF\MachineRepairPDFController;
use App\Http\Controllers\PDF\MachineSystemCheckPDFController;
use App\Http\Controllers\PDF\MachineHistoryRepairPDFController;
//******************************* PR ***********************************
use App\Http\Controllers\Machine\PRController;
use App\Http\Controllers\Machine\ReportPRController;

//Model
// use App\Models\Machine\Machine;
// use App\Models\SettingMenu\Mainmenu;
// use App\Models\SettingMenu\Menusubitem;




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
if (Gate::allows('isManager_Ma')) {
  Route::get('/machine/user/homepage', function () {
      return redirect('/machine/user/homepage');
  })->middleware('auth');
}elseif (Gate::allows('isManager_Pd')) {
  Route::get('/machine/user/homepage', function () {
      return redirect('/machine/user/homepage');
  })->middleware('auth');
}else {
  Route::get('/', function () {
      return redirect('/dashboard');
  })->middleware('auth');
}

//Logout
Route::get('/user/logout/',[MenuController::class,'Logout'])->name('user.logout');
Route::middleware(['auth:sanctum', 'verified']);
//user Page
Route::get('/machine/user/homepage',     [MachineController::class,'UserHomePage'])->name('user.homepage');
Route::get('/machine/repair/pdf/{UNID}',        'App\Http\Controllers\PDF\MachineRepairPDFController@RepairPdf');
Route::get('/machine/repair/savepdf/{UNID}',    'App\Http\Controllers\PDF\RepairSaveFormPDFController@RepairSaveForm');

Route::get('/machine/dashboard/dashboard'   ,[DashboardController::class,'Dashboard']);
Route::get('/machine'                       ,[DashboardController::class,'Dashboard']);
Route::get('/machine/dashboard'             ,[DashboardController::class,'Dashboard'])->name('dashboard.dashboard');
Route::get('/dashboard'                     ,[DashboardController::class,'Dashboard'])->name('dashboard');
Route::get('/dashboard/pm'                  ,[DashboardController::class,'PM'])       ->name('dashboard.pm');
Route::get('/dashboard/tablepm'             ,[DashboardController::class,'TablePM'])  ->name('dashboard.tablepm');
Route::get('/dashboard/tablepdm'            ,[DashboardController::class,'TablePDM']) ->name('dashboard.tablepdm');
Route::get('/dashboard/dowmtime'            ,[DashboardController::class,'Dowmtime']) ->name('dashboard.dowmtime');
//Cookie
Route::post('/cookie/set',[CookieController::class,'setCookie'])->name('cookie.set');
Route::get('/cookie/get' ,[CookieController::class,'getCookie'])->name('cookie.get');
//repair for pd
Route::get('machine/pd/repairlist'          ,[PDRepairController::class,'Index'])        ->name('pd.repairlist');
Route::get('machine/pd/fetchdata'           ,[PDRepairController::class,'FetchData'])    ->name('pd.fetchdata');
Route::post('machine/pd/result'             ,[PDRepairController::class,'ShowResult'])   ->name('pd.result');
Route::post('machine/pd/confirm'            ,[PDRepairController::class,'ConFirm'])   ->name('pd.confirm');
Route::get('machine/repair/renewconfirm'   ,[PDRepairController::class,'RenewConfirm'])     ->name('pd.renewconfirm');
Route::post('machine/repair/renew'          ,[PDRepairController::class,'Renew'])     ->name('pd.renew');
//repair
Route::get('machine/repair/repairlist'               ,[MachineRepairController::class,'Index'])         ->name('repair.list');
Route::get('machine/repair/fetchdata'                ,[MachineRepairController::class,'FetchData'])     ->name('repair.fetchdata');

  Route::get('machine/repair/form/{MACHINE_CODE}'    ,[MachineRepairController::class,'Create'])        ->name('repair.form');
  Route::get('machine/repair/repairsearch'           ,[MachineRepairController::class,'PrepareSearch']) ->name('repair.repairsearch');

  Route::post('machine/repair/store/{MACHINE_UNID}'  ,[MachineRepairController::class,'Store'])  ->name('repair.store');
  Route::get('machine/repair/edit/{UNID}'            ,[MachineRepairController::class,'Edit'])   ->name('repair.edit');
  Route::post('machine/repair/update/{UNID}'         ,[MachineRepairController::class,'Update']) ->name('repair.update');
  Route::get('machine/repair/delete'                 ,[MachineRepairController::class,'Delete']) ->name('repair.delete');

  Route::get('machine/repair/readnotify/ma'          ,[MachineRepairController::class,'ReadNotify'])  ->name('repair.readnotify');
  Route::get('machine/repair/readnotify/pd'          ,[PDRepairController::class,'ReadNotify'])       ->name('repair.readnotify.pd');

  Route::post('machine/repair/select/selectrepairdetail'  ,[RepairCloseFormController::class,'SelectRepairDetail']) ->name('repair.selectrepairdetail');
  Route::get('machine/repair/empcallajax'                 ,[RepairCloseFormController::class,'EMPCallAjax'])       ->name('repair.empcallajax');
  Route::post('machine/repair/addtableworker'             ,[RepairCloseFormController::class,'AddTableWorker'])    ->name('repair.addtableworker');
  Route::post('machine/repair/addsparepart'               ,[RepairCloseFormController::class,'AddSparePart'])      ->name('repair.addsparepart');
  Route::post('machine/repair/savestep'                   ,[RepairCloseFormController::class,'SaveStep'])          ->name('repair.savestep');
  Route::post('machine/repair/result'                     ,[RepairCloseFormController::class,'Result'])          ->name('repair.result');
  Route::post('machine/repair/closeform'                  ,[RepairCloseFormController::class,'CloseForm'])          ->name('repair.closeform');


  Route::get('machine/history/repairlist'        ,[HistoryController::class,'RepairList'])->name('history.repairlist');
  Route::get('machine/history/repairpdf/{UNID?}' ,[HistoryController::class,'RepairPDF'])->name('history.repairpdf');
  Route::get('machine/history/planpdf/{UNID?}'   ,[HistoryController::class,'PlanPDF'])->name('history.planpdf');

//group not user
Route::middleware('can:isAdminandManager')->group(function () {
//PDF FILE
Route::get('/machine/repairhistory/pdf/{UNID}', 'App\Http\Controllers\PDF\MachineHistoryRepairPDFController@RepairHistory');
Route::get('/machine/systemcheck/pdf/{UNID}',   'App\Http\Controllers\PDF\MachineSystemCheckPDFController@SystemCheckPdf');
  Route::get('/machine/assets/machineall/{LINE?}',[MachinePDFController::class,'MachinePDF']);
//Dashboard
Route::get('/machine/dashboard/sumaryline'          ,[DashboardController::class,'Sumaryline'])->name('dashboard.sumaryline');
Route::get('/machine/dashboard/notificationrepair'  ,[DashboardController::class,'NotificationRepair'])->name('dashboard.notificationrepair');

// calendar
 Route::get('/machine/calendar'         ,[CalendarController::class,'Index']);
 Route::get('/machine/calendar/modal'   ,[CalendarController::class,'ShowModal']);
//Document Out item
Route::get('/machine/pr/itemout'        ,[PRController::class,'ItemoutList'])->name('pr.itemout');

  Route::get('/machine/pr/openmodal'    ,[PRController::class,'OpenModal'])->name('pr.openmodal');
  Route::get('/machine/pr/typeselect'   ,[PRController::class,'TypeSelect'])->name('pr.typeselect');
  Route::get('/machine/pr/detail'       ,[PRController::class,'Detail'])->name('pr.detail');

  Route::get('/machine/pr/savestep1'    ,[PRController::class,'SaveStep1'])->name('pr.savestep1');
  Route::get('/machine/pr/savestep2'    ,[PRController::class,'SaveStep2'])->name('pr.savestep2');
  Route::get('/machine/pr/showresult'   ,[PRController::class,'ShowResult'])->name('pr.showresult');
  Route::get('/machine/pr/saveresult'   ,[PRController::class,'SaveResult'])->name('pr.saveresult');

  Route::get('/machine/pr/saverec'     ,[PRController::class,'SaveRec'])->name('pr.saverec');

  Route::get('/machine/pr/deletedetail' ,[PRController::class,'DeleteDetail'])->name('pr.deletedetail');
  Route::get('/machine/pr/canceldoc'    ,[PRController::class,'CancelDoc'])->name('pr.canceldoc');
  Route::get('/machine/pr/printdoc'     ,[ReportPRController::class,'PrintDoc'])->name('pr.printdoc');
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
  Route::get('machine/daily/view/{UNID}'            ,[DailyCheckController::class,'View']) ->name('daily.view');
//SparePart
Route::get('machine/sparepart/stock'              ,[SparepartController::class,'StockList']) ->name('sparepart.stock');
  Route::get('machine/sparepart/rec'              ,[SparepartController::class,'RecSparepartList']) ->name('sparepart.rec');
  Route::get('machine/sparepart/alert'            ,[SparepartController::class,'AlertSparepartList']) ->name('sparepart.alert');
  Route::post('machine/sparepart/recsave'         ,[SparepartController::class,'RecSparepartSave']) ->name('sparepart.recsave');
  Route::get('machine/sparepart/recdelete'        ,[SparepartController::class,'RecSparepartDelete']) ->name('sparepart.recdelete');
  Route::get('machine/sparepart/history/pdf'      ,[SparepartController::class,'HistoryPDF']) ->name('spareparthistory.pdf');
//***************************** tabledata ****************************************
//company
Route::get('machine/company/list',      [CompanyController::class,'list'])->name('company.list');
  Route::post('machine/company/save',   [CompanyController::class,'Save'])->name('company.save');
  Route::post('machine/company/update', [CompanyController::class,'Update'])->name('company.update');
  Route::get('machine/company/delete',  [CompanyController::class,'Delete'])->name('company.delete');
  Route::post('machin/company/switch',  [CompanyController::class,'SwitchStatus'])->name('company.switch');

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
  //***************************** PlanPm ****************************************
Route::get('machine/plan/planpm'                             ,[MachinePlanController::class,'PMPlanPrint']) ->name('plan.pm');
Route::post('machine/plan/planpmpdf'                         ,[MachinePlanController::class,'PdfPlanPm']) ->name('plan.pmpdf');
Route::get('machine/pdf/plan/planpm/{YEAR}'                  ,[PlanYearMachinePm::class,'PlanYearPDF']) ->name('plan.yearpdf');
Route::get('machine/pdf/plan/planpmmonth/{YEAR}/{MONTH?}'    ,[PlanMonthMachinePm::class,'PlanMonthPDF']) ->name('plan.monthpdf');
Route::get('machine/pm/planlist'                             ,[MachinePlanController::class,'PMPlanList'])  ->name('pm.planlist');
Route::post('machine/pm/planlist'                            ,[MachinePlanController::class,'PMPlanList']);
Route::get('machine/pm/plancheck/{UNID}'                     ,[MachinePlanController::class,'PMPlanCheckForm']) ->name('pm.plancheck');
Route::post('machine/pm/change/sparepart'                     ,[MachinePlanController::class,'SparePart']) ->name('pm.sparepart');
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

    Route::get('machine/config/permission'                ,[PerMissionController::class,'Home'])->name('permission.home');
    Route::post('machine/config/permission/store'           ,[PerMissionController::class,'Store'])->name('permission.store');
    Route::post('machine/config/permission/update'        ,[PerMissionController::class,'Update'])->name('permission.update');
    Route::get('machine/config/permission/confirm'        ,[PerMissionController::class,'Confirm'])->name('permission.confirm');
    Route::get('machine/config/permission/delete'        ,[PerMissionController::class,'Delete'])->name('permission.delete');
