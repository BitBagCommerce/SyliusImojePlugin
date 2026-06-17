<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Bus\Handler;

use BitBag\SyliusIngPayPlugin\Bus\Query\GetTransactionData;
use BitBag\SyliusIngPayPlugin\Entity\IngPayTransactionInterface;
use BitBag\SyliusIngPayPlugin\Exception\InvalidIngPayResponseException;
use BitBag\SyliusIngPayPlugin\Factory\Model\TransactionModelFactoryInterface;
use BitBag\SyliusIngPayPlugin\Factory\Transaction\IngPayTransactionFactoryInterface;
use BitBag\SyliusIngPayPlugin\Provider\IngPayClientConfigurationProviderInterface;
use BitBag\SyliusIngPayPlugin\Provider\IngPayClientProviderInterface;
use BitBag\SyliusIngPayPlugin\Resolver\TransactionData\TransactionDataResolverInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class GetTransactionDataHandler implements MessageHandlerInterface
{
    private IngPayClientConfigurationProviderInterface $configurationProvider;

    private TransactionModelFactoryInterface $transactionModelFactory;

    private IngPayClientProviderInterface $ingPayClientProvider;

    private IngPayTransactionFactoryInterface $ingPayTransactionFactory;

    private TransactionDataResolverInterface $transactionDataResolver;

    public function __construct(
        IngPayClientConfigurationProviderInterface $configurationProvider,
        TransactionModelFactoryInterface $transactionModelFactory,
        IngPayClientProviderInterface $ingPayClientProvider,
        IngPayTransactionFactoryInterface $ingPayTransactionFactory,
        TransactionDataResolverInterface $transactionDataResolver,
    ) {
        $this->configurationProvider = $configurationProvider;
        $this->transactionModelFactory = $transactionModelFactory;
        $this->ingPayClientProvider = $ingPayClientProvider;
        $this->ingPayTransactionFactory = $ingPayTransactionFactory;
        $this->transactionDataResolver = $transactionDataResolver;
    }

    public function __invoke(GetTransactionData $query): IngPayTransactionInterface
    {
        $code = $query->getCode();
        $config = $this->configurationProvider->getPaymentMethodConfiguration($code);

        $transactionModel = $this->transactionModelFactory->create(
            $query->getOrder(),
            $config,
            $this->transactionModelFactory::SALE_TYPE,
            $query->getPaymentMethod(),
            $query->getPaymentMethodCode(),
            $config->getServiceId(),
        );

        $response = $this->ingPayClientProvider
            ->getClient($code)
            ->createTransaction($transactionModel)
        ;

        $data = $this->transactionDataResolver->resolve($response);

        $paymentUrl = $data['paymentUrl'];
        $transactionId = $data['transactionId'];
        $serviceId = $data['serviceId'];
        $orderId = $data['orderId'];

        if (!$paymentUrl || !$transactionId || !$serviceId || !$orderId) {
            throw new InvalidIngPayResponseException('No configured transaction');
        }

        return $this->ingPayTransactionFactory->create(
            $query->getOrder()->getLastPayment(),
            $transactionId,
            $paymentUrl,
            $serviceId,
            $orderId,
            $query->getCode(),
        );
    }
}
