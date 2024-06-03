<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\Status;

interface StatusResolverInterface
{
    public const SUCCESS_STATUS = 'success';

    public const SUCCESS_SETTLED = 'settled';

    public const CANCEL_STATUS = 'cancel';

    public const PROCESS_STATUS = 'process';

    public const ERROR_STATUS = 'error';

    public function resolve(string $status): string;
}
