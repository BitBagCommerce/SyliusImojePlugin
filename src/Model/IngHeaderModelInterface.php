<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Model;

interface IngHeaderModelInterface
{
    public const ING_TOKEN_SECRET = 'j1hdp8u2u4e2mep1uzepx29d8ymy77x4dycs1tyx2dwgswjdcdwlo2jgcb3yagf2';

    public function getAccept(): string;

    public function getContentType(): string;

    public function getAuthorization(): string;
}
