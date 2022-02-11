<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Bus\Handler;

use BitBag\SyliusIngPlugin\Bus\Query\GetTransactionData;
use BitBag\SyliusIngPlugin\Entity\IngTransactionInterface;
use BitBag\SyliusIngPlugin\Exception\NoDataFromResponseException;
use BitBag\SyliusIngPlugin\Factory\Model\TransactionModelFactoryInterface;
use BitBag\SyliusIngPlugin\Factory\Transaction\IngTransactionFactoryInterface;
use BitBag\SyliusIngPlugin\Provider\IngClientConfigurationProviderInterface;
use BitBag\SyliusIngPlugin\Provider\IngClientProviderInterface;
use BitBag\SyliusIngPlugin\Resolver\TransactionData\TransactionDataResolverInterface;
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
        if ($query->getPaymentMethod() === 'blik') {
            $transactionModel = $this->transactionModelFactory->create(
                $query->getOrder(),
                $config,
                $this->transactionModelFactory::SALE_TYPE,
                $query->getPaymentMethod(),
                $query->getPaymentMethodCode(),
                $config->getServiceId(),
                $query->getBlikModel()
            );
        } else {
            $transactionModel = $this->transactionModelFactory->create(
                $query->getOrder(),
                $config,
                $this->transactionModelFactory::SALE_TYPE,
                $query->getPaymentMethod(),
                $query->getPaymentMethodCode(),
                $config->getServiceId(),
                null
            );
        }

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
            throw new NoDataFromResponseException('No configured transaction');
        }

        return $this->ingTransactionFactory->create(
            $query->getOrder()->getLastPayment(),
            $transactionId,
            $paymentUrl,
            $serviceId,
            $orderId
        );
    }
}
