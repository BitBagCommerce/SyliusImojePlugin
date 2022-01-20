<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Controller\Shop;

use BitBag\SyliusIngPlugin\Model\BillingModel;
use BitBag\SyliusIngPlugin\Model\CustomerModel;
use BitBag\SyliusIngPlugin\Model\ShippingModel;
use BitBag\SyliusIngPlugin\Model\TransactionModel;
use BitBag\SyliusIngPlugin\Provider\IngClientProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class InitializePaymentController
{
    private IngClientProviderInterface $ingClientProvider;

    public function __construct(IngClientProviderInterface $ingClientProvider)
    {
        $this->ingClientProvider = $ingClientProvider;
    }

    public function __invoke(Request $request): Response
    {
        $customerModel = new CustomerModel('','','','','','',);
        $billingModel = new BillingModel('',
            '',
            '',
            '',
            '',
            '',
            '',
            ''
        );

        $shippingModel = new ShippingModel('',
            '',
            '',
            '',
            '',
            '',
            '',
            ''
        );

        $transactionModel = new TransactionModel('',
            '',
            1,
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            $customerModel,
            $billingModel,
            $shippingModel
        );

        $client = $this->ingClientProvider->getClient('ing_code');
        $response = $client->createTransaction($transactionModel);

        return new Response();
    }
}
