<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\Webhook;

use BitBag\SyliusImojePlugin\Model\Status\StatusResponseModelInterface;

interface WebhookResolverInterface
{
    public function resolve(): StatusResponseModelInterface;
}
