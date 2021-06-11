<?php

namespace App\Models\Machine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PositionEMP extends Model
{
    use HasFactory;

    const CREATED_AT = 'CREATE_TIME';
    const UPDATED_AT = 'MODIFY_TIME';

    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = "UNID";
    protected $keyType = 'BigInteger';
    public $table ='PMCS_EMP_POSITION';

    protected $fillable = ['UNID'
,'EMP_POSITION_INDEX'
,'EMP_POSITION_CODE'
,'EMP_POSITION_NAME'
,'EMP_POSITION_LIMIT'
,'REMARK'
,'STATUS'
,'CREATE_BY'
,'CREATE_TIME'
,'MODIFY_BY'
,'MODIFY_TIME'];
}
