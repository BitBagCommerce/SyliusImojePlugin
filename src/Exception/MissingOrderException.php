<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class MissingOrderException extends NotFoundHttpException
{
}
