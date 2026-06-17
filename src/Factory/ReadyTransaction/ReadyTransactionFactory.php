<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Factory\ReadyTransaction;

use BitBag\SyliusIngPayPlugin\Entity\IngPayTransactionInterface;
use BitBag\SyliusIngPayPlugin\Exception\InvalidIngPayResponseException;
use BitBag\SyliusIngPayPlugin\Model\ReadyTransaction\ReadyTransactionModel;
use Sylius\Component\Core\Model\OrderInterface;

final class ReadyTransactionFactory implements ReadyTransactionFactoryInterface
{
    public function createReadyTransaction(
        string $contents,
        IngPayTransactionInterface $ingPayTransaction,
        OrderInterface $order,
    ): ReadyTransactionModel {
        /** @var array $transactionData */
        $transactionData = json_decode($contents, true);

        if (null === $transactionData['transaction'] || null === $transactionData['transaction']['status']) {
            throw new InvalidIngPayResponseException('Invalid data from response');
        }

        $status = $transactionData['transaction']['status'];

        return new ReadyTransactionModel($status, $ingPayTransaction, $order);
    }
}
