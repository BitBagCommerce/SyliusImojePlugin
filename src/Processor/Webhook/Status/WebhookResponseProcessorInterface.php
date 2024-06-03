<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Processor\Webhook\Status;

use BitBag\SyliusImojePlugin\Model\Status\StatusResponseModelInterface;
use Sylius\Component\Core\Model\PaymentInterface;

interface WebhookResponseProcessorInterface
{
    public function process(StatusResponseModelInterface $response, PaymentInterface $payment): void;
}
