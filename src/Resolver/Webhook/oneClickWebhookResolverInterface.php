<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Resolver\Webhook;

interface oneClickWebhookResolverInterface
{
    public function resolve(): bool;
}
