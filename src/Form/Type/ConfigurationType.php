<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('token', TextType::class, [
                'label' => 'bitbag_sylius_ing_plugin.ui.token',
                'constraints' => [
                    new NotBlank([
                        'message' => 'bitbag_sylius_ing_plugin.password.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->add('redirect', CheckboxType::class, [
                'label' => 'bitbag_sylius_ing_plugin.ui.redirect',
            ])
            ->add('sandboxUrl', TextType::class, [
                'label' => 'bitbag_sylius_ing_plugin.ui.sandbox_url',
            ])
            ->add('prodUrl', TextType::class, [
                'label' => 'bitbag_sylius_ing_plugin.ui.prod_url',
            ])
            ->add('isProdUrl', CheckboxType::class, [
                'label' => 'bitbag_sylius_ing_plugin.ui.is_prod_url',
            ]);
    }
}
