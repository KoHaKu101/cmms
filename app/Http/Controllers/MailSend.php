<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use \App\Mail\SendMail;
use App\Models\SettingMenu\MailSetup;

class MailSend extends Controller
{

  public function mailsend()
{

  $existing = config('mail');
  $host = MailSetup::select('*')->first();

  $new =array_merge(
      $existing, [
      
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
    dd($new);
  config(['mail'=>$new]);

  \Mail::to('poou8558@gmail.com')->send(new SendMail());

  return view('emails.thanks');
}
}
