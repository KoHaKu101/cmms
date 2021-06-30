<?php

namespace App\Models\Machine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class RepairSparepart extends Model
{

    use HasFactory;
    const CREATED_AT = 'CREATE_TIME';
    const UPDATED_AT = 'MODIFY_TIME';

    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = "UNID";
    protected $keyType = 'BigInteger';
    public $table ='PMCS_CMMS_REPAIR_SPAREPART';

    protected $fillable = ['UNID'
      ,'REPAIR_REQ_UNID'
      ,'REPAIR_DOC_NO'
      ,'SPAREPART_UNID'
      ,'SPAREPART_CODE'
      ,'SPAREPART_NAME'
      ,'SPAREPART_COST'
      ,'SPAREPART_TOTAL_COST'
      ,'SPAREPART_TOTAL_OUT'
      ,'SPAREPART_TYPE_OUT'
      ,'SPAREPART_UNIT'
      ,'SPAREPART_MODEL'
      ,'SPAREPART_SIZE'
      ,'CHANGE_DATE'
      ,'CREATE_BY'
      ,'CREATE_TIME'
      ,'MODIFY_BY'
      ,'MODIFY_TIME'
      ,'SPAREPART_PAY_TYPE'
      ,'SPAREPART_STOCK_TYPE'
    ];

}
