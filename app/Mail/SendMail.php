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
    public function SettingEmail(){
      $existing = config('mail');
      $host = MailSetup::select('*')->first();
      $new =array_merge(
          $existing, [
          'mailers' => [
            'smtp'=>[
              'transport' => 'smtp',
              'host' => $host->MAILHOST,
              'port' => $host->MAILPORT,
              'username' => $host->EMAILADDRESS,
              'password' => $host->MAILPASSWORD,
            ],
          ],
          'host' => $host->MAILHOST,
          'port' => $host->MAILPORT,
          'from' => [
              'address' => $host->EMAILADDRESS,
              'name' => $host->EMAILADDRESS,
              ],
          'encryption' => null,
          'username' => $host->EMAILADDRESS,
          'password' => $host->MAILPASSWORD,
          ]);
      config(['mail'=>$new]);
    }
    public function build()
      {


        $path_file_pdm = public_path('upload/mail/PlanPdm.xlsx');
        $path_file_pm = public_path('upload/mail/PlanPm.xlsx');
      return $this->subject('ตรวจเช็คเครื่องจักรประจำเดือน')
          ->view('emails.sendmail')->attach($path_file_pm)->attach($path_file_pdm);
    }
}
