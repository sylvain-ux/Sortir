<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\TripLocation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('street')
            ->add('longitude')
            ->add('latitude')
            ->add('city', EntityType::class, array(
                'choice_label' => function ($city) {
                    return $city->getName() . ' ' . $city->getZipCode();
                },
                'class' => 'App\Entity\City',
                'attr' => ['class' => 'select2'],
            ))
            ->add('save', SubmitType::class, ['label' => 'Modifier']);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TripLocation::class,
        ]);
    }
}
