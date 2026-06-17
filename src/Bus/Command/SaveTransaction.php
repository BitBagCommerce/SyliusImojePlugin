<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Bus\Command;

use BitBag\SyliusIngPayPlugin\Entity\IngPayTransactionInterface;

final class SaveTransaction
{
    private IngPayTransactionInterface $ingPayTransaction;

    public function __construct(IngPayTransactionInterface $ingPayTransaction)
    {
        $this->ingPayTransaction = $ingPayTransaction;
    }

    public function getingPayTransaction(): IngPayTransactionInterface
    {
        return $this->ingPayTransaction;
    }
}
