<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Factory\Bus;

use BitBag\SyliusImojePlugin\Bus\Command\Status\MarkAsCanceled;
use BitBag\SyliusImojePlugin\Bus\Command\Status\MarkAsFailed;
use BitBag\SyliusImojePlugin\Bus\Command\Status\MarkAsProcessed;
use BitBag\SyliusImojePlugin\Bus\Command\Status\MarkAsSettled;
use BitBag\SyliusImojePlugin\Bus\Command\Status\MarkAsSuccessful;
use BitBag\SyliusImojePlugin\Bus\Command\Status\PaymentFinalizationCommandInterface;
use Sylius\Component\Core\Model\PaymentInterface;

final class PaymentFinalizationCommandFactory implements PaymentFinalizationCommandFactoryInterface
{
    private const STATUSES = [
        'cancel' => MarkAsCanceled::class,
        'error' => MarkAsFailed::class,
        'failure' => MarkAsFailed::class,
        'process' => MarkAsProcessed::class,
        'success' => MarkAsSuccessful::class,
        'settled' => MarkAsSettled::class,
    ];

    public function createNew(string $status, PaymentInterface $payment): PaymentFinalizationCommandInterface
    {
        $class = self::STATUSES[$status] ?? null;

        if (null === $class) {
            throw new \InvalidArgumentException(
                \sprintf('Payment finalization command for status %s not found.', $status),
            );
        }

        return new $class($payment);
    }
}
