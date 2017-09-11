<?php

declare(strict_types=1);

namespace Santik\Tickets\Infrastructure;

use MicroDB\Database;
use Santik\Tickets\Domain\Listing;
use Santik\Tickets\Domain\Repository\ListingRepository;
use Santik\Tickets\Domain\Ticket;
use Santik\Tickets\Domain\User;

final class MicroDbBasedListingRepository implements ListingRepository
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function createListing(Listing $listing, User $owner): Listing
    {
        $newListingData = $listing->toArray();

        $newListingId = $this->saveListingInDb($listing->description(), $listing->price(), $owner->id());
        $newListingData['id'] = $newListingId;

        $newTickets = [];
        /** @var Ticket $ticket */
        foreach ($listing->tickets() as $ticket) {
            $newTicket = $this->createTicket($ticket, $newListingId);
            $newTickets[] = $newTicket;
        }

        $newListingData['tickets'] = $newTickets;

        return Listing::createFromArray($newListingData);
    }

    private function createTicket(Ticket $ticket, $listingId): Ticket
    {
        $newTicketData = $ticket->toArray();
        $newTicketId = $this->saveNewTicket($listingId);
        $newTicketData['id'] = $newTicketId;

        foreach ($ticket->barcodes() as $barcode) {
            $this->saveBarcodeToDb($barcode, $newTicketId);
        }

        return Ticket::createFromArray($newTicketData);
    }

    private function saveListingInDb($description, $price, $ownerId)
    {
        return $this->database->create([
            'type' => 'listing',
            'description' => $description,
            'price' => $price,
            'user_id' => $ownerId
        ]);
    }

    private function saveBarcodeToDb($barcode, $ticketId)
    {
        return $this->database->create([
            'type' => 'barcode',
            'barcode' => $barcode,
            'ticket_id' => $ticketId
        ]);
    }

    private function saveNewTicket($listingId)
    {
        return $this->database->create([
            'type' => 'ticket',
            'listing_id' => $listingId
        ]);
    }

    public function isBarcodeSaleable(string $barcode, int $sellerId): bool
    {
        $data = [
            'type' => 'barcode',
            'barcode' => $barcode,
        ];

        $dbBarcode = array_values($this->database->find($data));

        if (empty($dbBarcode)) {
            return true;
        }

        $ticket = $this->database->load($dbBarcode[0]['ticket_id']);

        if (!$ticket) {
            return true;
        }

        return $ticket['type'] == 'ticket' && $this->isTicketSoldToSeller($ticket, $sellerId);
    }

    private function isTicketSoldToSeller(array $ticket, int $sellerId): bool
    {
        return isset($ticket['boughtByUserId']) && $ticket['boughtByUserId'] == $sellerId;
    }

    /**
     * @return Listing[]
     */
    public function findAllListings(): array
    {
        $listings = $this->database->find(['type' => 'listing']);
        $domainListings = [];
        foreach ($listings as $listingId => $listing) {
            $tickets = $this->getListingTickets($listingId);
            $domainTickets = [];
            foreach ($tickets as $ticketId => $ticket) {
                foreach ($this->getAllTicketBarcodes($ticketId) as $barcode) {
                    $tickets[$ticketId]['barcodes'][] = $barcode['barcode'];
                }
                $tickets[$ticketId]['id'] = $ticketId;
                $domainTickets[] = Ticket::createFromArray($tickets[$ticketId]);
            }

            $listings[$listingId]['tickets'] = $domainTickets;
            $listings[$listingId]['id'] = $listingId;
            $domainListings[] = Listing::createFromArray($listings[$listingId]);
        }

        return $domainListings;
    }

    private function getListingTickets(int $listingId): array
    {
        return $this->database->find(['type' => 'ticket', 'listing_id' => $listingId]);
    }

    private function getAllTicketBarcodes(int $ticketId): array
    {
        return $this->database->find(['type' => 'barcode', 'ticket_id' => $ticketId]);
    }
}
