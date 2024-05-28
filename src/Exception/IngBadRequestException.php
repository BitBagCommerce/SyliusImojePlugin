<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class IngBadRequestException extends BadRequestHttpException implements IngClientExceptionInterface
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
