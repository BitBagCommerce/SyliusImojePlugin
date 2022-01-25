<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

final class ConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('token', TextType::class, [
                'label' => 'bitbag_sylius_ing_plugin.ui.token',
                'constraints' => [
                    new NotBlank([
                        'message' => 'bitbag_sylius_ing_plugin.token.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->add('merchantId', TextType::class, [
                'label' => 'bitbag_sylius_ing_plugin.ui.merchant',
                'constraints' => [
                    new NotBlank([
                        'message' => 'bitbag_sylius_ing_plugin.merchant_id.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->add('redirect', CheckboxType::class, [
                'label' => 'bitbag_sylius_ing_plugin.ui.redirect',
            ])
            ->add('sandboxUrl', TextType::class, [
                'label' => 'bitbag_sylius_ing_plugin.ui.sandbox_url',
                'constraints' => [
                    new NotBlank([
                        'message' => 'bitbag_sylius_ing_plugin.sandbox_url.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->add('prodUrl', TextType::class, [
                'label' => 'bitbag_sylius_ing_plugin.ui.prod_url',
                'constraints' => [
                    new NotBlank([
                        'message' => 'bitbag_sylius_ing_plugin.prod_url.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->add('isProd', CheckboxType::class, [
                'label' => 'bitbag_sylius_ing_plugin.ui.is_prod',
                ]);
    }
}
