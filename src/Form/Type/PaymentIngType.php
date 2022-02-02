<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Form\Type;

use BitBag\SyliusIngPlugin\Resolver\Payment\IngPaymentsMethodResolverInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class PaymentIngType extends AbstractType
{
    private SessionInterface $session;

    private IngPaymentsMethodResolverInterface $methodResolver;

    public function __construct(
        SessionInterface $session,
        IngPaymentsMethodResolverInterface $methodResolver
    ) {
        $this->methodResolver = $methodResolver;
        $this->session = $session;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $methods = $this->methodResolver->resolve();

        $data = $methods['data'];

        $builder
            ->add('ingPaymentMethods', ChoiceType::class, [
                'label' => false,
                'choices' => $data,
            ]);
    }
}
