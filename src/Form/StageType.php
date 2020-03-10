<?php

namespace App\Form;

use App\Entity\Stage;
use App\Entity\Formation;
use App\Form\EntrepriseType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class StageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('intitule')
            ->add('missions', TextareaType::class )
            ->add('contact')
            ->add('formations', EntityType::class , [
                'class' => Formation::class,
                'choice_label' => 'nomLong',
                'expanded' => true,
                'multiple' => true
            ])
            ->add('entreprise', EntrepriseType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Stage::class,
        ]);
    }
}
