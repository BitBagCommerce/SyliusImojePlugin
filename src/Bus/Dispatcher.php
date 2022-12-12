<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Bus;

use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class Dispatcher implements DispatcherInterface
{
    use HandleTrait;

    public function __construct(
        MessageBusInterface $messageBus
    ) {
        $this->messageBus = $messageBus;
    }

    /**
     * @return mixed
     */
    public function dispatch(object $action)
    {
        return $this->handle($action);
    }
}
