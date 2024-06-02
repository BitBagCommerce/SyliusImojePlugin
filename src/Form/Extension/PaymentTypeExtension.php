<?php

declare(strict_types=1);

namespace BitBag\SyliusImojePlugin\Form\Extension;

use BitBag\SyliusImojePlugin\Form\Type\PaymentImojeType;
use Sylius\Bundle\CoreBundle\Form\Type\Checkout\PaymentType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Valid;

final class PaymentTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('details', PaymentImojeType::class, [
                'validation_groups' => ['sylius'],
                'constraints' => [
                    new Valid(),
                ],
            ]);
    }

    public static function getExtendedTypes(): array
    {
        return [PaymentType::class];
    }
}
