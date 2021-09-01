<?php

namespace App\Exports;

use App\Models\Machine\MachineRepairREQ;
use Maatwebsite\Excel\Concerns\FromCollection;



class DowntimeExports implements FromCollection
{
    public function collection(){
    return new Collection([
        [1, 2, 3],
        [4, 5, 6]
    ]);
}
}
