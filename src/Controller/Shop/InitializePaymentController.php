<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Controller\Shop;

use BitBag\SyliusIngPayPlugin\Bus\Command\AssignTokenValue;
use BitBag\SyliusIngPayPlugin\Bus\Command\SaveTransaction;
use BitBag\SyliusIngPayPlugin\Bus\DispatcherInterface;
use BitBag\SyliusIngPayPlugin\Bus\Query\GetBlikTransactionData;
use BitBag\SyliusIngPayPlugin\Bus\Query\GetTransactionData;
use BitBag\SyliusIngPayPlugin\Entity\IngPayTransactionInterface;
use BitBag\SyliusIngPayPlugin\Exception\IngPayNotConfiguredException;
use BitBag\SyliusIngPayPlugin\Factory\Payment\PaymentDataModelFactoryInterface;
use BitBag\SyliusIngPayPlugin\Model\Payment\PaymentDataModelInterface;
use BitBag\SyliusIngPayPlugin\Provider\BlikModel\BlikModelProviderInterface;
use BitBag\SyliusIngPayPlugin\Resolver\Order\OrderResolverInterface;
use BitBag\SyliusIngPayPlugin\Resolver\Payment\OrderPaymentResolverInterface;
use BitBag\SyliusIngPayPlugin\Resolver\Payment\TransactionPaymentDataResolverInterface;
use Psr\Log\LoggerInterface;
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

    private LoggerInterface $logger;

    public function __construct(
        OrderResolverInterface $orderResolver,
        OrderPaymentResolverInterface $paymentResolver,
        DispatcherInterface $dispatcher,
        BlikModelProviderInterface $blikModelProvider,
        TransactionPaymentDataResolverInterface $transactionPaymentDataResolver,
        TranslatorInterface $translator,
        LoggerInterface $logger,
    ) {
        $this->orderResolver = $orderResolver;
        $this->paymentResolver = $paymentResolver;
        $this->dispatcher = $dispatcher;
        $this->blikModelProvider = $blikModelProvider;
        $this->transactionPaymentDataResolver = $transactionPaymentDataResolver;
        $this->translator = $translator;
        $this->logger = $logger;
    }

    public function __invoke(
        Request $request,
        ?string $orderId,
        ?string $paymentMethodCode,
        ?string $blikCode,
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
            $this->logger->error($e->getMessage());
            $this->addFlash('error', $this->translator->trans('bitbag_sylius_ing_pay_plugin.ui.payment_failed'));

            return $this->redirectToRoute('sylius_shop_checkout_select_payment');
        }
    }

    private function getPaymentFromOrder(OrderInterface $order): PaymentInterface
    {
        try {
            $payment = $this->paymentResolver->resolve($order);
        } catch (\InvalidArgumentException $e) {
            $this->logger->error($e->getMessage());

            throw new IngPayNotConfiguredException('Payment method not found');
        }

        return $payment;
    }

    private function getTransactionData(
        OrderInterface $order,
        PaymentInterface $payment,
        PaymentDataModelInterface $transactionPaymentData,
    ): IngPayTransactionInterface {
        return $this->dispatcher->dispatch(
            new GetTransactionData(
                $order,
                $payment->getMethod()->getCode(),
                $transactionPaymentData->getPaymentMethod(),
                $transactionPaymentData->getPaymentMethodCode(),
            ),
        );
    }

    private function getTransactionDataForBlik(
        OrderInterface $order,
        PaymentInterface $payment,
        PaymentDataModelInterface $transactionPaymentData,
        ?string $blikCode,
    ): IngPayTransactionInterface {
        $blikModel = $this->blikModelProvider->provideDataToBlikModel($blikCode);

        return $this->dispatcher->dispatch(
            new GetBlikTransactionData(
                $order,
                $payment->getMethod()->getCode(),
                $transactionPaymentData->getPaymentMethod(),
                $transactionPaymentData->getPaymentMethodCode(),
                $blikModel,
            ),
        );
    }
}
