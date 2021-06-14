<?php

namespace App\Models\Machine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EMPPAYTYPE extends Model
{
    use HasFactory;

    const CREATED_AT = 'CREATE_TIME';
    const UPDATED_AT = 'MODIFY_TIME';

    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = "UNID";
    protected $keyType = 'BigInteger';
    public $table ='EMCS_EMPLOYEE_TYPE';

    protected $fillable = ['TYPE_CODE'
      ,'TYPE_NAME'
      ,'TYPE_NOTE'
      ,'TYPE_STATUS'
      ,'CREATE_BY'
      ,'CREATE_TIME'
      ,'MODIFY_BY'
      ,'MODIFY_TIME'
      ,'UNID'
      ,'TYPE_DAY'];
}
