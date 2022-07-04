<?php

use Doctrine\DBAL\Connection;
use Eshop\App\EbookController;
use Eshop\App\OrderController;
use Ecotone\Lite\EcotoneLiteApplication;

require __DIR__ . '/vendor/autoload.php';

/* @var Connection $connection */
$connection = include_once __DIR__ . '/migrations-db.php';
$application = EcotoneLiteApplication::boostrap([Connection::class => $connection]);

// preparation
$connection->executeStatement(<<<SQL
    DELETE FROM ebooks;
    DELETE FROM orders;
    DELETE FROM promotions;
    SQL
);

/** @var EbookController $ebookController */
$ebookController = $application->getServiceFromContainer(EbookController::class);
/** @var OrderController $orderController */
$orderController = $application->getServiceFromContainer(OrderController::class);

$dogStoryEbookId = 1;
$ebookController->registerEbook(json_encode([
    "ebookId" => $dogStoryEbookId,
    "title"   => "Happy Dog Story",
    "content" => "Dog went for a walk and is happy because of that!",
    "price"   => 10
]));
$cookbookId = 2;
$ebookController->registerEbook(json_encode([
    "ebookId" => $cookbookId,
    "title"   => "Cookbook - Home Recipes",
    "content" => "To make scrambled eggs, you need to first have eggs.",
    "price"   => 20
]));

echo "Ebook {$dogStoryEbookId} was registered" . PHP_EOL;

echo sprintf("Current ebook: %s\n", $ebookController->getEbook($dogStoryEbookId));

echo "Making order for two books" . PHP_EOL;

$orderController->placeOrder(json_encode([
    "email" => "phpcodehead@gmail.com",
    "ebookIds" => [$dogStoryEbookId, $cookbookId],
    "creditCard" => [
        "number" => "4242424242424242",
        "validTillMonth" => 12,
        "validTillYear" => 2028,
        "cvc" => 123
    ]
    ]));

echo sprintf("Orders history:\n%s\n", $orderController->getOrders());
