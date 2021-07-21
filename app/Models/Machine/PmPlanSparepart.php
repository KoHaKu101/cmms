<?php

namespace App\Models\Machine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class PmPlanSparepart extends Model
{

    use HasFactory;
    const CREATED_AT = 'CREATE_TIME';
    const UPDATED_AT = 'MODIFY_TIME';

    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = "UNID";
    protected $keyType = 'BigInteger';
    public $table ='PMCS_CMMS_PM_SPAREPART';

    protected $fillable = ['UNID'
      ,'PM_PLAN_UNID'
      ,'PLAN_DATE'
      ,'MACHINE_PLAN_UNID'
      ,'MACHINE_CODE'
      ,'MACHINE_LINE'
      ,'MACHINE_NAME'
      ,'PM_USER_CHECK'
      ,'CHANGE_DATE'
      ,'SPAREPART_UNID'
      ,'SPAREPART_CODE'
      ,'SPAREPART_NAME'
      ,'SPAREPART_COST'
      ,'TOTAL_COST'
      ,'TOTAL_PIC'
      ,'INSPECTION_BY'
      ,'SPAREPART_PAY_TYPE'
      ,'CREATE_BY'
      ,'CREATE_TIME'
      ,'MODIFY_BY'
      ,'MODIFY_TIME'
    ];

}
