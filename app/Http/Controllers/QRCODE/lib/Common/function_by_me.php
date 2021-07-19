<?php
namespace App\Http\Controllers\QRCODE\lib\Common;

class function_by_me
{

  public function fill_array($index, $count, $value)
  {
      if ($count <= 0) {
          return [0];
      }

      return array_fill($index, $count, $value);
  }
  function uRShift($a, $b)
  {
      static $mask = (8 * PHP_INT_SIZE - 1);
      if ($b === 0) {
          return $a;
      }

      return ($a >> $b) & ~(1 << $mask >> ($b - 1));
  }

}
