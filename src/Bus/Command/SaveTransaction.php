<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Bus\Command;

use BitBag\SyliusImojePlugin\Entity\ImojeTransactionInterface;

final class SaveTransaction
{
    private ImojeTransactionInterface $ingTransaction;

    public function __construct(ImojeTransactionInterface $ingTransaction)
    {
        $this->ingTransaction = $ingTransaction;
    }

    public function getIngTransaction(): ImojeTransactionInterface
    {
        return $this->ingTransaction;
    }
}
