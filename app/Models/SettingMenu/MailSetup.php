<?php

namespace App\Models\Settingmenu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class MailSetup extends Model
{
    use HasFactory;
    const CREATED_AT = 'CREATE_TIME';
    const UPDATED_AT = 'MODIFY_TIME';

    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = "UNID";
    protected $keyType = 'BigInteger';
    public $table ='PMCS_CMMS_SETUP_MAIL';

    protected $fillable = [
         'UNID', 'MAILHOST', 'EMAILADDRESS', 'MAILPASSWORD', 'MAILPORT', 'MAILPROTOCOL'
         , 'AUTOMAIL', 'AUTOPLAN','PLAN_CHECK','DATESEND_MAIL','DATESEND_SET','STATUS_SEND','TOKEN_LINENOTIFY'
          ,'CREATE_BY', 'CREATE_TIME', 'MODIFY_BY', 'MODIFY_TIME'
    ];

}
