<?php

namespace App\Http\Controllers\Machine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Cookie;
use Response;
//******************** model ***********************

//************** Package form github ***************

class CookieController extends Controller
{

  public function randUNID($table){
    $number = date("ymdhis", time());
    $length=7;
    do {
      for ($i=$length; $i--; $i>0) {
        $number .= mt_rand(0,9);
      }
    }
    while ( !empty(DB::table($table)
    ->where('UNID',$number)
    ->first(['UNID'])) );
    return $number;
  }
  public function setCookie(Request $request) {
    $name = $request->NAME;
    $value = $request->VALUE;
    Cookie::queue($name, $value);
  }
  public function getCookie(Request $request) {
     $value = $request->cookie('name');
     echo $value;
  }
}
