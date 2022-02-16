<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\Status;

use BitBag\SyliusIngPlugin\Exception\NoCorrectStatusException;

final class StatusResolver implements StatusResolverInterface
{
    public function resolve(string $status): string
    {
        if ($status === 'settled') {
            return self::SUCCESS_STATUS;
        }

        if ($status === 'new' || $status == 'authorized' || $status == 'pending' || $status == 'submitted') {
            return self::SUCCESS_STATUS;
        }

        if ($status === 'rejected' || $status === 'error') {
            return self::ERROR_STATUS;
        }

        if ($status === 'cancelled') {
            return self::CANCEL_STATUS;
        }

        throw new NoCorrectStatusException('Could not found payment status');
    }
}
