<?php

declare(strict_types=1);

namespace Santik\Tickets\Application;

use Santik\Tickets\Domain\Listing;
use Santik\Tickets\Domain\Repository\ListingRepository;
use Santik\Tickets\Domain\User;
use Santik\Tickets\Domain\Validator\ListingValidator;

final class ListingService
{
    private $listingRepository;

    private $listingValidator;

    public function __construct(ListingRepository $listingRepository, ListingValidator $listingValidator)
    {
        $this->listingRepository = $listingRepository;
        $this->listingValidator = $listingValidator;
    }

    public function create(Listing $listing, User $owner): Listing
    {
        $this->listingValidator->validate($listing, $owner);
        return $this->listingRepository->createListing($listing, $owner);
    }

    /**
     * @return Listing[]
     */
    public function getListingsForShow(): array
    {
        return $this->listingRepository->findAllListings();
    }
}