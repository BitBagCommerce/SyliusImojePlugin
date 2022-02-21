<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Generator\Url\Status;

use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class AggregateStatusBasedUrlGenerator implements AggregateStatusBasedUrlGeneratorInterface
{
    private const DEFAULT_REDIRECT_ROUTE = 'sylius_shop_order_thank_you';

    private iterable $generators;

    private UrlGeneratorInterface $urlGenerator;

    public function __construct(
        iterable $processors,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->generators = $processors;
        $this->urlGenerator = $urlGenerator;
    }

    public function generate(
        OrderInterface $order,
        Request $request,
        string $status
    ): string
    {
        /** @var StatusBasedUrlGeneratorInterface $generator */
        foreach ($this->generators as $generator) {
            if ($generator->accepts($status)) {
                return $generator->generate($request, $order);
            }
        }

        return $this->urlGenerator->generate(self::DEFAULT_REDIRECT_ROUTE);
    }
}
