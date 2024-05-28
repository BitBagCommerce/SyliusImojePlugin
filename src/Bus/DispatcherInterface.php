<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Bus;

interface DispatcherInterface
{
    /**
     * @return mixed
     */
    public function dispatch(object $action);
}
