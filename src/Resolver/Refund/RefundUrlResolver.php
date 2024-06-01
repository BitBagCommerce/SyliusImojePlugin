<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\Refund;

use BitBag\SyliusImojePlugin\Configuration\ImojeClientConfigurationInterface;
use BitBag\SyliusImojePlugin\Entity\ImojeTransactionInterface;
use BitBag\SyliusImojePlugin\Repository\ImojeTransaction\ImojeTransactionRepositoryInterface;

final class RefundUrlResolver implements RefundUrlResolverInterface
{
    private ImojeTransactionRepositoryInterface $ingTransactionRepository;

    public function __construct(ImojeTransactionRepositoryInterface $ingTransactionRepository)
    {
        $this->ingTransactionRepository = $ingTransactionRepository;
    }

    public function resolve(ImojeClientConfigurationInterface $config, int $paymentId): string
    {
        $baseUrl = $config->isProd() ? $config->getProdUrl() : $config->getSandboxUrl();
        $merchantId = $config->getMerchantId();

        /** @var ImojeTransactionInterface $ingTransaction */
        $ingTransaction = $this->ingTransactionRepository->getByPaymentId($paymentId);
        $transactionId = $ingTransaction->getTransactionId();
        $url = \sprintf('%s/%s/transaction/%s/refund', $baseUrl, $merchantId, $transactionId);

        return $url;
    }
}
