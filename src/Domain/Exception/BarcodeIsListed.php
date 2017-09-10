<?php
declare(strict_types=1);

namespace Santik\Tickets\Domain\Exception;

class BarcodeIsListed extends \Exception
{
    public function __construct()
    {
        parent::__construct('Barcode is already listed');
    }
}