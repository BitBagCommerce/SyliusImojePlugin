<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Controller\Shop;

use BitBag\SyliusIngPlugin\Provider\IngClientConfigurationProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class InitializePaymentController
{
    private IngClientConfigurationProviderInterface $clientConfigurationProvider;

    public function __construct(IngClientConfigurationProviderInterface $clientConfigurationProvider)
    {
        $this->clientConfigurationProvider = $clientConfigurationProvider;
    }

    public function __invoke(Request $request): Response
    {
        return new Response();
    }
}
