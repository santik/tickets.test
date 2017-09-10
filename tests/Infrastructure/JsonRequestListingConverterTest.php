<?php
declare(strict_types=1);

namespace Santik\Tickets\Infrastructure;

use PHPUnit\Framework\TestCase;
use Santik\Tickets\Domain\Listing;
use Santik\Tickets\Domain\Ticket;
use Symfony\Component\HttpFoundation\Request;

class JsonRequestListingConverterTest extends TestCase
{
    public function testConvert_WithCorrectParams_WillReturnListing()
    {
        $converter = new JsonRequestListingConverter();

        $requestData = [
            'description' => 'some another concert',
            'price' => 12345,
            'tickets' => [
                [
                    'barcodes' => [
                        'barcode111'
                    ],
                ],
            ],
        ];

        $listingData = [
            'description' => 'some another concert',
            'price' => 12345,
            'tickets' => [
                Ticket::createFromArray([
                    'barcodes' => [
                        'barcode111'
                    ],
                ])
            ],
        ];

        $expectedListing = Listing::createFromArray($listingData);

        $request = $this->prophesize(Request::class);
        $request->getContent()->willReturn(json_encode($requestData));

        $listing = $converter->convert($request->reveal());

        $this->assertEquals($expectedListing, $listing);
    }

    public function testConvert_DataIsNotJson_WillThrowException()
    {
        $converter = new JsonRequestListingConverter();

        $request = $this->prophesize(Request::class);
        $request->getContent()->willReturn('');

        $this->expectException(\InvalidArgumentException::class);

        $converter->convert($request->reveal());
    }

    public function testConvert_DataIsEmptyJson_WillThrowException()
    {
        $converter = new JsonRequestListingConverter();

        $request = $this->prophesize(Request::class);
        $request->getContent()->willReturn(json_encode([]));

        $this->expectException(\InvalidArgumentException::class);

        $converter->convert($request->reveal());
    }

    public function testConvert_DataIsTicketsNotSet_WillThrowException()
    {
        $converter = new JsonRequestListingConverter();

        $request = $this->prophesize(Request::class);
        $request->getContent()->willReturn(json_encode(['some' => 'some']));

        $this->expectException(\InvalidArgumentException::class);

        $converter->convert($request->reveal());
    }
}
