<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class ImojeBadRequestException extends BadRequestHttpException implements ImojeClientExceptionInterface
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
