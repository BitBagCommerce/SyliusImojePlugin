<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Bus\Handler;

use BitBag\SyliusImojePlugin\Bus\Query\GetTransactionData;
use BitBag\SyliusImojePlugin\Entity\ImojeTransactionInterface;
use BitBag\SyliusImojePlugin\Exception\InvalidImojeResponseException;
use BitBag\SyliusImojePlugin\Factory\Model\TransactionModelFactoryInterface;
use BitBag\SyliusImojePlugin\Factory\Transaction\ImojeTransactionFactoryInterface;
use BitBag\SyliusImojePlugin\Provider\ImojeClientConfigurationProviderInterface;
use BitBag\SyliusImojePlugin\Provider\ImojeClientProviderInterface;
use BitBag\SyliusImojePlugin\Resolver\TransactionData\TransactionDataResolverInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class GetTransactionDataHandler
{
    private ImojeClientConfigurationProviderInterface $configurationProvider;
    private TransactionModelFactoryInterface $transactionModelFactory;
    private ImojeClientProviderInterface $imojeClientProvider;
    private ImojeTransactionFactoryInterface $imojeTransactionFactory;
    private TransactionDataResolverInterface $transactionDataResolver;

    public function __construct(
        ImojeClientConfigurationProviderInterface $configurationProvider,
        TransactionModelFactoryInterface $transactionModelFactory,
        ImojeClientProviderInterface $imojeClientProvider,
        ImojeTransactionFactoryInterface $imojeTransactionFactory,
        TransactionDataResolverInterface $transactionDataResolver
    ) {
        $this->configurationProvider = $configurationProvider;
        $this->transactionModelFactory = $transactionModelFactory;
        $this->imojeClientProvider = $imojeClientProvider;
        $this->imojeTransactionFactory = $imojeTransactionFactory;
        $this->transactionDataResolver = $transactionDataResolver;
    }

    public function __invoke(GetTransactionData $query): ImojeTransactionInterface
    {
        $code = $query->getCode();
        $config = $this->configurationProvider->getPaymentMethodConfiguration($code);

        $transactionModel = $this->transactionModelFactory->create(
            $query->getOrder(),
            $config,
            TransactionModelFactoryInterface::SALE_TYPE,
            $query->getPaymentMethod(),
            $query->getPaymentMethodCode(),
            $config->getServiceId()
        );

        $response = $this->imojeClientProvider
            ->getClient($code)
            ->createTransaction($transactionModel);

        $data = $this->transactionDataResolver->resolve($response);

        $paymentUrl = $data['paymentUrl'] ?? null;
        $transactionId = $data['transactionId'] ?? null;
        $serviceId = $data['serviceId'] ?? null;
        $orderId = $data['orderId'] ?? null;

        if (!$paymentUrl || !$transactionId || !$serviceId || !$orderId) {
            throw new InvalidImojeResponseException('No configured transaction');
        }

        return $this->imojeTransactionFactory->create(
            $query->getOrder()->getLastPayment(),
            $transactionId,
            $paymentUrl,
            $serviceId,
            $orderId,
            $query->getCode()
        );
    }
}
