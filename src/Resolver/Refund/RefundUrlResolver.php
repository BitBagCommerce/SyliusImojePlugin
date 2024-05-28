<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\Refund;

use BitBag\SyliusImojePlugin\Configuration\IngClientConfigurationInterface;
use BitBag\SyliusImojePlugin\Entity\IngTransactionInterface;
use BitBag\SyliusImojePlugin\Repository\IngTransaction\IngTransactionRepositoryInterface;

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
