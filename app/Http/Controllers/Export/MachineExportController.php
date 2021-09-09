<?php

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\MachineExport;
// use Maatwebsite\Excel\Excel;
use Excel;
use App\Models\Machine\Machine;



class MachineExportController extends Controller
{
  private $mail ;
  public function __construct($pass_mail = null){
    $this->mail = $pass_mail;
  }
  public function export(Request $request)
    {

        $LINE_CODE = $request->LINE_CODE;
        $MACHINE_EXPORT = new MachineExport($LINE_CODE);

        if(isset($this->mail)){
          Excel::store($MACHINE_EXPORT,'upload/machine/Machinelist.xlsx','real_public');
        }else {
          return Excel::download($MACHINE_EXPORT, 'Machinelist.xlsx');
        }

    }

  }
