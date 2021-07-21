<?php

namespace App\Models\Machine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparePartPlan extends Model
{
    use HasFactory;

    const CREATED_AT = 'CREATE_TIME';
    const UPDATED_AT = 'MODIFY_TIME';

    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = "UNID";
    protected $keyType = 'BigInteger';
    public $table ='PMCS_CMMS_SPAREPART_PLAN';

    protected $fillable = ['UNID'
,'MACHINE_UNID'
,'MACHINE_CODE'
,'MACHINE_LINE'
,'SPAREPART_UNID'
,'SPAREPART_NAME'
,'SPAREPART_CODE'
,'STATUS'
,'STATUS_OPEN'
,'REMARK'
,'PLAN_QTY'
,'ACT_QTY'
,'PERIOD'
,'DOC_YEAR'
,'DOC_MONTH'
,'PLAN_DATE'
,'NEXT_DATE'
,'COMPLETE_DATE'
,'COST_STD'
,'TOTAL_COST'
,'COST_ACT'
,'SPAREPART_PAY_TYPE'
,'CREATE_BY'
,'CREATE_TIME'
,'MODIFY_BY'
,'MODIFY_TIME'];
}
