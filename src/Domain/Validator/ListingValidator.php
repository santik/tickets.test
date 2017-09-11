<?php

declare(strict_types=1);

namespace Santik\Tickets\Domain\Validator;

use Santik\Tickets\Domain\Exception\BarcodeIsListed;
use Santik\Tickets\Domain\Exception\BarcodesAreNotUnique;
use Santik\Tickets\Domain\Listing;
use Santik\Tickets\Domain\Repository\ListingRepository;
use Santik\Tickets\Domain\User;

class ListingValidator
{
    private $listingRepository;

    public function __construct(ListingRepository $listingRepository)
    {

        $this->listingRepository = $listingRepository;
    }

    public function validate(Listing $listing, User $seller)
    {
        $this->validateBarcodesAreUnique($listing);

        $this->validateBarcodesAreSaleable($listing, $seller->id());
    }

    private function validateBarcodesAreUnique(Listing $listing)
    {
        $barcodes = $this->extractBarcodes($listing);

        if ($barcodes != array_unique($barcodes)) {
            throw new BarcodesAreNotUnique();
        }
    }

    private function extractBarcodes(Listing $listing): array
    {
        $barcodes = [];
        foreach ($listing->tickets() as $ticket) {
            $barcodes = array_merge($barcodes, $ticket->barcodes());
        }

        return $barcodes;
    }

    private function validateBarcodesAreSaleable($listing, $sellerId)
    {
        $barcodes = $this->extractBarcodes($listing);

        foreach ($barcodes as $barcode) {
            if (!$this->listingRepository->isBarcodeSaleable($barcode, $sellerId)) {
                throw new BarcodeIsListed();
            }
        }
    }
}
