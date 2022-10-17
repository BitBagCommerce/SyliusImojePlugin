<?php

declare(strict_types=1);

namespace BitBag\SyliusIngPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
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
            ->add('serviceId', TextType::class, [
                'label' => 'bitbag_sylius_ing_plugin.ui.service',
                'constraints' => [
                    new NotBlank([
                        'message' => 'bitbag_sylius_ing_plugin.serviceid.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
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
            ->add('shopKey', TextType::class, [
                'label' => 'bitbag_sylius_ing_plugin.ui.shop_key',
                'constraints' => [
                    new NotBlank([
                        'message' => 'bitbag_sylius_ing_plugin.shop_key.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->add('isProd', CheckboxType::class, [
                'label' => 'bitbag_sylius_ing_plugin.ui.is_prod',
                ])
            ->add('blik', HiddenType::class, [
                'label' => 'bitbag_sylius_ing_plugin.ui.blik',
            ])
            ->add('card', HiddenType::class, [
                'label' => 'bitbag_sylius_ing_plugin.ui.card',
            ])
            ->add('imoje_paylater', CheckboxType::class, [
                'label' => 'bitbag_sylius_ing_plugin.ui.pay_later',
            ])
            ->add('pbl', CheckboxType::class, [
            'label' => 'bitbag_sylius_ing_plugin.ui.pbl',
            ])
            ->add('ing', CheckboxType::class, [
                'label' => 'bitbag_sylius_ing_plugin.ui.ing',
                'attr' => ['class' => 'bb-pbl-methods'],
            ])
            ->add('mtransfer', CheckboxType::class, [
                'label' => 'bitbag_sylius_ing_plugin.ui.mtransfer',
                'attr' => ['class' => 'bb-pbl-methods'],
            ])
            ->add('bzwbk', CheckboxType::class, [
                'label' => 'bitbag_sylius_ing_plugin.ui.bzwbk',
                'attr' => ['class' => 'bb-pbl-methods'],
            ])
            ->add('pekao24', CheckboxType::class, [
                'label' => 'bitbag_sylius_ing_plugin.ui.pekao24',
                'attr' => ['class' => 'bb-pbl-methods'],
            ])
            ->add('inteligo', CheckboxType::class, [
                'label' => 'bitbag_sylius_ing_plugin.ui.inteligo',
                'attr' => ['class' => 'bb-pbl-methods'],
            ])
            ->add('ipko', CheckboxType::class, [
                'label' => 'bitbag_sylius_ing_plugin.ui.ipko',
                'attr' => ['class' => 'bb-pbl-methods'],
            ])
            ->add('getin', CheckboxType::class, [
                'label' => 'bitbag_sylius_ing_plugin.ui.getin',
                'attr' => ['class' => 'bb-pbl-methods'],
            ])
            ->add('noble', CheckboxType::class, [
                'label' => 'bitbag_sylius_ing_plugin.ui.noble',
                'attr' => ['class' => 'bb-pbl-methods'],
            ])
            ->add('creditagricole', CheckboxType::class, [
                'label' => 'bitbag_sylius_ing_plugin.ui.creditagricole',
                'attr' => ['class' => 'bb-pbl-methods'],
            ])
            ->add('alior', CheckboxType::class, [
                'label' => 'bitbag_sylius_ing_plugin.ui.alior',
                'attr' => ['class' => 'bb-pbl-methods'],
            ])
            ->add('pbs', CheckboxType::class, [
                'label' => 'bitbag_sylius_ing_plugin.ui.pbs',
                'attr' => ['class' => 'bb-pbl-methods'],
            ])
            ->add('millennium', CheckboxType::class, [
                'label' => 'bitbag_sylius_ing_plugin.ui.millennium',
                'attr' => ['class' => 'bb-pbl-methods'],
            ])
            ->add('citi', CheckboxType::class, [
                'label' => 'bitbag_sylius_ing_plugin.ui.citi',
                'attr' => ['class' => 'bb-pbl-methods'],
            ])
            ->add('bos', CheckboxType::class, [
                'label' => 'bitbag_sylius_ing_plugin.ui.bos',
                'attr' => ['class' => 'bb-pbl-methods'],
            ])
            ->add('bnpparibas', CheckboxType::class, [
                'label' => 'bitbag_sylius_ing_plugin.ui.bnpparibas',
                'attr' => ['class' => 'bb-pbl-methods'],
            ])
            ->add('pocztowy', CheckboxType::class, [
                'label' => 'bitbag_sylius_ing_plugin.ui.pocztowy',
                'attr' => ['class' => 'bb-pbl-methods'],
            ])
            ->add('plusbank', CheckboxType::class, [
                'label' => 'bitbag_sylius_ing_plugin.ui.plusbank',
                'attr' => ['class' => 'bb-pbl-methods'],
            ])
            ->add('bs', CheckboxType::class, [
                'label' => 'bitbag_sylius_ing_plugin.ui.bs',
                'attr' => ['class' => 'bb-pbl-methods'],
            ])
            ->add('bspb', CheckboxType::class, [
                'label' => 'bitbag_sylius_ing_plugin.ui.bspb',
                'attr' => ['class' => 'bb-pbl-methods'],
            ])
            ->add('nest', CheckboxType::class, [
                'label' => 'bitbag_sylius_ing_plugin.ui.nest',
                'attr' => ['class' => 'bb-pbl-methods'],
            ])
            ->add('envelo', CheckboxType::class, [
                'label' => 'bitbag_sylius_ing_plugin.ui.envelo',
                'attr' => ['class' => 'bb-pbl-methods'],
            ]);
    }
}
