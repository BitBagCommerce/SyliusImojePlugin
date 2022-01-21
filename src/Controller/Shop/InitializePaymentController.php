<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Controller\Shop;

use BitBag\SyliusIngPlugin\Model\BillingModel;
use BitBag\SyliusIngPlugin\Model\CustomerModel;
use BitBag\SyliusIngPlugin\Model\ShippingModel;
use BitBag\SyliusIngPlugin\Model\TransactionModel;
use BitBag\SyliusIngPlugin\Provider\IngClientProviderInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Request;

final class InitializePaymentController
{
    private IngClientProviderInterface $ingClientProvider;

    public function __construct(IngClientProviderInterface $ingClientProvider)
    {
        $this->ingClientProvider = $ingClientProvider;
    }

    public function __invoke(Request $request): ResponseInterface
    {
        $customerModel = new CustomerModel('Piotr', 'Szymanski', '123', 'Bitbag', '786945333', 'jan.kowalski@example.com');
        $billingModel = new BillingModel(
            'Piotr',
            'Szymanski',
            'Bitbag',
            'Jalowiec',
            'Luban',
            'Region',
            '59-800',
            'PL'
        );

        $shippingModel = new ShippingModel(
            'Piotr',
            'Szymanski',
            'Bitbag',
            'Jalowiec',
            'Luban',
            'Region',
            '59-800',
            'PL'
        );

        $transactionModel = new TransactionModel(
            'sale',
            '2ec212fc-d2a9-46e2-9414-e45afdcb47ea',
            1,
            'PLN',
            'BitBag payment',
            '123123123',
            'pbl',
            'ipko',
            'https://localhost:8000/en_US/',
            'https://localhost:8000/admin',
            $customerModel,
            $billingModel,
            $shippingModel
        );
        $client = $this->ingClientProvider->getClient('ing_code');

        return $client->createTransaction($transactionModel);
    }
}
