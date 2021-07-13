<?php

namespace App\Models\Machine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EMPALL extends Model
{
    use HasFactory;

    const CREATED_AT = 'CREATE_TIME';
    const UPDATED_AT = 'MODIFY_TIME';

    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = "EMP_CODE";
    protected $keyType = 'string';
    public $table ='EMCS_EMPLOYEE';

    protected $fillable = ['UNID','EMP_TH_NAME_FIRST','EMP_TH_NAME_LAST','EMP_CODE'];
}
