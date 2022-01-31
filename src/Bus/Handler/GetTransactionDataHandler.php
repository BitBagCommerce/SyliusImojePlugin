<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Bus\Handler;

use BitBag\SyliusIngPlugin\Bus\Query\GetTransactionData;
use BitBag\SyliusIngPlugin\Factory\Model\TransactionModelFactory;
use BitBag\SyliusIngPlugin\Model\Transaction\TransactionData;
use BitBag\SyliusIngPlugin\Provider\IngClientConfigurationProviderInterface;
use BitBag\SyliusIngPlugin\Provider\IngClientProviderInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class GetTransactionDataHandler implements MessageHandlerInterface
{
    private IngClientConfigurationProviderInterface $configurationProvider;

    private TransactionModelFactory $transactionModelFactory;

    private IngClientProviderInterface $ingClientProvider;

    public function __construct(
        IngClientConfigurationProviderInterface $configurationProvider,
        TransactionModelFactory $transactionModelFactory,
        IngClientProviderInterface $ingClientProvider
    ) {
        $this->configurationProvider = $configurationProvider;
        $this->transactionModelFactory = $transactionModelFactory;
        $this->ingClientProvider = $ingClientProvider;
    }

    public function __invoke(GetTransactionData $query): TransactionData
    {
        $code = $query->getCode();

        $config = $this->configurationProvider->getPaymentMethodConfiguration($code);
        $redirect = $config->isRedirect();

        $transactionModel = $this->transactionModelFactory->create(
            $query->getOrder(),
            $config,
            '',
            '',
            ''
        );

        $response = $this->ingClientProvider
            ->getClient($code)
            ->createTransaction($transactionModel)
        ;

        $transactionId = 'transactionId';
        $redirectUrl = $config->isProd() ? $config->getProdUrl() : $config->getSandboxUrl();

        return new TransactionData('$transactionId', $redirect ? $redirectUrl : null);
    }
}
