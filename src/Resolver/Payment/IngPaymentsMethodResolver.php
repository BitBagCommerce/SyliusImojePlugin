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
        $data = $this->paymentMethodRepository->getOneForIng()->getGatewayConfig()->getConfig();
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

        if ($data['pbl'] == true)
        {
            foreach ($data as $key => $value)
            {
                if (($value == false || $value == null) && ($key != 'ing' && $key != 'card' && $key != 'blik'))
                {
                    unset($data[$key]);
                }
            }
        }

        if ($data['pbl'] == false)
        {
            foreach ($data as $key => $value)
            {
                if (($key != 'ing' && $key != 'card' && $key != 'blik'))
                {
                    unset($data[$key]);
                }
            }
        }

        foreach ($data as $key => $value)
        {
            $finalData[$key] = $key;
        }

        return $finalData;
    }
}
