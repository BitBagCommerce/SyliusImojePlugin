<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Resolver\Refund;

use BitBag\SyliusIngPayPlugin\Configuration\IngPayClientConfigurationInterface;
use BitBag\SyliusIngPayPlugin\Entity\IngPayTransactionInterface;
use BitBag\SyliusIngPayPlugin\Repository\IngPayTransaction\IngPayTransactionRepositoryInterface;

final class RefundUrlResolver implements RefundUrlResolverInterface
{
    private IngPayTransactionRepositoryInterface $ingPayTransactionRepository;

    public function __construct(IngPayTransactionRepositoryInterface $ingPayTransactionRepository)
    {
        $this->ingPayTransactionRepository = $ingPayTransactionRepository;
    }

    public function resolve(IngPayClientConfigurationInterface $config, int $paymentId): string
    {
        $baseUrl = $config->isProd() ? $config->getProdUrl() : $config->getSandboxUrl();
        $merchantId = $config->getMerchantId();

        /** @var IngPayTransactionInterface $ingPayTransaction */
        $ingPayTransaction = $this->ingPayTransactionRepository->getByPaymentId($paymentId);
        $transactionId = $ingPayTransaction->getTransactionId();
        $url = \sprintf('%s/%s/transaction/%s/refund', $baseUrl, $merchantId, $transactionId);

        return $url;
    }
}
