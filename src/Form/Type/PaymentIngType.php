<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Form\Type;

use BitBag\SyliusImojePlugin\Resolver\Payment\IngPaymentsMethodResolverInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

final class PaymentIngType extends AbstractType
{
    private IngPaymentsMethodResolverInterface $methodResolver;

    public function __construct(
        IngPaymentsMethodResolverInterface $methodResolver
    ) {
        $this->methodResolver = $methodResolver;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $data = $this->methodResolver->resolve();

        $builder
            ->add('ingPaymentMethods', ChoiceType::class, [
                'label' => false,
                'choices' => $data,
            ]);
    }
}
