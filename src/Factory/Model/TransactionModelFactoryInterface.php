<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Factory\Model;

use BitBag\SyliusIngPayPlugin\Configuration\IngPayClientConfigurationInterface;
use BitBag\SyliusIngPayPlugin\Model\TransactionModelInterface;
use Sylius\Component\Core\Model\OrderInterface;

interface TransactionModelFactoryInterface
{
    public const REDIRECT_URL = 'bitbag_ing_pay_redirect';

    public const REDIRECT_ONECLICK_URL = 'bitbag_ing_pay_one_click_redirect';

    public const SALE_TYPE = 'sale';

    public function create(
        OrderInterface $order,
        IngPayClientConfigurationInterface $ingPayClientConfiguration,
        string $type,
        string $paymentMethod,
        string $paymentMethodCode,
        string $serviceId,
    ): TransactionModelInterface;
}
