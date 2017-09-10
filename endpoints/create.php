<?php

declare(strict_types=1);

require_once dirname(__FILE__) . "/../vendor/autoload.php";

use MicroDB\Database;
use Santik\Tickets\Application\ListingService;
use Santik\Tickets\Application\UserService;
use Santik\Tickets\Domain\Validator\ListingValidator;
use Santik\Tickets\Infrastructure\MicroDbBasedUserRepository;
use Santik\Tickets\Infrastructure\JsonRequestListingConverter;
use Santik\Tickets\Infrastructure\MicroDbBasedListingRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/* set up */
//using very simple json files based database
//path must be writable
$database = new Database(dirname(__FILE__) . '/../data/database');

$userRepository = new MicroDbBasedUserRepository($database);
$userService = new UserService($userRepository);

$listingRepository = new MicroDbBasedListingRepository($database);
$listingValidator = new ListingValidator($listingRepository);
$listingService = new ListingService($listingRepository, $listingValidator);

$converter = new JsonRequestListingConverter();

$request = Request::createFromGlobals();
/* end of set up */

//simple authentication protection
//if user is not found in database 403 is returned
try {
    $user = $userService->getUserFromRequest($request);
} catch (\Exception $e) {
    $response = new JsonResponse(['message' => 'You should be authenticated'], 403);
    $response->send();
    exit;
}


//here is 'real' application starts
try {
    //convert and save listing with owner
    $newListing = $listingService->create($converter->convert($request), $user);
    $response = new JsonResponse(['listing' => $newListing]);
} catch (\Exception $e) {
    $response = new JsonResponse(['message' => $e->getMessage()], 500);
}

$response->send();