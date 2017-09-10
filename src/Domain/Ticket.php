<?php

declare(strict_types=1);

namespace Santik\Tickets\Domain;

use JsonSerializable;
use Webmozart\Assert\Assert;

final class Ticket implements JsonSerializable
{
    private $barcodes;

    private $id;

    public static function createFromArray(array $data): Ticket
    {
        self::guardData($data);

        $id = isset($data['id']) ? $data['id'] : null;

        return new Ticket($data['barcodes'], $id);
    }

    private function __construct(array $barcodes, $id = null)
    {

        $this->barcodes = $barcodes;
        $this->id = $id;
    }

    private static function guardData(array $data)
    {
        Assert::keyExists($data, 'barcodes');
        Assert::isArray($data['barcodes']);
        Assert::notEmpty($data['barcodes']);

        foreach ($data['barcodes'] as $barcode) {
            Assert::string($barcode);
            Assert::notEmpty($barcode);
        }
    }

    public function barcodes(): array
    {
        return $this->barcodes;
    }

    public function id()
    {
        return $this->id;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id(),
            'barcodes' => $this->barcodes()
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
