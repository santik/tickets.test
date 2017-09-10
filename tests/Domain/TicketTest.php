<?php

declare(strict_types=1);

namespace Santik\Tickets\Domain;

use PHPUnit\Framework\TestCase;

final class TicketTest extends TestCase
{
    public function testCreateFromArray_WithCorrectParams_WillReturnTicket()
    {
        $data = [
            'barcodes' => [
                'foo',
                'bar'
            ],
        ];

        $ticket = Ticket::createFromArray($data);

        $this->assertInstanceOf(Ticket::class, $ticket);
        $this->assertEquals($ticket->barcodes(), $data['barcodes']);
        $this->assertEquals($ticket->id(), null);
    }

    public function testCreateFromArray_WithCorrectParamsAndId_WillReturnTicket()
    {
        $data = [
            'barcodes' => [
                'foo',
                'bar'
            ],
            'id' => 123
        ];

        $ticket = Ticket::createFromArray($data);

        $this->assertEquals($ticket->id(), $data['id']);
    }

    public function testCreateFromArray_WithNoBarcodes_WillThrowException()
    {
        $data = [
            'some'
        ];

        $this->expectException(\InvalidArgumentException::class);

        Ticket::createFromArray($data);
    }

    public function testCreateFromArray_WithEmptyBarcodes_WillThrowException()
    {
        $data = [
            'barcodes' => []
        ];

        $this->expectException(\InvalidArgumentException::class);

        Ticket::createFromArray($data);
    }

    public function testCreateFromArray_WithEmptyBarcode_WillThrowException()
    {
        $data = [
            'barcodes' => [
                ''
            ]
        ];

        $this->expectException(\InvalidArgumentException::class);

        Ticket::createFromArray($data);
    }

    public function testToArray_WillReturnCorrectData()
    {
        $data = [
            'barcodes' => [
                'foo',
                'bar'
            ],
            'id' => 123
        ];



        $ticket = Ticket::createFromArray($data);
        $this->assertEquals($data, $ticket->toArray());
    }

    public function testIsJsonSerializable()
    {
        $data = [
            'barcodes' => [
                'foo',
                'bar'
            ],
            'id' => 123
        ];



        $ticket = Ticket::createFromArray($data);
        $this->assertInstanceOf(\JsonSerializable::class, $ticket);
    }
}
