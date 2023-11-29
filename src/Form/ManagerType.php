<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ManagerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('commune',TextType::class,[
                'label'=> 'Commune',
            ])
            ->add('bureauVote',TextType::class,[
                'label'=> 'Numero du bureau de vote',
            ])
            ->add('votant', IntegerType::class,[
                'label'=> 'Nombre de votant',
            ])
            ->add('suffrageExp', IntegerType::class,[
                'label'=> 'Suffrage exprimé',
            ])
            ->add('suffrageNul', IntegerType::class,[
                'label'=> 'Suffrage null',
            ])
            ->add('voteOui', IntegerType::class,[
                'label'=> 'Vote oui',
            ])
            ->add('voteNon', IntegerType::class,[
                'label'=> 'Vote non',
            ])
            ->add('soumettre', SubmitType::class,[
                'label'=> 'Soumettre',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
