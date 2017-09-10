<?php

declare(strict_types=1);

require_once dirname(__FILE__) . "/../vendor/autoload.php";

use MicroDB\Database;
use Santik\Tickets\Application\ListingService;
use Santik\Tickets\Domain\Validator\ListingValidator;
use Santik\Tickets\Infrastructure\MicroDbBasedListingRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

$database = new Database(dirname(__FILE__) . '/../data/database');

$listingRepository = new MicroDbBasedListingRepository($database);
$listingService = new ListingService($listingRepository, new ListingValidator($listingRepository));

$response = new JsonResponse([$listingService->getListingsForShow()]);
$response->send();