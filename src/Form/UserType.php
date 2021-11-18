<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\DBAL\Types\JsonType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class UserType extends AbstractType
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
                'invalid_message' => 'FO SESIR 2 FOI LE MEM',
                // 'options' => ['attr' => ['class' => 'password-field']],
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
                
            ])
            ->add('save', SubmitType::class, [
                'label'=> 'Enregistrer',
                'attr'=>[
                    'class'=> 'btn-light'
                ]
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
