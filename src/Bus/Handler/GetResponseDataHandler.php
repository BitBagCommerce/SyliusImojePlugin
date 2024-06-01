<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Bus\Handler;

use BitBag\SyliusImojePlugin\Bus\Query\GetResponseData;
use BitBag\SyliusImojePlugin\Entity\ImojeTransactionInterface;
use BitBag\SyliusImojePlugin\Factory\ReadyTransaction\ReadyTransactionFactoryInterface;
use BitBag\SyliusImojePlugin\Model\ReadyTransaction\ReadyTransactionModelInterface;
use BitBag\SyliusImojePlugin\Provider\ImojeClientConfigurationProviderInterface;
use BitBag\SyliusImojePlugin\Provider\ImojeClientProviderInterface;
use BitBag\SyliusImojePlugin\Repository\ImojeTransaction\ImojeTransactionRepositoryInterface;
use BitBag\SyliusImojePlugin\Resolver\Url\UrlResolverInterface;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\OrderRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class GetResponseDataHandler implements MessageHandlerInterface
{
    private ImojeTransactionRepositoryInterface $ingTransactionRepository;

    private ImojeClientProviderInterface $ingClientProvider;

    private ImojeClientConfigurationProviderInterface $configurationProvider;

    private ReadyTransactionFactoryInterface $readyTransactionFactory;

    private OrderRepository $orderRepository;

    private UrlResolverInterface $urlResolver;

    public function __construct(
        ImojeTransactionRepositoryInterface       $ingTransactionRepository,
        ImojeClientProviderInterface              $ingClientProvider,
        ImojeClientConfigurationProviderInterface $configurationProvider,
        ReadyTransactionFactoryInterface          $readyTransactionFactory,
        OrderRepository                           $orderRepository,
        UrlResolverInterface                      $urlResolver
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
        /** @var ImojeTransactionInterface|null $ingTransaction */
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
