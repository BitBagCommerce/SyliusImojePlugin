<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Resolver\Refund;

use BitBag\SyliusIngPlugin\Configuration\IngClientConfigurationInterface;
use BitBag\SyliusIngPlugin\Entity\IngTransactionInterface;
use BitBag\SyliusIngPlugin\Repository\IngTransaction\IngTransactionRepositoryInterface;

final class RefundUrlResolver implements RefundUrlResolverInterface
{
    private IngTransactionRepositoryInterface $ingTransactionRepository;

    public function __construct(IngTransactionRepositoryInterface $ingTransactionRepository)
    {
        $this->ingTransactionRepository = $ingTransactionRepository;
    }

    public function resolve(IngClientConfigurationInterface $config, int $paymentId): string
    {
        $baseUrl = $config->isProd() ? $config->getProdUrl() : $config->getSandboxUrl();
        $merchantId = $config->getMerchantId();

        /** @var IngTransactionInterface $ingTransaction */
        $ingTransaction = $this->ingTransactionRepository->getByPaymentId($paymentId);
        $transactionId = $ingTransaction->getTransactionId();
        $url = \sprintf('%s/%s/transaction/%s/refund', $baseUrl, $merchantId, $transactionId);

        return $url;
    }
}
