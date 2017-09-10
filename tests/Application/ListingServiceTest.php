<?php

declare(strict_types=1);

namespace Santik\Tickets\Application;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Santik\Tickets\Domain\Listing;
use Santik\Tickets\Domain\Repository\ListingRepository;
use Santik\Tickets\Domain\Ticket;
use Santik\Tickets\Domain\User;
use Santik\Tickets\Domain\Validator\ListingValidator;

final class ListingServiceTest extends TestCase
{
    public function testCreate_withCorrectListing_WillReturnListingWithIds()
    {
        $data = [
            'description' => 'some',
            'price' => 1234,
            'tickets' => [
                $this->getTicket(),
                $this->getTicket(),
                $this->getTicket(),
            ],

        ];

        $listing = Listing::createFromArray($data);

        $data = [
            'id' => 1234,
            'description' => 'some',
            'price' => 1234,
            'tickets' => [
                $this->getTicket(1),
                $this->getTicket(2),
                $this->getTicket(3),
            ],

        ];

        $expectedListing = Listing::createFromArray($data);

        $owner = User::createFromArray(['id' => 1, 'name' => 'name']);

        $repository = $this->prophesize(ListingRepository::class);
        $repository->createListing($listing, $owner)->willReturn($expectedListing);
        $validator = $this->prophesize(ListingValidator::class);
        $service = new ListingService($repository->reveal(), $validator->reveal());


        $newListing = $service->create($listing, $owner);

        $this->assertEquals($newListing, $expectedListing);

    }

    public function testCreate_ValidatorThrowsException_RepositoryShouldNotBeCalled()
    {
        $owner = User::createFromArray(['id' => 1, 'name' => 'name']);
        $data = [
            'description' => 'some',
            'price' => 1234,
            'tickets' => [
                $this->getTicket(),
            ],

        ];

        $listing = Listing::createFromArray($data);

        $repository = $this->prophesize(ListingRepository::class);
        $repository->createListing($listing, $owner)->shouldNotBeCalled();
        $validator = $this->prophesize(ListingValidator::class);
        $validator->validate(Argument::any())->willThrow(\Exception::class);
        $service = new ListingService($repository->reveal(), $validator->reveal());

        $this->expectException(\Exception::class);

        $service->create($listing, $owner);
    }

    public function testGetListingsForShow_ShouldCallRepositoryMethod()
    {
        $repository = $this->prophesize(ListingRepository::class);
        $repository->findAllListings()->shouldBeCalled();
        $validator = $this->prophesize(ListingValidator::class);
        $service = new ListingService($repository->reveal(), $validator->reveal());

        $service->getListingsForShow();
    }

    private function getTicket($id = null): Ticket
    {
        $data = [
            'barcodes' => [
                'foo' . microtime() . rand(1,1000),
                'bar' . microtime() . rand(1,1000),
            ],
            'id' => $id
        ];

        return Ticket::createFromArray($data);
    }
}
