<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use \App\Mail\SendMail;
use App\Models\SettingMenu\MailSetup;
use App\Models\SettingMenu\MailAlert;

class MailSend extends Controller
{

  public function mailsend()
{

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

  $send_mail  = MailAlert::select('EMAILADDRESS1','EMAILADDRESS2','EMAILADDRESS3','EMAILADDRESS4','EMAILADDRESS5')->get();
  $mail_array =[ $send_mail[0]->EMAILADDRESS1,
                 $send_mail[0]->EMAILADDRESS2,
                 $send_mail[0]->EMAILADDRESS3,
                 $send_mail[0]->EMAILADDRESS4,
                 $send_mail[0]->EMAILADDRESS5
               ];

  $mail = array();
  foreach ($mail_array as $key => $row) {
    if($row != ''){
      $mail[$key] = $row;
    }

  }
  \Mail::to($mail)->send(new SendMail());
  return view('emails.thanks');
}
}
