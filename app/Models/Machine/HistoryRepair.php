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
    public $table ='PMCS_CMMS_HISTORY_REPAIR';

    protected $fillable = ['UNID'
      ,'REPAIR_REQ_UNID'
      ,'MACHINE_UNID'
      ,'MACHINE_CODE'
      ,'MACHINE_NAME'
      ,'DOC_NO'
      ,'DOC_DATE'
      ,'DOC_YEAR'
      ,'DOC_MONTH'
      ,'DOC_TYPE'
      ,'REPAIR_REQ_DETAIL'
      ,'REPAIR_DETAIL'
      ,'REPAIR_DATE'
      ,'REPAIR_BY'
      ,'TOTAL_COST'
      ,'INSPECTION_BY'
      ,'APPROVED_BY'
      ,'DOWN_TIME'
      ,'CREATE_BY'
      ,'CREATE_TIME'
      ,'MODIFY_BY'
      ,'MODIFY_TIME'
    ];

}
