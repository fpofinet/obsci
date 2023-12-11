<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('username')
            ->add('telephone')
            ->add('email',EmailType::class,[
                'label'=>"Email"
            ])
            ->add('sexe', ChoiceType::class, [
                'choices' => [
                    "Masculin" => "M",
                    "Féminin" => "F"
                ]
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    "OPERATEUR" => "ROLE_OPERATOR",
                    "SUPERVISEUR" => "ROLE_SUPERVISOR",
                    "ADMINISTRATEUR" =>"ROLE_ADMIN",
                    "OBSERVATEUR" => "ROLE_VIEWER",
                ]
            ])
            ->add('validator',EntityType::class, [
                'label'=>'Validateur(uniquement pour les operateurs)',
                'class' => User::class,
                'choice_label' => function ($user) {
                    return $user->getNom() . ' ' . $user->getPrenom();
                },
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->andWhere('u.roles LIKE :role')
                        ->setParameter('role', '%"ROLE_SUPERVISOR"%');
                },
                'multiple' => false,
                'expanded' => false,
                'mapped' =>false,
                'required' =>false,
               
                ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
           
        ]);
    }
}
