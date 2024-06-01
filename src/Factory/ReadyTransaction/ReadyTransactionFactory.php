<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Factory\ReadyTransaction;

use BitBag\SyliusImojePlugin\Entity\ImojeTransactionInterface;
use BitBag\SyliusImojePlugin\Exception\InvalidIngResponseException;
use BitBag\SyliusImojePlugin\Model\ReadyTransaction\ReadyTransactionModel;
use Sylius\Component\Core\Model\OrderInterface;

final class ReadyTransactionFactory implements ReadyTransactionFactoryInterface
{
    public function createReadyTransaction(
        string                    $contents,
        ImojeTransactionInterface $ingTransaction,
        OrderInterface            $order
    ): ReadyTransactionModel {
        /** @var array $transactionData */
        $transactionData = json_decode($contents, true);

        if (null === $transactionData['transaction'] || null === $transactionData['transaction']['status']) {
            throw new InvalidIngResponseException('Invalid data from response');
        }

        $status = $transactionData['transaction']['status'];

        return new ReadyTransactionModel($status, $ingTransaction, $order);
    }
}
