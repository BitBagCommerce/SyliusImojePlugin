<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Request;

use BitBag\SyliusIngPlugin\Factory\Model\TransactionModelFactoryInterface;
use BitBag\SyliusIngPlugin\Model\RedirectModel;
use BitBag\SyliusIngPlugin\Model\RedirectModelInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class RedirectFactory implements RedirectFactoryInterface
{
    private UrlGeneratorInterface $generator;

    public function __construct(UrlGeneratorInterface $generator)
    {
        $this->generator = $generator;
    }

    public function create(PaymentInterface $payment): RedirectModelInterface
    {
        $redirectRequest = new RedirectModel();
        $paymentId = $payment->getId();
        $redirectRequest->setSuccessUrl($this->generateRedirectUrl('success', $paymentId));
        $redirectRequest->setFailureUrl($this->generateRedirectUrl('failure', $paymentId));

        return $redirectRequest;
    }

    public function createForOneClick(PaymentInterface $payment): RedirectModelInterface
    {
        $redirectRequest = new RedirectModel();
        $paymentId = $payment->getId();
        $redirectRequest->setSuccessUrl($this->generateRedirectForOneClickUrl('success', $paymentId));
        $redirectRequest->setFailureUrl($this->generateRedirectForOneClickUrl('failure', $paymentId));

        return $redirectRequest;
    }

    private function generateRedirectUrl(string $slug, int $id): string
    {
        return $this->generator->generate(
            TransactionModelFactoryInterface::REDIRECT_URL,
            [
                'status' => $slug,
                'paymentId' => $id,
            ],
            Router::ABSOLUTE_URL
        );
    }

    private function generateRedirectForOneClickUrl(string $slug, int $id): string
    {
        return $this->generator->generate(
            TransactionModelFactoryInterface::REDIRECT_ONECLICK_URL,
            [
                'status' => $slug,
                'paymentId' => $id,
            ],
            Router::ABSOLUTE_URL
        );
    }
}
