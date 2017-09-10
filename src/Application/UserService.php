<?php

declare(strict_types=1);

namespace Santik\Tickets\Application;

use Santik\Tickets\Domain\Repository\UserRepository;
use Santik\Tickets\Domain\User;
use Symfony\Component\HttpFoundation\Request;

final class UserService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function findOrCreateUser($id): User
    {
        try {
            $user = $this->userRepository->findUser($id);
        } catch (\Exception $e) {
            //no user found
            $username = 'username' . time();
            $user = $this->userRepository->createUser($username);
        }

        return $user;
    }

    public function getUserFromRequest(Request $request): User
    {
        $data = json_decode($request->getContent(), true);

        $userId = isset($data['userId']) ? $data['userId'] : 0;

        return $this->userRepository->findUser($userId);
    }
}
