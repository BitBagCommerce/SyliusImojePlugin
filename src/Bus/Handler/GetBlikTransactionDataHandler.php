<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Bus\Handler;

use BitBag\SyliusImojePlugin\Bus\Query\GetBlikTransactionData;
use BitBag\SyliusImojePlugin\Entity\ImojeTransactionInterface;
use BitBag\SyliusImojePlugin\Exception\InvalidImojeResponseException;
use BitBag\SyliusImojePlugin\Factory\Model\TransactionBlikModelFactoryInterface;
use BitBag\SyliusImojePlugin\Factory\Transaction\ImojeTransactionFactoryInterface;
use BitBag\SyliusImojePlugin\Provider\ImojeClientConfigurationProviderInterface;
use BitBag\SyliusImojePlugin\Provider\ImojeClientProviderInterface;
use BitBag\SyliusImojePlugin\Resolver\TransactionData\TransactionDataResolverInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class GetBlikTransactionDataHandler implements MessageHandlerInterface
{
    private ImojeClientConfigurationProviderInterface $configurationProvider;

    private TransactionBlikModelFactoryInterface $transactionBlikModelFactory;

    private ImojeClientProviderInterface $imojeClientProvider;

    private ImojeTransactionFactoryInterface $imojeTransactionFactory;

    private TransactionDataResolverInterface $transactionDataResolver;

    public function __construct(
        ImojeClientConfigurationProviderInterface $configurationProvider,
        TransactionBlikModelFactoryInterface $transactionBlikModelFactory,
        ImojeClientProviderInterface $imojeClientProvider,
        ImojeTransactionFactoryInterface $imojeTransactionFactory,
        TransactionDataResolverInterface $transactionDataResolver,
    ) {
        $this->configurationProvider = $configurationProvider;
        $this->transactionBlikModelFactory = $transactionBlikModelFactory;
        $this->imojeClientProvider = $imojeClientProvider;
        $this->imojeTransactionFactory = $imojeTransactionFactory;
        $this->transactionDataResolver = $transactionDataResolver;
    }

    public function __invoke(GetBlikTransactionData $query): ImojeTransactionInterface
    {
        $code = $query->getCode();
        $config = $this->configurationProvider->getPaymentMethodConfiguration($code);

        $transactionModel = $this->transactionBlikModelFactory->create(
            $query->getOrder(),
            $config,
            $this->transactionBlikModelFactory::SALE_TYPE,
            $query->getPaymentMethod(),
            $query->getPaymentMethodCode(),
            $config->getServiceId(),
            $query->getBlikModel(),
        );

        $response = $this->imojeClientProvider
            ->getClient($code)
            ->createTransaction($transactionModel)
        ;

        $data = $this->transactionDataResolver->resolve($response);

        $paymentUrl = $data['paymentUrl'];
        $transactionId = $data['transactionId'];
        $serviceId = $data['serviceId'];
        $orderId = $data['orderId'];

        if (!$paymentUrl || !$transactionId || !$serviceId || !$orderId) {
            throw new InvalidImojeResponseException('No configured transaction');
        }

        return $this->imojeTransactionFactory->create(
            $query->getOrder()->getLastPayment(),
            $transactionId,
            $paymentUrl,
            $serviceId,
            $orderId,
            $query->getCode(),
        );
    }
}
