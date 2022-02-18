<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Factory\ReadyTransaction;

use BitBag\SyliusIngPlugin\Entity\IngTransactionInterface;
use BitBag\SyliusIngPlugin\Exception\InvalidIngResponseException;
use BitBag\SyliusIngPlugin\Model\ReadyTransaction\ReadyTransactionModel;
use Sylius\Component\Core\Model\OrderInterface;

final class ReadyTransactionFactory implements ReadyTransactionFactoryInterface
{
    public function createReadyTransaction(
        string $contents,
        IngTransactionInterface $ingTransaction,
        OrderInterface $order
    ): ReadyTransactionModel {
        /** @var array $transactionData */
        $transactionData = json_decode($contents, true);

        if ($transactionData['transaction'] === null || $transactionData['transaction']['status'] === null) {
            throw new InvalidIngResponseException('Invalid data from response');
        }

        $status = $transactionData['transaction']['status'];

        return new ReadyTransactionModel($status, $ingTransaction, $order);
    }
}
