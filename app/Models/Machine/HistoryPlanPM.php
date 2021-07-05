<?php

namespace App\Models\Machine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class HistoryRepair extends Model
{

    use HasFactory;
    const CREATED_AT = 'CREATE_TIME';
    const UPDATED_AT = 'MODIFY_TIME';

    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = "UNID";
    protected $keyType = 'BigInteger';
    public $table ='PMCS_CMMS_HISTORY_PM';

    protected $fillable = ['UNID'
      ,'PM_PLAN_UNID'
      ,'MACHINE_UNID'
      ,'MACHINE_CODE'
      ,'MACHINE_NAME'
      ,'MACHINE_TYPE'
      ,'DOC_NO'
      ,'DOC_DATE'
      ,'DOC_YEAR'
      ,'DOC_MONTH'
      ,'DOC_TYPE'
      ,'CHECK_DATE'
      ,'CHECK_BY'
      ,'DOWN_TIME'
      ,'RANK'
      ,'PERIOD'
      ,'NOTE'
      ,'CREATE_BY'
      ,'CREATE_TIME'
      ,'MODIFY_BY'
      ,'MODIFY_TIME'
    ];

}
