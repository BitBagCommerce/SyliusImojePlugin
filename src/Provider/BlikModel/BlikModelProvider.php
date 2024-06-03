<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Provider\BlikModel;

use BitBag\SyliusImojePlugin\Exception\BlikNoDataException;
use BitBag\SyliusImojePlugin\Factory\Model\Blik\BlikModelFactoryInterface;
use BitBag\SyliusImojePlugin\Model\Blik\BlikModelInterface;
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
