<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Filter;

use BitBag\SyliusIngPlugin\Model\PaymentMethod\PaymentMethodModelInterface;
use BitBag\SyliusIngPlugin\Provider\IngClientProviderInterface;

final class AvailablePaymentMethodsFilter implements AvailablePaymentMethodsFilterInterface
{
    private IngClientProviderInterface $clientProvider;

    public function __construct(IngClientProviderInterface $clientProvider)
    {
        $this->clientProvider = $clientProvider;
    }

    public function filter(
        string $code,
        string $serviceId,
        array $paymentMethods
    ): array {
        $paymentMethods = \array_keys($paymentMethods);

        $client = $this->clientProvider->getClient($code);
        $serviceModel = $client->getShopInfo($serviceId);
        $availablePaymentMethods = $serviceModel->getPaymentMethods();

        $result = [];

        /** @var PaymentMethodModelInterface $availablePaymentMethod */
        foreach ($availablePaymentMethods as $availablePaymentMethod) {
            $paymentMethodCode = $availablePaymentMethod->getPaymentMethodCode();

            if (
                \in_array($paymentMethodCode, $paymentMethods, true) &&
                $availablePaymentMethod->isActive()
            ) {
                $result[$paymentMethodCode] = $paymentMethodCode;
            }

            $paymentMethodType = $availablePaymentMethod->getPaymentMethod();

            if (self::TYPE_CARD === $paymentMethodType || self::TYPE_PBL === $paymentMethodType) {
                $result[$paymentMethodType] = $paymentMethodType;
            }
        }

        return $result;
    }
}
