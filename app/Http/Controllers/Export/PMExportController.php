<?php

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\Plan\PMExport;
// use Maatwebsite\Excel\Excel;
use Excel;
use File;
use App\Models\Machine\Machine;



class PMExportController extends Controller
{
  private $mail ;
  public function __construct($pass_mail = null){
    $this->mail = $pass_mail;
  }
  public function export()
    {
        $PM_EXPORT = new PMExport();
        $path_file = public_path('upload/mail');
          if(!File::isDirectory($path_file)){
            File::makeDirectory($path_file, 0777, true, true);
          }
          
        Excel::store($PM_EXPORT,'upload/mail/PlanPm.xlsx','real_public');
    }

  }
