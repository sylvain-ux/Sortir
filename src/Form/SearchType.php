<?php

namespace App\Form;

use App\Entity\Trip;
use App\Entity\TripLocation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('location', EntityType::class, ['class' => TripLocation::class, 'choice_label' => 'name'])
            ->add(
                'dateStart',
                DateTimeType::class,
                ['widget' => 'single_text', 'label' => 'Entre']
            )
            ->add(
                'dateEnd',
                DateTimeType::class,
                ['widget' => 'single_text', 'label' => 'et']
            )
            ->add('TripOrganizer', CheckboxType::class, ['label' => "Sorties dont je suis l'organisatreur/trice"])
            ->add('TripRegistered', CheckboxType::class, ['label' => "Sorties auxquelles je suis inscrit/e"])
            ->add('TripNotRegistered', CheckboxType::class, ['label' => "Sorties auxquelles je ne suis pas inscrit/e"])
            ->add('TripPast', CheckboxType::class, ['label' => "Sorties passées"]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [

            ]
        );
    }
}
