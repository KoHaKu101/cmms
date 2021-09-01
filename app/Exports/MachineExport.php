<?php

namespace App\Exports;

use App\Models\Machine\Machine;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;



class MachineExport implements FromView
{
  use Exportable;
  /**
    *@return \Illuminate\Support\Collection
    */

  public function view(): View
  {
      $data_set = Machine::all();
      return view('machine.export.machine',compact(['data_set']));
  }

}
