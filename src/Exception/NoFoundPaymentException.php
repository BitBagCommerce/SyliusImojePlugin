<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class NoFoundPaymentException extends NotFoundHttpException
{
}
