<?php

namespace App\Models\Machine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class RepairWorker extends Model
{

    use HasFactory;
    const CREATED_AT = 'CREATE_TIME';
    const UPDATED_AT = 'MODIFY_TIME';

    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = "UNID";
    protected $keyType = 'BigInteger';
    public $table ='PMCS_CMMS_REPAIR_WORKER';

    protected $fillable = ['UNID'
      ,'REPAIR_REQ_UNID'
      ,'REPAIR_DOC_NO'
      ,'WORKER_UNID'
      ,'WORKER_TYPE'
      ,'WORKER_CODE'
      ,'WORKER_NAME'
      ,'WORKER_COST'
      ,'WORKER_REPAIR_DETAIL'
      ,'CREATE_BY'
      ,'CREATE_TIME'
      ,'MODIFY_BY'
      ,'MODIFY_TIME'
    ];

}
