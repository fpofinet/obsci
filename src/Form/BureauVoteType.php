<?php

namespace App\Form;

use App\Entity\BureauVote;
use App\Entity\Commune;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BureauVoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code')
            ->add('commune',EntityType::class, [
                'label'=>'commune',
                'class' => Commune::class,
                'choice_label' => 'libelle',
                'multiple' => false,
                'expanded' => false,
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BureauVote::class,
        ]);
    }
}
