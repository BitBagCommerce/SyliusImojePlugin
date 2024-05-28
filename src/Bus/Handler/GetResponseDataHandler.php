<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Bus\Handler;

use BitBag\SyliusImojePlugin\Bus\Query\GetResponseData;
use BitBag\SyliusImojePlugin\Entity\IngTransactionInterface;
use BitBag\SyliusImojePlugin\Factory\ReadyTransaction\ReadyTransactionFactoryInterface;
use BitBag\SyliusImojePlugin\Model\ReadyTransaction\ReadyTransactionModelInterface;
use BitBag\SyliusImojePlugin\Provider\IngClientConfigurationProviderInterface;
use BitBag\SyliusImojePlugin\Provider\IngClientProviderInterface;
use BitBag\SyliusImojePlugin\Repository\IngTransaction\IngTransactionRepositoryInterface;
use BitBag\SyliusImojePlugin\Resolver\Url\UrlResolverInterface;
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
        $ingTransaction = $this->ingTransactionRepository->getByPaymentId($query->getPaymentId());
        $client = $this->ingClientProvider->getClient($ingTransaction->getGatewayCode());

        $url = $this->urlResolver->resolve($ingTransaction, $this->configurationProvider, $this->ingClientProvider);

        $response = $client->getTransactionData($url);

        $order = $this->orderRepository->find($ingTransaction->getOrderId());

        return $this->readyTransactionFactory->createReadyTransaction(
            $response->getBody()->getContents(),
            $ingTransaction,
            $order
        );
    }
}
