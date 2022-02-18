<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\TransactionData;

use Psr\Http\Message\ResponseInterface;

final class TransactionDataResolver implements TransactionDataResolverInterface
{
    public function resolve(ResponseInterface $response): array
    {
        $transactionData = [];
        $data = json_decode($response->getBody()->getContents(), true);
        $transactionData['transactionId'] = $data['transaction']['id'];
        $transactionData['serviceId'] = $data['transaction']['serviceId'];
        $transactionData['orderId'] = $data['transaction']['orderId'];
        $transactionData['paymentUrl'] = $data['action']['url'];

        return $transactionData;
    }
}
