<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\Payment;

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
        $data = [];
        $data['data'] = $this->paymentMethodRepository->getOneForIng()->getGatewayConfig()->getConfig();

        if (array_key_exists('isProd', $data['data'])) {
            unset($data['data']['isProd']);
        }

        if (array_key_exists('token', $data['data'])) {
            unset($data['data']['token']);
        }

        if (array_key_exists('prodUrl', $data['data'])) {
            unset($data['data']['prodUrl']);
        }

        if (array_key_exists('redirect', $data['data'])) {
            unset($data['data']['redirect']);
        }

        if (array_key_exists('sandboxUrl', $data['data'])) {
            unset($data['data']['sandboxUrl']);
        }

        if (array_key_exists('merchantId', $data['data'])) {
            unset($data['data']['merchantId']);
        }

        return $data;
    }
}
