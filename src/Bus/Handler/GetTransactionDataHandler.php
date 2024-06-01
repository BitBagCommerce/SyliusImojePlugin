<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Bus\Handler;

use BitBag\SyliusImojePlugin\Bus\Query\GetTransactionData;
use BitBag\SyliusImojePlugin\Entity\ImojeTransactionInterface;
use BitBag\SyliusImojePlugin\Exception\InvalidIngResponseException;
use BitBag\SyliusImojePlugin\Factory\Model\TransactionModelFactoryInterface;
use BitBag\SyliusImojePlugin\Factory\Transaction\ImojeTransactionFactoryInterface;
use BitBag\SyliusImojePlugin\Provider\ImojeClientConfigurationProviderInterface;
use BitBag\SyliusImojePlugin\Provider\ImojeClientProviderInterface;
use BitBag\SyliusImojePlugin\Resolver\TransactionData\TransactionDataResolverInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class GetTransactionDataHandler implements MessageHandlerInterface
{
    private ImojeClientConfigurationProviderInterface $configurationProvider;

    private TransactionModelFactoryInterface $transactionModelFactory;

    private ImojeClientProviderInterface $ingClientProvider;

    private ImojeTransactionFactoryInterface $ingTransactionFactory;

    private TransactionDataResolverInterface $transactionDataResolver;

    public function __construct(
        ImojeClientConfigurationProviderInterface $configurationProvider,
        TransactionModelFactoryInterface          $transactionModelFactory,
        ImojeClientProviderInterface              $ingClientProvider,
        ImojeTransactionFactoryInterface          $ingTransactionFactory,
        TransactionDataResolverInterface          $transactionDataResolver
    ) {
        $this->configurationProvider = $configurationProvider;
        $this->transactionModelFactory = $transactionModelFactory;
        $this->ingClientProvider = $ingClientProvider;
        $this->ingTransactionFactory = $ingTransactionFactory;
        $this->transactionDataResolver = $transactionDataResolver;
    }

    public function __invoke(GetTransactionData $query): ImojeTransactionInterface
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
