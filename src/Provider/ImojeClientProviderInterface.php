<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Provider;

use BitBag\SyliusImojePlugin\Client\ImojeApiClientInterface;

interface ImojeClientProviderInterface
{
    public function getClient(string $code): ImojeApiClientInterface;
}
