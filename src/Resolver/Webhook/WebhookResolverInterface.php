<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Resolver\Webhook;

use BitBag\SyliusIngPayPlugin\Model\Status\StatusResponseModelInterface;

interface WebhookResolverInterface
{
    public function resolve(): StatusResponseModelInterface;
}
