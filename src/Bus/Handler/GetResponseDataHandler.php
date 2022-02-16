<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Bus\Handler;

use BitBag\SyliusIngPlugin\Bus\Query\GetResponseData;
use BitBag\SyliusIngPlugin\Repository\IngTransaction\IngTransactionRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class GetResponseDataHandler implements MessageHandlerInterface
{
    private IngTransactionRepositoryInterface $ingTransactionRepository;

    public function __construct(IngTransactionRepositoryInterface $ingTransactionRepository)
    {
        $this->ingTransactionRepository = $ingTransactionRepository;
    }

    public function __invoke(GetResponseData $query): Response
    {
        $ingTransaction = $this->ingTransactionRepository->findByPaymentId($query->getPaymentId());
        return new Response();
    }
}
