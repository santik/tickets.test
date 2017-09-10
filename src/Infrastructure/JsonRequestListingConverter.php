<?php

namespace Santik\Tickets\Infrastructure;

use Santik\Tickets\Application\ListingConverter;
use Santik\Tickets\Domain\Listing;
use Santik\Tickets\Domain\Ticket;
use Symfony\Component\HttpFoundation\Request;
use Webmozart\Assert\Assert;

class JsonRequestListingConverter implements ListingConverter
{
    /**
     * @param Request $data
     */
    public function convert($data): Listing
    {
        $this->guardData($data);

        $data = json_decode($data->getContent(), true);

        $tickets = [];
        foreach ($data['tickets'] as $ticket) {
            $tickets[] = Ticket::createFromArray($ticket);
        }
        $data['tickets'] = $tickets;

        return Listing::createFromArray($data);
    }

    /**
     * @param Request $data
     */
    private function guardData($data)
    {
        Assert::isInstanceOf($data, Request::class);

        $data = json_decode($data->getContent(), true);
        Assert::notEmpty($data, 'data should be json content');

        Assert::keyExists($data, 'tickets');
    }
}