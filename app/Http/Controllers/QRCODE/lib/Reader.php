<?php

namespace App\Http\Controllers\QRCODE\lib;

interface Reader
{
    public function decode(BinaryBitmap $image);

    public function reset();
}
