<?php

namespace App\Form;

use App\Entity\School;
use App\Entity\Trip;
use App\Entity\TripLocation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('site', EntityType::class, [
                'class' => School::class,
                'choice_label' => 'name',
                'required' => false,
                'label' => 'École :',
                'mapped' => false,
                'placeholder' => 'Toutes',
                ])
            ->add(
                'dateStart',
                DateType::class,
                ['widget' => 'single_text', 'label' => 'À partir','required' => false,'mapped' => false, 'placeholder' => 'À partir',]
            )
            ->add(
                'dateEnd',
                DateType::class,
                ['widget' => 'single_text', 'label' => 'Jusqu\'à','required' => false,'mapped' => false, 'placeholder' => 'Jusqu\'à',]
            )
            ->add('TripOrganizer', CheckboxType::class, ['label' => "J'organise",'required' => false,'mapped' => false,])
            ->add('TripRegistered', CheckboxType::class, ['label' => "Je participe",'required' => false,'mapped' => false,
                'attr' => ['class' => 'check check1','data-class'=> '.check2']
            ])
            ->add('TripNotRegistered', CheckboxType::class, ['label' => "Non inscrit(e)",'required' => false,'mapped' => false,
                                                             'attr' => ['class' => 'check check2','data-class'=> '.check1']])
            ->add('TripPast', CheckboxType::class, ['label' => "Sorties passées",'required' => false,'mapped' => false,])
            ->add('save', SubmitType::class, ['label' => 'Rechercher']);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [

            ]
        );
    }
}
