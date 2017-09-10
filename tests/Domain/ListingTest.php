<?php

declare(strict_types=1);

namespace Santik\Tickets\Domain;

use PHPUnit\Framework\TestCase;

final class ListingTest extends TestCase
{
    public function testCreateFromArray_WithCorrectParams_WillReturnListing()
    {
        $data = [
            'description' => 'some',
            'price' => 1234,
            'tickets' => [
                $this->getTicket()
            ],

        ];

        $listing = Listing::createFromArray($data);

        $this->assertInstanceOf(Listing::class, $listing);
        $this->assertInstanceOf(\JsonSerializable::class, $listing);
        $this->assertEquals($listing->tickets(), $data['tickets']);
        $this->assertEquals($listing->description(), $data['description']);
        $this->assertEquals($listing->price(), $data['price']);
        $this->assertEquals($listing->id(), null);
    }

    public function testCreateFromArray_WithCorrectParamsAndId_WillReturnListing()
    {
        $data = [
            'description' => 'some',
            'price' => 1234,
            'id' => 123,
            'tickets' => [
                $this->getTicket()
            ],

        ];

        $listing = Listing::createFromArray($data);

        $this->assertInstanceOf(Listing::class, $listing);
        $this->assertEquals($listing->id(), $data['id']);
    }

    public function testCreateFromArray_WithNoTickets_WillThrowException()
    {
        $data = [
            'description' => 'some',
            'price' => 1234,
        ];

        $this->expectException(\InvalidArgumentException::class);

        Listing::createFromArray($data);
    }

    public function testCreateFromArray_WithEmptyTickets_WillThrowException()
    {
        $data = [
            'description' => 'some',
            'price' => 1234,
            'tickets' => []
        ];

        $this->expectException(\InvalidArgumentException::class);

        Listing::createFromArray($data);
    }

    public function testCreateFromArray_WithEmptyDescription_WillThrowException()
    {
        $data = [
            'description' => '',
            'price' => 1234,
            'tickets' => [
                $this->getTicket()
            ]
        ];

        $this->expectException(\InvalidArgumentException::class);

        Listing::createFromArray($data);
    }

    public function testCreateFromArray_WithoutDescription_WillThrowException()
    {
        $data = [
            'price' => 123,
            'tickets' => [
                $this->getTicket()
            ]
        ];

        $this->expectException(\InvalidArgumentException::class);

        Listing::createFromArray($data);
    }

    public function testCreateFromArray_WithoutPrice_WillThrowException()
    {
        $data = [
            'description' => 'some',
            'tickets' => [
                $this->getTicket()
            ]
        ];

        $this->expectException(\InvalidArgumentException::class);

        Listing::createFromArray($data);
    }

    public function testToArray_WillCorrectData()
    {
        $data = [
            'description' => 'some',
            'price' => 1234,
            'tickets' => [
                $this->getTicket()
            ],
            'id' => 123
        ];

        $listing = Listing::createFromArray($data);

        $this->assertEquals($data, $listing->toArray());
    }


    private function getTicket(): Ticket
    {
        $data = [
            'barcodes' => [
                'foo' . microtime() . rand(1,1000),
                'bar' . microtime() . rand(1,1000),
            ],
        ];

        return Ticket::createFromArray($data);
    }
}
