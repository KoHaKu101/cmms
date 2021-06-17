<?php

namespace App\Models\Machine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class MachineRepairREQ extends Model
{

    use HasFactory;
    const CREATED_AT = 'CREATE_TIME';
    const UPDATED_AT = 'MODIFY_TIME';

    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = "UNID,DOC_NO";
    protected $keyType = 'BigInteger';
    public $table ='PMCS_CMMS_REPAIR_REQ';

    protected $fillable = ['UNID'
    ,'MACHINE_UNID'
    ,'MACHINE_CODE'
    ,'MACHINE_LINE'
    ,'MACHINE_NAME'
    ,'MACHINE_STATUS'
    ,'REPAIR_MAINSELECT_UNID'
    ,'REPAIR_MAINSELECT_NAME'
    ,'REPAIR_SUBSELECT_UNID'
    ,'REPAIR_SUBSELECT_NAME'
    ,'EMP_UNID'
    ,'EMP_CODE'
    ,'EMP_NAME'
    ,'PRIORITY'
    ,'DOC_NO'
    ,'DOC_DATE'
    ,'YY'
    ,'MM'
    ,'DD'
    ,'REPAIR_REQ_TIME'
    ,'CLOSE_STATUS'
    ,'CLOSE_BY'
    ,'CREATE_BY'
    ,'CREATE_TIME'
    ,'MODIFY_BY'
    ,'MODIFY_TIME'
    ];

}
