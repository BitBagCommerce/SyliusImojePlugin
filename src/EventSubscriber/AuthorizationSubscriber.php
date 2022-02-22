<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\EventSubscriber;

use BitBag\SyliusIngPlugin\Controller\Shop\Webhook\WebhookController;
use BitBag\SyliusIngPlugin\Exception\IngBadRequestException;
use BitBag\SyliusIngPlugin\Provider\IngClientConfigurationProviderInterface;
use BitBag\SyliusIngPlugin\Resolver\GatewayCode\GatewayCodeResolverInterface;
use BitBag\SyliusIngPlugin\Resolver\Signature\SignatureResolverInterface;
use BitBag\SyliusIngPlugin\Resolver\TransactionId\TransactionIdResolverInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class AuthorizationSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;

    private SignatureResolverInterface $signatureResolver;

    private RequestStack $requestStack;

    private GatewayCodeResolverInterface $gatewayCodeResolver;

    private TransactionIdResolverInterface $transactionIdResolver;

    private IngClientConfigurationProviderInterface $configurationProvider;

    public function __construct(
        LoggerInterface $logger,
        SignatureResolverInterface $signatureResolver,
        RequestStack $requestStack,
        GatewayCodeResolverInterface $gatewayCodeResolver,
        TransactionIdResolverInterface $transactionIdResolver,
        IngClientConfigurationProviderInterface $configurationProvider
    ) {
        $this->logger = $logger;
        $this->signatureResolver = $signatureResolver;
        $this->requestStack = $requestStack;
        $this->gatewayCodeResolver = $gatewayCodeResolver;
        $this->transactionIdResolver = $transactionIdResolver;
        $this->configurationProvider = $configurationProvider;
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();

        if (is_array($controller)) {
            $controller = $controller[0];
        }

        if ($controller instanceof WebhookController) {
            $request = $this->requestStack->getCurrentRequest();
            $body = $request->getContent();
            $transactionId = $this->transactionIdResolver->resolve($body);
            $code = $this->gatewayCodeResolver->resolve($transactionId);
            $signature = $this->signatureResolver->resolve();
            $config = $this->configurationProvider->getPaymentMethodConfiguration($code);
            $ownSignature = \sprintf('%s%s', $body, $config->getShopKey());
            $ownHashSignature = \hash($this->signatureResolver::SIGNATURE_ALG, $ownSignature);

            if (hash_equals($ownHashSignature, $signature)) {
                $this->logger->info('Authorized request from ing');
            } else {
                throw new IngBadRequestException('Bad request from ing');
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
