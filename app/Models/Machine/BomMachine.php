<?php

namespace App\Models\Machine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BomMachine extends Model
{
    use HasFactory;

    const CREATED_AT = 'CREATE_TIME';
    const UPDATED_AT = 'MODIFY_TIME';

    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = "UNID";
    protected $keyType = 'BigInteger';
    public $table ='PDCS_BOM_MACHINE';

    protected $fillable = ['MACHINE_NO'
,'MACHINE_CODE'
,'MACHINE_NAME'
,'ON_CT'
,'PRODUCT_CODE'
,'FORMULA_CODE'
,'PROCESS_NO'
,'PROCESS_CODE'
,'PROCESS_NAME'
,'CREATE_BY'
,'CREATE_TIME'
,'MODIFY_BY'
,'MODIFY_TIME'
,'MASTER_UNID'
,'REF_UNID'
,'UNID'
,'ON_CT_HR'
,'ON_CT_DAY'
,'ON_PLAN_STATUS'
,'WORKING_HR'];
}
