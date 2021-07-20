<?php

namespace App\Models\Machine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class HistorySparepart extends Model
{

    use HasFactory;
    const CREATED_AT = 'CREATE_TIME';
    const UPDATED_AT = 'MODIFY_TIME';

    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = "UNID";
    protected $keyType = 'BigInteger';
    public $table ='PMCS_CMMS_HISTORY_SPAREPART';

    protected $fillable = ['UNID'
                          ,'SPAREPART_UNID'
                          ,'MACHINE_UNID'
                          ,'MACHINE_CODE'
                          ,'DOC_NO'
                          ,'DOC_DATE'
                          ,'DOC_YEAR'
                          ,'DOC_MONTH'
                          ,'TOTAL'
                          ,'IN_TOTAL'
                          ,'OUT_TOTAL'
                          ,'UNID_REF'
                          ,'TYPE'
                          ,'RECODE_BY'
                          ,'REMARK'
                          ,'CREATE_BY'
                          ,'CREATE_TIME'
                          ,'MODIFY_BY'
                          ,'MODIFY_TIME'
    ];

}
