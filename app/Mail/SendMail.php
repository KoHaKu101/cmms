<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Machine\MachinePlanPm;
use App\Models\Machine\SparePartPlan;
use App\Models\Machine\Machine;
use App\Models\SettingMenu\MailSetup;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;
    public $details;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    private function setMailConfig(){
         $existing = config('mail');
         $host = MailSetup::select('*')->first();
         $new =array_merge(
             $existing, [
             'host' => $host->MAIL_HOST,
             'port' => $host->MAIL_PORT,
             'from' => [
                 'address' => $host->EMAILADDRESS,
                 'name' => $host->EMAILADDRESS,
                 ],
             'encryption' => null,
             'username' => $host->EMAILADDRESS,
             'password' => $host->MAIL_PASSWORD,
             ]);
          dd($new);
         config(['mail'=>$new]);
     }

    // private function setMailFromSupport()
    // {
    //     $existing = config('mail');
    //     $new =array_merge(
    //         $existing, [
    //         'from' => [
    //             'address' => 'support@example.com',
    //             'name' => 'Support Services',
    //             ],
    //         ]);
    //
    //     config(['mail'=>$new]);
    // }
    public function build()
      {
        $DATA_PM  =  MachinePlanPm::select('MACHINE_CODE','MACHINE_LINE','PLAN_DATE','PM_MASTER_NAME')
                                  ->selectraw('dbo.decode_utf8(MACHINE_NAME) as MACHINE_NAME_TH')
                                  ->where('PLAN_YEAR','=',date('Y'))->where('PLAN_MONTH','=',date('n'))->where('PLAN_STATUS','!=','COMPLETE')
                                  ->orderBy('MACHINE_LINE')->orderby('PLAN_DATE')->get();


        $DATA_PDM =  SparePartPlan::select('MACHINE_CODE','MACHINE_LINE','SPAREPART_NAME','PLAN_DATE')
                                   ->where('DOC_YEAR','=',date('Y'))->where('DOC_MONTH','=',date('n'))->where('STATUS','!=','COMPLETE')
                                   ->orderBy('PLAN_DATE')->orderBy('MACHINE_LINE')->orderBy('MACHINE_CODE')->get();
        $MACHINE_ARRAY = array();
        foreach($DATA_PDM as $key => $row){
          $MACHINE_ARRAY[$row->MACHINE_CODE] = Machine::selectraw('dbo.decode_utf8(MACHINE_NAME) as MACHINE_NAME_TH')
                                              ->where('MACHINE_CODE','=',$row->MACHINE_CODE)->first();
        }

      return $this->subject('ตรวจเช็คเครื่องจักรประจำเดือน')
          ->view('emails.sendmail',compact('DATA_PM','DATA_PDM','MACHINE_ARRAY'));
    }
}
