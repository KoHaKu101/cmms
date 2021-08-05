<?php

namespace App\Models\Machine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocItemOutDetail extends Model
{
    use HasFactory;

    const CREATED_AT = 'CREATE_TIME';
    const UPDATED_AT = 'MODIFY_TIME';

    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = "UNID";
    protected $keyType = 'BigInteger';
    public $table ='PMCS_CMMS_DOC_ITEMOUT_DETAIL';

    protected $fillable = ['UNID'
,'DOC_ITEMOUT_UNID'
,'SPAREPART_UNID'
,'MACHINE_UNID'
,'SPAREPART_NAME'
,'SPAREPART_UNIT'
,'MACHINE_CODE'
,'TOTAL_OUT'
,'DATE_REC'
,'DATE_REC_CORRECT'
,'INDEX'
,'PR_CODE'
,'SERVICES_CODE'
,'DETAIL_INDEX'
,'STATUS'
,'NOTE'
,'COST_TOTAL'
,'CREATE_BY'
,'CREATE_TIME'
,'MODIFY_BY'
,'MODIFY_TIME'];
}
