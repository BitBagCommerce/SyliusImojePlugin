<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Bus;

interface DispatcherInterface
{
    /**
     * @return mixed
     */
    public function dispatch(object $action);
}
