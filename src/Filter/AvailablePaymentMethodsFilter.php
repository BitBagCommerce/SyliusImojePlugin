<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Filter;

use BitBag\SyliusImojePlugin\Model\PaymentMethod\PaymentMethodModelInterface;
use BitBag\SyliusImojePlugin\Provider\ImojeClientProviderInterface;

final class AvailablePaymentMethodsFilter implements AvailablePaymentMethodsFilterInterface
{
    private ImojeClientProviderInterface $clientProvider;

    public function __construct(ImojeClientProviderInterface $clientProvider)
    {
        $this->clientProvider = $clientProvider;
    }

    public function filter(
        string $code,
        string $serviceId,
        array $paymentMethods,
        string $currency,
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
                $availablePaymentMethod->isActive() &&
                $currency === $availablePaymentMethod->getCurrency()
            ) {
                if ('ing' === $paymentMethodCode) {
                    $result = array_merge([$paymentMethodCode => $paymentMethodCode], $result);
                } else {
                    $result[$paymentMethodCode] = $paymentMethodCode;
                }
            }

            $paymentMethodType = $availablePaymentMethod->getPaymentMethod();

            if ((self::TYPE_CARD === $paymentMethodType ||
                self::TYPE_PBL === $paymentMethodType ||
                self::TYPE_PAY_LATER == $paymentMethodType) &&
                $currency === $availablePaymentMethod->getCurrency()
            ) {
                $result[$paymentMethodType] = $paymentMethodType;
            }
        }

        return $result;
    }
}
