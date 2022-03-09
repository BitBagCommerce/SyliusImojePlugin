<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\Payment;

use BitBag\SyliusIngPlugin\Exception\IngNotConfiguredException;
use BitBag\SyliusIngPlugin\Filter\AvailablePaymentMethodsFilterInterface;
use BitBag\SyliusIngPlugin\Repository\PaymentMethodRepositoryInterface;

final class IngPaymentsMethodResolver implements IngPaymentsMethodResolverInterface
{
    private PaymentMethodRepositoryInterface $paymentMethodRepository;

    private AvailablePaymentMethodsFilterInterface $paymentMethodsFilter;

    public function __construct(
        PaymentMethodRepositoryInterface $paymentMethodRepository,
        AvailablePaymentMethodsFilterInterface $paymentMethodsFilter
    ) {
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->paymentMethodsFilter = $paymentMethodsFilter;
    }

    public function resolve(): array
    {
        $payment = $this->paymentMethodRepository->findOneForIng();

        if (null === $payment) {
            throw new IngNotConfiguredException('Payment method is not configured');
        }

        $config = $payment->getGatewayConfig();

        if (null === $config) {
            throw new IngNotConfiguredException('Payment method is not configured');
        }

        $data = $config->getConfig();

        if (array_key_exists('isProd', $data)) {
            unset($data['isProd']);
        }

        if (array_key_exists('serviceId', $data)) {
            unset($data['serviceId']);
        }

        if (array_key_exists('shopKey', $data)) {
            unset($data['shopKey']);
        }

        if (array_key_exists('token', $data)) {
            unset($data['token']);
        }

        if (array_key_exists('prodUrl', $data)) {
            unset($data['prodUrl']);
        }

        if (array_key_exists('redirect', $data)) {
            unset($data['redirect']);
        }

        if (array_key_exists('sandboxUrl', $data)) {
            unset($data['sandboxUrl']);
        }

        if (array_key_exists('merchantId', $data)) {
            unset($data['merchantId']);
        }

        if ($data['pbl']) {
            foreach ($data as $key => $value) {
                if (!$value && 'ing' !== $key && 'card' !== $key && 'blik' !== $key) {
                    unset($data[$key]);
                }
            }
        }

        if (!$data['pbl']) {
            foreach ($data as $key => $value) {
                if (('ing' !== $key && 'card' !== $key && 'blik' !== $key)) {
                    unset($data[$key]);
                }
            }
        }

        $paymentMethodConfig = $config->getConfig();
        $serviceId = $paymentMethodConfig['serviceId'] ?? '';

        $isPblPayment = in_array('pbl', $data);
        $data = $this->paymentMethodsFilter->filter($config->getGatewayName(), $serviceId, $data);
        $finalData = [];

        foreach ($data as $key => $value) {
            $finalData[$key] = $key;
        }

        if (!$isPblPayment) {
            unset($finalData['pbl']);
        }

        return $finalData;
    }
}
