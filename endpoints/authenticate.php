<?php

declare(strict_types=1);

use MicroDB\Database;
use Santik\Tickets\Application\UserService;
use Santik\Tickets\Infrastructure\MicroDbBasedUserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

require_once dirname(__FILE__) . "/../vendor/autoload.php";

$database = new Database(dirname(__FILE__) . '/../data/database');
$userRepository = new MicroDbBasedUserRepository($database);
$userService = new UserService($userRepository);

$request = Request::createFromGlobals();

try {
    $data = json_decode($request->getContent(), true);
    $user = $userService->findOrCreateUser($data['id']);
    $response = new JsonResponse(['user' => $user]);
} catch (\Exception $e) {
    $response = new JsonResponse(['message' => $e->getMessage()], 500);
}

$response->send();