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
        return $data;
    }
}
