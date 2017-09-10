<?php

namespace Santik\Tickets\Domain\Repository;

use Santik\Tickets\Domain\Listing;
use Santik\Tickets\Domain\User;

interface ListingRepository
{
    public function createListing(Listing $listing, User $owner): Listing;

    public function isBarcodeSaleable(string $barcode): bool;

    /**
     * @return Listing[]
     */
    public function findAllListings(): array;
}
