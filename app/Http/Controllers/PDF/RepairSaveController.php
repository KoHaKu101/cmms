<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Machine\Machine;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Http\Controllers\PDF\HeaderFooterPDF\MachinePDF as Machineheaderfooter;



class RepairSaveController extends Controller
{
  public function __construct(Machineheaderfooter $Machineheaderfooter){
    $this->middleware('auth');
      $this->pdf = $Machineheaderfooter;
  }

  public function RepairSave(Request $request){

  }
}
