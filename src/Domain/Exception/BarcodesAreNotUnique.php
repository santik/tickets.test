<?php
declare(strict_types=1);

namespace Santik\Tickets\Domain\Exception;

class BarcodesAreNotUnique extends \Exception
{
    public function __construct()
    {
        parent::__construct('Barcodes are not unique in listing');
    }
}