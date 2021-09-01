<?php

namespace App\Exports;

use App\Models\Machine\MachineRepairREQ;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;




class DowntimeExports implements WithMultipleSheets,ShouldAutoSize
{
  use Exportable;

  private $year;
  private $type;
  public function __construct(int $year){
    $this->year = $year;
  }
  public function sheets(): array{
    $year = $this->year;
    $sheets = [];
    for($month = 1 ; $month <= 12; $month++){
      $sheets[]= new SubDowntimeExports($year,$month);
    }
    return $sheets;
  }

}
