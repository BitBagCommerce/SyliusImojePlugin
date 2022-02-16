<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Generator\Url\Status;

use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
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
        return $status === self::STATUS;
    }

    public function generate(Request $request, OrderInterface $order): string
    {
        /** @var Session $session */
        $session = $request->getSession();
        $session
            ->getFlashBag()
            ->add('success', 'Your order is completed');
        $url = \sprintf(
            '%s%s',
            $request->getSchemeAndHttpHost(),
            $this->urlGenerator->generate(
                self::SYLIUS_SHOP_ORDER_THANK_YOU,
                ['tokenValue' => $order->getTokenValue()]
            )
        );
        return $url;
    }
}
