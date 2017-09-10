<?php

declare(strict_types=1);

namespace Santik\Tickets\Domain;

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testCreateFromArray_WithCorrectParams_WillReturnTicket()
    {
        $data = [
            'id' => 123,
            'name' => 'some'
        ];

        $user = User::createFromArray($data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertInstanceOf(\JsonSerializable::class, $user);
        $this->assertEquals($user->name(), $data['name']);
        $this->assertEquals($user->id(), $data['id']);
    }

    public function testCreateFromArray_WithoutId_WillThrowException()
    {
        $data = [
            'name' => 'some'
        ];

        $this->expectException(\InvalidArgumentException::class);

        User::createFromArray($data);
    }

    public function testCreateFromArray_WithoutName_WillThrowException()
    {
        $data = [
            'id' => 123
        ];

        $this->expectException(\InvalidArgumentException::class);

        User::createFromArray($data);
    }

    public function testToArray_WillReturnCorrectData()
    {
        $data = [
            'id' => 123,
            'name' => 'some'
        ];

        $user = User::createFromArray($data);

        $this->assertEquals($data, $user->toArray());
    }
}
