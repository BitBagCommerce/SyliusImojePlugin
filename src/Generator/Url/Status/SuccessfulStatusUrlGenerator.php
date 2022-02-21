<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Generator\Url\Status;

use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class SuccessfulStatusUrlGenerator implements StatusBasedUrlGeneratorInterface
{
    private const SYLIUS_SHOP_ORDER_THANK_YOU = 'sylius_shop_order_thank_you';

    private const STATUS = 'success';

    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function accepts(string $status): bool
    {
        return self::STATUS === $status;
    }

    public function generate(Request $request, OrderInterface $order): string
    {
        $session = $request->getSession();
        $session->set('sylius_order_id', $order->getId());

        return $this->urlGenerator->generate(
            self::SYLIUS_SHOP_ORDER_THANK_YOU,
            ['tokenValue' => $order->getTokenValue()]
        );
    }
}
