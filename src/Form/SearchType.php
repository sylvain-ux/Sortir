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
            ->add('site', EntityType::class, ['class' => School::class, 'choice_label' => 'name','required' => false, 'label' => 'Site :','mapped' => false,])
            ->add(
                'dateStart',
                DateType::class,
                ['widget' => 'single_text', 'label' => 'Entre','required' => false,'mapped' => false,]
            )
            ->add(
                'dateEnd',
                DateType::class,
                ['widget' => 'single_text', 'label' => 'et','required' => false,'mapped' => false,]
            )
            ->add('TripOrganizer', CheckboxType::class, ['label' => "Sorties dont je suis l'organisatreur/trice",'required' => false,'mapped' => false,])
            ->add('TripRegistered', CheckboxType::class, ['label' => "Sorties auxquelles je suis inscrit/e",'required' => false,'mapped' => false,])
            ->add('TripNotRegistered', CheckboxType::class, ['label' => "Sorties auxquelles je ne suis pas inscrit/e",'required' => false,'mapped' => false,])
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
