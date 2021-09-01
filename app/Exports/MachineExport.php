<?php

namespace App\Exports;

use App\Models\Machine\Machine;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class MachineExport implements FromView,ShouldAutoSize
{
  use Exportable;
  /**
    *@return \Illuminate\Support\Collection
    */
  protected $LINE;

   function __construct($LINE_CODE) {
          $this->LINE = $LINE_CODE;
   }

  public function view(): View
  {
      $LINE_CODE = $this->LINE;
      $data_set = Machine::where(function($query) use ($LINE_CODE){
          if ($LINE_CODE != 0) {
            $query->where('MACHINE_LINE','=',$LINE_CODE);
          }
      })->orderBy('MACHINE_LINE')->get();
      return view('machine.export.machine',compact(['data_set']));
  }

}
