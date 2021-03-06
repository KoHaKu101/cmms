<?php

namespace App\Http\Controllers\QRCODE\lib\Qrcode\Decoder;

class QRCodeDecoderMetaData
{
    /** @var bool */
    private $mirrored;

    /**
     * QRCodeDecoderMetaData constructor.
     * @param bool $mirrored
     */
    public function __construct($mirrored)
    {
        $this->mirrored = $mirrored;
    }

    public function isMirrored()
    {
        return $this->mirrored;
    }
}
