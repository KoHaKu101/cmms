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

  public function export(Request $request)
    {
        $LINE_CODE = $request->LINE_CODE;
        $MACHINE_EXPORT = new MachineExport($LINE_CODE);
        

        return Excel::download($MACHINE_EXPORT, 'Machinelist.xlsx');
    }

  }
