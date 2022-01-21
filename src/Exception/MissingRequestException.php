<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Exception;

final class MissingRequestException extends IngBadRequestException
{
    public function __construct()
    {
        parent::__construct('No request provided.');
    }
}
