<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Bus\Handler;

use BitBag\SyliusIngPlugin\Bus\Query\GetResponseData;
use BitBag\SyliusIngPlugin\Entity\IngTransactionInterface;
use BitBag\SyliusIngPlugin\Exception\NoDataFromResponseException;
use BitBag\SyliusIngPlugin\Factory\ReadyTransaction\ReadyTransactionFactoryInterface;
use BitBag\SyliusIngPlugin\Model\ReadyTransaction\ReadyTransactionModelInterface;
use BitBag\SyliusIngPlugin\Provider\IngClientConfigurationProviderInterface;
use BitBag\SyliusIngPlugin\Provider\IngClientProviderInterface;
use BitBag\SyliusIngPlugin\Repository\IngTransaction\IngTransactionRepositoryInterface;
use BitBag\SyliusIngPlugin\Resolver\Url\UrlResolverInterface;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\OrderRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class GetResponseDataHandler implements MessageHandlerInterface
{
    private IngTransactionRepositoryInterface $ingTransactionRepository;

    private IngClientProviderInterface $ingClientProvider;

    private IngClientConfigurationProviderInterface $configurationProvider;

    private ReadyTransactionFactoryInterface $readyTransactionFactory;

    private OrderRepository $orderRepository;

    private UrlResolverInterface $urlResolver;

    public function __construct(
        IngTransactionRepositoryInterface $ingTransactionRepository,
        IngClientProviderInterface $ingClientProvider,
        IngClientConfigurationProviderInterface $configurationProvider,
        ReadyTransactionFactoryInterface $readyTransactionFactory,
        OrderRepository $orderRepository,
        UrlResolverInterface $urlResolver
    ) {
        $this->ingTransactionRepository = $ingTransactionRepository;
        $this->ingClientProvider = $ingClientProvider;
        $this->configurationProvider = $configurationProvider;
        $this->readyTransactionFactory = $readyTransactionFactory;
        $this->orderRepository = $orderRepository;
        $this->urlResolver = $urlResolver;
    }

    public function __invoke(GetResponseData $query): ReadyTransactionModelInterface
    {
        /** @var IngTransactionInterface|null $ingTransaction */
        $ingTransaction = $this->ingTransactionRepository->findByPaymentId($query->getPaymentId());
        $client = $this->ingClientProvider->getClient($ingTransaction->getGatewayCode());

        $url = $this->urlResolver->resolve($ingTransaction, $this->configurationProvider, $this->ingClientProvider);

        $response = $client->getTransactionData($url);

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
}
