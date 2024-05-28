<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Factory\Model;

use BitBag\SyliusImojePlugin\Configuration\IngClientConfigurationInterface;
use BitBag\SyliusImojePlugin\Model\Blik\BlikModelInterface;
use BitBag\SyliusImojePlugin\Model\TransactionModelInterface;
use Sylius\Component\Core\Model\OrderInterface;

interface TransactionBlikModelFactoryInterface
{
    public const SALE_TYPE = 'sale';

    public function create(
        OrderInterface $order,
        IngClientConfigurationInterface $ingClientConfiguration,
        string $type,
        string $paymentMethod,
        string $paymentMethodCode,
        string $serviceId,
        BlikModelInterface $blikModel
    ): TransactionModelInterface;
}
