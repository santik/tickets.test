<?php

declare(strict_types=1);

namespace Santik\Tickets\Domain;

use Webmozart\Assert\Assert;

final class User implements \JsonSerializable
{
    private $id;

    private $name;

    public static function createFromArray($data): User
    {
        Assert::keyExists($data, 'name');
        Assert::keyExists($data, 'id');

        return new User($data['id'], $data['name']);
    }

    private function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function toArray()
    {
        return [
            'id' => $this->id(),
            'name' => $this->name()
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
