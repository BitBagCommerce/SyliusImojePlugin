<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\Webhook;

use BitBag\SyliusIngPlugin\Model\Status\StatusResponseModelInterface;

interface WebhookResolverInterface
{
    public function resolve(): StatusResponseModelInterface;
}
