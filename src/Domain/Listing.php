<?php

declare(strict_types=1);

namespace Santik\Tickets\Domain;

use JsonSerializable;
use Webmozart\Assert\Assert;

final class Listing implements JsonSerializable
{
    private $id;

    private $description;

    private $price;

    /**
     * @var Ticket[]
     */
    private $tickets;


    public static function createFromArray(array $data)
    {
        self::guardData($data);

        $id = isset($data['id']) ? $data['id'] : null;

        return new Listing($data['description'], $data['price'], $data['tickets'], $id);
    }

    private function __construct(string $description, int $price, array $tickets, int $id = null)
    {
        $this->id = $id;
        $this->description = $description;
        $this->price = $price;
        $this->tickets = $tickets;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id(),
            'description' => $this->description(),
            'price' => $this->price(),
            'tickets' => $this->tickets()
        ];
    }

    private static function guardData($data)
    {
        Assert::keyExists($data, 'description');
        Assert::keyExists($data, 'price');
        Assert::notEmpty($data['description']);
        Assert::notEmpty($data['price']);
        Assert::keyExists($data, 'tickets');
        Assert::isArray($data['tickets']);
        Assert::notEmpty($data['tickets']);

        foreach ($data['tickets'] as $ticket) {
            Assert::isInstanceOf($ticket, Ticket::class);
        }
    }

    public function id()
    {
        return $this->id;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function price(): int
    {
        return $this->price;
    }

    /**
     * @return Ticket[]
     */
    public function tickets(): array
    {
        return $this->tickets;
    }


    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
