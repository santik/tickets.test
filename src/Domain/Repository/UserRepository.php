<?php

namespace Santik\Tickets\Domain\Repository;

use Santik\Tickets\Domain\User;

interface UserRepository
{
    public function createUser($username): User;

    public function findUser(int $userId): User;
}