<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\Webhook;

interface oneClickWebhookResolverInterface
{
    public function resolve(): bool;
}
