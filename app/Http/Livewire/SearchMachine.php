<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Machine\Machine;

class searchmachine extends Component{

  use WithPagination;

  protected $paginationTheme = 'bootstrap';

  public $search = "";

  protected $queryString = ['search'];
  public $MACHINE_LINE;

  public function render(){
    $SEARCH = $this->search ;
    if ($this->MACHINE_LINE != NULL) {
      $machine = Machine::select('*')->selectRaw('dbo.decode_utf8(MACHINE_NAME) as MACHINE_NAME_V2')
                        ->where('MACHINE_CODE','like', '%'.$SEARCH.'%')
                        ->where('MACHINE_LINE',$this->MACHINE_LINE)
                        ->where('MACHINE_STATUS','!=','4')
                        ->orderBy('MACHINE_CODE','ASC')->paginate(10);
    }else {
      $machine = Machine::select('*')->selectRaw('dbo.decode_utf8(MACHINE_NAME) as MACHINE_NAME_V2')
                        ->where(function ($query) use ($SEARCH) {
                             $query->where('MACHINE_CODE', 'like', '%'.$SEARCH.'%')
                                   ->orWhere('MACHINE_LINE', 'like', '%'.$SEARCH.'%');})
                        ->where('MACHINE_STATUS','!=','4')
                        ->orderBy('MACHINE_CODE','ASC')->paginate(10);
    }


    return view('livewire.searchmachine',['machine' => $machine]);
  }
}
