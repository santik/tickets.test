<?php

declare(strict_types=1);

namespace Santik\Tickets\Domain\Validator;

use MicroDB\Database;
use PHPUnit\Framework\TestCase;
use Santik\Tickets\Domain\Exception\BarcodeIsListed;
use Santik\Tickets\Domain\Exception\BarcodesAreNotUnique;
use Santik\Tickets\Domain\Listing;
use Santik\Tickets\Domain\Repository\ListingRepository;
use Santik\Tickets\Domain\Ticket;

final class ListingValidatorTest extends TestCase
{
    public function testValidate_BarcodesAreNotUniqueInListing_WillThrowException()
    {
        $repository = $this->prophesize(ListingRepository::class);
        $validator = new ListingValidator($repository->reveal());

        $data = [
            'description' => 'some',
            'price' => 1234,
            'tickets' => [
                Ticket::createFromArray([
                    'barcodes' => [
                        'barcode1',
                        'barcode2',
                    ],
                ]),
                Ticket::createFromArray([
                    'barcodes' => [
                        'barcode2',
                        'barcode3',
                    ],
                ])
            ],

        ];

        $listing = Listing::createFromArray($data);

        $this->expectException(BarcodesAreNotUnique::class);

        $validator->validate($listing);
    }

    public function testValidate_BarcodesAreNotUniqueInDb_WillThrowException()
    {
        $repository = $this->prophesize(ListingRepository::class);
        $repository->isBarcodeSaleable('barcode1')->willReturn(false);

        $validator = new ListingValidator($repository->reveal());

        $data = [
            'description' => 'some',
            'price' => 1234,
            'tickets' => [
                Ticket::createFromArray([
                    'barcodes' => [
                        'barcode1',
                        'barcode2',
                    ],
                ]),
            ],

        ];

        $listing = Listing::createFromArray($data);

        $this->expectException(BarcodeIsListed::class);

        $validator->validate($listing);
    }
}
