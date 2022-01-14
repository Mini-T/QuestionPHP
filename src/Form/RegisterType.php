<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => "le même mdp trou de balle",
                'first_options' => [
                    'label'=> 'Mot De Passe'
                ],
                'second_options' => [
                    'label'=>'Répétez'
                ]
            ])
            ->add('first_name')
            ->add('last_name')
            ->add('roles', ChoiceType::class,[
                'choices' => [
                    'Administrateur' => 'ROLE_ADMIN',
                    'Comptable' => 'ROLE_COMPTABLE',
                    'Manager' => 'ROLE_MANAGER',
                ],
                'expanded' => true,
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
