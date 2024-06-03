<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Resolver\Refund;

use BitBag\SyliusImojePlugin\Configuration\ImojeClientConfigurationInterface;
use BitBag\SyliusImojePlugin\Entity\ImojeTransactionInterface;
use BitBag\SyliusImojePlugin\Repository\ImojeTransaction\ImojeTransactionRepositoryInterface;

final class RefundUrlResolver implements RefundUrlResolverInterface
{
    private ImojeTransactionRepositoryInterface $imojeTransactionRepository;

    public function __construct(ImojeTransactionRepositoryInterface $imojeTransactionRepository)
    {
        $this->imojeTransactionRepository = $imojeTransactionRepository;
    }

    public function resolve(ImojeClientConfigurationInterface $config, int $paymentId): string
    {
        $baseUrl = $config->isProd() ? $config->getProdUrl() : $config->getSandboxUrl();
        $merchantId = $config->getMerchantId();

        /** @var ImojeTransactionInterface $imojeTransaction */
        $imojeTransaction = $this->imojeTransactionRepository->getByPaymentId($paymentId);
        $transactionId = $imojeTransaction->getTransactionId();
        $url = \sprintf('%s/%s/transaction/%s/refund', $baseUrl, $merchantId, $transactionId);

        return $url;
    }
}
