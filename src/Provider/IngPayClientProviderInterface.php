<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Provider;

use BitBag\SyliusIngPayPlugin\Client\IngPayApiClientInterface;

interface IngPayClientProviderInterface
{
    public function getClient(string $code): IngPayApiClientInterface;
}
