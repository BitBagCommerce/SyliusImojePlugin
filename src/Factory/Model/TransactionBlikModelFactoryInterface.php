<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Factory\Model;

use BitBag\SyliusIngPayPlugin\Configuration\IngPayClientConfigurationInterface;
use BitBag\SyliusIngPayPlugin\Model\Blik\BlikModelInterface;
use BitBag\SyliusIngPayPlugin\Model\TransactionModelInterface;
use Sylius\Component\Core\Model\OrderInterface;

interface TransactionBlikModelFactoryInterface
{
    public const SALE_TYPE = 'sale';

    public function create(
        OrderInterface $order,
        IngPayClientConfigurationInterface $ingPayClientConfiguration,
        string $type,
        string $paymentMethod,
        string $paymentMethodCode,
        string $serviceId,
        BlikModelInterface $blikModel,
    ): TransactionModelInterface;
}
