<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\Payment;

use BitBag\SyliusIngPlugin\Exception\IngNotConfiguredException;
use BitBag\SyliusIngPlugin\Repository\PaymentMethodRepository;

final class IngPaymentsMethodResolver implements IngPaymentsMethodResolverInterface
{
    private PaymentMethodRepository $paymentMethodRepository;

    public function __construct(PaymentMethodRepository $paymentMethodRepository)
    {
        $this->paymentMethodRepository = $paymentMethodRepository;
    }

    public function resolve(): ?array
    {
        $payment = $this->paymentMethodRepository->findOneForIng();

        if ($payment === null) {
            throw new IngNotConfiguredException('Payment method is not configured');
        }

        $config = $payment->getGatewayConfig();

        if ($config === null) {
            throw new IngNotConfiguredException('Payment method is not configured');
        }

        $data = $config->getConfig();

        if (array_key_exists('isProd', $data)) {
            unset($data['isProd']);
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
                if (!$value && $key !== 'ing' && $key !== 'card' && $key !== 'blik') {
                    unset($data[$key]);
                }
            }
        }

        if (!$data['pbl']) {
            foreach ($data as $key => $value) {
                if (($key !== 'ing' && $key !== 'card' && $key !== 'blik')) {
                    unset($data[$key]);
                }
            }
        }

        $finalData = [];

        foreach ($data as $key => $value) {
            $finalData[$key] = $key;
        }

        return $finalData;
    }
}
