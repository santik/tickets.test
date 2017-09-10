<?php

namespace Santik\Tickets\Application;

use Santik\Tickets\Domain\Listing;

interface ListingConverter
{
    public function convert($data): Listing;
}