<?php

namespace App\Form;

use App\Entity\TripLocation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TripLocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Ville :'])
            ->add('street', TextType::class, ['label' => 'Lieu :'])
            ->add('longitude', TextType::class, ['label' => 'Longitude :'])
            ->add('latitude', TextType::class, ['label' => 'Latitude :'])
//            ->add('city')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TripLocation::class,
        ]);
    }
}
