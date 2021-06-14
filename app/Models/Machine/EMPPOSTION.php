<?php

namespace App\Models\Machine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EMPPOSTION extends Model
{
    use HasFactory;

    const CREATED_AT = 'CREATE_TIME';
    const UPDATED_AT = 'MODIFY_TIME';

    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = "UNID";
    protected $keyType = 'BigInteger';
    public $table ='EMCS_EMPLOYEE_POSITION';

    protected $fillable = ['POSITION_CODE'
      ,'POSITION_NAME'
      ,'POSITION_NOTE'
      ,'POSITION_STATUS'
      ,'CREATE_BY'
      ,'CREATE_TIME'
      ,'MODIFY_BY'
      ,'MODIFY_TIME'
      ,'UNID'
      ,'POSITION_ORDER'
      ,'POSITION_OT_ALLOW'
      ,'POSITION_KY_ALLOW'
      ,'POSITION_NAME_EN'
      ,'POSITION_LIFT_AMT'
      ,'POSITION_KY_AMT'
      ,'POSITION_LATE_LIMIT'
      ,'POSITION_START_D_TIME'
      ,'POSITION_START_N_TIME'
      ,'POSITION_AMT_ALLOW'
      ,'POSITION_KY_D_STIME'
      ,'POSITION_KY_N_STIME'
      ,'POSITION_KY_PROPASS'
      ,'POSITION_INCOME_01_ALLOW'
      ,'POSITION_INCOME_02_ALLOW'
      ,'POSITION_INCOME_03_ALLOW'
      ,'POSITION_INCOME_04_ALLOW'
      ,'POSITION_INCOME_04_CODE'
      ,'POSITION_INCOME_04_AMT'
      ,'POSITION_INCOME_04_START'
      ,'POSITION_APPROVE'
      ,'POSITION_INCOME_01_STIME'
      ,'POSITION_INCOME_02_STIME'];
}
