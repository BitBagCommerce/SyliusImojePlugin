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
    private ImojeTransactionRepositoryInterface $imojeTransactionRepository;

    private ImojeClientProviderInterface $imojeClientProvider;

    private ImojeClientConfigurationProviderInterface $configurationProvider;

    private ReadyTransactionFactoryInterface $readyTransactionFactory;

    private OrderRepository $orderRepository;

    private UrlResolverInterface $urlResolver;

    public function __construct(
        ImojeTransactionRepositoryInterface $imojeTransactionRepository,
        ImojeClientProviderInterface $imojeClientProvider,
        ImojeClientConfigurationProviderInterface $configurationProvider,
        ReadyTransactionFactoryInterface $readyTransactionFactory,
        OrderRepository $orderRepository,
        UrlResolverInterface $urlResolver,
    ) {
        $this->imojeTransactionRepository = $imojeTransactionRepository;
        $this->imojeClientProvider = $imojeClientProvider;
        $this->configurationProvider = $configurationProvider;
        $this->readyTransactionFactory = $readyTransactionFactory;
        $this->orderRepository = $orderRepository;
        $this->urlResolver = $urlResolver;
    }

    public function __invoke(GetResponseData $query): ReadyTransactionModelInterface
    {
        /** @var ImojeTransactionInterface|null $imojeTransaction */
        $imojeTransaction = $this->imojeTransactionRepository->getByPaymentId($query->getPaymentId());
        $client = $this->imojeClientProvider->getClient($imojeTransaction->getGatewayCode());

        $url = $this->urlResolver->resolve($imojeTransaction, $this->configurationProvider, $this->imojeClientProvider);

        $response = $client->getTransactionData($url);

        $order = $this->orderRepository->find($imojeTransaction->getOrderId());

        return $this->readyTransactionFactory->createReadyTransaction(
            $response->getBody()->getContents(),
            $imojeTransaction,
            $order,
        );
    }
}
