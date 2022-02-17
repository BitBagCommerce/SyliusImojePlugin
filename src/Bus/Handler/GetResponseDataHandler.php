<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Bus\Handler;

use BitBag\SyliusIngPlugin\Bus\Query\GetResponseData;
use BitBag\SyliusIngPlugin\Client\IngApiClientInterface;
use BitBag\SyliusIngPlugin\Configuration\IngClientConfigurationInterface;
use BitBag\SyliusIngPlugin\Entity\IngTransactionInterface;
use BitBag\SyliusIngPlugin\Exception\NoDataFromResponseException;
use BitBag\SyliusIngPlugin\Exception\NoTransactionException;
use BitBag\SyliusIngPlugin\Factory\ReadyTransaction\ReadyTransactionFactoryInterface;
use BitBag\SyliusIngPlugin\Model\ReadyTransaction\ReadyTransactionModelInterface;
use BitBag\SyliusIngPlugin\Provider\IngClientConfigurationProviderInterface;
use BitBag\SyliusIngPlugin\Provider\IngClientProviderInterface;
use BitBag\SyliusIngPlugin\Repository\IngTransaction\IngTransactionRepositoryInterface;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\OrderRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class GetResponseDataHandler implements MessageHandlerInterface
{
    private IngTransactionRepositoryInterface $ingTransactionRepository;

    private IngClientProviderInterface $ingClientProvider;

    private IngClientConfigurationProviderInterface $configurationProvider;

    private ReadyTransactionFactoryInterface $readyTransactionFactory;

    private OrderRepository $orderRepository;

    public function __construct(
        IngTransactionRepositoryInterface $ingTransactionRepository,
        IngClientProviderInterface $ingClientProvider,
        IngClientConfigurationProviderInterface $configurationProvider,
        ReadyTransactionFactoryInterface $readyTransactionFactory,
        OrderRepository $orderRepository
    ) {
        $this->ingTransactionRepository = $ingTransactionRepository;
        $this->ingClientProvider = $ingClientProvider;
        $this->configurationProvider = $configurationProvider;
        $this->readyTransactionFactory = $readyTransactionFactory;
        $this->orderRepository = $orderRepository;
    }

    public function __invoke(GetResponseData $query): ReadyTransactionModelInterface
    {
        /** @var IngTransactionInterface|null $ingTransaction */
        $ingTransaction = $this->ingTransactionRepository->findByPaymentId($query->getPaymentId());

        if ($ingTransaction === null) {
            throw new NoTransactionException('Could not find transaction');
        }
        $code = $ingTransaction->getGatewayCode();
        $config = $this->configurationProvider->getPaymentMethodConfiguration($code);
        $client = $this->ingClientProvider->getClient($code);
        $url = $this->createUrl($config, $ingTransaction, $client);

        $response = $client->gettingTransactionData($url, $config->getToken());

        /** @var array $transactionData */
        $transactionData = json_decode($response->getBody()->getContents(), true);

        if ($transactionData['transaction'] === null || $transactionData['transaction']['status'] === null) {
            throw new NoDataFromResponseException('No data from response');
        }
        $order = $this->orderRepository->find($ingTransaction->getOrderId());

        return $this->readyTransactionFactory->createReadyTransaction(
            $transactionData['transaction']['status'],
            $ingTransaction,
            $order
        );
    }

    private function createUrl(
        IngClientConfigurationInterface $config,
        IngTransactionInterface $ingTransaction,
        IngApiClientInterface $client
    ): string {
        return \sprintf(
            '%s/%s/%s/%s',
            $config->getSandboxUrl(),
            $config->getMerchantId(),
            $client::TRANSACTION_ENDPOINT,
            $ingTransaction->getTransactionId()
        );
    }
}
