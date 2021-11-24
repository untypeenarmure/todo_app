<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
            'label'=> 'Email de la grosse tâche'
        ])
        ->add('roles', CollectionType::class, [
            'entry_type' =>  ChoiceType::class, 
            'entry_options' => [
                'choices' => [
                'Administrateur' => 'ROLE_ADMIN',
                'Utilisateur' => 'ROLE_USER'],
            'label' => false,
            'expanded'=> false,
            'multiple'=> false
            ]
        ])
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'error_bubbling' => true,
            'invalid_message' => '/!\ FO SESIR 2 FOI LE MEM',
            'first_options'  => ['label' => 'Password'],
            'second_options' => ['label' => 'Repeat Password'],
            
        ])
        ->add('save', SubmitType::class, [
            'label'=> 'Enregistrer',
            'attr'=>[
                'class'=> 'btn-light'
            ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
