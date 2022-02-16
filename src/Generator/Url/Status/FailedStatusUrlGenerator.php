<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Generator\Url\Status;

use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class FailedStatusUrlGenerator implements StatusBasedUrlGeneratorInterface
{
    private const STATUS = 'error';

    private const SYLIUS_SHOP_ORDER_SHOW = 'sylius_shop_order_show';

    private TranslatorInterface $translator;

    private UrlGeneratorInterface $urlGenerator;

    public function __construct(TranslatorInterface $translator, UrlGeneratorInterface $urlGenerator)
    {
        $this->translator = $translator;
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
            ->add('error', $this->translator->trans('bitbag_sylius_ing_plugin.ui.payment_failure'));
        $url = \sprintf(
            '%s%s',
            $request->getSchemeAndHttpHost(),
            $this->urlGenerator->generate(
                self::SYLIUS_SHOP_ORDER_SHOW,
                ['tokenValue' => $order->getTokenValue()]
            )
        );
        return $url;
    }
}
