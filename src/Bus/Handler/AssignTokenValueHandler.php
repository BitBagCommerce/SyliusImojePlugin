<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Bus\Handler;

use BitBag\SyliusImojePlugin\Bus\Command\AssignTokenValue;

final class AssignTokenValueHandler
{
    public const ORDER_ID_KEY = 'sylius_order_id';

    public function __invoke(AssignTokenValue $command): void
    {
        $request = $command->getRequest();
        $order = $command->getOrder();

        if (null === $request->get('tokenValue')) {
            $request->getSession()->set(self::ORDER_ID_KEY, $order->getId());
        }
    }
}
