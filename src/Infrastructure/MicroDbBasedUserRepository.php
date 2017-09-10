<?php

declare(strict_types=1);

namespace Santik\Tickets\Infrastructure;

use MicroDB\Database;
use Santik\Tickets\Domain\Repository\UserRepository;
use Santik\Tickets\Domain\User;

final class MicroDbBasedUserRepository implements UserRepository
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function createUser($username): User
    {
        $id = $this->database->create([
            'type' => 'user',
            'name' => $username
        ]);

        $userData = [
            'id' => $id,
            'name' => $username
        ];

        return User::createFromArray($userData);
    }

    public function findUser(int $userId): User
    {
        $user = $this->database->load($userId);
        if ($user['type'] != 'user') {
            throw new \Exception('No user found');
        }

        $data = [
            'id' => $userId,
            'name' => $user['name']
        ];


        return User::createFromArray($data);
    }
}
