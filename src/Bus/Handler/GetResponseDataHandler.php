<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Bus\Handler;

use BitBag\SyliusIngPayPlugin\Bus\Query\GetResponseData;
use BitBag\SyliusIngPayPlugin\Entity\IngPayTransactionInterface;
use BitBag\SyliusIngPayPlugin\Factory\ReadyTransaction\ReadyTransactionFactoryInterface;
use BitBag\SyliusIngPayPlugin\Model\ReadyTransaction\ReadyTransactionModelInterface;
use BitBag\SyliusIngPayPlugin\Provider\IngPayClientConfigurationProviderInterface;
use BitBag\SyliusIngPayPlugin\Provider\IngPayClientProviderInterface;
use BitBag\SyliusIngPayPlugin\Repository\IngPayTransaction\IngPayTransactionRepositoryInterface;
use BitBag\SyliusIngPayPlugin\Resolver\Url\UrlResolverInterface;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\OrderRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class GetResponseDataHandler implements MessageHandlerInterface
{
    private IngPayTransactionRepositoryInterface $ingPayTransactionRepository;

    private IngPayClientProviderInterface $ingPayClientProvider;

    private IngPayClientConfigurationProviderInterface $configurationProvider;

    private ReadyTransactionFactoryInterface $readyTransactionFactory;

    private OrderRepository $orderRepository;

    private UrlResolverInterface $urlResolver;

    public function __construct(
        IngPayTransactionRepositoryInterface $ingPayTransactionRepository,
        IngPayClientProviderInterface $ingPayClientProvider,
        IngPayClientConfigurationProviderInterface $configurationProvider,
        ReadyTransactionFactoryInterface $readyTransactionFactory,
        OrderRepository $orderRepository,
        UrlResolverInterface $urlResolver,
    ) {
        $this->ingPayTransactionRepository = $ingPayTransactionRepository;
        $this->ingPayClientProvider = $ingPayClientProvider;
        $this->configurationProvider = $configurationProvider;
        $this->readyTransactionFactory = $readyTransactionFactory;
        $this->orderRepository = $orderRepository;
        $this->urlResolver = $urlResolver;
    }

    public function __invoke(GetResponseData $query): ReadyTransactionModelInterface
    {
        /** @var IngPayTransactionInterface|null $ingPayTransaction */
        $ingPayTransaction = $this->ingPayTransactionRepository->getByPaymentId($query->getPaymentId());
        $client = $this->ingPayClientProvider->getClient($ingPayTransaction->getGatewayCode());

        $url = $this->urlResolver->resolve($ingPayTransaction, $this->configurationProvider, $this->ingPayClientProvider);

        $response = $client->getTransactionData($url);

        $order = $this->orderRepository->find($ingPayTransaction->getOrderId());

        return $this->readyTransactionFactory->createReadyTransaction(
            $response->getBody()->getContents(),
            $ingPayTransaction,
            $order,
        );
    }
}
