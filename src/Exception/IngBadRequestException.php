<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Exception;

use ECSPrefix20211002\Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class IngBadRequestException extends BadRequestHttpException implements IngClientExceptionInterface
{
    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }
}
