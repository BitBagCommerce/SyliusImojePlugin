<?php

declare(strict_types=1);

namespace BitBag\SyliusDatatransPlugin\Controller\Shop;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class RedirectController
{
    public function __invoke(Request $request, string $status): Response
    {
        return new Response();
    }
}
