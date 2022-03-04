<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Processor\Webhook\Status;

use BitBag\SyliusIngPlugin\Model\Status\StatusResponseModelInterface;
use Sylius\Component\Core\Model\PaymentInterface;

interface WebhookResponseProcessorInterface
{
    public function process(StatusResponseModelInterface $response, PaymentInterface $payment): void;
}
