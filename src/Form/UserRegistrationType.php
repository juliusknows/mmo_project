<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use Override;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class UserRegistrationType extends AbstractType
{
    #[Override]
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'example@example.com',
                ],
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Пароль',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Введите пароль',
                ],
            ])
            ->add('passwordRepeat', PasswordType::class, [
                'label' => 'Подтверждение пароля',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Повторите пароль',
                ],
            ]);
    }

    #[Override]
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
