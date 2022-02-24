<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\Bus;

use BitBag\SyliusIngPlugin\Bus\Command\Status\MarkAsCanceled;
use BitBag\SyliusIngPlugin\Bus\Command\Status\MarkAsFailed;
use BitBag\SyliusIngPlugin\Bus\Command\Status\MarkAsProcessed;
use BitBag\SyliusIngPlugin\Bus\Command\Status\MarkAsSuccessful;
use BitBag\SyliusIngPlugin\Bus\Command\Status\PaymentFinalizationCommandInterface;
use Sylius\Component\Core\Model\PaymentInterface;

final class PaymentFinalizationCommandFactory implements PaymentFinalizationCommandFactoryInterface
{
    private const STATUSES = [
        'cancel' => MarkAsCanceled::class,
        'error' => MarkAsFailed::class,
        'process' => MarkAsProcessed::class,
        'success' => MarkAsSuccessful::class,
    ];

    public function createNew(string $status, PaymentInterface $payment): PaymentFinalizationCommandInterface
    {
        $class = self::STATUSES[$status] ?? null;

        if (null === $class) {
            throw new \InvalidArgumentException(
                \sprintf('Payment finalization command for status %s not found.', $status)
            );
        }

        return new $class($payment);
    }
}
