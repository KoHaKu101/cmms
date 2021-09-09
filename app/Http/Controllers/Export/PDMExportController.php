<?php

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\Plan\PDMExport;
// use Maatwebsite\Excel\Excel;
use Excel;
use File;
use App\Models\Machine\Machine;



class PDMExportController extends Controller
{
  private $mail ;
  public function __construct($pass_mail = null){
    $this->mail = $pass_mail;
  }
  public function export()
    {
        $PDM_EXPORT = new PDMExport();
        $path_file = public_path('upload/mail');
          if(!File::isDirectory($path_file)){
            File::makeDirectory($path_file, 0777, true, true);
          }

        Excel::store($PDM_EXPORT,'upload/mail/PlanPdm.xlsx','real_public');
    }

  }
