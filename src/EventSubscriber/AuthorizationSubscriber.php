<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\EventSubscriber;

use BitBag\SyliusIngPlugin\Calculator\SignatureCalculatorInterface;
use BitBag\SyliusIngPlugin\Controller\Shop\Webhook\WebhookController;
use BitBag\SyliusIngPlugin\Resolver\Signature\OwnSignatureResolverInterface;
use BitBag\SyliusIngPlugin\Resolver\Signature\SignatureResolverInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class AuthorizationSubscriber implements EventSubscriberInterface
{
    private SignatureResolverInterface $signatureResolver;

    private OwnSignatureResolverInterface $ownSignatureResolver;

    private SignatureCalculatorInterface $signatureCalculator;

    public function __construct(
        SignatureResolverInterface $signatureResolver,
        OwnSignatureResolverInterface $ownSignatureResolver,
        SignatureCalculatorInterface $signatureCalculator
    ) {
        $this->signatureResolver = $signatureResolver;
        $this->ownSignatureResolver = $ownSignatureResolver;
        $this->signatureCalculator = $signatureCalculator;
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();

        if (is_array($controller)) {
            $controller = $controller[0];
        }

        if (!$controller instanceof WebhookController) {
            return;
        }

        $signature = $this->signatureResolver->resolve();
        $ownSignature = $this->ownSignatureResolver->resolve();
        $ownHashSignature = \hash($this->signatureResolver::SIGNATURE_ALG, $ownSignature);
        $this->signatureCalculator->calculate($signature, $ownHashSignature);
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
