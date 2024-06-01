<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Controller\Shop;

use BitBag\SyliusImojePlugin\Bus\Command\AssignTokenValue;
use BitBag\SyliusImojePlugin\Bus\Command\SaveTransaction;
use BitBag\SyliusImojePlugin\Bus\DispatcherInterface;
use BitBag\SyliusImojePlugin\Bus\Query\GetBlikTransactionData;
use BitBag\SyliusImojePlugin\Bus\Query\GetTransactionData;
use BitBag\SyliusImojePlugin\Entity\ImojeTransactionInterface;
use BitBag\SyliusImojePlugin\Exception\ImojeNotConfiguredException;
use BitBag\SyliusImojePlugin\Factory\Payment\PaymentDataModelFactoryInterface;
use BitBag\SyliusImojePlugin\Model\Payment\PaymentDataModelInterface;
use BitBag\SyliusImojePlugin\Provider\BlikModel\BlikModelProviderInterface;
use BitBag\SyliusImojePlugin\Resolver\Order\OrderResolverInterface;
use BitBag\SyliusImojePlugin\Resolver\Payment\OrderPaymentResolverInterface;
use BitBag\SyliusImojePlugin\Resolver\Payment\TransactionPaymentDataResolverInterface;
use Sylius\Bundle\CoreBundle\Form\Type\Checkout\CompleteType;
use Sylius\Bundle\CoreBundle\Form\Type\Checkout\SelectPaymentType;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;
use Throwable;

final class InitializePaymentController extends AbstractController
{
    private OrderResolverInterface $orderResolver;

    private OrderPaymentResolverInterface $paymentResolver;

    private DispatcherInterface $dispatcher;

    private BlikModelProviderInterface $blikModelProvider;

    private TransactionPaymentDataResolverInterface $transactionPaymentDataResolver;

    private TranslatorInterface $translator;

    public function __construct(
        OrderResolverInterface $orderResolver,
        OrderPaymentResolverInterface $paymentResolver,
        DispatcherInterface $dispatcher,
        BlikModelProviderInterface $blikModelProvider,
        TransactionPaymentDataResolverInterface $transactionPaymentDataResolver,
        TranslatorInterface $translator
    ) {
        $this->orderResolver = $orderResolver;
        $this->paymentResolver = $paymentResolver;
        $this->dispatcher = $dispatcher;
        $this->blikModelProvider = $blikModelProvider;
        $this->transactionPaymentDataResolver = $transactionPaymentDataResolver;
        $this->translator = $translator;
    }

    public function __invoke(
        Request $request,
        ?string $orderId,
        ?string $paymentMethodCode,
        ?string $blikCode
    ): Response {
        $order = $this->orderResolver->resolve($orderId);
        $this->dispatcher->dispatch(new AssignTokenValue($order, $request));

        if (null === $paymentMethodCode && null === $blikCode) {
            $form = $this->createForm(CompleteType::class, $order);
            $form->handleRequest($request);

            if (!$form->isSubmitted() || !$form->isValid()) {
                return $this->render('@SyliusShop/Checkout/complete.html.twig', [
                    'form' => $form->createView(),
                    'order' => $order,
                ]);
            }
        }

        if ('blik' === $paymentMethodCode) {
            $formShowOrder = $this->createForm(SelectPaymentType::class, $order);
            $formShowOrder->handleRequest($request);

            if (null === $blikCode || PaymentDataModelFactoryInterface::BLIK_LENGTH !== strlen($blikCode)) {
                return $this->render('@SyliusShop/Order/show.html.twig', [
                    'form' => $formShowOrder->createView(),
                    'order' => $order,
                    'isFailure' => true,
                ]);
            }
        }

        $payment = $this->getPaymentFromOrder($order);

        $transactionPaymentData = $this->transactionPaymentDataResolver->resolve($paymentMethodCode, $payment, $blikCode);
        $isBlik = 'blik' === $transactionPaymentData->getPaymentMethod();
        try {
            $transactionData = $isBlik ? $this->getTransactionDataForBlik($order, $payment, $transactionPaymentData, $blikCode)
                : $this->getTransactionData($order, $payment, $transactionPaymentData);

            $this->dispatcher->dispatch(new SaveTransaction($transactionData));

            return new RedirectResponse($transactionData->getPaymentUrl());
        } catch (Throwable $e) {
            $this->addFlash('error', $this->translator->trans('bitbag_sylius_imoje_plugin.ui.payment_failed'));
            return $this->redirectToRoute('sylius_shop_checkout_select_payment');
        }

    }

    private function getPaymentFromOrder(OrderInterface $order): PaymentInterface
    {
        try {
            $payment = $this->paymentResolver->resolve($order);
        } catch (\InvalidArgumentException $e) {
            throw new ImojeNotConfiguredException('Payment method not found');
        }

        return $payment;
    }

    private function getTransactionData(
        OrderInterface $order,
        PaymentInterface $payment,
        PaymentDataModelInterface $transactionPaymentData
    ): ImojeTransactionInterface {
        return $this->dispatcher->dispatch(
            new GetTransactionData(
                $order,
                $payment->getMethod()->getCode(),
                $transactionPaymentData->getPaymentMethod(),
                $transactionPaymentData->getPaymentMethodCode(),
            )
        );
    }

    private function getTransactionDataForBlik(
        OrderInterface $order,
        PaymentInterface $payment,
        PaymentDataModelInterface $transactionPaymentData,
        ?string $blikCode
    ): ImojeTransactionInterface {
        $blikModel = $this->blikModelProvider->provideDataToBlikModel($blikCode);

        return $this->dispatcher->dispatch(
            new GetBlikTransactionData(
                $order,
                $payment->getMethod()->getCode(),
                $transactionPaymentData->getPaymentMethod(),
                $transactionPaymentData->getPaymentMethodCode(),
                $blikModel
            )
        );
    }
}
