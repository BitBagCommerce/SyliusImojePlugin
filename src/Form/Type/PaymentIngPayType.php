<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPayPlugin\Form\Type;

use BitBag\SyliusIngPayPlugin\Resolver\Payment\IngPayPaymentsMethodResolverInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

final class PaymentIngPayType extends AbstractType
{
    private IngPayPaymentsMethodResolverInterface $methodResolver;

    public function __construct(
        IngPayPaymentsMethodResolverInterface $methodResolver,
    ) {
        $this->methodResolver = $methodResolver;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $data = $this->methodResolver->resolve();

        $builder
            ->add('ingPayPaymentMethods', ChoiceType::class, [
                'label' => false,
                'choices' => $data,
            ]);
    }
}
