<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\Webhook;

interface oneClickWebhookResolverInterface
{
    public function resolve(): bool;
}
