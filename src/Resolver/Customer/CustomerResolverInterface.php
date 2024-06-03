<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\Customer;

use Sylius\Component\Core\Model\OrderInterface;

interface CustomerResolverInterface
{
    public function resolveFirstname(OrderInterface $order): ?string;

    public function resolveLastname(OrderInterface $order): ?string;

    public function resolvePhoneNumber(OrderInterface $order): ?string;
}
