<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Factory\Model;

use BitBag\SyliusImojePlugin\Configuration\ImojeClientConfigurationInterface;
use BitBag\SyliusImojePlugin\Model\TransactionModelInterface;
use Sylius\Component\Core\Model\OrderInterface;

interface TransactionModelFactoryInterface
{
    public const REDIRECT_URL = 'bitbag_imoje_redirect';

    public const REDIRECT_ONECLICK_URL = 'bitbag_imoje_one_click_redirect';

    public const SALE_TYPE = 'sale';

    public function create(
        OrderInterface $order,
        ImojeClientConfigurationInterface $imojeClientConfiguration,
        string $type,
        string $paymentMethod,
        string $paymentMethodCode,
        string $serviceId,
    ): TransactionModelInterface;
}
