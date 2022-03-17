<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\Status;

use BitBag\SyliusIngPlugin\Exception\NoCorrectStatusException;

final class StatusResolver implements StatusResolverInterface
{
    public function resolve(string $status): string
    {
        switch ($status) {
            case 'settled':
                return self::SUCCESS_SETTLED;
            case 'success':
                return self::SUCCESS_STATUS;
            case 'new':
            case 'authorized':
            case 'pending':
            case 'submitted':
                return self::PROCESS_STATUS;
            case 'rejected':
            case 'error':
            case 'failure':
                return self::ERROR_STATUS;
            case 'cancelled':
                return self::CANCEL_STATUS;
        }

        throw new NoCorrectStatusException('Could not find payment status');
    }
}
