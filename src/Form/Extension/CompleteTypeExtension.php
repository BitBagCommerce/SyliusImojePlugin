<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Form\Extension;

use BitBag\SyliusIngPlugin\Resolver\Order\OrderResolverInterface;
use BitBag\SyliusIngPlugin\Resolver\Payment\OrderPaymentResolverInterface;
use Sylius\Bundle\CoreBundle\Form\Type\Checkout\CompleteType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

final class CompleteTypeExtension extends AbstractTypeExtension
{
    private OrderResolverInterface $orderResolver;

    private OrderPaymentResolverInterface $paymentResolver;

    public function __construct(
        OrderResolverInterface $orderResolver,
        OrderPaymentResolverInterface $paymentResolver
    ) {
        $this->orderResolver = $orderResolver;
        $this->paymentResolver = $paymentResolver;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $order = $this->orderResolver->resolve();
        $payment = $this->paymentResolver->resolve($order);
        $paymentCode = implode($payment->getDetails());

        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($paymentCode): void {
                if ('blik' === $paymentCode) {
                    $form = $event->getForm();
                    $form->add('blik_code', NumberType::class, [
                        'label' => 'Blik',
                        'mapped' => false,
                        'constraints' => [
                            new NotBlank([
                                'message' => 'bitbag_sylius_ing_plugin.blik_code.not_blank',
                                'groups' => ['sylius'],
                            ]),
                        ],
                    ]);
                }
            });
    }

    public static function getExtendedTypes(): array
    {
        return [CompleteType::class];
    }
}
