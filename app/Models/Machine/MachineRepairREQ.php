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
      ,'REPAIR_REQ_TIME'
      ,'CLOSE_STATUS'
      ,'CLOSE_BY'
      ,'CREATE_BY'
      ,'CREATE_TIME'
      ,'MODIFY_BY'
      ,'MODIFY_TIME'
      ,'DOC_YEAR'
      ,'DOC_MONTH'
      ,'INSPECTION_CODE'
      ,'INSPECTION_NAME'
      ,'INSPECTION_START_DATE'
      ,'INSPECTION_START_TIME'
      ,'INSPECTION_END_DATE'
      ,'INSPECTION_END_TIME'
      ,'INSPECTION_RESULT_TIME'
      ,'INSPECTION_DETAIL'
      ,'SPAREPART_START_DATE'
      ,'SPAREPART_START_TIME'
      ,'SPAREPART_END_DATE'
      ,'SPAREPART_END_TIME'
      ,'SPAREPART_RESULT_TIME'
      ,'WORKERIN_START_DATE'
      ,'WORKERIN_START_TIME'
      ,'WORKERIN_END_DATE'
      ,'WORKERIN_END_TIME'
      ,'WORKERIN_RESULT_TIME'
      ,'WORKEROUT_START_DATE'
      ,'WORKEROUT_START_TIME'
      ,'WORKEROUT_END_DATE'
      ,'WORKEROUT_END_TIME'
      ,'WORKEROUT_RESULT_TIME'
      ,'REPAIR_DETAIL'
      ,'DOWNTIME'
      ,'TOTAL_COST_SPAREPART'
      ,'TOTAL_COST_WORKER'
      ,'TOTAL_COST_REPAIR'
      ,'STATUS'
      ,'WORK_STEP'
      ,'REC_WORK_DATE'
      ,'MACHINE_REPORT_NO'
      ,'CLOSE_TIME'
      ,'CLOSE_DATE'
      ,'PD_UNID'
      ,'PD_CODE'
      ,'PD_NAME'
      ,'PD_CHECK_DATE'
      ,'PD_CHECK_TIME'
      ,'PD_CHECK_STATUS'
      ,'STATUS_NOTIFY'
      ,'STATUS_LINE_NOTIFY'
    ];

}
