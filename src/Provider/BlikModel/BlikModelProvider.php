<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Provider\BlikModel;

use BitBag\SyliusIngPayPlugin\Exception\BlikNoDataException;
use BitBag\SyliusIngPayPlugin\Factory\Model\Blik\BlikModelFactoryInterface;
use BitBag\SyliusIngPayPlugin\Model\Blik\BlikModelInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class BlikModelProvider implements BlikModelProviderInterface
{
    private RequestStack $requestStack;

    private BlikModelFactoryInterface $blikModelFactory;

    public function __construct(RequestStack $requestStack, BlikModelFactoryInterface $blikModelFactory)
    {
        $this->requestStack = $requestStack;
        $this->blikModelFactory = $blikModelFactory;
    }

    public function provideDataToBlikModel(?string $blikCode): BlikModelInterface
    {
        $request = $this->requestStack->getCurrentRequest();

        if (null !== $blikCode) {
            return $this->blikModelFactory->create($blikCode, $request->getClientIp());
        }

        /** @var array $requestData */
        $requestData = $request->request->all();
        /** @var array $blikData */
        $blikData = $requestData['sylius_checkout_complete'];
        $blikCode = $blikData['blik_code'];
        if (!$blikCode) {
            throw new BlikNoDataException('The Blik data has not been entered');
        }

        $blikModel = $this->blikModelFactory->create($blikCode, $request->getClientIp());

        return $blikModel;
    }
}
