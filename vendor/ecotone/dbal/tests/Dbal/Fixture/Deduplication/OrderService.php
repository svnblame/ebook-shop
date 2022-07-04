<?php


namespace Test\Ecotone\Dbal\Fixture\AsynchronousChannelTransaction;

use Ecotone\Messaging\Attribute\Asynchronous;
use Ecotone\Messaging\Attribute\ServiceActivator;
use Ecotone\Messaging\MessagingException;
use Ecotone\Modelling\Attribute\CommandHandler;
use Ecotone\Modelling\Attribute\QueryHandler;
use InvalidArgumentException;

class OrderService
{
    private int $callCounter = 0;
    /** @var string[] */
    private array $orders = [];

    #[Asynchronous("processOrders")]
    #[CommandHandler("placeOrder", "placeOrderEndpoint")]
    public function placeOrder(string $order) : void
    {
        $this->callCounter++;
        $this->orders[] = $order;
    }

    #[QueryHandler("order.getRegistered")]
    public function getOrders(): array
    {
        return $this->orders;
    }
}