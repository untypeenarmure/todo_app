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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, [
                'label'=> 'Email de la grosse tâche'
            ])
            ->add('roles', ChoiceType::class, ['choices' => [
                'Administrateur' => "ROLE_ADMIN",
                'Utilisateur' => "ROLE_ADMIN",],
                ])
            ->add('password', TextType::class, [
                'label'=> 'Mot de passe'
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