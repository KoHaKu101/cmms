<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use App\Exports\DowntimeExports;
use Maatwebsite\Excel\Facades\Excel;

class DowtimeexportController extends Controller
{
  public function __construct(){
    $this->middleware('auth');
  }
  public function Dowtimeexport(Request $request){
    return Excel::download(new DowntimeExports(date('Y')), 'downtime.xlsx');
  }
}