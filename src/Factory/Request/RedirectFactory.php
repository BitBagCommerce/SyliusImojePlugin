<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Model;

use BitBag\SyliusIngPlugin\Model\RedirectModel;
use BitBag\SyliusIngPlugin\Model\RedirectModelInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class RedirectFactory implements RedirectFactoryInterface
{
    private UrlGeneratorInterface $generator;

    public function __construct(UrlGeneratorInterface $generator)
    {
        $this->generator = $generator;
    }

    public function createNew(): RedirectModelInterface
    {
        $redirectRequest = new RedirectModel();
        $redirectRequest->setSuccessUrl($this->generateRedirectUrl('success'));
        $redirectRequest->setFailureUrl($this->generateRedirectUrl('failure'));

        return $redirectRequest;
    }

    private function generateRedirectUrl(string $slug): string
    {
        return $this->generator->generate(
            TransactionModelFactoryInterface::REDIRECT_URL,
            ['status' => $slug],
            Router::ABSOLUTE_URL
        );
    }
}
