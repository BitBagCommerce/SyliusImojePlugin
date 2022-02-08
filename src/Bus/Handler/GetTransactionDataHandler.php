<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Bus\Handler;

use BitBag\SyliusIngPlugin\Bus\Query\GetTransactionData;
use BitBag\SyliusIngPlugin\Entity\IngTransactionInterface;
use BitBag\SyliusIngPlugin\Factory\Model\TransactionModelFactory;
use BitBag\SyliusIngPlugin\Factory\Transaction\IngTransactionFactoryInterface;
use BitBag\SyliusIngPlugin\Provider\IngClientConfigurationProviderInterface;
use BitBag\SyliusIngPlugin\Provider\IngClientProviderInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class GetTransactionDataHandler implements MessageHandlerInterface
{
    public const SALE_TYPE = 'sale';

    private IngClientConfigurationProviderInterface $configurationProvider;

    private TransactionModelFactory $transactionModelFactory;

    private IngClientProviderInterface $ingClientProvider;

    private IngTransactionFactoryInterface $ingTransactionFactory;

    public function __construct(
        IngClientConfigurationProviderInterface $configurationProvider,
        TransactionModelFactory $transactionModelFactory,
        IngClientProviderInterface $ingClientProvider,
        IngTransactionFactoryInterface $ingTransactionFactory
    ) {
        $this->configurationProvider = $configurationProvider;
        $this->transactionModelFactory = $transactionModelFactory;
        $this->ingClientProvider = $ingClientProvider;
        $this->ingTransactionFactory = $ingTransactionFactory;
    }

    public function __invoke(GetTransactionData $query): IngTransactionInterface
    {
        $code = $query->getCode();
        $config = $this->configurationProvider->getPaymentMethodConfiguration($code);

        $transactionModel = $this->transactionModelFactory->create(
            $query->getOrder(),
            $config,
            self::SALE_TYPE,
            $query->getPaymentMethod(),
            $query->getPaymentMethodCode()
        );

        $response = $this->ingClientProvider
            ->getClient($code)
            ->createTransaction($transactionModel)
        ;

        $paymentUrl = 'Example_URL';
        $transactionId = 'id';

        /** @var IngTransactionInterface $transaction */
        $transaction = $this->ingTransactionFactory->createForPayment(
            $query->getOrder()->getLastPayment(),
            $transactionId,
            $paymentUrl
        );

        return $transaction;
    }
}
