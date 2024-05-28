<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Bus\Handler;

use BitBag\SyliusImojePlugin\Bus\Query\GetTransactionData;
use BitBag\SyliusImojePlugin\Entity\IngTransactionInterface;
use BitBag\SyliusImojePlugin\Exception\InvalidIngResponseException;
use BitBag\SyliusImojePlugin\Factory\Model\TransactionModelFactoryInterface;
use BitBag\SyliusImojePlugin\Factory\Transaction\IngTransactionFactoryInterface;
use BitBag\SyliusImojePlugin\Provider\IngClientConfigurationProviderInterface;
use BitBag\SyliusImojePlugin\Provider\IngClientProviderInterface;
use BitBag\SyliusImojePlugin\Resolver\TransactionData\TransactionDataResolverInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class GetTransactionDataHandler implements MessageHandlerInterface
{
    private IngClientConfigurationProviderInterface $configurationProvider;

    private TransactionModelFactoryInterface $transactionModelFactory;

    private IngClientProviderInterface $ingClientProvider;

    private IngTransactionFactoryInterface $ingTransactionFactory;

    private TransactionDataResolverInterface $transactionDataResolver;

    public function __construct(
        IngClientConfigurationProviderInterface $configurationProvider,
        TransactionModelFactoryInterface $transactionModelFactory,
        IngClientProviderInterface $ingClientProvider,
        IngTransactionFactoryInterface $ingTransactionFactory,
        TransactionDataResolverInterface $transactionDataResolver
    ) {
        $this->configurationProvider = $configurationProvider;
        $this->transactionModelFactory = $transactionModelFactory;
        $this->ingClientProvider = $ingClientProvider;
        $this->ingTransactionFactory = $ingTransactionFactory;
        $this->transactionDataResolver = $transactionDataResolver;
    }

    public function __invoke(GetTransactionData $query): IngTransactionInterface
    {
        $code = $query->getCode();
        $config = $this->configurationProvider->getPaymentMethodConfiguration($code);

        $transactionModel = $this->transactionModelFactory->create(
            $query->getOrder(),
            $config,
            $this->transactionModelFactory::SALE_TYPE,
            $query->getPaymentMethod(),
            $query->getPaymentMethodCode(),
            $config->getServiceId()
        );

        $response = $this->ingClientProvider
            ->getClient($code)
            ->createTransaction($transactionModel)
        ;

        $data = $this->transactionDataResolver->resolve($response);

        $paymentUrl = $data['paymentUrl'];
        $transactionId = $data['transactionId'];
        $serviceId = $data['serviceId'];
        $orderId = $data['orderId'];

        if (!$paymentUrl || !$transactionId || !$serviceId || !$orderId) {
            throw new InvalidIngResponseException('No configured transaction');
        }

        return $this->ingTransactionFactory->create(
            $query->getOrder()->getLastPayment(),
            $transactionId,
            $paymentUrl,
            $serviceId,
            $orderId,
            $query->getCode()
        );
    }
}
