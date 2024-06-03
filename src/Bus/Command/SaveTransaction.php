<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Bus\Command;

use BitBag\SyliusImojePlugin\Entity\ImojeTransactionInterface;

final class SaveTransaction
{
    private ImojeTransactionInterface $imojeTransaction;

    public function __construct(ImojeTransactionInterface $imojeTransaction)
    {
        $this->imojeTransaction = $imojeTransaction;
    }

    public function getimojeTransaction(): ImojeTransactionInterface
    {
        return $this->imojeTransaction;
    }
}
