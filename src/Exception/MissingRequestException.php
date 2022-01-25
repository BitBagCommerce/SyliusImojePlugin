<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class MissingRequestException extends BadRequestHttpException
{
    public function __construct()
    {
        parent::__construct('No request provided.');
    }
}
