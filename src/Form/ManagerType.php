<?php

namespace App\Form;

use App\Entity\Commune;
use App\Entity\Province;
use App\Entity\Departement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ManagerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('province',EntityType::class,[
                'class'=>Province::class,
                'choice_label'=>'libelle',
                'label'=> 'Province',
                'row_attr' => [
                    'class' => 'input-group my-2',
                ],
            ])
            ->add('departement',EntityType::class,[
                'class'=>Departement::class,
                'choice_label'=>'libelle',
                'label'=> 'Departement',
                'row_attr' => [
                    'class' => 'input-group my-2',
                ],
            ])
            ->add('commune',EntityType::class,[
                'class'=>Commune::class,
                'choice_label'=>'libelle',
                'label'=> 'Commune',
                'row_attr' => [
                    'class' => 'input-group my-2',
                ],
            ])
            ->add('bureauVote',TextType::class,[
                'label'=> 'Numero du bureau de vote',
                'row_attr' => [
                    'class' => 'input-group my-2',
                ],
            ])
            ->add('votant', IntegerType::class,[
                'label'=> 'Nombre de votant',
                'row_attr' => [
                    'class' => 'input-group my-2',
                ],
            ])
            ->add('suffrageNul', IntegerType::class,[
                'label'=> 'Suffrage null',
                'row_attr' => [
                    'class' => 'input-group my-2',
                ],
            ])
            ->add('suffrageExp', IntegerType::class,[
                'label'=> 'Suffrage exprimé',
                'row_attr' => [
                    'class' => 'input-group my-2',
                ],
            ])
            ->add('voteOui', IntegerType::class,[
                'label'=> 'Vote oui',
                'row_attr' => [
                    'class' => 'input-group my-2',
                ],
            ])
            ->add('voteNon', IntegerType::class,[
                'label'=> 'Vote non',
                'row_attr' => [
                    'class' => 'input-group my-2',
                ],
            ])
            ->add('soumettre', SubmitType::class,[
                'label'=> 'Soumettre',
                'row_attr' => [
                    'class' => 'input-group my-2',
                ],
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
