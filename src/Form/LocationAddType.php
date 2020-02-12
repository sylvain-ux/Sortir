<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\TripLocation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('street')
            ->add('longitude')
            ->add('latitude')
            ->add('city',EntityType::class,[
                'class' => City::class,
                'choice_label' => 'name',
            ])
            ->add('save', SubmitType::class, ['label' => 'Ajouter']);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TripLocation::class,
        ]);
    }
}
