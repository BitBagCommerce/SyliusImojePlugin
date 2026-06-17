<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class IngPayBadRequestException extends BadRequestHttpException implements IngPayClientExceptionInterface
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
