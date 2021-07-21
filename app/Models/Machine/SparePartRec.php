<?php

namespace App\Models\Machine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparePartRec extends Model
{
    use HasFactory;

    const CREATED_AT = 'CREATE_TIME';
    const UPDATED_AT = 'MODIFY_TIME';

    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = "SPAREPART_CODE";
    protected $keyType = 'string';
    public $table ='PMCS_CMMS_SPAREPART_REC';

    protected $fillable = ['UNID'
      ,'SPAREPART_CODE'
      ,'SPAREPART_UNID'
      ,'SPAREPART_NAME'
      ,'SPAREPART_MODEL'
      ,'SPAREPART_UNIT'
      ,'DOC_NO'
      ,'DOC_DATE'
      ,'DOC_YEAR'
      ,'DOC_MONTH'
      ,'TOTAL'
      ,'IN_TOTAL'
      ,'RECODE_BY'
      ,'CREATE_BY'
      ,'CREATE_TIME'
      ,'MODIFY_BY'
      ,'MODIFY_TIME'];
}
