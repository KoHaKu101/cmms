<?php

namespace App\Models\Machine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachinePlanPm extends Model
{
    use HasFactory;

    const CREATED_AT = 'CREATE_TIME';
    const UPDATED_AT = 'MODIFY_TIME';

    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = "UNID";
    protected $keyType = 'BigInteger';
    public $table ='PMCS_MACHINE_PLAN_PM';

    protected $fillable = ['UNID','PLAN_YEAR','PLAN_MONTH','PLAN_DATE','PLAN_NEXTDATE','PLAN_DOCNO','MACHINE_UNID','MACHINE_NAME'
    ,'MACHINE_CODE','MACHINE_LINE','PLAN_PERIOD','PLAN_RANK','PM_TYPE','PM_MASTER_NAME','PM_MASTER_UNID','PLAN_STATUS','COMPLETE_DATE','PLAN_RE_MARK'
    ,'START_TIME','END_TIME','DOWNTIME','CREATE_BY','CREATE_TIME','MODIFY_BY','MODIFY_TIME'];
}
