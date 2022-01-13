<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Model;

final class IngHeaderModel implements IngHeaderModelInterface
{
    public string $accept = 'application/json';

    public string $contentType = 'application/json';

    public string $authorization = 'Bearer ' . self::ING_TOKEN_SECRET;

    public function getAccept(): string
    {
        return $this->accept;
    }

    public function getContentType(): string
    {
        return $this->contentType;
    }

    public function getAuthorization(): string
    {
        return $this->authorization;
    }
}
