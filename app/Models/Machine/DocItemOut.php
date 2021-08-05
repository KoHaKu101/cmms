<?php

namespace App\Models\Machine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocItemOut extends Model
{
    use HasFactory;

    const CREATED_AT = 'CREATE_TIME';
    const UPDATED_AT = 'MODIFY_TIME';

    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = "UNID";
    protected $keyType = 'BigInteger';
    public $table ='PMCS_CMMS_DOC_ITEMOUT';

    protected $fillable = ['UNID'
                          ,'DOC_NO'
                          ,'DOC_DATE'
                          ,'DOC_YEAR'
                          ,'DOC_MONTH'
                          ,'DOC_TYPE'
                          ,'COMPANY_UNID'
                          ,'COMPANY_NAME'
                          ,'EMP_NAME'
                          ,'EMP_CODE'
                          ,'CANCEL_NOTE'
                          ,'DATE_SET_REC'
                          ,'STATUS'
                          ,'COUNT_DETAIL'
                          ,'COST_TOTAL'
                          ,'CREATE_BY'
                          ,'CREATE_TIME'
                          ,'MODIFY_BY'
                          ,'MODIFY_TIME'];
}
